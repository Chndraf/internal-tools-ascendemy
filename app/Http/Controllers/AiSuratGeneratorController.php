<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiSuratGeneratorController extends Controller
{
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'jenis_surat' => 'required|string|max:100',
            'penerima'    => 'required|string|max:255',
            'konteks'     => 'required|string'
        ]);

        try {
            $apiKey = env('GROQ_API_KEY');
            if (!$apiKey) {
                throw new Exception('GROQ_API_KEY tidak ditemukan.');
            }

            $userPrompt = "Jenis Surat: " . $request->jenis_surat . "\n"
                        . "Pihak Tujuan/Penerima: " . $request->penerima . "\n\n"
                        . "Konteks/Isi Pokok Surat:\n" . $request->konteks;

            $response = Http::withoutVerifying()
                ->withToken($apiKey)
                ->timeout(45)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'openai/gpt-oss-20b', // Sesuai permintaan
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $this->getSystemPrompt()
                        ],
                        [
                            'role' => 'user',
                            'content' => $userPrompt
                        ]
                    ],
                    'temperature' => 0.5,
                ]);

            if ($response->successful()) {
                $result = $response->json();
                return response()->json([
                    'success' => true,
                    'result'  => trim($result['choices'][0]['message']['content'] ?? '')
                ]);
            }

            return response()->json([
                'success' => false, 
                'message' => 'Ditolak Groq: ' . $response->body()
            ], $response->status());

        } catch (Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getSystemPrompt(): string
    {
        return '# PERAN
Kamu adalah Sekretaris Eksekutif dan Ahli Administrasi Perkantoran. Tugasmu adalah menyusun surat resmi perusahaan/organisasi berdasarkan poin-poin acak dari pengguna.

# ATURAN KETAT
1. Susun menjadi format surat resmi standar Indonesia yang siap di-copy ke Microsoft Word.
2. Wajib memuat struktur baku jika memungkinkan: Tempat & Tanggal, Nomor Surat, Lampiran, Perihal, Alamat Tujuan, Salam Pembuka, Isi Surat, Salam Penutup, dan Kolom Tanda Tangan.
3. Gunakan bahasa Indonesia yang SANGAT FORMAL, sopan, baku (EYD), dan terstruktur.
4. DILARANG KERAS menggunakan emoji, emoticon, atau ikon apa pun.
5. HANYA berikan isi surat. DILARANG memberikan kalimat pengantar/penutup (seperti "Ini draf surat Anda...").
6. Gunakan placeholder dalam kurung siku untuk data yang harus diisi manual, contoh: [Nomor Surat], [Tanggal Pelaksanaan], [Nama Kota], [Nama Pimpinan].';
    }
}
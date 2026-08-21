<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiDataParserController extends Controller
{
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'kolom_target' => 'required|string|max:500',
            'data_mentah'  => 'required|string'
        ]);

        try {
            $apiKey = env('GROQ_API_KEY');
            if (!$apiKey) {
                throw new Exception('GROQ_API_KEY tidak ditemukan.');
            }

            $userPrompt = "Target Kolom:\n" . $request->kolom_target . "\n\nData Mentah:\n" . $request->data_mentah;

            $response = Http::withoutVerifying()
                ->withToken($apiKey)
                ->timeout(45)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'openai/gpt-oss-20b',
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
                    'temperature' => 0.1, // Dibuat rendah agar AI sangat fokus dan tidak berhalusinasi
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
Kamu adalah Data Analyst ahli. Tugasmu adalah mengekstrak informasi dari teks yang berantakan (seperti chat WhatsApp) dan mengubahnya menjadi format tabel terstruktur.

# ATURAN KETAT
1. Output WAJIB berupa format tabel Markdown.
2. Gunakan "Target Kolom" yang diminta pengguna sebagai header tabel (jika relevan). Jika ada data yang tidak jelas, kosongkan sel tersebut (tulis "-").
3. HANYA berikan tabel Markdown saja.
4. DILARANG KERAS memberikan kalimat pembuka, penutup, atau penjelasan apa pun.
5. DILARANG KERAS menggunakan emoji, emoticon, atau ikon di dalam output.';
    }
}
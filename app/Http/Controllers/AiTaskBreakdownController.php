<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiTaskBreakdownController extends Controller
{
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'project_name' => 'required|string|max:500',
            'context'      => 'required|string'
        ]);

        try {
            $apiKey = env('GROQ_API_KEY');
            if (!$apiKey) {
                throw new Exception('GROQ_API_KEY tidak ditemukan.');
            }

            $userPrompt = "Nama Proyek/Target: " . $request->project_name . "\n\nKonteks/Detail Tambahan:\n" . $request->context;

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
                    'temperature' => 0.4, // Sedikit kreatif tapi tetap terstruktur
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
Kamu adalah Project Manager senior. Tugasmu memecah sebuah proyek besar/target kerja menjadi Work Breakdown Structure (WBS) atau daftar langkah kerja (to-do list) yang detail, logis, dan sangat mudah dieksekusi oleh karyawan.

# ATURAN KETAT
1. Bagilah proyek menjadi beberapa fase (Misal: PERSIAPAN, PELAKSANAAN, EVALUASI).
2. Di setiap fase, buat daftar tugas menggunakan bullet point.
3. Berikan estimasi waktu singkat di samping setiap tugas (contoh: "[2 Hari]", "[4 Jam]").
4. Gunakan bahasa Indonesia yang baku, profesional, dan to the point.
5. HANYA berikan daftar tugas tersebut. DILARANG KERAS memberikan kalimat pembuka atau penutup (seperti "Berikut adalah rinciannya...").
6. DILARANG KERAS menggunakan emoji, emoticon, atau ikon apa pun.';
    }
}
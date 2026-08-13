<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiMeetingMinutesController extends Controller
{
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'agenda' => 'required|string|max:255',
            'raw_notes' => 'required|string'
        ]);

        try {
            $apiKey = env('GROQ_API_KEY');
            if (!$apiKey) {
                throw new Exception('GROQ_API_KEY tidak ditemukan.');
            }

            $userPrompt = "Topik/Agenda Rapat: " . $request->agenda . "\n\nCatatan Mentah:\n" . $request->raw_notes;

            $response = Http::withoutVerifying()
                ->withToken($apiKey)
                ->timeout(45) // Dibuat agak lama karena output nya bisa panjang
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'openai/gpt-oss-20b',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Kamu adalah sekretaris profesional. Ubah catatan mentah/acak menjadi Notulen Rapat (Meeting Minutes) yang sangat rapi dan terstruktur dalam Bahasa Indonesia. 
                            Wajib memiliki struktur berikut:
                                1. Ringkasan Singkat (1-2 kalimat)
                                2. Poin Diskusi Utama (Gunakan Bullet Points)
                                3. Keputusan yang Diambil

                            ATURAN SANGAT KETAT: Berikan HANYA teks notulennya saja. DILARANG KERAS memberikan kalimat pembuka (seperti "Berikut adalah notulennya...") atau penutup.'
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
                    'result' => trim($result['choices'][0]['message']['content'])
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Ditolak Groq: ' . $response->body()], $response->status());

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiBroadcastController extends Controller
{
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'topic' => 'required|string|max:1000',
            'audience' => 'required|string|max:255',
            'tone' => 'required|string',
        ]);

        try {
            $apiKey = env('GROQ_API_KEY');
            if (! $apiKey) {
                throw new Exception('GROQ_API_KEY tidak ditemukan.');
            }

            $userPrompt = 'Topik/Isi Pesan: '.$request->topic."\n"
                        .'Platform & Target Audiens: '.$request->audience."\n"
                        .'Gaya Bahasa: '.$request->tone;

            $response = Http::withoutVerifying()
                ->withToken($apiKey)
                ->timeout(30)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.1-8b-instant',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Kamu adalah ahli komunikasi dan copywriter. Buat teks pesan broadcast yang terdengar sangat natural, ditulis seperti manusia biasa, dan BUKAN seperti mesin/AI. Gunakan bahasa Indonesia yang pas, tidak menggunakan lo/gue. Jika untuk WhatsApp, gunakan format WA (*bold*, _italic_). Sediakan placeholder [Nama] jika perlu. ATURAN KETAT: Berikan HANYA teks broadcastnya saja tanpa kalimat pengantar/penutup tambahan (seperti "Berikut adalah...").',
                        ],
                        [
                            'role' => 'user',
                            'content' => $userPrompt,
                        ],
                    ],
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                $result = $response->json();

                return response()->json([
                    'success' => true,
                    'result' => trim($result['choices'][0]['message']['content']),
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Ditolak Groq: '.$response->body()], $response->status());

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: '.$e->getMessage()], 500);
        }
    }
}

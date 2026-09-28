<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiEmailController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:1000',
        ]);

        $prompt = $request->input('prompt');
        $apiKey = env('GROQ_API_KEY');

        try {
            $response = Http::withoutVerifying()
                ->withToken($apiKey)
                ->timeout(15)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'openai/gpt-oss-20b',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Kamu adalah asisten ahli penulis email profesional. Tulislah email yang sopan, jelas, dan terstruktur berdasarkan instruksi singkat dari pengguna. Gunakan bahasa Indonesia. Sediakan placeholder seperti [Nama Anda] atau [Tanggal] jika diperlukan. Jawab langsung dengan isi email saja tanpa kalimat pengantar atau penutup tambahan.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                $result = $response->json();
                $emailContent = $result['choices'][0]['message']['content'];

                return response()->json([
                    'success' => true,
                    'result' => trim($emailContent),
                ]);
            }

            $errorDetail = $response->body();

            return response()->json(['success' => false, 'message' => 'Ditolak Groq: '.$errorDetail], 500);

        } catch (\Exception $e) {
            // Menampilkan pesan error asli dari sistem
            return response()->json(['success' => false, 'message' => 'Error: '.$e->getMessage()], 500);
        }
    }
}

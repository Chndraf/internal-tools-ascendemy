<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiEmailResponseController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'incoming_email' => 'required|string',
            'reply_context' => 'required|string|max:1000'
        ]);

        $apiKey = env('GROQ_API_KEY');
        
        // Menggabungkan email asli dan instruksi pengguna
        $prompt = "Berikut adalah email masuk yang saya terima:\n\n\"" . $request->incoming_email . "\"\n\nSaya ingin membalas email tersebut dengan tujuan/konteks berikut: " . $request->reply_context;

        try {
            $response = Http::withoutVerifying()
                ->withToken($apiKey)
                ->timeout(15)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'openai/gpt-oss-20b',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Kamu adalah asisten ahli penulis email profesional. Buatlah draf balasan email (Bahasa Indonesia) yang sopan dan profesional berdasarkan email masuk dan instruksi pengguna. ATURAN SANGAT KETAT: Kamu HANYA Boleh memberikan isi teks emailnya saja. DILARANG KERAS memberikan kalimat pembuka (seperti "Berikut adalah draf..."), kalimat penutup (seperti "Jangan lupa ganti..."), atau membungkus pesan dengan tanda kutip (""). Jika butuh nama, gunakan [Nama Anda]. Outputmu harus 100% teks mentah yang siap copy-paste.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                $result = $response->json();
                return response()->json([
                    'success' => true,
                    'result' => trim($result['choices'][0]['message']['content'])
                ]);
            }
            return response()->json(['success' => false, 'message' => 'Ditolak Groq: ' . $response->body()], 500);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
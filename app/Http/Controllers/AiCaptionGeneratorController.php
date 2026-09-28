<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiCaptionGeneratorController extends Controller
{
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'context' => 'required|string|max:1000',
            'images' => 'required|array|max:4',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $apiKey = env('GROQ_API_KEY');
            if (! $apiKey) {
                throw new Exception('GROQ_API_KEY tidak ditemukan.');
            }

            $images = $request->file('images');
            $imageCount = count($images);
            $contentArray = [];

            $contentArray[] = [
                'type' => 'text',
                'text' => $this->getSystemPrompt($request->context, $imageCount),
            ];

            foreach ($images as $image) {
                $mimeType = $image->getClientMimeType();
                $imagePath = $image->path();

                // Gunakan fungsi kompresi untuk mengecilkan ukuran gambar sebelum dikirim
                $base64 = $this->compressImageToBase64($imagePath, $mimeType);

                $contentArray[] = [
                    'type' => 'image_url',
                    'image_url' => [
                        // Karena kita merubah semuanya jadi JPEG saat kompresi, mime diset ke image/jpeg
                        'url' => "data:image/jpeg;base64,{$base64}",
                    ],
                ];
            }

            $apiEndpoint = 'https://api.groq.com/openai/v1/chat/completions';
            $modelsToTry = ['qwen/qwen3.6-27b'];

            $lastErrorResponse = '';
            $lastStatusCode = 500;

            foreach ($modelsToTry as $modelName) {
                $response = Http::withoutVerifying()
                    ->withToken($apiKey)
                    ->timeout(60)
                    ->post($apiEndpoint, [
                        'model' => $modelName,
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => $contentArray,
                            ],
                        ],
                        'temperature' => 0.7,
                    ]);

                if ($response->successful()) {
                    $result = $response->json();
                    $rawText = $result['choices'][0]['message']['content'] ?? '';

                    // Bersihkan tag <think> jika ada
                    $cleanedText = preg_replace('/<think>.*?<\/think>/s', '', $rawText);

                    return response()->json([
                        'success' => true,
                        'result' => trim($cleanedText),
                    ]);
                }

                $lastErrorResponse = $response->body();
                $lastStatusCode = $response->status();
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal merespon. Error: '.$lastErrorResponse,
            ], $lastStatusCode);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Memperkecil dimensi gambar menjadi max 256px untuk menghemat Token API
     */
    private function compressImageToBase64(string $filePath, string $mimeType): string
    {
        // Kompresi ekstrem: Turunkan ke 256px agar aman untuk 4 gambar sekaligus
        $maxWidth = 256;
        $maxHeight = 256;

        if ($mimeType == 'image/jpeg' || $mimeType == 'image/jpg') {
            $sourceImage = @imagecreatefromjpeg($filePath);
        } elseif ($mimeType == 'image/png') {
            $sourceImage = @imagecreatefrompng($filePath);
        } elseif ($mimeType == 'image/webp') {
            $sourceImage = @imagecreatefromwebp($filePath);
        } else {
            return base64_encode(file_get_contents($filePath));
        }

        if (! $sourceImage) {
            return base64_encode(file_get_contents($filePath));
        }

        $origWidth = imagesx($sourceImage);
        $origHeight = imagesy($sourceImage);

        $ratio = $origWidth / $origHeight;
        $newWidth = $origWidth;
        $newHeight = $origHeight;

        if ($newWidth > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = $maxWidth / $ratio;
        }
        if ($newHeight > $maxHeight) {
            $newHeight = $maxHeight;
            $newWidth = $maxHeight * $ratio;
        }

        $destinationImage = imagecreatetruecolor((int) $newWidth, (int) $newHeight);
        $whiteBackground = imagecolorallocate($destinationImage, 255, 255, 255);
        imagefill($destinationImage, 0, 0, $whiteBackground);

        imagecopyresampled($destinationImage, $sourceImage, 0, 0, 0, 0, (int) $newWidth, (int) $newHeight, $origWidth, $origHeight);

        ob_start();
        // Turunkan kualitas JPEG ke 45 (Sangat menghemat token, AI tetap bisa melihat visualnya)
        imagejpeg($destinationImage, null, 45);
        $imageBytes = ob_get_clean();

        imagedestroy($sourceImage);
        imagedestroy($destinationImage);

        return base64_encode($imageBytes);
    }

    private function getSystemPrompt(string $userContext, int $imageCount): string
    {
        $prompt = "Kamu adalah Social Media Manager ahli. Buatkan caption media sosial yang profesional, menarik dan kreatif.\n\nInstruksi dari pengguna:\n\"{$userContext}\"\n\nATURAN:\n1. Analisis apa yang ada di gambar yang dilampirkan.";

        if ($imageCount > 1) {
            $prompt .= "\n2. Karena ada {$imageCount} gambar, kamu WAJIB membuatkan caption spesifik untuk MASING-MASING gambar dengan format:\n   **Gambar 1:**\n   [Caption Gambar 1]\n   **Gambar 2:**\n   [Caption Gambar 2]\n   (dan seterusnya)";
        } else {
            $prompt .= "\n2. Buat HANYA SATU caption utama untuk gambar ini.";
        }

        // Aturan ketat baru untuk melarang emoji dan membatasi output hanya teks
        $prompt .= "\n3. Sertakan hashtag yang relevan di akhir.\n4. DILARANG KERAS menggunakan emoji, emoticon, atau ikon apa pun di dalam teks caption.\n5. HANYA berikan teks caption saja, tanpa kalimat pengantar/penutup.";

        return $prompt;
    }
}

<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;

class AiPdfSummarizerController extends Controller
{
    /**
     * Menangani permintaan unggah PDF dan membuat ringkasan menggunakan AI.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function generate(Request $request): JsonResponse
    {
        // 1. Validasi Input: Wajib PDF dan maksimal 5MB
        $request->validate([
            'pdf_file' => 'required|mimes:pdf|max:5120',
        ]);

        try {
            // 2. Ekstrak teks dari file PDF
            $parser = new Parser();
            $pdfContent = $parser->parseFile($request->file('pdf_file')->path());
            $rawText = $pdfContent->getText();

            // 3. Pembersihan Teks
            // Hapus spasi/newline berlebih
            $cleanText = preg_replace('/\s+/', ' ', trim($rawText));

            // Batasi panjang teks agar tidak melebihi konteks memori AI (token limit)
            // Sekitar 15.000 karakter (~3000-4000 kata)
            $characterLimit = 15000;
            $limitedText = substr($cleanText, 0, $characterLimit);

            if (empty($limitedText)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada teks yang bisa dibaca dari dokumen ini. Pastikan PDF bukan hasil scan/gambar.'
                ], 422); // Gunakan 422 Unprocessable Entity untuk error konten
            }

            // 4. Persiapan Data untuk API Groq
            $apiKey = env('GROQ_API_KEY');
            if (!$apiKey) {
                throw new Exception('GROQ_API_KEY tidak ditemukan di file .env');
            }

            $userPrompt = "Berikut adalah teks dari dokumen PDF:\n\n\"" . $limitedText . "\"\n\nTolong buatkan ringkasan dokumen tersebut sesuai instruksi.";

            // Konfigurasi Model
            $modelName = 'openai/gpt-oss-20b';
            $apiEndpoint = 'https://api.groq.com/openai/v1/chat/completions';

            // 5. Kirim Permintaan ke API Groq
            $response = Http::withoutVerifying() // Opsional: Hanya untuk local dev jika ada isu SSL
                ->withToken($apiKey)
                ->timeout(60) // Beri waktu lebih lama karena proses ringkasan butuh waktu
                ->post($apiEndpoint, [
                    'model'       => $modelName,
                    'messages'    => [
                        [
                            'role'    => 'system',
                            'content' => $this->getSystemPrompt() // Prompt panjang diambil dari metode privat
                        ],
                        [
                            'role'    => 'user',
                            'content' => $userPrompt
                        ]
                    ],
                    'temperature' => 0.5, // Temperature sedang untuk keseimbangan akurasi & variasi
                ]);

            // 6. Tangani Respon API
            if ($response->successful()) {
                $jsonData = $response->json();
                $aiSummary = $jsonData['choices'][0]['message']['content'] ?? 'Gagal mengambil konten ringkasan.';

                return response()->json([
                    'success' => true,
                    'result'  => trim($aiSummary)
                ]);
            }

            // Jika API menolak/error
            return response()->json([
                'success' => false,
                'message' => 'API Groq gagal merespon: ' . $response->body()
            ], $response->status());

        } catch (Exception $e) {
            // Tangani error sistem (file corrupted, parsing error, koneksi, dll)
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengembalikan prompt sistem yang sangat ketat dan terstruktur untuk AI.
     *
     * @return string
     */
    private function getSystemPrompt(): string
    {
        return 'Anda adalah asisten AI yang bertugas membuat ringkasan dokumen PDF secara akurat, jelas, dan terstruktur.

TUGAS:
Baca dan pahami seluruh isi dokumen PDF yang diberikan, lalu buat ringkasan yang mencakup:

1. RINGKASAN EKSEKUTIF (2-4 kalimat)
   - Inti/tujuan utama dokumen dalam bahasa yang mudah dipahami

2. POIN-POIN UTAMA
   - Sajikan dalam bullet points
   - Urutkan berdasarkan tingkat kepentingan atau urutan kemunculan di dokumen
   - Sertakan data, angka, atau fakta kunci jika ada (jangan diubah/dibulatkan tanpa keterangan)

3. STRUKTUR PER BAGIAN (jika dokumen memiliki bab/section jelas)
   - Ringkas tiap bagian secara singkat sesuai urutan aslinya
   - Sebutkan nomor halaman atau nama bagian sebagai referensi

4. KESIMPULAN / TINDAK LANJUT (jika relevan)
   - Rekomendasi, keputusan, atau langkah selanjutnya yang disebutkan dalam dokumen

ATURAN PENTING:
- Gunakan bahasa yang sama dengan dokumen asli, kecuali pengguna meminta bahasa lain
- JANGAN menambahkan informasi, opini, atau asumsi yang tidak ada di dalam dokumen
- JANGAN menghilangkan informasi krusial (angka, tanggal, nama, klausul penting) demi keringkasan
- Jika dokumen berisi tabel, ringkas insight utamanya, bukan menyalin seluruh tabel
- Jika ada bagian yang tidak terbaca/tidak jelas (misal hasil OCR buruk), sebutkan secara eksplisit: "[bagian ini tidak dapat dibaca dengan jelas]"
- Sesuaikan panjang ringkasan dengan panjang dokumen:
  * Dokumen < 5 halaman: ringkasan 150-250 kata
  * Dokumen 5-20 halaman: ringkasan 300-500 kata
  * Dokumen > 20 halaman: ringkasan per-bagian + ringkasan eksekutif keseluruhan
- Gunakan format Markdown (heading, bold, bullet) agar mudah dibaca di UI

FORMAT OUTPUT:
## Ringkasan Eksekutif
[isi]

## Poin-Poin Utama
- [poin 1]
- [poin 2]

## Ringkasan Per Bagian (opsional, jika dokumen panjang)
**[Judul Bagian]** (hal. X-Y)
[ringkasan singkat]

## Kesimpulan / Tindak Lanjut
[isi, jika ada]';
    }
}
<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiDailyReporterController extends Controller
{
    /**
     * Menghasilkan laporan harian berdasarkan target dan log aktivitas acak.
     */
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'main_target' => 'required|string|max:500',
            'raw_logs' => 'required|string',
        ]);

        try {
            $apiKey = env('GROQ_API_KEY');
            if (! $apiKey) {
                throw new Exception('GROQ_API_KEY tidak ditemukan.');
            }

            $userPrompt = "Target Utama Hari Ini:\n".$request->main_target."\n\nCatatan Aktivitas (Acak):\n".$request->raw_logs;

            $apiEndpoint = 'https://api.groq.com/openai/v1/chat/completions';
            $modelName = 'openai/gpt-oss-20b';

            $response = Http::withoutVerifying()
                ->withToken($apiKey)
                ->timeout(45)
                ->post($apiEndpoint, [
                    'model' => $modelName,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $this->getSystemPrompt(),
                        ],
                        [
                            'role' => 'user',
                            'content' => $userPrompt,
                        ],
                    ],
                    'temperature' => 0.5,
                ]);

            if ($response->successful()) {
                $result = $response->json();

                return response()->json([
                    'success' => true,
                    'result' => trim($result['choices'][0]['message']['content'] ?? ''),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Ditolak Groq: '.$response->body(),
            ], $response->status());

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mengembalikan prompt sistem terstruktur untuk AI.
     */
    private function getSystemPrompt(): string
    {
        return '# PERAN & TUGAS
Kamu adalah asisten HR sekaligus manajer proyek. Tugasmu: mengubah catatan aktivitas kerja karyawan — teks bebas dan kronologis, biasanya disertai jam (contoh: "09:00 membaca ulang laporan atasan") — menjadi Laporan Progres Harian yang profesional dan terstruktur dalam Bahasa Indonesia formal. Gunakan hanya informasi yang tercantum di catatan; jangan menambahkan aktivitas atau detail yang tidak disebutkan.

# LOGIKA PENGOLAHAN
1. **Target Utama** — Jika disebut eksplisit di catatan, ringkas ulang. Jika tidak, simpulkan dari aktivitas yang paling dominan/menyita waktu terbanyak.
2. **Progres Pekerjaan** — Kelompokkan menjadi "Tugas Utama" (selaras target) dan "Tugas Tambahan/Ad-hoc" (di luar target, jika ada — section ini dihapus total kalau tidak ada). Sertakan jam dari catatan asli untuk menjaga kronologi. Nyatakan status tiap item (selesai/berjalan/terkendala) sesuai catatan, jangan berasumsi.
3. **Kendala** — Cari sinyal tersirat: revisi berulang, menunggu pihak lain, data/akses tidak lengkap, dsb. Tidak ada sinyal → tulis "Berjalan lancar tanpa kendala berarti."
4. **Analisis Performa** — Tidak ada data hari sebelumnya sebagai pembanding, jadi evaluasi berdiri sendiri berdasarkan: tingkat penyelesaian target, volume & kompleksitas aktivitas, dan penanganan tugas ad-hoc.
   - Meningkat: target tercapai + tugas ad-hoc tertangani baik
   - Normal: target tercapai sesuai rencana, tanpa hal menonjol
   - Menurun: target tidak tercapai atau banyak kendala menghambat

# FORMAT OUTPUT (WAJIB — plain text, TANPA markdown, TANPA icon)
FOKUS/TARGET UTAMA
[ringkasan target]

PROGRES PEKERJAAN
Tugas Utama:
- [bullet, sertakan jam bila tersedia]

Tugas Tambahan/Ad-hoc:
- [bullet — hapus section ini jika tidak ada]

Rangkuman Aktivitas Hari Ini
[berisi agenda yang dilakukan dari awal masuk sampai pulang]

KENDALA / CATATAN
[isi kendala, atau kalimat default di atas]

ANALISIS PERFORMA HARIAN
Status: [Meningkat / Normal / Menurun]
[1-2 kalimat alasan + motivasi singkat]

# ATURAN KETAT
- Output HANYA teks laporan sesuai format di atas, mulai langsung dari "FOKUS/TARGET UTAMA".
- DILARANG KERAS memakai sintaks markdown apa pun (tanpa ##, tanpa **) dan DILARANG memakai emoji/icon dalam bentuk apa pun. Judul section ditulis polos HURUF KAPITAL seperti contoh.
- Tanda "-" untuk bullet point tetap boleh dipakai.
- DILARANG ada kalimat pembuka, penutup, atau catatan tambahan di luar format ini.
- Bahasa Indonesia formal ala laporan kantor: ringkas, padat, hindari kalimat bertele-tele.';
    }
}

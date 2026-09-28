<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class SpamCheckerService
{
    public function analyze($subject, $body)
    {
        $score = 100;
        $foundSpamWords = [];
        $fullText = strtolower($subject.' '.$body);

        // 1. Baca Database JSON dari folder Storage
        $jsonPath = storage_path('app/spam_words.json');
        $spamDictionary = [];

        if (File::exists($jsonPath)) {
            $spamDictionary = json_decode(File::get($jsonPath), true);
        }

        // 2. Cek Kata Pemicu Spam
        // 2. Cek Kata Pemicu Spam
        if (! empty($spamDictionary)) {
            foreach ($spamDictionary as $word => $penalty) {
                // \b memastikan sistem hanya mencari kata utuh (Whole Word Match)
                if (preg_match('/\b'.preg_quote($word, '/').'\b/i', $fullText)) {
                    $score -= $penalty;
                    $foundSpamWords[] = ucwords($word);
                }
            }
        }

        // 3. Cek Format (Subjek Kapital Semua)
        $cleanSubject = preg_replace('/[^a-zA-Z]/', '', $subject);
        if (strlen($cleanSubject) > 0 && $cleanSubject === strtoupper($cleanSubject)) {
            $score -= 15;
            $foundSpamWords[] = 'Subjek Huruf Kapital';
        }

        // 4. Cek Tanda Seru Berlebihan
        if (substr_count($fullText, '!') > 3) {
            $score -= 10;
            $foundSpamWords[] = 'Terlalu Banyak Tanda Seru';
        }

        // 5. Cek Panjang Subjek
        if (strlen(trim($subject)) > 0 && strlen(trim($subject)) < 10) {
            $score -= 5;
            $foundSpamWords[] = 'Subjek Terlalu Pendek';
        }

        $score = max(0, $score);

        return [
            'score' => $score,
            'spam_words' => $foundSpamWords,
        ];
    }
}

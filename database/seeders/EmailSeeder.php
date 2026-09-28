<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Email;
use App\Models\Keyword;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class EmailSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan data salah yang telanjur masuk sebelumnya
        Email::truncate();

        $path = database_path('seeders/data_negara');
        $files = File::files($path);

        $batchData = [];
        $batchSize = 500;
        $processedEmails = []; // Track unique emails

        foreach ($files as $file) {
            $filename = $file->getFilenameWithoutExtension();
            $countryName = str_replace('_', ' ', $filename);

            $country = Country::where('name', $countryName)->first();

            if (! $country) {
                continue;
            }

            $csvData = fopen($file->getRealPath(), 'r');
            $isHeader = true;

            while (($row = fgetcsv($csvData, 1000, ',')) !== false) {
                if ($isHeader) {
                    $isHeader = false;

                    continue;
                }

                // CSV Structure: Keyword, Judul, Penulis, Email, Negara, DOI
                $keywordText = $row[0] ?? null;
                $title = $row[1] ?? null;
                $author = $row[2] ?? null;
                $emailString = $row[3] ?? null;
                $doi = $row[5] ?? null;

                if ($keywordText && $emailString) {
                    $emailsArray = explode(',', $emailString);

                    foreach ($emailsArray as $singleEmail) {
                        $cleanEmail = trim($singleEmail);

                        // Only save valid emails and avoid duplicates
                        if (filter_var($cleanEmail, FILTER_VALIDATE_EMAIL) && ! isset($processedEmails[$cleanEmail])) {

                            $keyword = Keyword::firstOrCreate(
                                ['name' => trim($keywordText)]
                            );

                            $batchData[] = [
                                'email' => $cleanEmail,
                                'author' => $author ? trim($author) : null,
                                'title' => $title ? trim($title) : null,
                                'doi' => $doi ? trim($doi) : null,
                                'country_id' => $country->id,
                                'keyword_id' => $keyword->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];

                            $processedEmails[$cleanEmail] = true;

                            // Insert in batches for better performance
                            if (count($batchData) >= $batchSize) {
                                DB::table('emails')->insert($batchData);
                                $batchData = [];
                                $this->command->info('Inserted '.count($processedEmails).' emails...');
                            }
                        }
                    }
                }
            }
            fclose($csvData);
        }

        // Insert remaining data
        if (! empty($batchData)) {
            DB::table('emails')->insert($batchData);
        }

        $this->command->info('Total '.count($processedEmails).' emails imported successfully!');
    }
}

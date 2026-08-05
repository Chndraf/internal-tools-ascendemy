<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Country;
use App\Models\Keyword;
use App\Models\Email;

class EmailSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan data salah yang telanjur masuk sebelumnya
        Email::truncate(); 

        $path = database_path('seeders/data_negara'); 
        $files = File::files($path);

        foreach ($files as $file) {
            $filename = $file->getFilenameWithoutExtension();
            $countryName = str_replace('_', ' ', $filename);

            $country = Country::where('name', $countryName)->first();

            if (!$country) {
                continue; 
            }

            $csvData = fopen($file->getRealPath(), 'r');
            $isHeader = true;

            while (($row = fgetcsv($csvData, 1000, ',')) !== false) {
                if ($isHeader) {
                    $isHeader = false;
                    continue; 
                }

                $keywordText = $row[0] ?? null; 
                $emailString = $row[3] ?? null; 

                if ($keywordText && $emailString) {
                    $emailsArray = explode(',', $emailString);

                    foreach ($emailsArray as $singleEmail) {
                        $cleanEmail = trim($singleEmail);
                        
                        // 2. KUNCI UTAMA: Hanya simpan jika formatnya benar-benar email
                        if (filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
                            
                            $keyword = Keyword::firstOrCreate(
                                ['name' => trim($keywordText)]
                            );

                            Email::firstOrCreate([
                                'email'      => $cleanEmail,
                                'country_id' => $country->id,
                                'keyword_id' => $keyword->id,
                            ]);
                        }
                    }
                }
            }
            fclose($csvData);
        }

        $this->command->info("Data yang salah sudah dihapus, dan Email asli berhasil diimpor!");
    }
}
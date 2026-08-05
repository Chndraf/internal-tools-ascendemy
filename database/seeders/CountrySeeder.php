<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        // Lokasi folder tempat kamu menyimpan 138 file CSV
        $path = database_path('seeders/data_negara'); 
        
        // Cek apakah foldernya ada
        if (!File::isDirectory($path)) {
            $this->command->error("Folder tidak ditemukan: {$path}");
            return;
        }

        $files = File::files($path);
        $countriesData = [];

        foreach ($files as $file) {
            // Mengambil nama file tanpa ekstensi .csv (contoh: "British_Indian_Ocean_Territory")
            $filename = $file->getFilenameWithoutExtension();
            
            // Merapikan nama (mengganti underscore dengan spasi)
            $countryName = str_replace('_', ' ', $filename);

            $countriesData[] = [
                'name' => $countryName,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // (Opsional) Jika kamu ingin MEMBACA ISI BARIS di dalam CSV-nya, 
            // kamu bisa menggunakan fopen() di sini. 
            // Beritahu saya jika di dalam CSV tersebut berisi daftar email/jurnal!
        }

        // Insert ke database MySQL sekaligus
        DB::table('countries')->insert($countriesData);
        
        $this->command->info("Berhasil mengimpor " . count($countriesData) . " negara dari file CSV!");
    }
}

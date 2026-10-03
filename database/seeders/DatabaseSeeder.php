<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Region;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        echo "🗑️ Tablolar temizleniyor...\n";
        
        $tables = [
            'team_assignments', 'notifications', 'reports', 
            'distribution', 'relief_teams', 'needs', 
            'resources', 'victims', 'users', 'regions'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        echo "✅ Tablolar temizlendi.\n\n";
        echo "📥 CSV dosyaları yükleniyor...\n\n";

        // Tablo => Olası CSV Dosya Adları
        $tablesMap = [
            'regions'       => ['regions.csv', 'regions_2.csv', 'regions_3.csv'],
            'users'         => ['users.csv', 'users_2.csv', 'users_3.csv'],
            'victims'       => ['victims.csv', 'victims_2.csv', 'victims_3.csv'],
            'resources'     => ['resources.csv', 'resources_2.csv', 'resources_3.csv'],
            'relief_teams'  => ['relief_teams.csv', 'relief_teams_2.csv', 'relief_teams_3.csv'],
            'needs'         => ['needs.csv', 'needs_2.csv', 'needs_3.csv'],
            'distribution'  => ['distribution.csv', 'distributions.csv', 'distribution_2.csv'],
            'reports'       => ['reports.csv', 'reports_2.csv', 'reports_3.csv'],
            'notifications' => ['notifications.csv', 'notifications_2.csv', 'notifications_3.csv'],
            'team_assignments' => ['team_assignments.csv', 'team_assignments_2.csv'],
        ];

        foreach ($tablesMap as $tableName => $possibleFileNames) {
            $filePath = $this->findCsvFile($possibleFileNames);

            if (!$filePath) {
                echo "⚠️ Atlandı: {$tableName} için CSV dosyası bulunamadı.\n";
                continue;
            }

            $this->importCsvFile($filePath, $tableName);
        }

        // -------------------------------------------------------------
        // 1️⃣ ÖZEL KULLANICI EKLEME (UserSeeder Mantığı)
        // -------------------------------------------------------------
        echo "\n👤 Özel test kullanıcıları kontrol ediliyor...\n";
        if (!User::where('email', 'fatema@gmail.com')->exists()) {
            User::create([
                'name'     => 'fatema',
                'email'    => 'fatema@gmail.com',
                'password' => Hash::make('123456'),
                'rol'      => 'admin', // İhtiyacınıza göre rol belirleyebilirsiniz
            ]);
            echo "✅ 'fatema@gmail.com' kullanıcısı eklendi.\n";
        }

        // -------------------------------------------------------------
        // 2️⃣ BÖLGE KOORDİNATLARINI TAMAMLAMA (RegionSeeder Mantığı)
        // -------------------------------------------------------------
        echo "\n📍 Eksik bölge koordinatları geocode ediliyor...\n";
        $regionsWithoutCoords = Region::whereNull('latitude')->orWhereNull('longitude')->get();

        foreach ($regionsWithoutCoords as $region) {
            $address = "{$region->il} {$region->ilce} {$region->ad}";
            $coords = $this->geocodeAddress($address);

            if ($coords) {
                $region->update([
                    'latitude'  => $coords['latitude'],
                    'longitude' => $coords['longitude'],
                ]);
                echo "✅ Coords eklendi: {$address}\n";
            }
            sleep(1); // API Rate-limit
        }

        Schema::enableForeignKeyConstraints();

        echo "\n" . str_repeat("=", 60) . "\n";
        echo "🎉 TÜM İŞLEMLER BAŞARIYLA TAMAMLANDI!\n";
        echo str_repeat("=", 60) . "\n";
    }

    /**
     * Olası yollarda CSV dosyasını arar
     */
    private function findCsvFile(array $fileNames): ?string
    {
        foreach ($fileNames as $fileName) {
            $pathsToTry = [
                database_path("seeders/csv/{$fileName}"),
                database_path("seeders/{$fileName}"),
                storage_path("app/{$fileName}"),
                base_path("{$fileName}"),
            ];

            foreach ($pathsToTry as $path) {
                if (file_exists($path)) {
                    return $path;
                }
            }
        }

        return null;
    }

    /**
     * Esnek CSV Oku ve Veritabanına Bas
     */
    private function importCsvFile(string $filePath, string $tableName): void
    {
        $dbColumns = Schema::getColumnListing($tableName);

        if (($handle = fopen($filePath, 'r')) === false) {
            return;
        }

        $headers = fgetcsv($handle, 2000, ',');
        if (!$headers) {
            fclose($handle);
            return;
        }

        // Header temizliği (UTF-8 BOM kaldır)
        $headers = array_map(function ($header) {
            return trim(preg_replace('/\x{FEFF}/u', '', $header));
        }, $headers);

        $batchData = [];
        $batchSize = 100;
        $count = 0;

        while (($row = fgetcsv($handle, 2000, ',')) !== false) {
            if (count($headers) !== count($row)) {
                continue;
            }

            $rowData = array_combine($headers, $row);

            // 'role' -> 'rol' alanına dönüşüm
            if (isset($rowData['role']) && in_array('rol', $dbColumns)) {
                $rowData['rol'] = $rowData['role'];
                unset($rowData['role']);
            }

            // Sadece veritabanında var olan sütunları al
            $rowData = array_intersect_key($rowData, array_flip($dbColumns));

            // Veri temizliği (boşluklar, NULL dönüşümü)
            foreach ($rowData as $column => $value) {
                $value = trim($value);

                if ($value === '') {
                    $rowData[$column] = null;
                } elseif (is_numeric($value) && str_contains($value, '.')) {
                    // Tamsayı sütunları için float dönüşüm kontrolü
                    if (in_array($column, ['yas', 'miktar', 'su_litre', 'gida_paketi', 'cadir', 'ilac_adet'])) {
                        $rowData[$column] = (int) floatval($value);
                    }
                }
            }

            // Telefon uzunluğunu kısıtla
            if ($tableName === 'victims' && !empty($rowData['telefon'])) {
                $rowData['telefon'] = substr($rowData['telefon'], 0, 20);
            }

            // Zaman damgalarını ayarla
            if (in_array('created_at', $dbColumns) && empty($rowData['created_at'])) {
                $rowData['created_at'] = now();
            }
            if (in_array('updated_at', $dbColumns) && empty($rowData['updated_at'])) {
                $rowData['updated_at'] = now();
            }

            $batchData[] = $rowData;
            $count++;

            if (count($batchData) >= $batchSize) {
                DB::table($tableName)->insert($batchData);
                $batchData = [];
            }
        }

        if (!empty($batchData)) {
            DB::table($tableName)->insert($batchData);
        }

        fclose($handle);
        echo "✅ {$tableName}: {$count} kayıt aktarıldı.\n";
    }

    /**
     * Nominatim API üzerinden koordinat çeker
     */
    private function geocodeAddress(string $address): ?array
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'KDSProject/Seeder'
            ])->get('https://nominatim.openstreetmap.org/search', [
                'q'      => $address,
                'format' => 'json',
                'limit'  => 1,
            ]);

            if ($response->successful() && !empty($response->json())) {
                return [
                    'latitude'  => (float) $response->json()[0]['lat'],
                    'longitude' => (float) $response->json()[0]['lon'],
                ];
            }
        } catch (\Exception $e) {
            // API hatasında akışı bozmasın
        }

        return null;
    }
}
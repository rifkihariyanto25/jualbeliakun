<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Hero;
use App\Models\Skin;
use Illuminate\Support\Facades\DB;

class ImportMlbbHeroes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mlbb:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import MLBB heroes data from JSON file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting import MLBB Heroes...');

        $jsonPath = storage_path('app/data/mlbb_heroes.json');

        if (!File::exists($jsonPath)) {
            $this->error('File JSON tidak ditemukan di: ' . $jsonPath);
            return 1;
        }

        $heroes = json_decode(File::get($jsonPath), true);

        if (!$heroes) {
            $this->error('Gagal membaca file JSON atau file kosong');
            return 1;
        }

        $this->info('Found ' . count($heroes) . ' heroes in JSON file');
        $bar = $this->output->createProgressBar(count($heroes));
        $bar->start();

        DB::beginTransaction();

        try {
            foreach ($heroes as $heroData) {
                // Create or update hero
                $hero = Hero::updateOrCreate(
                    ['hero_name' => $heroData['hero_name']],
                    [
                        'url' => $heroData['url'] ?? null,
                        'hero_image' => $heroData['hero_image'] ?? null,
                        'total_skins' => count($heroData['skins'] ?? [])
                    ]
                );

                // Delete existing skins and recreate
                $hero->skins()->delete();

                // Create skins
                if (isset($heroData['skins']) && is_array($heroData['skins'])) {
                    foreach ($heroData['skins'] as $skinData) {
                        Skin::create([
                            'hero_id' => $hero->id,
                            'skin_name' => $skinData['skin_name'] ?? 'Unknown',
                            'skin_image' => $skinData['skin_image'] ?? null,
                            'category' => $skinData['category'] ?? 'Common'
                        ]);
                    }
                }

                $bar->advance();
            }

            DB::commit();
            $bar->finish();
            $this->newLine(2);
            $this->info('✓ Import selesai!');
            $this->info('Total heroes imported: ' . count($heroes));
            $this->info('Total skins imported: ' . Skin::count());

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $bar->finish();
            $this->newLine(2);
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}

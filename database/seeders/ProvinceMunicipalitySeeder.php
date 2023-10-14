<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceMunicipalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(database_path('seeders/province-municipalities.json'));
        $provinces = json_decode($json, true);

        foreach ($provinces as $province) {
            $p = Province::firstOrCreate([
                'name' => $province['name']
            ]);
            foreach ($province['municipalities'] as $municipality) {
                $p->municipalities()->create([
                    'name' => $municipality
                ]);
            }
        }
    }
}

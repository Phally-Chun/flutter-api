<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $settingsData = [
            "app_name" => "Mobile App",
            "app_version" => "1.0.0",
        ];

        $settings = [];

        foreach ($settingsData as $key => $value) {
            $settings[] = [
                'key' => $key,
                'value' => $value,
            ];
        }

        DB::table('settings')->insert($settings);
    }
}

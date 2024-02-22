<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // UserSeeder::class,
            SettingsSeeder::class,
            BannerSeeder::class,
            FruitSeeder::class,
            MealSeeder::class,
            VegetableSeeder::class,
            BeveragesSeeder::class,
        ]);
    }
}


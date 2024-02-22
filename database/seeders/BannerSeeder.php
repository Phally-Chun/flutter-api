<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $banners = [
            [
                'title' => 'Banner 1',
                'description' => 'This is banner 1',
                'image' => 'banner1.jpg',
            ],
            [
                'title' => 'Banner 2',
                'description' => 'This is banner 2',
                'image' => 'banner2.jpg',
            ],
            [
                'title' => 'Banner 3',
                'description' => 'This is banner 3',
                'image' => 'banner3.jpg',
            ],
        ];

        DB::table('banners')->insert($banners);
    }
}

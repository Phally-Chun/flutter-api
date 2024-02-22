<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BeveragesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $beverages = [
            [
                'name' => 'Coca Cola',
                'image' => 'coca-cola.jpg',
                'price' => 100,
            ],
            [
                'name' => 'Pepsi',
                'image' => 'pepsi.jpg',
                'price' => 50,
            ],
            [
                'name' => 'Sprite',
                'image' => 'sprite.jpg',
                'price' => 80,
            ],
        ];

        DB::table('beverages')->insert($beverages);
    }
}

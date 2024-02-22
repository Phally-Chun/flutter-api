<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FruitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $fruits = [
            [
                'name' => 'Mango',
                'description' => 'This is apple',
                'image' => 'https://images.pexels.com/photos/2294471/pexels-photo-2294471.jpeg?auto=compress&cs=tinysrgb&w=600',
                'price' => '2',
            ],
            [
                'name' => 'Stoberry',
                'description' => 'This is Stoberry',
                'image' => 'https://images.pexels.com/photos/2820144/pexels-photo-2820144.jpeg?auto=compress&cs=tinysrgb&w=600',
                'price' => '2',
            ],
            [
                'name' => 'Banana',
                'description' => 'This is Banana',
                'image' => 'https://images.pexels.com/photos/57556/pexels-photo-57556.jpeg?auto=compress&cs=tinysrgb&w=600',
                'price' => 2,
            ],
            [
                'name' => 'Apple',
                'description' => 'This is apple',
                'image' => 'https://images.pexels.com/photos/672101/pexels-photo-672101.jpeg?auto=compress&cs=tinysrgb&w=600',
                'price' => '3',
            ],
            [
                'name' => 'Grape',
                'description' => 'This is orange',
                'image' => 'https://images.pexels.com/photos/760281/pexels-photo-760281.jpeg?auto=compress&cs=tinysrgb&w=600',
                'price' => '4',
            ],
            [
                'name' => 'Orange',
                'description' => 'This is orange',
                'image' => 'https://images.pexels.com/photos/51958/oranges-fruit-vitamins-healthy-eating-51958.jpeg?auto=compress&cs=tinysrgb&w=600',
                'price' => '2',
            ],
            [
                'name' => 'Water Melon',
                'description' => 'This is Water Melon',
                'image' => 'https://images.pexels.com/photos/1313267/pexels-photo-1313267.jpeg?auto=compress&cs=tinysrgb&w=600',
                'price' => '2',
            ],
            [
                'name' => 'Cherry',
                'description' => 'This is Cherry',
                'image' => 'https://images.pexels.com/photos/768009/pexels-photo-768009.jpeg?auto=compress&cs=tinysrgb&w=600',
                'price' => '2',
            ],
        ];

        DB::table('fruits')->insert($fruits);
    }
}

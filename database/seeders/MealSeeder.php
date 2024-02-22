<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $meals = [
            [
                'name' => 'Burger',
                'description' => 'This is burger',
                'image' => 'https://images.pexels.com/photos/15037451/pexels-photo-15037451/free-photo-of-burger-with-turkish-bread-buns.jpeg?auto=compress&cs=tinysrgb&w=600',
                'price' => '100',
                'status' => 1,

            ],
            [
                'name' => 'Pizza',
                'description' => 'This is pizza',
                'image' => 'https://images.pexels.com/photos/5710204/pexels-photo-5710204.jpeg?auto=compress&cs=tinysrgb&w=600',
                'price' => '50',
                'status'=> 1,
            ],
            [
                'name' => 'Pasta',
                'description' => 'This is pasta',
                'image' => 'https://images.pexels.com/photos/552056/pexels-photo-552056.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                'price' => '80',
                'status'=> 1
            ],
            [
                'name' => 'Pasta',
                'description' => 'This is pasta',
                'image' => 'https://images.pexels.com/photos/699953/pexels-photo-699953.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '80',
                'status'=> 1
            ],
            [
                'name' => 'Pasta',
                'description' => 'This is pasta',
                'image' => 'https://images.pexels.com/photos/2116094/pexels-photo-2116094.jpeg?auto=compress&cs=tinysrgb&w=400',
                'price' => '80',
                'status'=> 1
            ],
            [
                'name' => 'Pasta',
                'description' => 'This is pasta',
                'image' => 'https://images.pexels.com/photos/5718025/pexels-photo-5718025.jpeg?auto=compress&cs=tinysrgb&w=600',
                'price' => '80',
                'status'=> 1
            ],
        ];
        DB::table('meals')->insert($meals);
    }
}

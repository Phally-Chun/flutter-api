<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VegetableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vegetables = [
            [
                'name' => 'Carrot',
                'description' => 'This is carrot',
                'image' => 'https://images.pexels.com/photos/1306559/pexels-photo-1306559.jpeg?auto=compress&cs=tinysrgb&w=600',
                'price' => '100',
                'status' => 1,
            ],
            [
                'name' => 'Cabbage',
                'description' => 'This is cabbage',
                'image' => 'https://images.pexels.com/photos/23388/pexels-photo.jpg?auto=compress&cs=tinysrgb&w=600',
                'price' => '50',
                'status'=> 1,
            ],
            [
                'name' => 'Potato',
                'description' => 'This is potato',
                'image' => 'https://images.pexels.com/photos/248420/pexels-photo-248420.jpeg?auto=compress&cs=tinysrgb&w=600',
                'price' => '80',
                'status'=> 1,
            ],
            [
                'name' => 'Potato',
                'description' => 'This is potato',
                'image' => 'https://images.pexels.com/photos/209515/pexels-photo-209515.jpeg?auto=compress&cs=tinysrgb&w=600',
                'price' => '80',
                'status'=> 1,
            ],
            [
                'name' => 'Chili',
                'description' => 'This is Chili',
                'image' => 'https://images.pexels.com/photos/1274613/pexels-photo-1274613.jpeg?auto=compress&cs=tinysrgb&w=600',
                'price' => '80',
                'status'=> 1,
            ],
            [
                'name' => 'Potato',
                'description' => 'This is potato',
                'image' => 'https://images.pexels.com/photos/248420/pexels-photo-248420.jpeg?auto=compress&cs=tinysrgb&w=600',
                'price' => '80',
                'status'=> 1,
            ],
        ];

        DB::table('vegetables')->insert($vegetables);
    }
}

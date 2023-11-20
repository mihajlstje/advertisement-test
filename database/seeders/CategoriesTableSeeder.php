<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Vehicles',

            'children' => [
                ['name' => 'Cars'],
                ['name' => 'Trucks'],
                ['name' => 'Motorcycles'],
            ],
        ]);

        Category::create([
            'name' => 'Computers',

            'children' => [
                [
                    'name' => 'Components',
                    'children' => [
                        ['name' => 'Graphic Cards'],
                        ['name' => 'Processors'],
                        ['name' => 'Mother Boards'],
                        ['name' => 'Hard Disks']
                    ]
                ],
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['name' => 'Subotica'],
            ['name' => 'Novi Sad'],
            ['name' => 'Beograd'],
            ['name' => 'Kragujevac'],
            ['name' => 'Nis'],
            ['name' => 'Pristina'],
        ];

        foreach($cities as $city){

            City::updateOrCreate($city);

        }
    }
}

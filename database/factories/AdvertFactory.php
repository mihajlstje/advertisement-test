<?php

namespace Database\Factories;

use App\Enums\Conditions;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Advert>
 */
class AdvertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = Category::factory()->create();
        $city = City::factory()->create();

        Storage::fake('uploads');

        return [
            'title' => fake()->title(),
            'desc' => fake()->text(),
            'category_id' => $category->id,
            'city_id' => $city->id,
            'price' => rand(1, 1000),
            'condition' => Conditions::NEW->value,
            'phone' => substr(fake()->phoneNumber(), 0, 15),
            'image' => UploadedFile::fake()->image('advert.jpg', 800, 600),
            'user_id' => User::factory()->create()->id
        ];
    }
}

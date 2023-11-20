<?php

namespace Tests\Feature\Controllers;

use App\Enums\Roles;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class=RolesTableSeeder');
    }

    public function test_any_user_can_see_home_page(): void
    {

        $result = $this->get('/');

        $result->assertStatus(200);

        $customer = User::factory()->create();
        $customer->assignRole(Roles::CUSTOMER->name());

        $result = $this->actingAs($customer)->get('/');
        $result->assertStatus(200);

        $admin = User::factory()->create();
        $admin->assignRole(Roles::ADMIN->name());
        $result = $this->actingAs($admin)->get('/');
        $result->assertStatus(200);
    }

    public function test_sort_validation(): void
    {

        $result = $this->get('/?sortBy=unknown');

        $result->assertSessionHasErrors(['sortBy', 'sortType']);

        $result = $this->get('/?sortBy=title');

        $result->assertSessionHasErrors(['sortType']);

        $result = $this->get('/?sortType=unknown');

        $result->assertSessionHasErrors(['sortType', 'sortBy']);

        $result = $this->get('/?sortType=desc');

        $result->assertSessionHasErrors(['sortBy']);

        $result = $this->get('/?sortBy=title&sortType=asc');

        $result->assertStatus(200);

    }

    public function test_category_validation(): void
    {

        $category = Category::factory()->create();

        $response = $this->get('/?category_id=test');

        $response->assertSessionHasErrors(['category_id']);

        $response = $this->get('/?category_id=100000000000');

        $response->assertSessionHasErrors(['category_id']);

        $response = $this->get('/?category_id='.$category->id);

        $response->assertStatus(200);

    }

    public function test_city_validation(): void
    {

        $city = City::factory()->create();

        $response = $this->get('/?city_id=test');

        $response->assertSessionHasErrors(['city_id']);

        $response = $this->get('/?city_id=100000000000');

        $response->assertSessionHasErrors(['city_id']);

        $response = $this->get('/?city_id='.$city->id);

        $response->assertStatus(200);

    }
}

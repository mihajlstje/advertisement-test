<?php

namespace Tests\Feature\Controllers;

use App\Enums\Conditions;
use App\Enums\Roles;
use App\Models\Advert;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdvertControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class=RolesTableSeeder');
    }

    public function test_guest_cant_see_index(): void
    {
        $response = $this->get('/advert');
        $response->assertRedirect();
    }

    public function test_admin_sees_all_adverts(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(Roles::ADMIN->name());

        $user1 = User::factory()->create();
        $user1->assignRole(Roles::CUSTOMER->name());

        $user2 = User::factory()->create();
        $user2->assignRole(Roles::CUSTOMER->name());

        Advert::factory()->count(5)->create(['user_id' => $user1->id]);
        Advert::factory()->count(5)->create(['user_id' => $user2->id]);

        $allAdvertsCount = Advert::get()->count();

        $response = $this->actingAs($admin)->get('/advert?limit=5');

        $response->assertViewHas('data', function($data) use($allAdvertsCount){
            return $data->total() == $allAdvertsCount;
        });
    }

    public function test_user_sees_only_his_own_adverts(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::CUSTOMER->name());

        $user2 = User::factory()->create();
        $user2->assignRole(Roles::CUSTOMER->name());

        Advert::factory()->count(5)->create(['user_id' => $user->id]);
        Advert::factory()->count(5)->create(['user_id' => $user2->id]);

        $allAdvertsCount = Advert::where(['user_id' => $user->id])->count();

        $response = $this->actingAs($user)->get('/advert?limit=5');

        $response->assertViewHas('data', function($data) use($allAdvertsCount){
            return $data->total() == $allAdvertsCount;
        });
    }

    public function test_guest_can_not_see_create_form(): void
    {
        $response = $this->get('/advert/create');

        $response->assertRedirect();
    }

    public function test_it_shows_create_form(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::CUSTOMER->name());

        $response = $this->actingAs($user)->get('/advert/create');

        $response->assertStatus(200);
        $response->assertSee('Create Advert');
    }

    public static function createDataProvider()
    {
        return [
            [// required
                ['title' => '', 'desc' => '', 'category_id' => '', 'city_id' => '', 'condition' => '', 'price' => '', 'phone' => '', 'image' => ''],
                ['title', 'desc', 'category_id', 'city_id', 'condition', 'price', 'phone', 'image']
            ],
            [// string test
                ['title' => 1, 'desc' => 2, 'phone' => 3],
                ['title', 'desc', 'phone']
            ],
            [// int test
                ['category_id' => 'string', 'city_id' => 'string', 'condition' => 'string', 'price' => 'string'],
                ['category_id', 'city_id', 'condition', 'price']
            ],
            [// string length test
                ['title' => str_repeat('x', 256), 'phone' => str_repeat('x', 8)],
                ['title', 'phone']
            ],
            [// price number size test
                ['price' => 12345678901],
                ['price']
            ],
        ];
    }

    /**
    * @dataProvider createDataProvider
    */
    public function test_it_validates_create($data, $invalidFields): void
    {

        $user = User::factory()->create();
        $user->assignRole(Roles::CUSTOMER->name());

        $response = $this->actingAs($user)->post('/advert', $data);
        $response->assertSessionHasErrors($invalidFields);
        $this->assertDatabaseCount('adverts', 0);

    }

    public function test_it_creates_advert(): void
    {

        $user = User::factory()->create();
        $user->assignRole(Roles::CUSTOMER->name());

        Storage::fake('uploads');

        $response = $this->actingAs($user)->post('/advert',[
            'title' => fake()->jobTitle(),
            'desc' => fake()->text(),
            'category_id' => Category::factory()->create()->id,
            'city_id' => City::factory()->create()->id,
            'condition' => Conditions::USED->value,
            'price' => rand(0, 100000000),
            'phone' => substr(fake()->phoneNumber(), 0, 15),
            'image' => UploadedFile::fake()->image('advert.jpg', 800, 600)
        ]);

        $response->assertRedirectToRoute('advert.index');
        $this->assertDatabaseCount('adverts', 1);

    }

    public function test_guest_can_not_see_update_form(): void
    {

        $advert = Advert::factory()->create();

        $response = $this->get('/advert/'.$advert->id.'/edit');

        $response->assertRedirect();
    }

    public function test_it_shows_edit_form(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::CUSTOMER->name());

        $advert = Advert::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/advert/'.$advert->id.'/edit');

        $response->assertStatus(200);
        $response->assertSee('Edit Advert');
    }

    public static function updateDataProvider()
    {
        return [
            [// required
                ['title' => '', 'desc' => '', 'category_id' => '', 'city_id' => '', 'condition' => '', 'price' => '', 'phone' => ''],
                ['title', 'desc', 'category_id', 'city_id', 'condition', 'price', 'phone']
            ],
            [// string test
                ['title' => 1, 'desc' => 2, 'phone' => 3],
                ['title', 'desc', 'phone']
            ],
            [// int test
                ['category_id' => 'string', 'city_id' => 'string', 'condition' => 'string', 'price' => 'string'],
                ['category_id', 'city_id', 'condition', 'price']
            ],
            [// string length test
                ['title' => str_repeat('x', 256), 'phone' => str_repeat('x', 8)],
                ['title', 'phone']
            ],
            [// price number size test
                ['price' => 12345678901],
                ['price']
            ],
        ];
    }

    /**
    * @dataProvider updateDataProvider
    */
    public function test_it_validates_update($data, $invalidFields): void
    {

        $user = User::factory()->create();
        $user->assignRole(Roles::CUSTOMER->name());

        $advert = Advert::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put('/advert/'.$advert->id, $data);
        $response->assertSessionHasErrors($invalidFields);

    }

    public function test_it_updates_advert(): void
    {

        $user = User::factory()->create();
        $user->assignRole(Roles::CUSTOMER->name());

        $advert = Advert::factory()->create(['user_id' => $user->id]);

        $data = [
            'title' => fake()->jobTitle(),
            'desc' => fake()->text(),
            'category_id' => Category::factory()->create()->id,
            'city_id' => City::factory()->create()->id,
            'condition' => Conditions::USED->value,
            'price' => rand(0, 100000000),
            'phone' => substr(fake()->phoneNumber(), 0, 15)
        ];

        $response = $this->actingAs($user)->put('/advert/'.$advert->id, $data);

        $response->assertSessionHas('success', 'Successfully updated advert!');
        $this->assertDatabaseHas('adverts', ['id' => $advert->id, 'title' => $data['title']]);
    }

    public function test_user_can_not_see_edit_form_for_advert_of_another_user(): void
    {

        $user1 = User::factory()->create();
        $user1->assignRole(Roles::CUSTOMER->name());

        $user2 = User::factory()->create();
        $user2->assignRole(Roles::CUSTOMER->name());

        $advert = Advert::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->get('/advert/'.$advert->id.'/edit');

        $response->assertForbidden();

    }

    public function test_user_can_not_update_advert_of_another_user(): void
    {

        $user1 = User::factory()->create();
        $user1->assignRole(Roles::CUSTOMER->name());

        $user2 = User::factory()->create();
        $user2->assignRole(Roles::CUSTOMER->name());

        $advert = Advert::factory()->create(['user_id' => $user1->id]);

        $data = [
            'title' => fake()->jobTitle(),
            'desc' => fake()->text(),
            'category_id' => Category::factory()->create()->id,
            'city_id' => City::factory()->create()->id,
            'condition' => Conditions::USED->value,
            'price' => rand(0, 100000000),
            'phone' => substr(fake()->phoneNumber(), 0, 15)
        ];

        $response = $this->actingAs($user2)->put('/advert/'.$advert->id, $data);

        $response->assertForbidden();

    }

    public function test_admin_can_see_edit_form_for_all_users(): void
    {

        $admin = User::factory()->create();
        $admin->assignRole(Roles::ADMIN->name());

        $user = User::factory()->create();
        $user->assignRole(Roles::CUSTOMER->name());

        $advert = Advert::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($admin)->get('/advert/'.$advert->id.'/edit');

        $response->assertStatus(200);

    }

    public function test_admin_can_update_advert_of_any_user(): void
    {

        $user1 = User::factory()->create();
        $user1->assignRole(Roles::CUSTOMER->name());

        $admin = User::factory()->create();
        $admin->assignRole(Roles::ADMIN->name());

        $advert = Advert::factory()->create(['user_id' => $user1->id]);

        $data = [
            'title' => fake()->jobTitle(),
            'desc' => fake()->text(),
            'category_id' => Category::factory()->create()->id,
            'city_id' => City::factory()->create()->id,
            'condition' => Conditions::USED->value,
            'price' => rand(0, 100000000),
            'phone' => substr(fake()->phoneNumber(), 0, 15)
        ];

        $response = $this->actingAs($admin)->put('/advert/'.$advert->id, $data);

        $response->assertSessionHas('success', 'Successfully updated advert!');
        $this->assertDatabaseHas('adverts', ['id' => $advert->id, 'title' => $data['title']]);

    }

    public function test_it_deletes_advert(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::CUSTOMER->name());

        $advert = Advert::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete('/advert/'.$advert->id);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('adverts', ['id' => $advert->id]);
    }

    public function test_user_can_not_delete_advert_of_another_user(): void
    {
        $user1 = User::factory()->create();
        $user1->assignRole(Roles::CUSTOMER->name());

        $user2 = User::factory()->create();
        $user2->assignRole(Roles::CUSTOMER->name());

        $advert = Advert::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->delete('/advert/'.$advert->id);
        $response->assertForbidden();
        $this->assertDatabaseHas('adverts', ['id' => $advert->id]);
    }

    public function test_admin_can_delete_advert_of_any_user(): void
    {
        $user1 = User::factory()->create();
        $user1->assignRole(Roles::CUSTOMER->name());

        $admin = User::factory()->create();
        $admin->assignRole(Roles::ADMIN->name());

        $advert = Advert::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($admin)->delete('/advert/'.$advert->id);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('adverts', ['id' => $advert->id]);
    }

    public function test_guest_can_see_any_advert(): void
    {

        $user = User::factory()->create();
        $advert = Advert::factory()->create(['user_id' => $user->id]);

        $response = $this->get('/advert/'.$advert->id);

        $response->assertStatus(200);
        $response->assertSee(['Contact', 'Description']);
    }
}

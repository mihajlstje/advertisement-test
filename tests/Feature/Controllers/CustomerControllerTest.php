<?php

namespace Tests\Feature\Controllers;

use App\Enums\Roles;
use App\Models\Advert;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class=RolesTableSeeder');
        $this->user = User::factory()->create();
        $this->user->assignRole(Roles::ADMIN->name());
    }

    public function test_all_endpoints_are_forbidden_for_guest(): void
    {

        $customer = User::factory()->create();
        $customer->assignRole(Roles::CUSTOMER->name());

        $response = $this->get('/customer');
        $response->assertForbidden();
        $response = $this->patch('/customer/'.$customer->id);
        $response->assertForbidden();
        $response = $this->delete('/customer/'.$customer->id);
        $response->assertForbidden();
    }

    public function test_all_endpoints_are_forbidden_for_user(): void
    {

        $user = User::factory()->create();
        $user->assignRole(Roles::CUSTOMER->name());

        $customer = User::factory()->create();
        $customer->assignRole(Roles::CUSTOMER->name());

        $response = $this->actingAs($user)->get('/customer');
        $response->assertForbidden();
        $response = $this->actingAs($user)->patch('/customer/'.$customer->id);
        $response->assertForbidden();
        $response = $this->actingAs($user)->delete('/customer/'.$customer->id);
        $response->assertForbidden();
    }

    public function test_sort_validation(): void
    {

        $result = $this->actingAs($this->user)->get('/customer?sortBy=unknown');

        $result->assertSessionHasErrors(['sortBy', 'sortType']);

        $result = $this->actingAs($this->user)->get('/customer?sortBy=name');

        $result->assertSessionHasErrors(['sortType']);

        $result = $this->actingAs($this->user)->get('/customer?sortBy=email');

        $result->assertSessionHasErrors(['sortType']);

        $result = $this->actingAs($this->user)->get('/customer?sortType=unknown');

        $result->assertSessionHasErrors(['sortType', 'sortBy']);

        $result = $this->actingAs($this->user)->get('/customer?sortType=desc');

        $result->assertSessionHasErrors(['sortBy']);

        $result = $this->actingAs($this->user)->get('/customer?sortBy=name&sortType=asc');

        $result->assertStatus(200);

    }

    public function test_index_method(): void
    {
        $users = User::factory()->count(5)->create();

        foreach($users as $user){
            $user->assignRole(Roles::CUSTOMER->name());
        }

        $admins = User::factory()->count(5)->create();

        foreach($admins as $admin){
            $admin->assignRole(Roles::ADMIN->name());
        }

        $response = $this->actingAs($this->user)->get('customer');

        $response->assertStatus(200);
        $response->assertViewHas('data', function($data) use($users){
            return $data->total() == $users->count();
        });

    }

    public function test_it_ban_customer(): void
    {

        $customer = User::factory()->create([
            'password' => Hash::make('password')
        ]);
        $customer->assignRole(Roles::CUSTOMER->name());

        $response = $this->actingAs($this->user)->patch('/customer/'.$customer->id);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('users', ['id' => $customer->id, 'active' => false]);

        auth()->logout();

        $response = $this->post('/login', [
            'email' => $customer->email,
            'password' => 'password',
        ]);

        $this->assertGuest();

    }

    public function test_it_unban_customer(): void
    {

        $customer = User::factory()->create([
            'password' => Hash::make('password'),
            'active' => false
        ]);
        $customer->assignRole(Roles::CUSTOMER->name());

        $response = $this->actingAs($this->user)->patch('/customer/'.$customer->id);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('users', ['id' => $customer->id, 'active' => true]);

        $this->post('/login', [
            'email' => $customer->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

    }

    public function test_it_deletes_customer_and_all_their_adverts(): void
    {
        $customer = User::factory()->create();
        $customer->assignRole(Roles::CUSTOMER->name());

        Advert::factory()->count(5)->create(['user_id' => $customer->id]);

        $response = $this->actingAs($this->user)->delete('/customer/'.$customer->id);

        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('adverts', ['user_id' => $customer->id]);
    }

    public function test_it_can_not_ban_admin_role(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(Roles::ADMIN->name());

        $response = $this->actingAs($this->user)->patch('/customer/'.$admin->id);
        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $admin->id, 'active' => true]);
    }

    public function test_it_can_not_delete_admin_role(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(Roles::ADMIN->name());

        $response = $this->actingAs($this->user)->delete('/customer/'.$admin->id);
        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }
}

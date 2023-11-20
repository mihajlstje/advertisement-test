<?php

namespace Tests\Feature\Controllers;

use App\Enums\Roles;
use App\Models\Advert;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
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

        $category = Category::factory()->create();

        $response = $this->get('/category');
        $response->assertForbidden();
        $response = $this->get('/category/'.$category->id);
        $response->assertForbidden();
        $response = $this->put('/category/'.$category->id);
        $response->assertForbidden();
        $response = $this->post('/category');
        $response->assertForbidden();
        $response = $this->delete('/category/'.$category->id);
        $response->assertForbidden();
    }

    public function test_all_endpoints_are_forbidden_for_user(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::CUSTOMER->name());

        $category = Category::factory()->create();

        $response = $this->actingAs($user)->get('/category');
        $response->assertForbidden();
        $response = $this->actingAs($user)->get('/category/'.$category->id);
        $response->assertForbidden();
        $response = $this->actingAs($user)->put('/category/'.$category->id);
        $response->assertForbidden();
        $response = $this->actingAs($user)->post('/category');
        $response->assertForbidden();
        $response = $this->actingAs($user)->delete('/category/'.$category->id);
        $response->assertForbidden();
    }

    public function test_sort_validation(): void
    {

        $result = $this->actingAs($this->user)->get('/category?sortBy=unknown');

        $result->assertSessionHasErrors(['sortBy', 'sortType']);

        $result = $this->actingAs($this->user)->get('/category?sortBy=name');

        $result->assertSessionHasErrors(['sortType']);

        $result = $this->actingAs($this->user)->get('/category?sortBy=parent');

        $result->assertSessionHasErrors(['sortType']);

        $result = $this->actingAs($this->user)->get('/category?sortType=unknown');

        $result->assertSessionHasErrors(['sortType', 'sortBy']);

        $result = $this->actingAs($this->user)->get('/category?sortType=desc');

        $result->assertSessionHasErrors(['sortBy']);

        $result = $this->actingAs($this->user)->get('/category?sortBy=name&sortType=asc');

        $result->assertStatus(200);

    }

    public function test_index_method(): void
    {
        $categories = Category::factory()->count(5)->create();

        $response = $this->actingAs($this->user)->get('category');

        $response->assertStatus(200);
        $response->assertViewHas('data', function($data) use($categories){
            return $data->total() == $categories->count();
        });

    }

    public function test_it_shows_create_form():void
    {

        $response = $this->actingAs($this->user)->get('category/create');

        $response->assertStatus(200);
        $response->assertSee('Create Category');

    }

    public function test_it_validates_creation():void
    {

        $response = $this->actingAs($this->user)->post('category', [
            'name' => '',
            'parent_id' => 'xxx'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['name', 'parent_id']);
    }

    public function test_it_creates_category(): void
    {
        $data = [
            'name' => 'XXX',
        ];

        $response = $this->actingAs($this->user)->post('category', $data);

        $response->assertRedirect(route('category.index'));
        $this->assertDatabaseHas('categories', ['name' => $data['name']]);
    }

    public function it_shows_edit_form():void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)->get('/category/'.$category->id);

        $response->assertStatus(200);
        $response->assertSee('Edit Category');
    }

    public function it_updates_category():void
    {
        $category = Category::factory()->create();

        $data = [
            'name' => 'xxx'
        ];

        $response = $this->actingAs($this->user)->put('/category/'.$category->id, $data);

        $response->assertSessionHas('success', 'Successfully updated category!');
        $response->assertDatabaseHas('categories', ['id' => $category->id, 'name' => $data['name']]);
    }

    public function test_it_will_not_set_category_as_its_own_parent(): void
    {

        $category = Category::factory()->create();

        $data = [
            'name' => 'xxx',
            'parent_id' => $category->id
        ];

        $response = $this->actingAs($this->user)->put('/category/'.$category->id, $data);
        $response->assertSessionHasErrors(['parent_id']);
        $this->assertDatabaseMissing('categories', ['id' => $category->id, 'parent_id' => $data['parent_id']]);

    }

    public function test_it_deletes_category(): void
    {

        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)->delete('/category/'.$category->id);

        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);

    }

    public function test_it_will_not_delete_category_if_there_are_adverts_which_belong_to_it(): void
    {

        $category = Category::factory()->create();
        Advert::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->user)->delete('/category/'.$category->id);
        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['id' => $category->id]);

    }
}

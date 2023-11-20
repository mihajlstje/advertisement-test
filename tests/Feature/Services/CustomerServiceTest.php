<?php

namespace Tests\Feature\Services;

use App\DTO\Customer\CustomerListDTO;
use App\Enums\Roles;
use App\Interfaces\CustomerServiceInterface;
use App\Models\Advert;
use App\Models\User;
use App\Services\CustomerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class CustomerServiceTest extends TestCase
{
    use RefreshDatabase;

    private CustomerServiceInterface $customerService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class=RolesTableSeeder');
        $this->customerService = new CustomerService();
    }

    public function test_index_pagination(): void
    {

        $users = User::factory()->count(5)->create();
        
        foreach($users as $user){
            $user->assignRole(Roles::CUSTOMER->name());
        }

        $customerListDTO = new CustomerListDTO(
            sortBy: null,
            sortType: null,
            limit: 3,
            page: 1
        );

        $result = $this->customerService->index($customerListDTO);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(5, $result->total());
        $this->assertEquals(3, $result->count());
        $this->assertEquals(1, $result->currentPage());

    }

    public function test_index_will_not_return_admin_users(): void
    {
        $count = User::role(Roles::CUSTOMER->name())->count();

        $users = User::factory()->count(5)->create();
        
        foreach($users as $user){
            $user->assignRole(Roles::ADMIN->name());
        }

        $customerListDTO = new CustomerListDTO(
            sortBy: null,
            sortType: null,
            limit: 100,
            page: 1
        );

        $result = $this->customerService->index($customerListDTO);

        $this->assertSame($count, $result->count());
    }

    public function test_it_will_ban_unban_user(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::CUSTOMER->name());

        $this->assertTrue($user->active);
        
        $this->customerService->ban($user);

        $this->assertTrue(!$user->active);

        $this->customerService->ban($user);

        $this->assertTrue($user->active);

    }

    public function test_it_will_delete_user(): void
    {
        $user = User::factory()->create();

        $adverts = Advert::factory()->count(5)->create(['user_id' => $user->id]);

        $result = $this->customerService->delete($user);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('adverts', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}

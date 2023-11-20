<?php

namespace Tests\Feature\Services;

use App\DTO\Category\CategoryDTO;
use App\DTO\Category\CategoryListDTO;
use App\Interfaces\CategoryServiceInterface;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    private CategoryServiceInterface $categoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class=RolesTableSeeder');
        $this->categoryService = new CategoryService();
    }
    
    public function test_index_pagination(): void
    {

        Category::factory()->count(5)->create();

        $categoryListDTO = new CategoryListDTO(
            sortBy: null,
            sortType: null,
            limit: 3,
            page: 1
        );

        $result = $this->categoryService->index($categoryListDTO);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(5, $result->total());
        $this->assertEquals(3, $result->count());
        $this->assertEquals(1, $result->currentPage());

    }

    public function test_index_sorting(): void
    {

        $categories = Category::factory()->count(5)->create();

        $categoryListDTO = new CategoryListDTO(
            sortBy: 'id',
            sortType: 'desc',
            limit: null,
            page: null
        );

        $result = $this->categoryService->index($categoryListDTO);

        $this->assertEquals($categories->last()->id, $result->first()->id);

    }

    public function test_it_creates_category(): void
    {
        
        $categoryDTO = new CategoryDTO(
            fake()->name(),
            null
        );

        $category = $this->categoryService->create($categoryDTO);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertSame($category->name, $categoryDTO->name);

        $categoryDTO = new CategoryDTO(
            fake()->name(),
            $category->id
        );

        $category2 = $this->categoryService->create($categoryDTO);

        $this->assertSame($category2->parent_id, $category->id);

    }

    public function test_it_updates_category(): void
    {

        $category = Category::factory()->create();

        $categoryDTO = new CategoryDTO(
            fake()->name(),
            null
        );

        $result = $this->categoryService->update($category, $categoryDTO);

        $this->assertSame($categoryDTO->name, $result->name);
    }

    public function test_all_method(): void
    {
        $count = Category::get()->count();

        $result = $this->categoryService->all();

        $this->assertCount($count, $result);
    }

}

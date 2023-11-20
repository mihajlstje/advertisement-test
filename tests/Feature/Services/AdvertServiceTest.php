<?php

namespace Tests\Feature\Services;

use App\DTO\Advert\AdvertDTO;
use App\DTO\Advert\AdvertListDTO;
use App\Enums\Conditions;
use App\Interfaces\AdvertServiceInterface;
use App\Interfaces\CategoryServiceInterface;
use App\Models\Advert;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use App\Services\AdvertService;
use App\Services\CategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdvertServiceTest extends TestCase
{
    use RefreshDatabase;


    private CategoryServiceInterface $categoryService;
    private AdvertServiceInterface $advertService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class=RolesTableSeeder');
        $this->categoryService = new CategoryService();
        $this->advertService = new AdvertService($this->categoryService);
    }

    public function test_index_return_type(): void
    {

        Advert::factory()->count(5)->create();

        $advertListDTO = AdvertListDTO::factory([
            'limit' => 3,
            'page' => 1
        ]);

        $result = $this->advertService->index($advertListDTO);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(5, $result->total());
        $this->assertEquals(3, $result->count());
        $this->assertEquals(1, $result->currentPage());

    }

    public function test_index_sorting(): void
    {

        $adverts = Advert::factory()->count(5)->create();

        $advertListDTO = AdvertListDTO::factory([
            'sortBy' => 'id',
            'sortType' => 'desc'
        ]);

        $result = $this->advertService->index($advertListDTO);

        $this->assertEquals($result->first()->id, $adverts->last()->id);

    }

    public function test_index_search_by_keyword(): void
    {

        $str = Str::random(5);

        Advert::factory()->create(['title' => $str.Str::random(5)]);
        Advert::factory()->create(['desc' => Str::random(5).$str.Str::random(5)]);
        Advert::factory()->count(3)->create();

        $advertListDTO = AdvertListDTO::factory(['keyword' => $str]);

        $result = $this->advertService->index($advertListDTO);
        
        $this->assertCount(2, $result);

    }

    public function test_index_filter_by_category(): void
    {

        $category = Category::factory()->create();

        Advert::factory()->create(['category_id' => $category->id]);
        Advert::factory()->create(['category_id' => $category->id]);
        Advert::factory()->count(3)->create();

        $advertListDTO = AdvertListDTO::factory(['category_id' => $category->id]);

        $result = $this->advertService->index($advertListDTO);
        
        $this->assertCount(2, $result);

    }

    public function test_index_filter_by_city(): void
    {

        $city = City::factory()->create();

        Advert::factory()->create(['city_id' => $city->id]);
        Advert::factory()->create(['city_id' => $city->id]);
        Advert::factory()->count(3)->create();

        $advertListDTO = AdvertListDTO::factory(['city_id' => $city->id]);

        $result = $this->advertService->index($advertListDTO);
        
        $this->assertCount(2, $result);

    }

    public function test_index_filter_by_price(): void
    {

        $minPrice = 100;
        $maxPrice = 1000;

        Advert::factory()->create(['price' => 99]);
        Advert::factory()->create(['price' => rand($minPrice, $maxPrice)]);
        Advert::factory()->create(['price' => rand($minPrice, $maxPrice)]);
        Advert::factory()->create(['price' => 1001]);

        $advertListDTO = AdvertListDTO::factory(['minPrice' => $minPrice, 'maxPrice' => $maxPrice]);

        $result = $this->advertService->index($advertListDTO);
        
        $this->assertCount(2, $result);

    }

    public function test_it_creates_advert(): void
    {

        Storage::fake('uploads');

        $user = User::factory()->create();

        $this->be($user);

        $advertDTO = new AdvertDTO(
            title: fake()->title(),
            desc: fake()->text(),
            category_id: Category::factory()->create()->id,
            city_id: City::factory()->create()->id,
            price: rand(1, 1000),
            condition: Conditions::NEW->value,
            phone: substr(fake()->phoneNumber(), 0, 15),
            image: UploadedFile::fake()->image('advert.jpg', 800, 600)
        );

        $result = $this->advertService->create($advertDTO);

        $this->assertInstanceOf(Advert::class, $result);
        $this->assertSame($advertDTO->title, $result->title);
        $this->assertSame($user->id, $result->user_id);

    }

    public function test_it_updates_advert(): void
    {

        $advert = Advert::factory()->create();

        $advertDTO = new AdvertDTO(
            title: fake()->title(),
            desc: fake()->text(),
            category_id: Category::factory()->create()->id,
            city_id: City::factory()->create()->id,
            price: rand(1, 1000),
            condition: Conditions::NEW->value,
            phone: substr(fake()->phoneNumber(), 0, 15),
            image: UploadedFile::fake()->image('advert.jpg', 800, 600)
        );

        $result = $this->advertService->update($advert, $advertDTO);

        $this->assertInstanceOf(Advert::class, $result);
        $this->assertSame($advertDTO->title, $result->title);

    }

    public function test_it_deletes_advert(): void
    {
        $advert = Advert::factory()->create();

        $result = $this->advertService->delete($advert);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('adverts', ['id' => $advert->id]);
    }
}

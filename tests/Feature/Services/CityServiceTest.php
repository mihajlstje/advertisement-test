<?php

namespace Tests\Feature\Services;

use App\Interfaces\CityServiceInterface;
use App\Models\Category;
use App\Models\City;
use App\Services\CityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CityServiceTest extends TestCase
{
    use RefreshDatabase;

    private CityServiceInterface $cityService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cityService = new CityService();
    }

    public function test_all_method(): void
    {
        $count = City::get()->count();

        $result = $this->cityService->all();

        $this->assertCount($count, $result);
    }

}

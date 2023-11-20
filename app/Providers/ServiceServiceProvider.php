<?php

namespace App\Providers;

use App\Interfaces\AdvertServiceInterface;
use App\Interfaces\CategoryServiceInterface;
use App\Interfaces\CityServiceInterface;
use App\Interfaces\CustomerServiceInterface;
use App\Services\AdvertService;
use App\Services\CategoryService;
use App\Services\CityService;
use App\Services\CustomerService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $singletons = [
        CategoryServiceInterface::class => CategoryService::class,
        CityServiceInterface::class => CityService::class,
        AdvertServiceInterface::class => AdvertService::class,
        CustomerServiceInterface::class => CustomerService::class
    ];

    /**
     * Register services.
     */
    public function provides(): array
    {
        return [
            CategoryServiceInterface::class,
            CityServiceInterface::class,
            AdvertServiceInterface::class,
            CustomerServiceInterface::class
        ];
    }
}

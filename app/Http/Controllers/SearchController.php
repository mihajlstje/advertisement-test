<?php

namespace App\Http\Controllers;

use App\DTO\Advert\AdvertListDTO;
use App\Http\Requests\Advert\AdvertListRequest;
use App\Interfaces\AdvertServiceInterface;
use App\Interfaces\CategoryServiceInterface;
use App\Interfaces\CityServiceInterface;
use Illuminate\Support\Facades\View;

class SearchController extends Controller
{
    
    public function __construct(
        private AdvertServiceInterface $advertService,
        private CategoryServiceInterface $categoryService,
        private CityServiceInterface $cityService
    )
    {
        
    }

    public function __invoke(AdvertListRequest $request){

        $categories = $this->categoryService->getCategoryTree();
        
        $cities = $this->cityService->all();

        $selectedCategory = !is_null($request->category_id) ? $this->categoryService->find($request->category_id) : null;

        $data = $this->advertService->index(AdvertListDTO::fromRequest($request));

        return View::make('search.index', compact('data', 'categories', 'cities', 'selectedCategory'));

    }

}

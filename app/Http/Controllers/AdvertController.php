<?php

namespace App\Http\Controllers;

use App\DTO\Advert\AdvertDTO;
use App\DTO\Advert\AdvertListDTO;
use App\Enums\Roles;
use App\Http\Middleware\AdvertBelongsToUser;
use App\Http\Requests\Advert\AdvertListRequest;
use App\Http\Requests\Advert\AdvertRequest;
use App\Interfaces\AdvertServiceInterface;
use App\Interfaces\CategoryServiceInterface;
use App\Interfaces\CityServiceInterface;
use App\Models\Advert;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\View;

class AdvertController extends Controller
{
    public function __construct(
        private CategoryServiceInterface $categoryService,
        private CityServiceInterface $cityService,
        private AdvertServiceInterface $advertService    
    )
    {
        $this->middleware(AdvertBelongsToUser::class, ['only' => ['edit', 'update', 'destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(AdvertListRequest $request)
    {

        $user_id = auth()->user()->hasRole(Roles::CUSTOMER->name()) ? auth()->id() : null;

        $data = $this->advertService->index(AdvertListDTO::fromRequest($request), $user_id);

        return View::make('advert.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $categories = $this->categoryService->getCategoryTree();
        $cities = $this->cityService->all();

        return View::make('advert.create', compact('categories', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdvertRequest $request)
    {
        $this->advertService->create(AdvertDTO::fromRequest($request));

        return redirect()->route('advert.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Advert $advert)
    {
        $categories = $this->categoryService->getCategoryTree();
        $cities = $this->cityService->all();

        return View::make('advert.edit', compact('advert', 'categories', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdvertRequest $request, Advert $advert)
    {
        $this->advertService->update($advert, AdvertDTO::fromRequest($request));

        return redirect()->back()->with('success', 'Successfully updated advert!');
    }

    /**
     * Show the specified resource.
     */
    public function show(Advert $advert){

        return View::make('advert.show', compact('advert'));
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Advert $advert): JsonResponse
    {
        $this->advertService->delete($advert);

        return response()->json(['success' => true]);
    }
}

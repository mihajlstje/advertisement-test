<?php

namespace App\Http\Controllers;

use App\DTO\Category\CategoryDTO;
use App\DTO\Category\CategoryListDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryListRequest;
use App\Http\Requests\Category\CategoryRequest;
use App\Interfaces\CategoryServiceInterface;
use App\Models\Category;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends Controller
{

    public function __construct(private CategoryServiceInterface $categoryService)
    {
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index(CategoryListRequest $request)
    {

        $data = $this->categoryService->index(CategoryListDTO::fromRequest($request));

        return View::make('category.index', compact('data'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $categories = $this->categoryService->all();

        return View::make('category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $this->categoryService->create(CategoryDTO::fromRequest($request));

        return redirect()->route('category.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $categories = $this->categoryService->all();

        return View::make('category.edit', [
            'category' => $category,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $this->categoryService->update($category, CategoryDTO::fromRequest($request));

        return redirect()->back()->with('success', 'Successfully updated category!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        $this->categoryService->delete($category);

        return response()->json(['success' => true]);
    }
}

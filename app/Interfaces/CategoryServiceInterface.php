<?php

namespace App\Interfaces;

use App\DTO\Category\CategoryDTO;
use App\DTO\Category\CategoryListDTO;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Kalnoy\Nestedset\Collection;

interface CategoryServiceInterface {

    public function create(CategoryDTO $dto): Category;

    public function index(CategoryListDTO $dto): LengthAwarePaginator;

    public function update(Category $category, CategoryDTO $dto): Category;

    public function delete(Category $category): bool;

    public function getCategoryTree(): Collection;

    public function all(): Collection;

    public function find(int $id): Category;

}
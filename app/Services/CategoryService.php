<?php

namespace App\Services;

use App\DTO\Category\CategoryDTO;
use App\DTO\Category\CategoryListDTO;
use App\Interfaces\CategoryServiceInterface;
use App\Models\Advert;
use App\Models\Category;
use Kalnoy\Nestedset\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use LogicException;

class CategoryService implements CategoryServiceInterface {

    public function find(int $id): Category
    {

        return Category::findOrFail($id);

    }

    public function index(CategoryListDTO $dto): LengthAwarePaginator
    {

        $query = DB::table('categories')
        ->select(['categories.id', 'categories.name', DB::raw('parent_category.name as parent')])
        ->leftJoin('categories as parent_category', 'parent_category.id', '=', 'categories.parent_id');

        if(!is_null($dto->sortBy)){
            $query->orderBy($dto->sortBy, $dto->sortType);
        }

        return $query->paginate($dto->limit ?? 10);

    }

    public function create(CategoryDTO $dto): Category
    {

        try{

            $category = null;

            DB::transaction(function() use(&$category, $dto){

                $category = Category::create([
                    'name' => $dto->name
                ]);
        
                if(!is_null($dto->parent_id)){
        
                    $parent = Category::find($dto->parent_id);
        
                    $parent->appendNode($category);
        
                }

            });
    
            return $category;

        }catch(\Exception $e){

            throw ValidationException::withMessages([
                'name' => 'An error occurred, please contact our support team.'
            ]);

        }
        
        
    }

    public function update(Category $category, CategoryDTO $dto): Category
    {
        try{

            DB::transaction(function() use(&$category, $dto){

                $category->update(['name' => $dto->name]);

                if(!is_null($dto->parent_id)){
        
                    $parent = Category::find($dto->parent_id);
        
                    if($parent->id !== $category->parent_id){
                        
                        $category->parent()->associate($parent)->save();

                    }
                    
        
                }

            });

            return $category;

        }catch(LogicException $e){

            throw ValidationException::withMessages([
                'parent_id' => $e->getMessage()
            ]);

        }catch(\Exception $e){

            throw ValidationException::withMessages([
                'name' => 'An error occurred, please contact our support team.'
            ]);

        }

    }

    public function delete(Category $category): bool
    {


        if($category->adverts->count() > 0){
            
            throw ValidationException::withMessages([
                'message' => 'There are adverts which belong to this category, delete them first.'
            ]);
            
        }

        $ids = $category->descendants->map(fn($item) => $item->id)->toArray();

        $adverts = Advert::whereIn('category_id', $ids)->count();

        if($adverts > 0){

            throw ValidationException::withMessages([
                'message' => 'There are adverts which belong to children categories of this category, delete them first.'
            ]);

        }

        return $category->delete();

    }

    public function getCategoryTree(): Collection
    {
        
        return Category::get()->toTree();

    }

    public function all(): Collection
    {

        return Category::get();

    }

}
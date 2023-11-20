<?php

namespace App\Services;

use App\DTO\Advert\AdvertDTO;
use App\DTO\Advert\AdvertListDTO;
use App\Enums\Roles;
use App\Interfaces\AdvertServiceInterface;
use App\Interfaces\CategoryServiceInterface;
use App\Models\Advert;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AdvertService implements AdvertServiceInterface {

    public function __construct(private CategoryServiceInterface $categoryService)
    {
        
    }

    public function index(AdvertListDTO $dto, ?int $user_id = null): LengthAwarePaginator
    {


        $query = DB::table('adverts')
        ->join('categories', 'categories.id', '=', 'adverts.category_id')
        ->join('users', 'users.id', '=', 'adverts.user_id')
        ->join('cities', 'cities.id', '=', 'adverts.city_id')
        ->select([
            'adverts.id', 
            'adverts.image', 
            'title', 
            'categories.name as category', 
            'users.name as userName', 
            'price', 
            'adverts.created_at', 
            'cities.name as city',
            DB::raw('case when length(adverts.desc) > 100 then concat(substring(adverts.desc, 1, 100), "...") else adverts.desc end as short_desc')
        ])
        ->where(function($q) use($dto, $user_id){

            if(!is_null($user_id)){
                $q->where(['user_id' => $user_id]);
            }

            if(!is_null($dto->keyword)){

                $q->where(function($q) use($dto){
                    
                    $q->where('title', 'LIKE', '%'.$dto->keyword.'%');
                    $q->orWhere('desc', 'LIKE', '%'.$dto->keyword.'%');
                    $q->orWhere('price', 'LIKE', '%'.$dto->keyword.'%');

                });

            }

            if(!is_null($dto->category_id)){

                $category = $this->categoryService->find($dto->category_id);
                $ids = [];

                if(count($category->children) == 0) $ids = [$category->id];
                else {
                    $ids = $category->descendants->map(fn($item) => $item->id)->toArray();
                }

                $q->whereIn('category_id', $ids);

            }

            if(!is_null($dto->minPrice)){

                $q->where('price', '>=', $dto->minPrice);

            }

            if(!is_null($dto->maxPrice)){

                $q->where('price', '<=', $dto->maxPrice);

            }

            if(!is_null($dto->city_id)){

                $q->where('city_id', '=', $dto->city_id);

            }

        });
        
        if(!is_null($dto->sortBy)){
            $query->orderBy($dto->sortBy, $dto->sortType);
        }

        return $query->paginate($dto->limit ?? 10);
    
    }

    public function create(AdvertDTO $dto): Advert
    {
        
        try{

            $path = $dto->image->store('uploads');

            return Advert::create([
                'user_id' => auth()->id(),
                'title' => $dto->title,
                'desc' => $dto->desc,
                'category_id' => $dto->category_id,
                'city_id' => $dto->city_id,
                'condition' => $dto->condition,
                'price' => $dto->price,
                'phone' => $dto->phone,
                'image' => $path
            ]);


        }catch(\Exception $e){

            if(isset($path)){
                Storage::delete($path);
            }

            throw ValidationException::withMessages(['title' => 'An error ocurred, please contact our support team.']);

        }
  
    }

    public function update(Advert $advert, AdvertDTO $dto): Advert
    {

        try{

            if(!is_null($dto->image)){
                
                $path = $dto->image->store('uploads');

            }

            $advert->update([
                'title' => $dto->title,
                'desc' => $dto->desc,
                'category_id' => $dto->category_id,
                'city_id' => $dto->city_id,
                'condition' => $dto->condition,
                'price' => $dto->price,
                'phone' => $dto->phone,
                'image' => $path ?? $advert->image
            ]);

            return $advert;


        }catch(\Exception $e){

            if(isset($path)){
                Storage::delete($path);
            }

            throw ValidationException::withMessages(['title' => 'An error ocurred, please contact our support team.']);

        }

    }

    public function delete(Advert $advert): bool
    {
        return $advert->delete();
    }
}
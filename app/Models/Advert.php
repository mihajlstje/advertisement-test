<?php

namespace App\Models;

use App\Enums\Conditions;
use App\Enums\Roles;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Advert extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'category_id', 'city_id', 'title', 'desc', 'price', 'condition', 'phone', 'image'];

    protected $casts = [
        'condition' => Conditions::class
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}

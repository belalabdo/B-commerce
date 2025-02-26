<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "price",
        "desc",
        "category_id",
        "brand_id",
        "stock_quantity",
        "discount"
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}

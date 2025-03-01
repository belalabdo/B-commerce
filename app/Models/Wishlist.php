<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = [
        "user_id"
    ];

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }
}

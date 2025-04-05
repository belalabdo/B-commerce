<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        "user_id",
        "country",
        "city",
        "state",
        "postal_code",
        "address_line_1",
        "address_line_2",
        "is_default",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

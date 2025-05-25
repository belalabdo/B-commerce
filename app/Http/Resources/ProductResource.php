<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "desc" => $this->desc,
            "price" => $this->price,
            "category" => $this->category,
            "brand" => $this->brand,
            "stock_quantity" => $this->stock_quantity,
            "rate" => $this->ratings_avg_rate,
            "discount" => $this->discount
        ];
    }
}

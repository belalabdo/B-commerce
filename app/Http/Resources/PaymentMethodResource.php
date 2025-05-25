<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "method_name" => $this->method_name,
            "account_number" => $this->account_number,
            "provider" => $this->provider,
            "expiry_date" => $this->expiry_date,
            "is_default" => $this->is_default,
        ];
    }
}

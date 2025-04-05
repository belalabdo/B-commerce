<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use function Pest\Laravel\get;

class AddressesController extends Controller
{
    private function parseUserToken(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->header("token"));
        return $token->tokenable_id;
    }
    private function validate(Request $request, $required = "required")
    {
        $addressData = Validator::make($request->all(), [
            "country" => "$required|alpha",
            "city" => "$required|alpha",
            "state" => "$required|alpha",
            "postal_code" => "numeric",
            "address_line_1" => "$required|string",
            "address_line_2" => "string"
        ])->validate();
        if ($required)
            $addressData["user_id"] = $this->parseUserToken($request);
        return $addressData;
    }
    public function get(Request $request, $id)
    {
        $address = Address::where("id", $id)->first();

        return response([
            "id" => $address->id,
            "country" => $address->country,
            "city" => $address->city,
            "state" => $address->state,
            "postal_code" => $address->postal_code,
            "address_line_1" => $address->address_line_1,
            "address_line_2" => $address->address_line_2,
            "is_default" => $address->is_default
        ]);
    }
    public function getAll(Request $request)
    {
        $userId = $this->parseUserToken($request);
        $addresses = Address::where("user_id", $userId)->get();
        $addresses = $addresses->map(function (Address $address) {
            return [
                "id" => $address->id,
                "country" => $address->country,
                "city" => $address->city,
                "state" => $address->state,
                "postal_code" => $address->postal_code,
                "address_line_1" => $address->address_line_1,
                "address_line_2" => $address->address_line_2,
                "is_default" => $address->is_default
            ];
        });
        return response($addresses, 200);
    }
    public function add(Request $request)
    {
        $address = Address::create($this->validate($request));
        return response([
            "message" => "Address added sucessfully",
            "address" => [
                "id" => $address->id,
                "country" => $address->country,
                "city" => $address->city,
                "state" => $address->state,
                "postal_code" => $address->postal_code,
                "address_line_1" => $address->address_line_1,
                "address_line_2" => $address->address_line_2,
                "is_default" => $address->is_default
            ]
        ]);
    }
    public function update(Request $request, $id)
    {
        $userId = $this->parseUserToken($request);
        $newAddressData = $this->validate($request, false);
        $address = Address::where("id", $id)->first();
        if ($userId != $address->user_id)
            return response(["message" => "Unauthorized request !",], 401);

        $address->update($newAddressData);

        return response([
            "message" => "Address updated sucessfully",
            "address" => [
                "id" => $address->id,
                "country" => $address->country,
                "city" => $address->city,
                "state" => $address->state,
                "postal_code" => $address->postal_code,
                "address_line_1" => $address->address_line_1,
                "address_line_2" => $address->address_line_2,
                "is_default" => $address->is_default
            ]
        ]);
    }
    public function delete(Request $request, $id)
    {
        $userId = $this->parseUserToken($request);
        $address = Address::where("id", $id)->first();
        if ($userId != $address->user_id)
            return response(["message" => "Unauthorized request !",], 401);

        $address->delete();

        return response([
            "message" => "Address deleted succefuly"
        ]);
    }
}

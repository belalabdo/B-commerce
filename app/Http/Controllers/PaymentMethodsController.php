<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentMethodCollection;
use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class PaymentMethodsController extends Controller
{
    public function vaildate(Request $request, $required = "required")
    {
        $data = Validator::make($request->all(), [
            'method_name' => "$required|string",
            'account_number' => "$required|regex:/^[0-9]{16}$/|unique:payment_methods,account_number",
            'provider' => "$required|string",
            'expiry_date' => ["$required", "regex:/\b(0[1-9]|1[0-2])\/\d{2}\b/"],
            'is_default' => "$required|numeric|gt:-1|lt:2",
        ])->validate();
        return $data;
    }
    public function parseUserToken(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->header('token'));
        return $token->tokenable_id;
    }
    public function getAll(Request $request)
    {
        $userId = $this->parseUserToken($request);

        $paymentMethods = PaymentMethod::where("user_id", $userId)->get();

        return response(new PaymentMethodCollection($paymentMethods), 200);
    }
    public function get(Request $request, $id)
    {
        $userId = $this->parseUserToken($request);
        $paymentMethod = PaymentMethod::where("user_id", $userId)
            ->where("id", $id)
            ->first();

        if (!$paymentMethod)
            return response([
                "message" => "Payment method not found."
            ], 404);

        return response(new PaymentMethodResource($paymentMethod), 200);
    }
    public function add(Request $request)
    {
        $userId = $this->parseUserToken($request);
        $data = $this->vaildate($request);
        $data["user_id"] = $userId;

        PaymentMethod::create($data);
        return response([
            "message" => "Payment method added successfuly",
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $userId = $this->parseUserToken($request);
        $data = $this->vaildate($request, false);

        $paymentMethod = PaymentMethod::where("user_id", $userId)
            ->where("id", $id)
            ->first();

        if (!$paymentMethod) {
            return response([
                "message" => "Payment method not found"
            ], 404);
        }

        if (isset($data["is_default"])) {
            if ($data["is_default"] == "1")
                PaymentMethod::where("user_id", $userId)
                    ->whereNot("id", $id)
                    ->update(["is_default" => "0"]);
        }

        $paymentMethod->update($data);

        return response([
            "message" => "Payment method updated successfuly",
            "payment_method" => $paymentMethod
        ], 200);
    }
    public function delete(Request $request, $id)
    {
        $userId = $this->parseUserToken($request);

        $paymentMethod = PaymentMethod::where("user_id", $userId)
            ->where("id", $id)
            ->first();

        if (!$paymentMethod) {
            return response([
                "message" => "Payment method not found"
            ], 404);
        }

        $paymentMethod->delete();
        return response([
            "message" => "Payment method deleted successfuly"
        ], 200);
    }
}

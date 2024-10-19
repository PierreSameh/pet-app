<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleTrait;
use App\Models\Address;
use Illuminate\Support\Facades\Validator;


class AddressController extends Controller
{
    use HandleTrait;

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            "address"=> ["required","string","max:255"],
        ],
    [
        "required"=> __('validation.required'),
        "max"=> __('validation.max.string'),
        "string"=> __('validation.string')
    ]);

        if ($validator->fails()) {
            return $this->handleResponse(
                false,
                "",
                [$validator->errors()->first()],
                [],
                []
            );
        }

        $user = $request->user();
        $address = Address::create([
            "user_id"=> $user->id,
            "address"=> $request->address,
        ]);

        if ($address){
            return $this->handleResponse(
                true,
                "Address added successfully",
                [],
                [
                    "address" => $address
                ],
                []
            );
        }
        return $this->handleResponse(
            false,
            "Couldn't add your address, please try again",
            [],
            [],
            []
        );
    }

    public function getAll(Request $request){
        $user = $request->user();
        $addresses = Address::where("user_id", $user->id)->get();
        if(count($addresses) > 0){
            return $this->handleResponse(
                true,
                "",
                [],
                [
                    "addresses"=> $addresses
                ],
                []
                );
            }
            return $this->handleResponse(
                true,
                "No results",
                [],
                [],
                []
            );
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            "address_id"=> ["required"],
            "address"=> ["nullable","string","max:255"],
        ]);
        if ($validator->fails()){
            return $this->handleResponse(
                false,
                "",
                [$validator->errors()->first()],
                [],
                []
                );
        }

        $address = Address::find($request->address_id);
        if($address){
            if($request->address){
            $address->address = $request->address;
            }
            $address->save();

            return $this->handleResponse(
                true,
                "Address updated successfully",
                [],
                [
                    "address" => $address
                ],
                []
            );
        }
        return $this->handleResponse(
            false,
            __('strings.not_found'),
            [],
            [],
            []
            );
    }

    public function get(Request $request){
        $validator = Validator::make($request->all(), [
            "address_id"=> "required",
        ]);
        if ($validator->fails()){
            return $this->handleResponse(
                false,
                "",
                [$validator->errors()->first()],
                [],
                []
                );
            }
            $address = Address::find($request->address_id);
            if($address){
                return $this->handleResponse(
                    true,
                    "",
                    [],
                    [
                        "address"=> $address
                    ],
                    []
                    );
                }
            return $this->handleResponse(
                false,
                "Not found",
                [],
                [],
                []
                );
    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
            "address_id"=> "required",
            ]);
            if ($validator->fails()){
                return $this->handleResponse(
                    false,
                    "",
                    [$validator->errors()->first()],
                    [],
                    []
                    );
            }
            $address = Address::find($request->address_id);
            if($address){
                $address->delete();
                return $this->handleResponse(
                    true,
                    "Deleted successfully",
                    [],
                    [],
                    []
                    );
                }
                return $this->handleResponse(
                    false,
                    "Couldn't delete, please try again",
                    [],
                    [],
                    []
                );
    }

    public function setDefault(Request $request){
        $validator = Validator::make($request->all(), [
            "address_id"=> "required|exists:addresses,id"
        ]);
        if($validator->fails()){
            return $this->handleResponse(
                false,
                "",
                [$validator->errors()->first()],
                [],
                []
            );
        }
        $user = $request->user();
        $default = Address::where('user_id', $user->id)->where('default', 1)->first();
        $address = Address::find($request->address_id);
        if($address){
        if($default){
            $default->update(["default"=> 0]);
            $address->update(["default"=> 1]);
        } else {
            $address->update(["default"=> 1]);
        }
        return $this->handleResponse(
            true,
            "Default address set",
            [],
            [
                "address"=> $address
            ],
            []
        );
        } 
        return $this->handleResponse(
            false,
            "Address not found",
            [],
            [],
            []
        );
    }
}

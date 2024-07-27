<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LostPet;
use App\Models\LostPetGallery;
use App\HandleTrait;
use Illuminate\Support\Facades\Validator;
class LostController extends Controller
{
    use HandleTrait;

    public function addLostPet(Request $request) {

        try {
        $validator = Validator::make($request->all(), [
            'name'=> 'required|string|max:255',
            'age'=> 'required|integer',
            'type'=> 'required|string',
            'gender'=> 'required|string',
            'breed'=> 'nullable|string',
            'lastseen_location'=> 'required|string|max:255',
            'lastseen_time'=> 'required|string|max:255',
            'lastseen_info'=> 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->handleResponse(
                false,
                "Error Getting Your Lost Pet Informations",
                [$validator->errors()],
                [],
                []
            );
        }

        $pet = LostPet::create([
            'user_id'=> $request->user()->id,
            'name'=> $request->name,
            'age'=> $request->age,
            'type'=> $request->type,
            'gender'=> $request->gender,
            'breed'=> $request->breed,
            'lastseen_location'=> $request->lastseen_location,
            'lastseen_time'=> $request->lastseen_time,
            'lastseen_info'=> $request->lastseen_info,

        ]);

        return $this->handleResponse(
            true,
            "Lost Pet Added Successfully",
            [],
            [
                $request->user()->lostpet,
            ],
            []
        );

     } catch (\Exception $e) {
        return $this->handleResponse(
            false,
            "Coudln't Add Your Lost Pet",
            [$e->getMessage()],
            [],
            []
        );
    }
    }
}

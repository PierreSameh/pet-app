<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use App\HandleTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class PetController extends Controller
{
    use HandleTrait;

    public function getPet(Request $request) {
        $pets = $request->user()->pets;
 
        return $this->handleResponse(
         true,
         "Pet Data",
         [],
         [$pets],
         []
     );
    } 

    public function addPet(Request $request) {

        try {
        $validator = Validator::make($request->all(), [
            'name'=> 'required|string|max:255',
            'age'=> 'required|integer',
            'type'=> 'required|string',
            'gender'=> 'required|string',
            'breed'=> 'nullable|string',
            'picture'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->handleResponse(
                false,
                "Error Getting Your Pet Informations",
                [$validator->errors()],
                [],
                []
            );
        }
        $imagePath = $request->file('picture')->store('/storage/pets', 'public');

        $pet = Pet::create([
            'user_id'=> $request->user()->id,
            'name'=> $request->name,
            'age'=> $request->age,
            'type'=> $request->type,
            'gender'=> $request->gender,
            'breed'=> $request->breed,
            'picture'=> $imagePath,
        ]);

        return $this->handleResponse(
            true,
            "Pet Added Successfully",
            [],
            [
                $request->user()->pets,
            ],
            []
        );
        
     } catch (\Exception $e) {
        return $this->handleResponse(
            false,
            "Error Signing UP",
            [$e->getMessage()],
            [],
            []
        );
    }
    }
}

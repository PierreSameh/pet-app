<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use App\Models\PetGallery;
use App\HandleTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class PetController extends Controller
{
    use HandleTrait;

    //////////////////////////////////////
    //        PETS METHODS              //
    /////////////////////////////////////
    public function getPet(Request $request, $petID) {
        $pets = $request->user()->pets;
        $petImages = [PetGallery::where("pet_id", $petID)->get()];
        return $this->handleResponse(
         true,
         "Pet Data",
         [],
         [
            "pets" => $pets,
            "petImages" => $petImages],
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
                "",
                [$validator->errors()->first()],
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
               "pets" => $request->user()->pets,
            ],
            []
        );

     } catch (\Exception $e) {
        return $this->handleResponse(
            false,
            "Coudln't Add Your Pet",
            [$e->getMessage()],
            [],
            []
        );
    }
    }

    public function editPet(Request $request, $petID) {
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
                    "",
                    [$validator->errors()->first()],
                    [],
                    []
                );
            }
            $imagePath = $request->file('picture')->store('/storage/pets', 'public');
    
            $pet = Pet::find( $petID );

                $pet->name = $request->name;
                $pet->age = $request->age;
                $pet->type = $request->type;
                $pet->gender = $request->gender;
                $pet->breed = $request->breed;
                $pet->picture = $imagePath;
                $pet->save();
 

    
            return $this->handleResponse(
                true,
                "Info Updated Successfully",
                [],
                [
                  "pet" => $pet,
                ],
                []
            );
    
         } catch (\Exception $e) {
            return $this->handleResponse(
                false,
                "Coudln't Edit Your Pet's Info",
                [$e->getMessage()],
                [],
                []
            );
        }
    }
    
    public function deletePet($petID) {
    
        $pet = Pet::where('id', $petID);
        if ($pet->count() > 0) {
        $pet->delete();

        return $this->handleResponse(
            true,
            "Pet Deleted Successfully",
            [],
            [],
            []
        );
        } else {
            return $this->handleResponse(
                false,
                "Couldn't Delete Your Pet",
                [],
                [],
                []
            );
        }

    }
    //////////////////////////////////////
    //        GALLERY METHODS           //
    /////////////////////////////////////

    public function addImage(Request $request, $petID) {
        $validator = Validator::make($request->all(), [
            'images.*'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
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
        $uploadedImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('/storage/pets', 'public');
    
                $petImage = PetGallery::create([
                    'pet_id' => $petID,
                    'image' => $imagePath,
                ]);

                $uploadedImages[] = $petImage;
            } 
            $petImages = [PetGallery::where("pet_id", $petID)->get()];
            return $this->handleResponse(
                true,
                "Image Added Successfully",
                [],
                [
                    "petImages" => $petImages
                ],
                []
            );            
         }
          else {
            return $this->handleResponse(
                false,
                "Upload Images Correctly",
                ["No Images Uploaded"],
                [],
                []
            );
         }
    }

    public function deleteImage($imageID) {
    
        $image = PetGallery::where('id', $imageID);
        if ($image ->count() > 0) {
        $image->delete();

        return $this->handleResponse(
            true,
            "Image Deleted Successfully",
            [],
            [],
            []
        );
        } else {
            return $this->handleResponse(
                false,
                "Couldn't Delete Your Image",
                [],
                [],
                []
            );
        }

    }

    
}

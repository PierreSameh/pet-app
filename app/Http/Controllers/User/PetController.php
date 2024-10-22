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
        $user = $request->user();
        $pets = Pet::where('id', $petID)->where('user_id', $user->id)->with('petgallery')->get();
        if ($pets) {
        return $this->handleResponse(
         true,
         "Pet Data",
         [],
         [
            "pets" => $pets,
         ],
         []
     );
    }
    return $this->handleResponse(
        false,
        "",
        [],
        [],
        []
        );
    } 

    public function getUserPets(Request $request) {
        $user = $request->user();
        $pets = Pet::where("user_id", $user->id)->with('petgallery')->get();
        if (count($pets) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [
                    "pets"=> $pets,
                ],
                []
                );
        }
        return $this->handleResponse(
            true,
            "No Pets",
            [],
            [],
            []
            );
    }

    public function addPet(Request $request) {

        try {
        $validator = Validator::make($request->all(), [
            'name'=> 'required|string|max:255',
            'age'=> 'required|date_format:Y-m-d H:i:s',
            'type'=> 'required|string',
            'gender'=> 'required|string',
            'breed'=> 'nullable|string',
            'picture'=> 'nullable|image|mimes:jpeg,png,jpg,gif',
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


        $pet = new Pet();
            $pet->user_id = $request->user()->id;
            $pet->name = $request->name;
            $pet->age = $request->age;
            $pet->type = $request->type;
            $pet->gender = $request->gender;
            if($request->breed) {
            $pet->breed = $request->breed;
            }

            if ($request->picture) {
                $imagePath = $request->file('picture')->store('/storage/pets', 'public');
                $pet->picture = $imagePath;
            }

            $pet->save();

        return $this->handleResponse(
            true,
            "Pet Added Successfully",
            [],
            [
               "pet" => $pet,
            ],
            []
        );

     } catch (\Exception $e) {
        return $this->handleResponse(
            false,
            "",
            [$e->getMessage()],
            [],
            []
        );
    }
    }

    public function editPet(Request $request, $petID) {
        try {
            $validator = Validator::make($request->all(), [
                'name'=> 'nullable|string|max:255',
                'age'=> 'nullable|date_format:Y-m-d H:i:s',
                'type'=> 'nullable|string',
                'gender'=> 'nullable|string',
                'breed'=> 'nullable|string',
                'picture'=> 'nullable|image|mimes:jpeg,png,jpg,gif',
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
    
            $pet = Pet::find( $petID );
                if ($request->name){
                $pet->name = $request->name;
                }
                if ($request->age) {
                $pet->age = $request->age;
                }
                if ($request->type) {
                $pet->type = $request->type;
                }
                if ($request->gender) {
                $pet->gender = $request->gender;
                }
                if ($request->breed) {
                $pet->breed = $request->breed;
                }
                if ($request->picture) {
                $imagePath = $request->file('picture')->store('/storage/pets', 'public');
                $pet->picture = $imagePath;
                }
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
            'images.*'=> 'required|image|mimes:jpeg,png,jpg,gif'
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
            $pet = Pet::where("id", $petID)->with('petgallery')->first();
            return $this->handleResponse(
                true,
                "Image Added Successfully",
                [],
                [
                $pet
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

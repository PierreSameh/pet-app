<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MarketPet;
use App\Models\MarketPetGallery;
use App\Models\BankCard;
use App\Models\Wallet;
use App\HandleTrait;
use Illuminate\Support\Facades\Validator;


class MarketController extends Controller
{
    use HandleTrait;
    public function addMarketPet(Request $request) {
        try {
        $petValidator = Validator::make($request->all(), [
            'name'=> 'required|string|max:255',
            'age'=> 'required|integer',
            'type'=> 'required|string',
            'gender'=> 'required|string',
            'breed'=> 'nullable|string',
            'picture'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'for_adoption'=> 'numeric|max:1',
            'price'=> 'nullable|numeric',
        ]);

        if ($petValidator->fails()) {
            return $this->handleResponse(
                false,
                "Error Getting Your Pet Informations",
                [$petValidator->errors()->first()],
                [],
                []
            );
        }


        $user = $request->user();
        $pet = new MarketPet();

        $pet->user_id = $user->id;
        $pet->name= $request->name;
        $pet->age= $request->age;
        $pet->type= $request->type;
        $pet->gender= $request->gender;
        $pet->breed= $request->breed;
        $pet->for_adoption= $request->for_adoption;
        $pet->price= $request->price;

        if ($request->picture) {
            $imagePath = $request->file('picture')->store('/storage/profile', 'public');
            $pet->picture = $imagePath;
        }
        $pet->save();

        return $this->handleResponse(
            true,
            "You are Pet Added Successfully",
            [],
            [
               "user" => $user,
                "pet" => $pet,
            ],
            []
        );
        } catch (\Exception $e) {
        return $this->handleResponse(
            false,
            "Error Adding Your Pet",
            [$e->getMessage()],
            [],
            []
        );
        }
    }
    public function editMarketPet(Request $request, $petID) {
        try {
            $validator = Validator::make($request->all(), [
                'name'=> 'required|string|max:255',
                'age'=> 'required|integer',
                'type'=> 'required|string',
                'gender'=> 'required|string',
                'breed'=> 'nullable|string',
                'picture'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'for_adoption'=> 'numeric|max:1',
                'price'=> 'nullable|numeric',
            ]);
    
            if ($validator->fails()) {
                return $this->handleResponse(
                    false,
                    "Error Getting Your Pet Informations",
                    [$validator->errors()->first()],
                    [],
                    []
                );
            }
            $imagePath = $request->file('picture')->store('/storage/marketpets', 'public');
    
            $pet = MarketPet::where('id', $petID)->first();

                $pet->name = $request->name;
                $pet->age = $request->age;
                $pet->type = $request->type;
                $pet->gender = $request->gender;
                $pet->breed = $request->breed;
                $pet->picture = $imagePath;
                $pet->for_adoption = $request->for_adoption;
                $pet->price = $request->price;
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
    
    public function deleteMarketPet($petID) {
    
        $pet = MarketPet::where('id', $petID);
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
    public function getMarketDogs() {
        $dogs = MarketPet::where('type', 'dog')->get();
            if (count($dogs) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [
                   "dogs" => $dogs
                ],
                []
            );
        }
        return $this->handleResponse(
            false,
            "Empty",
            [],
            [],
            []
        );
    }
    public function getMarketCats() {
        $cats = MarketPet::where('type', 'cat')->get();
            if (count($cats) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [
                   "cats" => $cats
                ],
                []
            ); 
            }
            return $this->handleResponse(
                false,
                "Empty",
                [],
                [],
                []
            );

    }

    public function getMarketBirds() {
        $birds = MarketPet::where('type', 'bird')->get();
            if (count($birds) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [
                   "birds" => $birds
                ],
                []
            );
        }
        return $this->handleResponse(
            false,
            "Empty",
            [],
            [],
            []
        );
    }
    public function getMarketTurtles() {
        $turtles = MarketPet::where('type', 'turtle')->get();
            if (count($turtles) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [
                 "turtles" => $turtles
                ],
                []
            );
        }
        return $this->handleResponse(
            false,
            "Empty",
            [],
            [],
            []
        );
    }
    public function getMarketFishes() {
        $fishes = MarketPet::where('type', 'fish')->get();
            if (count($fishes) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [
                "fishes" => $fishes
                ],
                []
            );
        }
        return $this->handleResponse(
            false,
            "Empty",
            [],
            [],
            []
        );
    }
    public function getMarketMonkeys() {
        $monkeys = MarketPet::where('type', 'monkey')->get();
            if (count($monkeys) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [
                 "monkeys" => $monkeys
                ],
                []
            );
        }
        return $this->handleResponse(
            false,
            "Empty",
            [],
            [],
            []
        );
    }

    // Get Pet Gender
    public function getMarketMales() {
        $males = MarketPet::where('gender', 'male')->get();
            if (count($males) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [
                 "males" => $males
                ],
                []
            );
        }
        return $this->handleResponse(
            false,
            "Empty",
            [],
            [],
            []
        );
    }
    public function getMarketFemales() {
        $females = MarketPet::where('gender', 'female')->get();
            if (count($females) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [
                    "females" => $females
                ],
                []
            );
        }
        return $this->handleResponse(
            false,
            "Empty",
            [],
            [],
            []
        );
    }

    public function filterMarketPets(Request $request)
    {
        $query = MarketPet::query();

        // Filter by age if provided
        if ($request->has('age')) {
            $query->where('age', $request->input('age'));
        }

        // Filter by type if provided
        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        // Filter by gender if provided
        if ($request->has('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        if ($request->has('breed')) {
            $query->where('breed', $request->input('breed'));
        }

        if ($request->has('for_adoption')) {
            $query->where('for_adoption', $request->input('for_adoption'));
        }

        // Get the filtered results
        $pets = $query->get();
        if (count($pets) > 0) {
        // Return the filtered data as a JSON response
        return $this->handleResponse(
            true,
            '',
            [],
            [
                "pets" => $pets
            ],
            []
        );
        }
        
        return $this->handleResponse(
            false,
            'No Search Matches',
            [],
            [],
            []
        );
    }

    // Pet Dating Profile
    public function getMarketPet($petID) {
        $pet = MarketPet::where('id', $petID)->first();
        if (isset($pet)) {
        $petImages = [MarketPetGallery::where("marketpet_id", $petID)->get()];
        $owner = User::where("id", $pet['user_id'])->first();
        return $this->handleResponse(
         true,
         "Pet Data",
         [],
         [
            "pet" => $pet,
            "owner" => $owner,
            "petImages" => $petImages],
         []
         );
        }
        return $this->handleResponse(
            false,
            "Pet Not Found",
            [],
            [],
            []
            );
    } 

    // Gallery Methods
    public function addMarketImage(Request $request, $petID) {
        $validator = Validator::make($request->all(), [
            'images.*'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return $this->handleResponse(
                false,
                "Error Uploading Your Photo",
                [$validator->errors()->first()],
                [],
                []
            );
        }
        $uploadedImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('/storage/marketpets', 'public');
    
                $petImage = MarketPetGallery::create([
                    'marketpet_id' => $petID,
                    'image' => $imagePath,
                ]);

                $uploadedImages[] = $petImage;
            } 
            $petImages = [MarketPetGallery::where("marketpet_id", $petID)->get()];
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

    public function deleteMarketImage($imageID) {
    
        $image = MarketPetGallery::where('id', $imageID);
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



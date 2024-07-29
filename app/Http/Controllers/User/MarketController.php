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
                [$petValidator->errors()],
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
                $user,
                $pet,
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
    public function getMarketDogs() {
        $dogs = MarketPet::where('type', 'dog')->get();
            if (count($dogs) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [$dogs],
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
                [$cats],
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
                [$birds],
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
                [$turtles],
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
                [$fishes],
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
                [$monkeys],
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
                [$males],
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
                [$females],
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
            [$pets],
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
        $petImages = [MarketPetGallery::where("pet_id", $petID)->get()];
        $owner = User::where("id", $pet['user_id'])->first();
        return $this->handleResponse(
         true,
         "Pet Data",
         [],
         [$pet, $owner, $petImages],
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
}



<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use App\Models\PetGallery;
use App\Models\BankCard;
use App\Models\Wallet;
use App\Models\Breed;
use App\HandleTrait;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;



class HomeController extends Controller
{
    use HandleTrait;
    // Get Pet Type
    public function getDogs() {
        $dogs = Pet::with('petgallery')->where('type', 'dog')->paginate(20);
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
    public function getCats() {
        $cats = Pet::with('petgallery')->where('type', 'cat')->paginate(20);
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

    public function getBirds() {
        $birds = Pet::with('petgallery')->where('type', 'bird')->paginate(20);
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
    public function getTurtles() {
        $turtles = Pet::with('petgallery')->where('type', 'turtle')->paginate(20);
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
    public function getFishes() {
        $fishes = Pet::with('petgallery')->where('type', 'fish')->paginate(20);
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
    public function getMonkeys() {
        $monkeys = Pet::with('petgallery')->where('type', 'monkey')->paginate(20);
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
    public function getMales() {
        $males = Pet::with('petgallery')->where('gender', 'male')->paginate(20);
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
    public function getFemales() {
        $females = Pet::with('petgallery')->where('gender', 'female')->paginate(20);
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

    // public function filterPets(Request $request)
    // {
    //     $query = Pet::query();

    //     // Filter by age if provided
    //     if ($request->has('age')) {
    //         $query->where('age', $request->input('age'));
    //     }

    //     // Filter by type if provided
    //     if ($request->has('type')) {
    //         $query->where('type', $request->input('type'));
    //     }

    //     // Filter by gender if provided
    //     if ($request->has('gender')) {
    //         $query->where('gender', $request->input('gender'));
    //     }

    //     if ($request->has('breed')) {
    //         $query->where('breed', $request->input('breed'));
    //     }

    //     // Get the filtered results
    //     $pets = $query->with('user','petgallery')->paginate(20);
    //     if (count($pets) > 0) {
    //     // Return the filtered data as a JSON response
    //     return $this->handleResponse(
    //         true,
    //         '',
    //         [],
    //         [
    //          "pets" => $pets
    //         ],
    //         []
    //     );
    //     }
        
    //     return $this->handleResponse(
    //         true,
    //         'No Search Matches',
    //         [],
    //         [],
    //         []
    //     );
    // }

    public function filterPets(Request $request)
{
    $query = Pet::query();

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

    // Get the filtered results
    $pets = $query->with(['user', 'petgallery'])->get();

    if ($pets->isNotEmpty()) {
        // Transform the pets data to include breed information
        $petsData = $pets->map(function ($pet) {
            $petData = $pet->toArray();
            if ($pet->breed) {
                // If no breed is found, you might want to search for it
                $breed = Breed::where('name', $pet->breed)->first();
                $petData['breed_info'] = $breed ? $breed->toArray() : null;
            }
            return $petData;
        });

        // Return the filtered data as a JSON response
        return $this->handleResponse(
            true,
            '',
            [],
            [
                "pets" => $petsData
            ],
            []
        );
    }
    
    return $this->handleResponse(
        true,
        'No Search Matches',
        [],
        [],
        []
    );
}
    // Pet Dating Profile
    public function getPetDating($petID) {
        $pet = Pet::with('petgallery')->where('id', $petID)->first();
        if (isset($pet)) {
        $owner = User::where("id", $pet['user_id'])->first();
        $breed = Breed::where('name', $pet->breed)->first();
        return $this->handleResponse(
         true,
         "Pet Data",
         [],
         [
            "pet" => $pet,
            "owner" => $owner,
            "about_breed" => $breed
        ],
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

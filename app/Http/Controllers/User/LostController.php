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

    public function showLostPets() {
        $lostPets = LostPet::all();

        if (count($lostPets) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [$lostPets],
                []
            );
        }
        return $this->handleResponse(
            false,
            "Lost Pets list is Empty",
            [],
            [],
            []
            );
    }

    public function filterLostPets(Request $request)
    {
        $query = LostPet::query();

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

        // Get the filtered results
        $lostPets = $query->get();

        // Return the filtered data as a JSON response
        return $this->handleResponse(
            true,
            '',
            [],
            [$lostPets],
            []
        );
    }

    public function getLostPet($lostPetID) {
        $lostPet = LostPet::where('id', $lostPetID)->first();
        // $petImages = [LostPetGallery::where("pet_id", $lostPetID)->get()];
        
        if (isset($lostPet)) {
        $owner = User::where("id", $lostPet['user_id'])->first();
        return $this->handleResponse(
         true,
         "Pet Data",
         [],
         [$lostPet, $owner],
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

    public function deleteLostPet($lostPetID) {
    
        $lostPet = LostPet::where('id', $lostPetID);
        if ($lostPet->count() > 0) {
        $lostPet->delete();

        return $this->handleResponse(
            true,
            "Lost Pet Deleted Successfully",
            [],
            [],
            []
        );
        } else {
            return $this->handleResponse(
                false,
                "Couldn't Delete Your Lost Pet",
                [],
                [],
                []
            );
        }

    }
}

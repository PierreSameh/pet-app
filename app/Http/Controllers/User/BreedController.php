<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\HandleTrait;
use App\Models\Breed;


class BreedController extends Controller
{
    use HandleTrait;

    public function addBreed(Request $request) {
        $validator = Validator::make($request->all(), [
            "type"=> ["required","string","max:255"],
            "name"=> ["required","string","max:255"],
            "life_expectancy"=> ["required","string","max:1000"],
            "weight"=> ["required","string","max:1000"],
            "height"=> ["required","string","max:1000"],
            "physical_charactaristcs"=> ["required","string","max:1000"],
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
        $breed = new Breed();
        $breed->type = $request->type;
        $breed->name = $request->name;
        $breed->life_expectancy = $request->life_expectancy;
        $breed->weight = $request->weight;
        $breed->height = $request->height;
        $breed->physical_charactaristcs = $request->physical_charactaristcs;
        $breed->save();
        if (isset($breed)) {
        return $this->handleResponse(
            true,
            "Breed Added Successfully",
            [],
            [
                "breed" => $breed
            ],
            []
        );
        } else {
            return $this->handleResponse(
                false,
                "Couldn't Add Your Breed",
                [],
                [],
                []
            );
        }

    }

    public function editBreed(Request $request, $breedID) {
        $validator = Validator::make($request->all(), [
            "type"=> ["required","string","max:255"],
            "name"=> ["required","string","max:255"],
            "life_expectancy"=> ["required","string","max:1000"],
            "weight"=> ["required","string","max:1000"],
            "height"=> ["required","string","max:1000"],
            "physical_charactaristcs"=> ["required","string","max:1000"],
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
        $breed = Breed::find($breedID);
        if (!$breed) {
            return $this->handleResponse(
                false,
                "Breed Not Found",
                [],
                [],
                []
            );
        }

        $breed->type = $request->type;
        $breed->name = $request->name;
        $breed->life_expectancy = $request->life_expectancy;
        $breed->weight = $request->weight;
        $breed->height = $request->height;
        $breed->physical_charactaristcs = $request->physical_charactaristcs;
        $breed->save();

        return $this->handleResponse(
            true,
            "Breed Updated Successfully",
            [],
            [
                "breed"=> $breed,
            ],
            []
        );
    }

    public function getBreed(Request $request, $breedID) {
        $breed = Breed::find($breedID);
        if (isset($breed)) {
            return $this->handleResponse(
                true,
                "",
                [],
                ["breed" => $breed],
                []
            );
        }
        return $this->handleResponse(
            false,
            "Breed Not Found",
            [],
            [],
            []
            );
    }

    public function getAllBreeds(Request $request) {
        $breeds = Breed::all();
        if (count($breeds) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                ["breeds"=> $breeds],
                []
            );
        }
        return $this->handleResponse(
            false,
            "Breed List is Empty",
            [],
            [],
            []
        );
    }

    public function deleteBreed(Request $request, $breedID) {
        $breed = Breed::find($breedID);
        if (isset($breed)) {
            $breed->delete();
            return $this->handleResponse(
                true,
                "Breed Deleted",
                [],
                [],
                []
            );
        }
        return $this->handleResponse(
            false,
            "Breed Not Found",
            [],
            [],
            []
        );
    }
}

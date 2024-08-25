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
            return redirect()->back()->withErrors($validator)->withInput(); 
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
        return redirect()->back()->with("success","Breed Added Successfully");
        } else {
            return redirect()->back()->with("error","Couldn't Add You Breed");
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
            return redirect()->back()->withErrors($validator)->withInput(); 
        }
        $breed = Breed::find($breedID);
        if (!$breed) {
            return redirect()->back()->with("error","Breed Not Found");
        }

        $breed->type = $request->type;
        $breed->name = $request->name;
        $breed->life_expectancy = $request->life_expectancy;
        $breed->weight = $request->weight;
        $breed->height = $request->height;
        $breed->physical_charactaristcs = $request->physical_charactaristcs;
        $breed->save();

        return redirect()->back()->with("success","Breed Updated Successfully");
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
            return redirect()->back()->with("success","Breed Deleted Successfully");
        }
        return redirect()->back()->with("error", "Couldn't Delete Breed");
    }
}

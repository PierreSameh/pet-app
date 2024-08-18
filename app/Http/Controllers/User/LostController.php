<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LostPet;
use App\Models\LostPetGallery;
use App\Models\FoundPet;
use App\Models\FoundPetGallery;
use App\HandleTrait;
use Illuminate\Support\Facades\Validator;
class LostController extends Controller
{
    use HandleTrait;
    //////////////////////////////////////
    //       Lost PETS METHODS          //
    /////////////////////////////////////

    public function addLostPet(Request $request) {

        try {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'name'=> 'required|string|max:255',
            'age'=> 'required|integer',
            'type'=> 'required|string',
            'gender'=> 'required|string',
            'breed'=> 'nullable|string',
            'lastseen_location'=> 'required|string|max:255',
            'lastseen_time'=> 'required|string|max:255',
            'lastseen_info'=> 'required|string|max:1000',
            'images.*'=> 'required|image|mimes:jpeg,png,jpg,gif|max:2048'

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
        if (!$request->hasFile('images')) {
            return $this->handleResponse(
                false,
                'Pet Gallery Is Required',
                [],
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
        $uploadedImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('/storage/lostpets', 'public');
                $create = new LostPetGallery();
                $create->lost_pet_id = $pet->id;
                $create->image = $imagePath;
                $create->save();
                $uploadedImages[] = $create;
            }
        } 
        $petDetails = LostPet::where('id', $pet->id)->with('lostPetGallery', 'user')->first(); 
        return $this->handleResponse(
            true,
            "Lost Pet Added Successfully",
            [],
            [
                "pet"=> $petDetails,
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
        $lostPets = LostPet::with('lostPetGallery', 'founder')->paginate(20);

        if (count($lostPets) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [
                    "lostPets" => $lostPets
                ],
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


        if ($request->has('breed')) {
            $query->where('breed', $request->input('breed'));
        }

        // Get the filtered results
        $lostPets = $query->with('user','lostPetGallery', 'founder')->paginate(20);
        if (count($lostPets) > 0) {
        // Return the filtered data as a JSON response
        return $this->handleResponse(
            true,
            '',
            [],
            [
                "lostPets" => $lostPets
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

    public function getLostPet($lostPetID) {
        $lostPet = LostPet::with('lostPetGallery', 'founder')->where('id', $lostPetID)->first();
        
        if (isset($lostPet)) {
        $owner = User::where("id", $lostPet['user_id'])->first();
        return $this->handleResponse(
         true,
         "Pet Data",
         [],
         [
            "lostPet" => $lostPet,
            "owner" => $owner,
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

    public function editLostPet(Request $request, $lostPetID) {
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
                    "",
                    [$validator->errors()->first()],
                    [],
                    []
                );
            }
    
            $lostPet = LostPet::find( $lostPetID );
            if (!$lostPet) {
                return $this->handleResponse(
                    false,
                    "Pet Not Found",
                    [],
                    [],
                    []
                );
            }
                $lostPet->name = $request->name;
                $lostPet->age = $request->age;
                $lostPet->type = $request->type;
                $lostPet->gender = $request->gender;
                $lostPet->breed = $request->breed;
                $lostPet->lastseen_location = $request->lastseen_location;
                $lostPet->lastseen_time = $request->lastseen_time;
                $lostPet->lastseen_info = $request->lastseen_info;
                $lostPet->save();
 

    
            return $this->handleResponse(
                true,
                "Info Updated Successfully",
                [],
                [
                   "lostPet" => $lostPet,
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

    public function isFound(Request $request, $lostPetID) {
        try {
            $user = $request->user();
            $lostPet = LostPet::with('founder')->find( $lostPetID );
            if (!$lostPet) {
                return $this->handleResponse(
                    false,
                    "Pet Not Found",
                    [],
                    [],
                    []
                );
            }
            $lostPet->found = 1;
            $lostPet->founder_id = $user->id;
            $lostPet->save();

            return $this->handleResponse(
                true,
                "Info Updated Successfully",
                [],
                [
                  "lostPet" => $lostPet,
                ],
                []
            );
    
         } catch (\Exception $e) {
            return $this->handleResponse(
                false,
                "Coudln't Edit Your Pet's Info",
                [$e->getMessage()],
                [],
                ['Use 0 or 1 in this boolean']
            );
        }
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


    //////////////////////////////////////
    //        GALLERY METHODS           //
    /////////////////////////////////////

    public function addImage(Request $request, $lostPetID) {
        $validator = Validator::make($request->all(), [
            'images.*'=> 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
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
                $imagePath = $image->store('/storage/lostpets', 'public');
                $create = new LostPetGallery();
                $create->lost_pet_id = $lostPetID;
                $create->image = $imagePath;
                $create->save();
                // $petImage = LostPetGallery::create([
                //     'lost_pet_id' => $lostPetID,
                //     'image' => $imagePath,
                // ]);

                $uploadedImages[] = $create;
            } 
            $lostPetImages = [LostPetGallery::where("lost_pet_id", $lostPetID)->get()];
            return $this->handleResponse(
                true,
                "Image Added Successfully",
                [],
                [
                    "lostPetImage" => $lostPetImages
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
    
        $image = LostPetGallery::where('id', $imageID);
        if (!$image) {
            return $this->handleResponse(
                false,
                "Image Not Found",
                [],
                [],
                []
            );
        }
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

    //////////////////////////////////////
    //       FOUND PETS METHODS          //
    /////////////////////////////////////

    public function addFoundPet(Request $request) {

        try {
        $validator = Validator::make($request->all(), [
            'type'=> 'required|string',
            'gender'=> 'required|string',
            'breed'=> 'nullable|string',
            'found_location'=> 'required|string|max:255',
            'found_time'=> 'required|string|max:255',
            'found_info'=> 'required|string|max:1000',
            'images.*'=> 'required|image|mimes:jpeg,png,jpg,gif|max:2048'

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
        if (!$request->hasFile('images')) {
            return $this->handleResponse(
                false,
                'Pet Gallery Is Required',
                [],
                [],
                []
            );
        }
        $user = $request->user();
        $pet = FoundPet::create([
            'user_id'=> $request->user()->id,
            'founder_id'=> $user->id,
            'type'=> $request->type,
            'gender'=> $request->gender,
            'breed'=> $request->breed,
            'found_location'=> $request->found_location,
            'found_time'=> $request->found_time,
            'found_info'=> $request->found_info,

        ]);
        $uploadedImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('/storage/foundpets', 'public');
    
                $create = new FoundPetGallery();
                $create->found_pet_id = $pet->id;
                $create->image = $imagePath;
                $create->save();

                $uploadedImages[] = $create;
            }
        }
            $petDetails = FoundPet::where('id', $pet->id)->with('foundPetGallery', 'founder')->first();

        return $this->handleResponse(
            true,
            "Found Pet Added Successfully",
            [],
            [
              "pet" => $petDetails,
            ],
            []
        );

     } catch (\Exception $e) {
        return $this->handleResponse(
            false,
            "Coudln't Add Your Found Pet",
            [$e->getMessage()],
            [],
            []
        );
    }
    }

    public function showFoundPets() {
        $foundPets = FoundPet::with('foundPetGallery', 'founder')->paginate(20);

        if (count($foundPets) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [
                    "foundPets" => $foundPets
                ],
                []
            );
        }
        return $this->handleResponse(
            false,
            "Found Pets list is Empty",
            [],
            [],
            []
            );
    }

    public function filterFoundPets(Request $request)
    {
        $query = FoundPet::query();

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
        $foundPets = $query->with('user','foundPetGallery', 'founder')->paginate(20);

        if (count($foundPets) > 0) {
            // Return the filtered data as a JSON response
            return $this->handleResponse(
                true,
                '',
                [],
                [
                    "foundPets" => $foundPets
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

    public function getFoundPet($foundPetID) {
        $foundPet = FoundPet::with('foundPetGallery', 'founder')->where('id', $foundPetID)->first();
        
        if (isset($foundPet)) {
        $owner = User::where("id", $foundPet['user_id'])->first();
        return $this->handleResponse(
         true,
         "Pet Data",
         [],
         [
            "foundPet" => $foundPet,
            "owner" => $owner,
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

    public function editFoundPet(Request $request, $foundPetID) {
        try {
            $validator = Validator::make($request->all(), [
                'type'=> 'required|string',
                'gender'=> 'required|string',
                'breed'=> 'nullable|string',
                'found_location'=> 'required|string|max:255',
                'found_time'=> 'required|string|max:255',
                'found_info'=> 'required|string|max:1000',
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
    
            $foundPet = FoundPet::find( $foundPetID );
            if (!$foundPet) {
                return $this->handleResponse(
                    false,
                    "Pet Not Found",
                    [],
                    [],
                    []
                );
            }

                $foundPet->type = $request->type;
                $foundPet->gender = $request->gender;
                $foundPet->breed = $request->breed;
                $foundPet->found_location = $request->found_location;
                $foundPet->found_time = $request->found_time;
                $foundPet->found_info = $request->found_info;
                $foundPet->save();
 

    
            return $this->handleResponse(
                true,
                "Info Updated Successfully",
                [],
                [
                  "foundPet" => $foundPet,
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

    public function deleteFoundPet($foundPetID) {
    
        $foundPet = FoundPet::where('id', $foundPetID);
        if ($foundPet->count() > 0) {
        $foundPet->delete();

        return $this->handleResponse(
            true,
            "Found Pet Deleted Successfully",
            [],
            [],
            []
        );
        } else {
            return $this->handleResponse(
                false,
                "Couldn't Delete Your Found Pet",
                [],
                [],
                []
            );
        }

    }


    //////////////////////////////////////
    //        GALLERY METHODS           //
    /////////////////////////////////////

    public function addImageF(Request $request, $foundPetID) {
        $validator = Validator::make($request->all(), [
            'images.*'=> 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
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
                $imagePath = $image->store('/storage/foundpets', 'public');
    
                $create = new FoundPetGallery();
                $create->found_pet_id = $foundPetID;
                $create->image = $imagePath;
                $create->save();

                $uploadedImages[] = $create;
            } 
            $foundPetImages = [FoundPetGallery::where("found_pet_id", $foundPetID)->get()];
            return $this->handleResponse(
                true,
                "Image Added Successfully",
                [],
                [
                    "foundPetImages" => $foundPetImages
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

    public function deleteImageF($imageID) {
    
        $image = FoundPetGallery::where('id', $imageID);
        if (!$image) {
            return $this->handleResponse(
                false,
                "Image Not Found",
                [],
                [],
                []
            );
        }
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

    public function getMyLostPets(Request $request) {
        $user = $request->user();
        $lostPets = LostPet::where("user_id", $user->id)->with('lostPetGallery', 'founder')->get();
        if (count($lostPets) > 0) {
            return $this->handleResponse(
                true,
                '',
                [],
                [
                    'lostpets'=> $lostPets
                ],
                []
            );
        }
        return $this->handleResponse(
            true,
            'Lost pets list is empty',
            [],
            [],
            []
        );
    }

    public function getMyFoundPets(Request $request) {
        $user = $request->user();
        $foundPets = FoundPet::where("user_id", $user->id)->with('foundPetGallery', 'founder')->get();
        if (count($foundPets) > 0) {
            return $this->handleResponse(
                true,
                '',
                [],
                [
                    'foundpets'=> $foundPets
                ],
                []
            );
        }
        return $this->handleResponse(
            true,
            'Found pets list is empty',
            [],
            [],
            []
        );
    }

}


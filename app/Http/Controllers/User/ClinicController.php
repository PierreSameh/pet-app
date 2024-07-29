<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Clinic;
use App\HandleTrait;
use Illuminate\Validation\Rule;


class ClinicController extends Controller
{
    use HandleTrait;

    public function addClinic(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                "clinic_name"=> 'required|string|max:255|unique:clinics,clinic_name',
                "doctor"=> 'required|string|max:255',
                "specialization"=> 'required|string|max:255',
                "address"=> 'required|string|max:255',
                "medical_fees"=> 'required|string|max:255',
                "working_days"=> 'required|string|max:255',
                "working_times"=> 'required|string|max:255',
                "picture"=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            if ($validator->fails()) {
                return $this->handleResponse(
                    false,
                    "",
                    [$validator->errors()],
                    [],
                    []
                    );
            }
            $clinic = new Clinic();
            $clinic->user_id = $request->user()->id;
            $clinic->clinic_name = $request->clinic_name;
            $clinic->doctor = $request->doctor;
            $clinic->specialization = $request->specialization;
            $clinic->address = $request->address;
            $clinic->medical_fees = $request->medical_fees;
            $clinic->working_days = $request->working_days;
            $clinic->working_times = $request->working_times;

            if ($request->picture) {
                $imagePath = $request->file('picture')->store('/storage/clinics', 'public');
                $clinic->picture = $imagePath;
            }

            $clinic->save();
            return $this->handleResponse(
                true,
                "Clinic Added Successfully",
                [],
                [$clinic],
                []
            );
            } catch (\Exception $e) {
                return $this->handleResponse(
                    false,
                    "Coudln't Add Your Store",
                    [$e->getMessage()],
                    [],
                    []
                );
            }
    }

    public function editClinic(Request $request, $clinicID) {
        try {
            $validator = Validator::make($request->all(), [
                "clinc_name"=> ['string','max:255',Rule::unique('clinics', 'clinic_name')->ignore($clinicID)],
                "doctor"=> 'required|string|max:255',
                "specialization"=> 'required|string|max:255',
                "address"=> 'required|string|max:255',
                "medical_fees"=> 'required|string|max:255',
                "working_days"=> 'required|string|max:255',
                "working_times"=> 'required|string|max:255',
                "picture"=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            if ($validator->fails()) {
                return $this->handleResponse(
                    false,
                    "Error Editting Your Store Informations",
                    [$validator->errors()],
                    [],
                    []
                );
            }
            $clinic = Clinic::find($clinicID);
            if (!$clinic) {
                return $this->handleResponse(
                    false,
                    "Store Not Found",
                    [],
                    [],
                    []
                );
            }
            $clinic->clinic_name = $request->clinic_name;
            $clinic->doctor = $request->doctor;
            $clinic->specialization = $request->specialization;
            $clinic->address = $request->address;
            $clinic->medical_fees = $request->medical_fees;
            $clinic->working_days = $request->working_days;
            $clinic->working_times = $request->working_times;
            if ($request->image) {
                $imagePath = $request->file('picture')->store('/storage/clinics', 'public');
                $clinic->image = $imagePath;
            }
            $clinic->save();

            return $this->handleResponse(
                true,
                "Clinic Updated Successfully",
                [],
                [$clinic],
                []
            );
    
         } catch (\Exception $e) {
            return $this->handleResponse(
                false,
                "Coudln't Edit Your Clinic",
                [$e->getMessage()],
                [],
                []
            );
        }
    } 

    public function getClinic($clinicID) {
        $clinic = Clinic::where('id', $clinicID)->first();
        
        if (isset($clinic)) {
        return $this->handleResponse(
         true,
         "Clinic Details",
         [],
         [$clinic],
         []
            );
        }
        return $this->handleResponse(
            false,
            "clinic Not Found",
            [],
            [],
            []
            );
    }

    public function allClinic(){
        $clinics = Clinic::get();
        if (count($clinics) > 0) {
        return $this->handleResponse(
            true,
            "",
            [],
            [$clinics],
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

    public function deleteClinic($clinicID) {
        $clinic = Clinic::where("id", $clinicID)->first();
        if (isset($clinic)) {
            $clinic->delete();
            return $this->handleResponse(
                true,
                "$clinic->name . 'Deleted Successfully'",
                [],
                [],
                []
                );
            }
            return $this->handleResponse(
                false,
                "Couldn't Delete Your Clinic",
                [],
                [],
                []
                );
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Clinic;
use App\HandleTrait;

class ClinicController extends Controller
{
    use HandleTrait;

    public function addClinic(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                "clinic_name"=> 'required|string|max:255',
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
                $imagePath = $request->file('picture')->store('/storage/store', 'public');
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
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Clinic;
use App\Models\User;
use App\Models\BookVisit;
use App\HandleTrait;
use Illuminate\Validation\Rule;
use App\SendMailTrait;


class ClinicController extends Controller
{
    use HandleTrait, SendMailTrait;

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

    //// BOOK VISITS

    public function book(Request $request, $clinicID) {
        try {
            $validator = Validator::make($request->all(), [
                'time' => 'required|date_format:Y-m-d H:i:s|unique:book_visits,time'
            ],[
                'time.unique'=> 'Clinic is Busy With other clients, Can you choose a different Time?'
            ]
        
        );
            if ($validator->fails()) {
                return $this->handleResponse(
                    false,
                    '',
                    [$validator->errors()],
                    [],
                    []
                    );
            }
            
            $user = $request->user();
            $clinic = Clinic::where('id', $clinicID)->first();
            $clinicAdmin = User::where('id', $clinic->user_id)->first();
            $pets = $user->pets;
            $book = new BookVisit();
            $book->user_id = $user->id;
            $book->clinic_id= $clinicID;
            $book->time= $request->time;
            $book->save();

            if($book) {
                $msg_content = "<h1>";
                $msg_content = " New Booked Visit by: " . $user->first_name . ' ' . $user->last_name;
                $msg_content .= "</h1>";
                $msg_content .= "<br>";
                $msg_content .= "<h3>";
                $msg_content .= "Client Details: ";
                $msg_content .= "</h3>";

                $msg_content .= "<h4>";
                $msg_content .= "Phone: ";
                $msg_content .= $user->phone;
                $msg_content .= "</h4>";


                $msg_content .= "<h4>";
                $msg_content .= "Address: ";
                $msg_content .= $user->address;
                $msg_content .= "</h4>";


                $this->sendEmail($clinicAdmin->email, "New Visit Booked", $msg_content);

            }

            return $this->handleResponse(
                true,
                'Booked Your Visit!',
                [],
                [$book, $pets, $user],
                []
                );
        } catch (\Exception $e) {
            return $this->handleResponse(
                false,
                "Error Booking Your Visit",
                [$e->getMessage()],
                [],
                []
            );
        }
    }
}
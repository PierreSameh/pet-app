<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\HandleTrait;
use App\Models\Support;

class SupportController extends Controller
{
    use HandleTrait;

    public function message(Request $request){
        $validator = Validator::make($request->all(), [
            "message" => ["required", "string", "max:1000"],
        ]);
        if ($validator->fails()){
            return $this->handleResponse(
                false,
                "",
                [$validator->errors()->first()],
                [],
                []
            );
        }
        $user = $request->user();
        $message = Support::create([
            "user_id" => $user->id,
            "message" => $request->message
        ]);
        if($message){
            return $this->handleResponse(
                true,
                "Your Message Has Been Sent",
                [],
                [],
                []
            );
        }
        return $this->handleResponse(
            false,
            "Couldn't Send Your Message",
            [],
            [],
            []
        );
    }
}

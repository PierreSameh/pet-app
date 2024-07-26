<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use App\HandleTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use HandleTrait;
    public function register(Request $request)
    {
        DB::beginTransaction();

        try {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|numeric|digits:11|unique:users,phone',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:12|
            regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/u
            |confirmed',
        ], [
            "password.regex" => "Password must have Captial and small letters, and a special character",
        ]);

        if ($validator->fails()) {
                return $this->handleResponse(
                false,
                "Error Signing UP",
                [$validator->errors()],
                [],
                []
            );
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone'=> $request->phone,
            'address'=> $request->address,
            'password' => Hash::make($request->password),
        ]);

        

        $petValidator = Validator::make($request->all(), [
            'name'=> 'required|string|max:255',
            'age'=> 'required|integer',
            'type'=> 'required|string',
            'gender'=> 'required|string',
            'breed'=> 'nullable|string',
        ]);

        if ($petValidator->fails()) {
            return $this->handleResponse(
                false,
                "Error Getting Your Pet Informations",
                [$validator->errors()],
                [],
                []
            );
        }

        $pet = Pet::create([
            'user_id'=> $user->id,
            'name'=> $request->name,
            'age'=> $request->age,
            'type'=> $request->type,
            'gender'=> $request->gender,
            'breed'=> $request->breed,
        ]);

        $token = $user->createToken('token')->plainTextToken;

        DB::commit();

        // return response()->json(compact(['user', 'pet'], 'token'), 201);

        return $this->handleResponse(
            true,
            "You are Signed Up",
            [],
            [
                $user,
                $pet,
                $token
            ],
            []
        );

        } catch (\Exception $e) {
            DB::rollBack();

            return $this->handleResponse(
                false,
                "Error Signing UP",
                [$e->getMessage()],
                [],
                []
            );
        }

    } 

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $userManual = Auth::user();
            $token = $userManual->createToken('token')->plainTextToken;

        }else  {
                return $this->handleResponse(
                false,
                "Error Signing UP",
                ['Invalid Credentials'],
                [],
                []
            );
        }

        // return response()->json(compact('token'));
        return $this->handleResponse(
            true,
            "You are Signed Up",
            [],
            [
                $token,
            ],
            []
        );
    }

    public function getUser(Request $request) {
       $user = $request->user();

        return response()->json(compact('user'));
    }
}

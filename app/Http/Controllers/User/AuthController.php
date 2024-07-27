<?php

namespace App\Http\Controllers\User;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use App\Models\BankCard;
use App\Models\Wallet;
use App\HandleTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\SendMailTrait;

class AuthController extends Controller
{
    use HandleTrait, SendMailTrait;

    // Sign Up Section
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
                [$petValidator->errors()],
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
    public function askEmailCode(Request $request) {
        $user = $request->user();


        if ($user) {
            $code = rand(100000, 999999);


            $user->email_last_verfication_code = Hash::make($code);
            $user->email_last_verfication_code_expird_at = Carbon::now()->addMinutes(10)->timezone('Europe/Istanbul');
            $user->save();


            $msg_title = "Here's your Authentication Code";
            $msg_content = "<h1>";
            $msg_content .= "Your Authentication code is<span style='color: blue'>" . $code . "</span>";
            $msg_content .= "</h1>";


            $this->sendEmail($user->email, $msg_title, $msg_content);


            return $this->handleResponse(
                true,
                "Authentication Code Sent To Your Email Successfully! ",
                [],
                [],
                [
                    "code get expired after 10 minuts",
                    "the same endpoint you can use for ask resend email"
                ]
            );
        }


        return $this->handleResponse(
            false,
            "",
            ["invalid process"],
            [],
            [
                "code get expired after 10 minuts",
                "the same endpoint you can use for ask resend email"
            ]
        );
    }

    public function verifyEmail(Request $request) {
        $validator = Validator::make($request->all(), [
            "code" => ["required"],
        ], [
            "code.required" => "Enter Authentication Code",
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




        $user = $request->user();
        $code = $request->code;


        if ($user) {
            if (!Hash::check($code, $user->email_last_verfication_code ? $user->email_last_verfication_code : Hash::make(0000))) {
                return $this->handleResponse(
                    false,
                    "",
                    ["Incorrect Code"],
                    [],
                    []
                );
            } else {
                $timezone = 'Europe/Istanbul'; // Replace with your specific timezone if different
                $verificationTime = new Carbon($user->email_last_verfication_code_expird_at, $timezone);
                if ($verificationTime->isPast()) {
                    return $this->handleResponse(
                        false,
                        "",
                        ["Code is Expired"],
                        [],
                        []
                    );
                } else {
                    $user->is_email_verified = true;
                    $user->save();


                    if ($user) {
                        return $this->handleResponse(
                            true,
                            "Your Email is Verifyied",
                            [],
                            [],
                            []
                        );
                    }
                }
            }
        }


    }

    public function changePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            "old_password" => 'required',
            'password' => 'required|string|min:12|
            regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/u
            |confirmed',
            ], [
            "password.regex" => "Password must have Captial and small letters, and a special character",
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




        $user = $request->user();
        $old_password = $request->old_password;


        if ($user) {
            if (!Hash::check($old_password, $user->password)) {
                return $this->handleResponse(
                    false,
                    "",
                    ["Current Password is Incorrect"],
                    [],
                    []
                );
            }


            $user->password = Hash::make($request->password);
            $user->save();


            return $this->handleResponse(
                true,
                "Password Changed Successfully",
                [],
                [],
                []
            );
        }


    }

    public function forgetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            "email" => 'required|email',
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




        $user = User::where("email", $request->email)->first();

            if ($user) {
                $code = rand(100000, 999999);


                $user->email_last_verfication_code = Hash::make($code);
                $user->email_last_verfication_code_expird_at = Carbon::now()->addMinutes(10)->timezone('Europe/Istanbul');
                $user->save();
    
    
                $msg_title = "Here's your Authentication Reset Password Code";
                $msg_content = "<h1>";
                $msg_content .= "Your Authentication Reset Password Dode is<span style='color: blue'>" . $code . "</span>";
                $msg_content .= "</h1>";
    
    
                $this->sendEmail($user->email, $msg_title, $msg_content);
    
    
                return $this->handleResponse(
                    true,
                    "Authentication Reset Code Sent To Your Email Successfully! ",
                    [],
                    [],
                    [
                        "code get expired after 10 minuts",
                        "the same endpoint you can use for ask resend email"
                    ]
                );
            }
            else {
                return $this->handleResponse(
                    false,
                    "",
                    ["This email is not used"],
                    [],
                    []
                );
            }


    }

    public function forgetPasswordCheckCode(Request $request) {
        $validator = Validator::make($request->all(), [
            "code" => ["required"],
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



        // This email request is coming from a hidden input type that referes to the previous page
        $user = User::where("email", $request->email)->first();
        $code = $request->code;


        if ($user) {
            if (!Hash::check($code, $user->email_last_verfication_code ? $user->email_last_verfication_code : Hash::make(0000))) {
                return $this->handleResponse(
                    false,
                    "",
                    ["Enter a Valid Code"],
                    [],
                    []
                );
            } else {
                $timezone = 'Europe/Istanbul'; // Replace with your specific timezone if different
                $verificationTime = new Carbon($user->email_last_verfication_code_expird_at, $timezone);
                if ($verificationTime->isPast()) {
                    return $this->handleResponse(
                        false,
                        "",
                        ["Code is Expired"],
                        [],
                        []
                    );
                } else {
                    if ($user) {
                        $passwordValidator = Validator::make($request->all(), [
                            "password" => 'required|string|min:12|
                            regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/u
                            |confirmed',
                            ], [
                                "password.regex" => "Password must have Captial and small letters, and a special character",
                            ]);

                            if ($passwordValidator->fails()) {
                                return $this->handleResponse(
                                    false,
                                    "",
                                    [$validator->errors()->first()],
                                    [],
                                    []
                                );
                            }

                            $user->password = Hash::make($request->password);
                            $user->save();
                
                
                            return $this->handleResponse(
                                true,
                                "Password Changed Successfully",
                                [],
                                [],
                                []
                            );
                        }
                
                    }
                }
            }
        }



    // Login Section
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
            "You are Loged In",
            [],
            [
                $token,
            ],
            []
        );
    }

    public function getUser(Request $request) {
       $user = $request->user();

       return $this->handleResponse(
        true,
        "User Data",
        [],
        [$user],
        []
    );
        // return response()->json(compact('user'));
    }

    public function editProfile(Request $request) {
        try {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'picture'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
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
        $user = $request->user();

        if ($request->picture) {
        $imagePath = $request->file('picture')->store('/storage/profile', 'public');
        $user->picture = $imagePath;
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->address = $request->address;

        $user->save();
        
        return $this->handleResponse(
            true,
            "Info Updated Successfully",
            [],
            [
                $user,
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

    // Payment Section
    public function addBankCard(Request $request) {
        $validator = Validator::make($request->all(), [
            'cardholder_name'=> 'required|string|max:255',
            'card_number'=> 'required|numeric|digits_between:13,19',
            'expiry_date' => 'required|date_format:m/y|after_or_equal:now',
            'encrypted_cvv' => 'required|digits_between:3,4'
        ]);
        if ($validator->fails()) {
            return $this->handleResponse(
                false,
                'Enter a Valid Bank Information',
                [$validator->errors(),],
                [],
                []
            );
        }
        $user = $request->user();
        $expiryDate = Carbon::createFromFormat('m/y', $request->expiry_date)->startOfMonth();
        BankCard::create([
            'user_id'=> $user->id,
            'cardholder_name'=> $request->cardholder_name,
            'card_number'=> $request->card_number,
            'expiry_date'=> $expiryDate,
            'encrypted_cvv'=> Crypt::encrypt($request->encrypted_cvv),
        ]);
        $bankCards = BankCard::where('user_id', $user->id)->get();

        return $this->handleResponse(
            true,
            'Bank Card Saved!',
            [],
            [$bankCards],
            []
            );
    }

    public function deleteBankCard($cardID) {
        $card = BankCard::where('id', $cardID);
        if ($card->count() > 0) {
        $card->delete();

        return $this->handleResponse(
            true,
            "Card Deleted Successfully",
            [],
            [],
            []
        );
        } else {
            return $this->handleResponse(
                false,
                "Couldn't Delete Your Card",
                [],
                [],
                []
            );
        }
    }
}

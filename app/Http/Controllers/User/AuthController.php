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
    ///// Look At app/SendMailEXAMPLE.php before using SendMailTrait
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
            'joined_with'=>"required|in:1,2,3,4",
            'password' => 'required_if:joined_with,1|string|min:8|
            regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W])[A-Za-z\d\W]+$/u
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
                [
                    "joined_with" => [
                        "1" => "تعني تسجيل يدوي",
                        "2" => "تعني تسجيل عن طريق جوجل ولا يشترط ارسال كلمة مرور",
                        "3" => "تعني تسجيل عن طريق فيس بوك ولا يشترط ارسال كلمة مرور",
                        "4" => "تعني تسجيل الدخول عن طريق أبل ولا يشترط ارسال كلمة مرور"
                    ]
                ]
            );
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone'=> $request->phone,
            'address'=> $request->address,
            'joined_with'=> $request->joined_with,
            "password" => (int) $request->joined_with === 1 ? Hash::make($request->password) : ((int) $request->joined_with === 2 ? Hash::make("Google") : Hash::make("Facebook")),
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
                "",
                [$petValidator->errors()->first()],
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
               "user" => $user,
                "pet" => $pet,
                "token" => $token
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
            $code = rand(1000, 9999);


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

    public function sendForgetPasswordEmail(Request $request) {
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
                $code = rand(1000, 9999);

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

    public function forgetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            "email" => ["required", "email"],
            'password' => [
                'required', // Required only if joined_with is 1
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/u',
                'confirmed'
            ],
        ], [
            "email.required" => "من فضلك ادخل بريدك الاكتروني ",
            "email.email" => "من فضلك ادخل بريد الكتروني صالح",
            "password.required" => "ادخل كلمة المرور",
            "password.min" => "يجب ان تكون كلمة المرور من 8 احرف على الاقل",
            "password.regex" => "يجب ان تحتوي كلمة المرور علي حروف وارقام ورموز",
            "password.confirmed" => "كلمة المرور والتاكيد غير متطابقان",
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
        $code = $request->code;


        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();


            if ($user) {
                return $this->handleResponse(
                    true,
                    "تم تعين كلمة المرور بنجاح ",
                    [],
                    [],
                    []
                );
            }
        }
        else {
            return $this->handleResponse(
                false,
                "",
                ["هذا الحساب غير مسجل"],
                [],
                []
            );
        }


    }


    public function forgetPasswordCheckCode(Request $request) {
        $validator = Validator::make($request->all(), [
            "email" => ["required", "email"],
            "code" => ["required"],
        ], [
            "code.required" => "ادخل رمز التاكيد ",
            "email.required" => "من فضلك ادخل بريدك الاكتروني ",
            "email.email" => "من فضلك ادخل بريد الكتروني صالح",
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
        $code = $request->code;


        if ($user) {
            if (!Hash::check($code, $user->email_last_verfication_code ? $user->email_last_verfication_code : Hash::make(0000))) {
                return $this->handleResponse(
                    false,
                    "",
                    ["الرمز غير صحيح"],
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
                        ["الرمز غير ساري"],
                        [],
                        []
                    );
                } else {
                    if ($user) {
                        return $this->handleResponse(
                            true,
                            "الرمز صحيح",
                            [],
                            [],
                            []
                        );
                    }
                }
            }
        } else {
            return $this->handleResponse(
                false,
                "",
                ["هذا الحساب غير مسجل"],
                [],
                []
            );
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
               "token" => $token,
            ],
            []
        );
    }

    public function logout(Request $request) {
        $user = $request->user();

        if ($user) {
            if ($user->tokens())
                $user->tokens()->delete();
        }

        return $this->handleResponse(
            true,
            "Loged Out",
            [],
            [
            ],
            [
                "On logout" => "كل التوكينز بتتمسح انت كمان امسحها من الكاش عندك"
            ]
        );
    }

    public function getUser(Request $request) {
       $user = $request->user();
        $bankCards = $request->user()->bankcard;
        $wallets   = $request->user()->wallet;

       return $this->handleResponse(
        true,
        "User Data",
        [],
        [
            "user" => $user,
            "bankCards" => $bankCards,
            "wallets" => $wallets
        ],
        []
    );
        // return response()->json(compact('user'));
    }

    public function editProfile(Request $request) {
        try {
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'picture'=> 'nullable|image|mimes:jpeg,png,jpg,gif'
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

        if ($request->first_name) {
        $user->first_name = $request->first_name;
        }
        if ($request->last_name) {
        $user->last_name = $request->last_name;
        }
        if ($request->address) {
        $user->address = $request->address;
        }

        if ($request->picture) {
            $imagePath = $request->file('picture')->store('/storage/profile', 'public');
            $user->picture = $imagePath;
        }

        $user->save();
        
        return $this->handleResponse(
            true,
            "Info Updated Successfully",
            [],
            [
               "user" => $user,
            ],
            []
        );
        } catch (\Exception $e) {
            return $this->handleResponse(
                false,
                "",
                [$e->getMessage()],
                [],
                []
            );
        }
        
    }

    // Payment Section
    // Bank Card //
    public function addBankCard(Request $request) {
        try {
        $validator = Validator::make($request->all(), [
            'cardholder_name'=> 'required|string|max:255',
            'card_number'=> 'required|numeric|digits_between:13,19',
            'expiry_date' => 'required|date_format:m/y|after_or_equal:now',
            'encrypted_cvv' => 'required|digits_between:3,4'
        ]);
        if ($validator->fails()) {
            return $this->handleResponse(
                false,
                '',
                [$validator->errors()->first()],
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
            [
                "bankCards" => $bankCards
            ],
            []
            );
        } catch (\Exception $e) {
            return $this->handleResponse(
                false,
                "",
                [$e->getMessage()],
                [],
                []
            );
        }
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

    // Wallet //
    
    public function addWallet(Request $request) {
        try {
        $validator = Validator::make($request->all(), [
            "phone"=> "required|string|numeric|digits:11|unique:wallets,phone",
            "pin"=> "required|numeric|digits:6",
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
        Wallet::create([
            "user_id"=> $user->id,
            "phone"=> $request->phone,
            "pin"=> Crypt::encrypt($request->pin),
            
        ]);
        return $this->handleResponse(
            true,
            "Wallet Added Successfully!",
            [],
            [],
            []
            );

    } catch (\Exception $e) {
        return $this->handleResponse(
            false,
            "",
            [$e->getMessage()],
            [],
            []
        );
     }
    }

    public function deleteWallet($walletID) {
        $wallet = Wallet::where('id', $walletID);
        if ($wallet->count() > 0) {
        $wallet->delete();

        return $this->handleResponse(
            true,
            "Wallet Deleted Successfully",
            [],
            [],
            []
        );
        } else {
            return $this->handleResponse(
                false,
                "Couldn't Delete Your Wallet",
                [],
                [],
                []
            );
        }
    }
}

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
use Carbon\Carbon;


class PetController extends Controller
{
    use HandleTrait;

    public function getPet(Request $request) {
        $pets = $request->user()->pets;
 
        return $this->handleResponse(
         true,
         "Pet Data",
         [],
         [$pets],
         []
     );
    } 
}

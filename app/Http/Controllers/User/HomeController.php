<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use App\Models\BankCard;
use App\Models\Wallet;
use App\HandleTrait;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class HomeController extends Controller
{
    use HandleTrait;
    // Get Pet Type
    public function getDogs() {
        $dogs = Pet::where('type', 'dog')->get();
            if (count($dogs) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [$dogs],
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
    public function getCats() {
        $cats = Pet::where('type', 'cat')->get();
            if (count($cats) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [$cats],
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

    public function getBirds() {
        $birds = Pet::where('type', 'bird')->get();
            if (count($birds) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [$birds],
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
    public function getTurtles() {
        $turtles = Pet::where('type', 'turtle')->get();
            if (count($turtles) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [$turtles],
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
    public function getFishes() {
        $fishes = Pet::where('type', 'fish')->get();
            if (count($fishes) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [$fishes],
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
    public function getMonkeys() {
        $monkeys = Pet::where('type', 'monkey')->get();
            if (count($monkeys) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [$monkeys],
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

    // Get Pet Gender
    public function getMales() {
        $males = Pet::where('gender', 'male')->get();
            if (count($males) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [$males],
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
    public function getFemales() {
        $females = Pet::where('gender', 'female')->get();
            if (count($females) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [$females],
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
}

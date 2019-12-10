<?php

namespace App\Http\Controllers;

use App\Models\QuestradeCredential;
use App\Services\External\QuestradeService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(QuestradeService $questrade_service)
    {
        $questrade_credentials = QuestradeCredential::where('user_id', Auth::id())->latest('updated_at')->first();


        $number = "51703389";
        // dd($questrade_service->getAccounts());

        dd($questrade_service->getAccountActivities($number, Carbon::now()->subDays(30), Carbon::now()));

        return view('home', [
            'questrade_credentials' => $questrade_credentials,
        ]);
    }

    /**
     * Show the welcome page - guest
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view('welcome');
    }
}

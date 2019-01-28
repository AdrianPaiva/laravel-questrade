<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public $questrade_credential_service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(\App\Services\QuestradeCredentialService $questrade_credential_service)
    {
        $this->questrade_credential_service = $questrade_credential_service;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        dd($this->service->getAccounts());
        return view('home');
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

    public function getQuestradeService()
    {
        $creds = $this->questrade_credential_service->getCurrent();

        if (!$creds) {
            return null;
        }

        return new \App\Services\External\QuestradeService($this->questrade_credential_service, $creds);
    }
}

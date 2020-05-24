<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ThankYouController extends Controller
{
    public function index(){
        if (Auth::check()) {
            $state = 'LOGOUT';
            $email = Auth::user()->email;
        }else{
            $state = 'LOGIN';
            $email = '';
        }

        return view('thankyou')->with([
            'email' => $email,
            'state' => $state,
        ]);
    }
}

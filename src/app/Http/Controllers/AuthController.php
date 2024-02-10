<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function getRegister()
    {
        return view('register');
    }

    public function postRegister()
    {
        return view('login');
    }
    public function index()
    {
        return view('index');
    }
}

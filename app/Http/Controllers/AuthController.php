<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AuthController extends Controller
{
  public function login(Request $request)
  {
     if (Auth::attempt($request->only(['email', 'password']))) {
         return response(["success" => true], 200);
     } else {
         return response(["success" => false], 403);
     }
  }

  public function logout(Request $request)
  {
    // Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();
  }

}

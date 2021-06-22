<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

  // Login Function
  public function goodbye(Request $request)
  {
    if(Auth::check()){
      $active_session = Session::where('user_id', Auth::user()->id)->where('active', true)->orderBy('id', 'desc')->first();
      if (!empty($active_session)){
        $active_session->end_time = \Carbon\Carbon::now();
        $active_session->duration = \Carbon\Carbon::parse($active_session->start_time)->diffInSeconds(\Carbon\Carbon::now());
        $active_session->active = false;
        $active_session->save();
        return response(["success" => true], 200);
      }
    }
    return response(["success" => false], 200);


  }
  public function login(Request $request)
  {
     if (Auth::attempt($request->only(['email', 'password']))) {
       $open_sessions = Session::where('user_id', Auth::user()->id)->where('active', true)->get();
       foreach ($open_sessions as $open_session){
         $open_session->delete();
       }
       $session = New Session;
       $session->user_id = Auth::user()->id;
       $session->start_time = \Carbon\Carbon::now();
       $session->active = true;
       $session->save();
        // Auth::user()->last_login = \Carbon\Carbon::now();
        return response()->json([ "success" => true, "user" => Auth::user() ], 200);
         // return response(["success" => Auth::user()], 200);
     } else {
         return response(["success" => false], 403);
     }
  }

  // Logout Function
  public function logout(Request $request)
  {
    if(Auth::check()){
      $active_session = Session::where('user_id', Auth::user()->id)->where('active', true)->orderBy('id', 'desc')->first();
      if (!empty($active_session)){
        $active_session->end_time = \Carbon\Carbon::now();
        $active_session->duration = \Carbon\Carbon::parse($active_session->start_time)->diffInSeconds(\Carbon\Carbon::now());
        $active_session->active = false;
        $active_session->save();
      }
    }
    // return response($active_session, 200);

    // Auth::user()->save();
    Auth::guard('web')->logout();
    // return $request;
    $request->session()->invalidate();
    //
    $request->session()->regenerateToken();

    return response(["success" => true], 200);
  }


  // Refresh the User
  public function refresh(Request $request){
    if(Auth::user()){
      return response()->json([ "success" => true, "user" => Auth::user() ], 200);
    }
    return response()->json([ "success" => false ], 200);


  }

  public function register(Request $request)
  {
    try {
      if(!Auth::check()){
        return response()->json([ "success" => false, "msg" => "You are not authenticated"], 201);
      }

      $loggedUser = Auth::user();

      if($loggedUser->role==2){
        if ($request->has('role')){
          if($request->role==0){
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 0,
                'password' => Hash::make($request->password)
            ]);
            return response()->json([ "success" => true, "msg" => "User was successfully created"], 200);
          }
          if($request->role==1){
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 1,
                'password' => Hash::make($request->password)
            ]);
            return response()->json([ "success" => true, "msg" => "Client was successfully created"], 200);
          }
          if($request->role==2){
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 2,
                'password' => Hash::make($request->password)
            ]);
            return response()->json([ "success" => true, "msg" => "Superadmin was successfully created"], 200);
          }
        }
        else{
          User::create([
              'name' => $request->name,
              'email' => $request->email,
              'role' => 0,
              'password' => Hash::make($request->password)
          ]);
          return response()->json([ "success" => true, "msg" => "User was successfully created"], 200);
        }
      }
      else{
        return response()->json([ "success" => false, "msg" => "You are not a Superadmin"], 201);
      }
    }
    catch (\Exception $e) { // It's actually a QueryException but this works too
     if ($e->getCode() == 23000) {
         return response()->json([ "success" => false, "msg" => "This E-Mail already exists!"], 201);


     }
      // return response()->json("User created", 200);

    }
  }

}

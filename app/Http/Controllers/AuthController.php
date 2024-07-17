<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Add this line


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
    // Log::info('Login attempt:', ['email' => $request->email]);

    if (Auth::attempt($request->only(['email', 'password']))) {
        // Log::info('Login successful:', ['user_id' => Auth::user()->id]);

        $open_sessions = Session::where('user_id', Auth::user()->id)->where('active', true)->get();
        // Log::info('Open sessions found:', ['count' => $open_sessions->count()]);

        foreach ($open_sessions as $open_session) {
            $open_session->end_time = $open_session->last_alive;
            $open_session->duration = \Carbon\Carbon::parse($open_session->start_time)->diffInSeconds(\Carbon\Carbon::parse($open_session->end_time));
            $open_session->active = false;
            $open_session->save();
            // Log::info('Closed session:', ['session_id' => $open_session->id]);
            // $open_session->delete();
        }

        $session = new Session;
        $session->user_id = Auth::user()->id;
        $session->start_time = \Carbon\Carbon::now();
        $session->active = true;
        $session->last_alive = \Carbon\Carbon::now();
        $session->session_string = bin2hex(random_bytes(16)); // Generate a random session string
        $session->save();
        // Log::info('New session started:', ['session_id' => $session->id]);

        // Auth::user()->last_login = \Carbon\Carbon::now();
        return response()->json(["success" => true, "user" => Auth::user(), "session_string" => $session->session_string], 200);
        // return response(["success" => Auth::user()], 200);
    } else {
        // Log::warning('Login failed:', ['email' => $request->email]);
        return response(["success" => false], 403);
    }
}

 
  // public function login(Request $request)
  // {
  //     // Log::info('Login attempt:', ['email' => $request->email]);
  
  //     if (Auth::attempt($request->only(['email', 'password']))) {
  //         // Log::info('Login successful:', ['user_id' => Auth::user()->id]);
  
  //         $open_sessions = Session::where('user_id', Auth::user()->id)->where('active', true)->get();
  //         // Log::info('Open sessions found:', ['count' => $open_sessions->count()]);
  
  //         foreach ($open_sessions as $open_session) {
  //             $open_session->end_time = $open_session->last_alive;
  //             $open_session->duration = \Carbon\Carbon::parse($open_session->start_time)->diffInSeconds(\Carbon\Carbon::parse($open_session->end_time));
  //             $open_session->active = false;
  //             $open_session->save();
  //             // Log::info('Closed session:', ['session_id' => $open_session->id]);
  //             // $open_session->delete();
  //         }
  
  //         $session = new Session;
  //         $session->user_id = Auth::user()->id;
  //         $session->start_time = \Carbon\Carbon::now();
  //         $session->active = true;
  //         $session->last_alive = \Carbon\Carbon::now();
  //         $session->save();
  //         // Log::info('New session started:', ['session_id' => $session->id]);
  
  //         // Auth::user()->last_login = \Carbon\Carbon::now();
  //         return response()->json(["success" => true, "user" => Auth::user()], 200);
  //         // return response(["success" => Auth::user()], 200);
  //     } else {
  //         // Log::warning('Login failed:', ['email' => $request->email]);
  //         return response(["success" => false], 403);
  //     }
  // }
  

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
  public function refresh(Request $request)
  {
      try {
          Log::info('Session data', ['session' => $request->session()->all()]);
          Log::info('Cookies', ['cookies' => $request->cookies->all()]);
  
          if (Auth::check()) {
              $user = Auth::user();
              Log::info('Refresh successful', ['user_id' => $user->id, 'user_email' => $user->email]);
              return response()->json(["success" => true, "user" => $user], 200);
          } else {
              Log::info('Refresh failed: user not authenticated');
              return response()->json(["success" => false], 200);
          }
      } catch (\Exception $e) {
          Log::error('Refresh error: ' . $e->getMessage(), [
              'trace' => $e->getTraceAsString(),
              'request' => $request->all()
          ]);
          return response()->json(["success" => false, "message" => "Internal Server Error"], 500);
      }
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

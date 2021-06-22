<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Session;
use App\Models\User;
use Auth;


class CheckSessionStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

      $response = $next($request);

      if(Auth::check()){
        $last_session = Session::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
        if(\Carbon\Carbon::now()->diffInSeconds(\Carbon\Carbon::parse($last_session->end_time))<5){
          $last_session->end_time=null;
          $last_session->active=1;
          $last_session->duration=null;
          $last_session->save();
        }
        else{
          $active_session = Session::where('user_id', Auth::user()->id)->where('active', true)->orderBy('id', 'desc')->first();
          if (empty($active_session)){
            $session = New Session;
            $session->user_id = Auth::user()->id;
            $session->start_time = \Carbon\Carbon::now();
            $session->active = true;
            $session->save();
          }
        }
      }

      return $response;


    }
}

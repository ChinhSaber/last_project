<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class CheckDoctor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Session::get('doctor_id') > 0){
            return $next($request);
        }else{
            return redirect()-> route('login')->with('erron','Bạn phải đăng nhập ');
        }
    }
}   

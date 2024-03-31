<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
       $header = $request->header("Authorization");
     
        if (!$header || !User::where("token", str_replace("Bearer ", "",$header))->first()) {
            return response()->json([
                "errors" => [
                    "messages" =>  ["unauthorized"]
                ]
                ])->setStatusCode(401);
        } else {
            
            Auth::login(User::where("token", str_replace("Bearer ", "",$header))->first(), false);
            return $next($request);
        }
        
       
    }
}
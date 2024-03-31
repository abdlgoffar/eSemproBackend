<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResponse;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function create(UserCreateRequest $request): JsonResponse {
 
        $data = $request->validated();
       
        if (User::where("username", $data["username"])->count() == 1) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messages" => [
                        "username already registered"
                    ]
                ]
            ], 404));
        }

        $user = new User($data);
        $user->password = Hash::make($data["password"]);
        $user->save();

        return (new UserResponse($user))->response()->setStatusCode(201); 
    }

    public function login(UserLoginRequest $request): UserResponse
    {
        $data = $request->validated();

        $user = User::where('username', $data['username'])->first();
        
        // check username available and password is valid
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messages" => [
                        "username or password is wrong"
                    ]
                ]
            ], 401));
        }

        $user->token = Str::uuid()->toString();
        $user->save();

        return new UserResponse($user);
    }

    public function get(Request $request): UserResponse
    {
        $user = Auth::user();
        
        return new UserResponse($user);
    }
    
    public function update(UserUpdateRequest $request): UserResponse
    {
        $data = $request->validated();

        $user = Auth::user();

        if (isset($data['username'])) {
            $user->name = $data['username'];
        }
        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();
        return new UserResponse($user);
    }

    
    public function logout(Request $request): JsonResponse {
        $user = Auth::user();
        $user->token = null;
        $user->save();

        return response()->json([
            "data" => true
        ])->setStatusCode(200);
    }

}
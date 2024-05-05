<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCoordinatorCreateRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserExaminerCreateRequest;
use App\Http\Requests\UserHeadStudyProgramCreateRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserStudentCreateRequest;
use App\Http\Requests\UserSupervisorCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResponse;
use App\Models\Coordinator;
use App\Models\Examiner;
use App\Models\HeadStudyProgram;
use App\Models\Student;
use App\Models\Supervisor;
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

    public function getUserHeadStudyProgram() 
    {
        $user = Auth::user();
        $headStudyProgram = User::find($user->id)->headStudyProgram;
        return $headStudyProgram;
    }


    public function createStudentUser(UserStudentCreateRequest $request): JsonResponse
    {
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


        $user = new User();
        $user->username = $data["username"];
        $user->password = Hash::make($data["password"]);
        $user->role = "students";
        $user->save();

        $student = new Student();
        $student->name = $data["name"];
        $student->address = $data["address"];
        $student->phone = $data["phone"];
        $student->nrp = $data["nrp"];
        $student->user_id = $user->id;
        $student->head_study_program_id = $data["head_study_program_id"];
        $student->save();

        return response()->json([
            "data" => true
        ])->setStatusCode(201);

    }

    public function createSupervisorUser(UserSupervisorCreateRequest $request): JsonResponse
    {
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


        $user = new User();
        $user->username = $data["username"];
        $user->password = Hash::make($data["password"]);
        $user->role = "supervisors";
        $user->save();

        $supervisor = new Supervisor();
        $supervisor->name = $data["name"];
        $supervisor->address = $data["address"];
        $supervisor->phone = $data["phone"];
        $supervisor->user_id = $user->id;
        $supervisor->save();

        return response()->json([
            "data" => true
        ])->setStatusCode(201);

    }

    public function createHeadStudyProgramUser(UserHeadStudyProgramCreateRequest $request): JsonResponse
    {
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


        $user = new User();
        $user->username = $data["username"];
        $user->password = Hash::make($data["password"]);
        $user->role = "head-study-programs";
        $user->save();

        $headStudyProgram = new HeadStudyProgram();
        $headStudyProgram->name = $data["name"];
        $headStudyProgram->address = $data["address"];
        $headStudyProgram->phone = $data["phone"];
        $headStudyProgram->user_id = $user->id;
        $headStudyProgram->save();

        return response()->json([
            "data" => true
        ])->setStatusCode(201);

    }

    public function createExaminerUser(UserExaminerCreateRequest $request): JsonResponse
    {
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


        $user = new User();
        $user->username = $data["username"];
        $user->password = Hash::make($data["password"]);
        $user->role = "examiners";
        $user->save();

        $examiner = new Examiner();
        $examiner->name = $data["name"];
        $examiner->address = $data["address"];
        $examiner->phone = $data["phone"];
        $examiner->user_id = $user->id;
        $examiner->save();

        return response()->json([
            "data" => true
        ])->setStatusCode(201);

    }

    public function createCoordinatorUser(UserCoordinatorCreateRequest $request): JsonResponse
    {
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


        $user = new User();
        $user->username = $data["username"];
        $user->password = Hash::make($data["password"]);
        $user->role = "coordinators";
        $user->save();

        $coordinator = new Coordinator();
        $coordinator->name = $data["name"];
        $coordinator->address = $data["address"];
        $coordinator->phone = $data["phone"];
        $coordinator->user_id = $user->id;
        $coordinator->save();

        return response()->json([
            "data" => true
        ])->setStatusCode(201);

    }

    public function getUserByRole($role) 
    {
        $users = User::with('student')->with('examiner')->with('supervisor')->with('headStudyProgram')->with('coordinator')->where('role', $role)->get();


        return $users;
    }
}
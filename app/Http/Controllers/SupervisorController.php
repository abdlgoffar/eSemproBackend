<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupervisorCreateRequest;
use App\Http\Requests\SupervisorUpdateRequest;
use App\Http\Resources\SupervisorResponse;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{

    private function getUserToFK(int $user_id): User
    {
        $user = User::where('id', $user_id)->first();
        if (!$user) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "messages" => [
                        "user data not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        return $user;
    }

    public function create(int $user_id, SupervisorCreateRequest $request): JsonResponse
    {

        $user = $this->getUserToFK($user_id);

        $data = $request->validated();

        if (Supervisor::where('user_id', $user_id)->count() == 1) {
            throw new HttpResponseException(response([
                'errors' => [
                    "messages" => [
                        "supervisor data is available"
                    ]
                ]
            ], 404));
        }

        $supervisor = new Supervisor($data);
        $supervisor->user_id = $user->id;
        $supervisor->save();

        return (new SupervisorResponse($supervisor))->response()->setStatusCode(201);
    }

    public function update(int $supervisor_id, SupervisorUpdateRequest $request): SupervisorResponse
    {
        $data = $request->validated();

        $supervisor = Supervisor::where('id', $supervisor_id)->first();

        if ($supervisor == false) {
            throw new HttpResponseException(response([
                'errors' => [
                    "messages" => [
                        "supervisor data not found"
                    ]
                ]
            ], 404));  
        }


        if (isset($data['name'])) {
            $supervisor->name = $data['name'];
        }
        if (isset($data['address'])) {
            $supervisor->address = $data['address'];
        }
        if (isset($data['phone'])) {
            $supervisor->phone = $data['phone'];
        }


        $supervisor->save();
        return new SupervisorResponse($supervisor);
    }

    public function get(): JsonResponse 
    {
        $supervisors = Supervisor::paginate();
        return response()->json($supervisors, 201, ["Content-Type" => "application/json"]);
    }
}
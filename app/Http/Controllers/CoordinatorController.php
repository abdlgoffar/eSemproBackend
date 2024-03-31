<?php

namespace App\Http\Controllers;

use App\Http\Requests\CoordinatorCreateRequest;
use App\Http\Requests\CoordinatorUpdateRequest;
use App\Http\Resources\CoordinatorResponse;
use App\Models\Coordinator;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CoordinatorController extends Controller
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

    public function create(int $user_id, CoordinatorCreateRequest $request): JsonResponse
    {

        $user = $this->getUserToFK($user_id);

        $data = $request->validated();

        if (Coordinator::where('user_id', $user_id)->count() == 1) {
            throw new HttpResponseException(response([
                'errors' => [
                    "messages" => [
                        "coordinator data is available"
                    ]
                ]
            ], 404));
        }

        $coordinator = new Coordinator($data);
        $coordinator->user_id = $user->id;
        $coordinator->save();

        return (new CoordinatorResponse($coordinator))->response()->setStatusCode(201);
    }

    public function update(int $coordinator_id, CoordinatorUpdateRequest $request): CoordinatorResponse
    {
        $data = $request->validated();

        
        if (!Coordinator::where('id', $coordinator_id)->first()) {
            throw new HttpResponseException(response([
                'errors' => [
                    "messages" => [
                        "coordinator data not found"
                    ]
                ]
            ], 404));
        }

        $coordinator = Coordinator::where('id', $coordinator_id)->first();

        if (isset($data['name'])) {
            $coordinator->name = $data['name'];
        }
        if (isset($data['address'])) {
            $coordinator->address = $data['address'];
        }
        if (isset($data['phone'])) {
            $coordinator->phone = $data['phone'];
        }
       

        $coordinator->save();
        return new CoordinatorResponse($coordinator);
    }
}
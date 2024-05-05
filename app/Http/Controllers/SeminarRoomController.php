<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeminarRoomCreateRequest;
use App\Http\Resources\SeminarRoomResponse;
use App\Models\SeminarRoom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SeminarRoomController extends Controller
{
    public function create(SeminarRoomCreateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $seminarRoom = new SeminarRoom($data);

        $seminarRoom->save();

        return (new SeminarRoomResponse($seminarRoom))->response()->setStatusCode(201);
    }

    public function get(): JsonResponse 
    {
        $seminarRooms = SeminarRoom::paginate();
        return response()->json($seminarRooms, 201, ["Content-Type" => "application/json"]);
    }
}
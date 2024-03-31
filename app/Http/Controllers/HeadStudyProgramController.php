<?php

namespace App\Http\Controllers;

use App\Http\Requests\HeadStudyProgramCreateRequest;
use App\Http\Requests\HeadStudyProgramUpdateRequest;
use App\Http\Resources\HeadStudyProgramResponse;
use App\Models\HeadStudyProgram;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HeadStudyProgramController extends Controller
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

    public function create(int $user_id, HeadStudyProgramCreateRequest $request): JsonResponse
    {

        $user = $this->getUserToFK($user_id);

        $data = $request->validated();

        if (HeadStudyProgram::where('user_id', $user_id)->count() == 1) {
            throw new HttpResponseException(response([
              
                'errors' => [
                    "messages" => [
                        "head study program data is available"
                    ]
                ]
            ], 404));
        }

        $headStudyProgram = new HeadStudyProgram($data);
        $headStudyProgram->user_id = $user->id;
        $headStudyProgram->save();

        return (new HeadStudyProgramResponse($headStudyProgram))->response()->setStatusCode(201);
    }

    public function update(int $head_study_program_id, HeadStudyProgramUpdateRequest $request): HeadStudyProgramResponse
    {
        $data = $request->validated();

        
        if (!HeadStudyProgram::where('id', $head_study_program_id)->first()) {
        
            throw new HttpResponseException(response([
              
                'errors' => [
                    "messages" => [
                        "head study program data not found"
                    ]
                ]
            ], 404));
        }

        $headStudyProgram = HeadStudyProgram::where('id', $head_study_program_id)->first();

        if (isset($data['name'])) {
            $headStudyProgram->name = $data['name'];
        }
        if (isset($data['address'])) {
            $headStudyProgram->address = $data['address'];
        }
        if (isset($data['phone'])) {
            $headStudyProgram->phone = $data['phone'];
        }
       

        $headStudyProgram->save();
        return new HeadStudyProgramResponse($headStudyProgram);
    }

    public function get(): JsonResponse 
    {
        $headStudyProgram = HeadStudyProgram::paginate();
        return response()->json($headStudyProgram, 201, ["Content-Type" => "application/json"]);
    }
}
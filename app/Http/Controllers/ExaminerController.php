<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExaminerCreateRequest;
use App\Http\Requests\ExaminerUpdateRequest;
use App\Http\Resources\ExaminerResponse;
use App\Models\Examiner;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExaminerController extends Controller
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

    public function create(int $user_id, ExaminerCreateRequest $request): JsonResponse
    {

        $user = $this->getUserToFK($user_id);

        $data = $request->validated();

        if (Examiner::where('user_id', $user_id)->count() == 1) {
            throw new HttpResponseException(response([
                'errors' => [
                    "messages" => [
                        "examiner data is available"
                    ]
                ]
            ], 404));
        }

        $examiner = new Examiner($data);
        $examiner->user_id = $user->id;
        $examiner->save();

        return (new ExaminerResponse($examiner))->response()->setStatusCode(201);
    }

    public function update(int $examiner_id, ExaminerUpdateRequest $request): ExaminerResponse
    {
        $data = $request->validated();

        
        if (!Examiner::where('id', $examiner_id)->first()) {
        
            throw new HttpResponseException(response([
                'errors' => [
                    "messages" => [
                        "examiner data not found"
                    ]
                ]
            ], 404));
        }

        $examiner = Examiner::where('id', $examiner_id)->first();

        if (isset($data['name'])) {
            $examiner->name = $data['name'];
        }
        if (isset($data['address'])) {
            $examiner->address = $data['address'];
        }
        if (isset($data['phone'])) {
            $examiner->phone = $data['phone'];
        }
       

        $examiner->save();
        return new ExaminerResponse($examiner);
    }

    public function get(): JsonResponse 
    {
        $examiners = Examiner::paginate();
        return response()->json($examiners, 201, ["Content-Type" => "application/json"]);
    }



    public function getExaminerInvitations() 
    {
        // $user = Auth::user();
        // $examiner = Examiner::where('user_id', $user->id)->first();

        // $invitations = $examiner->invitations;//display invitations
        
        // return $examiner;

        $user = Auth::user();
        $examiner = Examiner::where('user_id', $user->id)->first();
        
        $examiner->load('invitations.students');
  
    
        return $examiner;
    }
}
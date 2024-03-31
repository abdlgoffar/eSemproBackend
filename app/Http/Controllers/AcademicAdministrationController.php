<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcademicAdministrationCreateRequest;
use App\Http\Requests\AcademicAdministrationUpdateRequest;
use App\Http\Resources\AcademicAdministrationResponse;
use App\Models\AcademicAdministration;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class AcademicAdministrationController extends Controller
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
 
    public function create(int $user_id, AcademicAdministrationCreateRequest $request): JsonResponse
    {

        $user = $this->getUserToFK($user_id);

        $data = $request->validated();

        if (AcademicAdministration::where('user_id', $user_id)->count() == 1) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messages" => [
                        "academic administration data is available"
                    ]
                ]
            ], 404));
        }

        $academicAdministration = new AcademicAdministration($data);
        $academicAdministration->user_id = $user->id;
        $academicAdministration->save();

        return (new AcademicAdministrationResponse($academicAdministration))->response()->setStatusCode(201);
    }

    public function update(int $academic_administration_id, AcademicAdministrationUpdateRequest $request): AcademicAdministrationResponse
    {
        $data = $request->validated();

        
        if (!AcademicAdministration::where('id', $academic_administration_id)->first()) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messages" => [
                        "academic administration data not found"
                    ]
                ]

            ], 404));
        }

        $academicAdministration = AcademicAdministration::where('id', $academic_administration_id)->first();

        if (isset($data['name'])) {
            $academicAdministration->name = $data['name'];
        }
        if (isset($data['address'])) {
            $academicAdministration->address = $data['address'];
        }
        if (isset($data['phone'])) {
            $academicAdministration->phone = $data['phone'];
        }
       

        $academicAdministration->save();
        return new AcademicAdministrationResponse($academicAdministration);
    }

    
}
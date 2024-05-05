<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvitationCreateRequest;
use App\Http\Resources\InvitationResponse;
use App\Models\Coordinator;
use App\Models\Examiner;
use App\Models\Invitation;
use App\Models\InvitationPdf;
use App\Models\Seminar;
use App\Models\Student;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvitationController extends Controller
{

    private function getCoordinatorToFK($coordinator_id): Coordinator
    {
        $coordinator = Coordinator::where('id', $coordinator_id)->first();
        if (!$coordinator) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "messages" => [
                        "coordinator data not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        return $coordinator;
    }
    
    private function createInvitationStudent(int $student_id, $invitation_id)
    {
        $student = Student::where('id', $student_id)->first();
        $student->invitation_id = $invitation_id;
        $student->save();
        
    }

    private function createInvitationExaminers(int $student_id, $invitation_id) 
    {
        $student = Student::find($student_id);
      
        $examiners = $student->examiners;

        foreach ($examiners as $examiner) {
            $invitation = Invitation::where('id', $invitation_id)->first();
            $invitation->examiners()->sync([$examiner->id]);
        }
       
    }
    
    public function create(InvitationCreateRequest $request)
    {
        $data = $request->validated();

        $coordinator = $this->getCoordinatorToFK($data["coordinator_id"]);
        
        $seminar = new Seminar();
        $seminar->implementation_date = $data["implementation_date"];
        $seminar->save();

        $invitation = new Invitation($data);
        $invitation->seminar_id = $seminar->id;
        $invitation->coordinator_id = $coordinator->id;
        $invitation->save();
        
        
        //invitation
        foreach ($data["students"] as $studentId) {
            $this->createInvitationStudent($studentId, $invitation->id);

            $this->createInvitationExaminers($studentId, $invitation->id);
        }

        // return (new InvitationResponse($invitation))->response()->setStatusCode(201);
        
    }
}
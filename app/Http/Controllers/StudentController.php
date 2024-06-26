<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentCreateRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Http\Resources\StudentResponse;
use App\Models\Examiner;
use App\Models\HeadStudyProgram;
use App\Models\Invitation;
use App\Models\Proposal;
use App\Models\SeminarRoom;
use App\Models\Student;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
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

    private function getInvitationToFK(int $invitation_id): Invitation
    {
        $invitation = Invitation::where('id', $invitation_id)->first();
        if (!$invitation) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "messages" => [
                        "invitation data not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        return $invitation;
    }

    private function getHeadStudyProgramToFK(int $head_study_program_id): HeadStudyProgram
    {
        $headStudyProgram = HeadStudyProgram::where('id', $head_study_program_id)->first();
        if (!$headStudyProgram) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "messages" => [
                        "head study program data not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        return $headStudyProgram;
    }

    private function getProposalToFK(int $proposal_id): Proposal
    {
        $proposal = Proposal::where('id', $proposal_id)->first();
        if (!$proposal) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "messages" => [
                        "proposal data not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        return $proposal;
    }

    private function getSupervisorToFK(int $supervisor_id): Supervisor
    {
        $supervisor = Supervisor::where('id', $supervisor_id)->first();
        if (!$supervisor) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "messages" => [
                        "supervisor data not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        return $supervisor;
    }

    private function getExaminerToFK(int $examiner_id): Examiner
    {
        $examiner = Examiner::where('id', $examiner_id)->first();
        if (!$examiner) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "messages" => [
                        "examiner data not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        return $examiner;
    }

    private function getSeminarRoomToFK(int $seminar_room_id): SeminarRoom
    {
        $seminarRoom = SeminarRoom::where('id', $seminar_room_id)->first();
        if (!$seminarRoom) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "messages" => [
                        "seminar room data not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        return $seminarRoom;
    }

    public function getStudentById(int $student_id): StudentResponse
    {
        $student = Student::where('id', $student_id)->first();
        if (!$student) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "messages" => [
                        "student data not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        return new StudentResponse($student);
    }

    public function getStudentProposal(int $student_id)
    {
        $student = Student::find($student_id);
        $proposal = $student->proposal;

        return $proposal;
    }

    public function create(int $user_id, int $head_study_program_id, StudentCreateRequest $request): JsonResponse
    {

        $user = $this->getUserToFK($user_id);

        $headStudyProgram = $this->getHeadStudyProgramToFK($head_study_program_id);

        $data = $request->validated();

        if (Student::where('user_id', $user_id)->count() == 1) {
            throw new HttpResponseException(response([
                'errors' => [
                    "messages" => [
                        "student data is available"
                    ]
                ]
            ], 404));
        }

        $student = new Student($data);
        $student->user_id = $user->id;
        $student->head_study_program_id = $headStudyProgram->id;
        $student->save();

        return (new StudentResponse($student))->response()->setStatusCode(201);
    }

    public function update(int $student_id, ?int $invitation_id = 0, ?int $head_study_program_id = 0, ?int $proposal_id = 0, ?int $seminar_room_id = 0, StudentUpdateRequest $request): StudentResponse
    {
        $data = $request->validated();

        $student = Student::where('id', $student_id)->first();

        if ($student == false) {
            throw new HttpResponseException(response([
                'errors' => [
                    "messages" => [
                        "student data not found"
                    ]
                ]
            ], 404));
        }

        if (isset($data['name'])) {
            $student->name = $data['name'];
        }
        if (isset($data['address'])) {
            $student->address = $data['address'];
        }
        if (isset($data['phone'])) {
            $student->phone = $data['phone'];
        }
        if (isset($data['nrp'])) {
            $student->nrp = $data['nrp'];
        }

        if ($invitation_id !== 0) {
            $invitation = $this->getInvitationToFK($invitation_id);
            $student->invitation_id = $invitation->id;
        }
        if ($head_study_program_id !== 0) {
            $headStudyProgram = $this->getHeadStudyProgramToFK($head_study_program_id);
            $student->head_study_program_id = $headStudyProgram->id;
        }
        if ($proposal_id !== 0) {
            $proposal = $this->getProposalToFK($proposal_id);
            $student->proposal_id = $proposal->id;
            $student->is_proposal_available = 1;
        }
        if ($seminar_room_id !== 0) {
            $seminarRoom = $this->getSeminarRoomToFK($seminar_room_id);
            $student->seminar_room_id = $seminarRoom->id;
        }

        $student->save();
        return new StudentResponse($student);
    }

    public function createStudentSupervisor(Request $request): JsonResponse
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if ($student == false) {
            throw new HttpResponseException(response([
                'errors' => [
                    "messages" => [
                        "student data not found"
                    ]
                ]
            ], 404));
        }

        $data = Validator::make($request->all(), ['supervisors' => ["required", "array"]]);

        if ($data->fails()) throw new ValidationException($data);

        if (empty($request->supervisors)) throw new HttpResponseException(response(['errors' => ["messages" => ["array data supervisors is empty"]]], 400));

        foreach ($request->supervisors as $i) {
            if (is_numeric($i) == false || $i == 0) throw new HttpResponseException(response(['errors' => ["messages" => ["array data value must number and not zero"]]], 400));
        }

        foreach ($request->supervisors as $i) {
            $this->getSupervisorToFK($i);
        }

        $result = $student->supervisors()->sync($request->supervisors);

        return response()->json([$result], 201);
    }

    public function createStudentExaminer(Request $request, int $student_id): JsonResponse
    {

        $student = Student::where('id', $student_id)->first();

        if ($student == false) {
            throw new HttpResponseException(response([
                'errors' => [
                    "messages" => [
                        "student data not found"
                    ]
                ]
            ], 404));
        }

        $data = Validator::make($request->all(), ['examiners' => ["required", "array"]]);

        if ($data->fails()) throw new ValidationException($data);

        if (empty($request->examiners)) throw new HttpResponseException(response(['errors' => ["messages" => ["array data examiners is empty"]]], 400));

        foreach ($request->examiners as $i) {
            if (is_numeric($i) == false || $i == 0) throw new HttpResponseException(response(['errors' => ["messages" => ["array data value must number and not zero"]]], 400));
        }

        foreach ($request->examiners as $i) {
            $this->getExaminerToFK($i);
        }

        $result = $student->examiners()->sync($request->examiners);

        return response()->json([$result], 201);
    }

    public function deleteStudentExaminer(int $student_id): JsonResponse
    {

        $student = Student::where('id', $student_id)->first();

        if ($student == false) {
            throw new HttpResponseException(response([
                'errors' => [
                    "messages" => [
                        "student data not found"
                    ]
                ]
            ], 404));
        }

        $result = $student->examiners()->detach();

        return response()->json([$result], 201);
    }


    public function getStudentsHaveExaminer()
    {
        $studentsWithExaminers = Student::whereHas('examiners')->get();
        return $studentsWithExaminers;
    }

    public function getStudentByUserId(): JsonResponse
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if ($student == false) {
            throw new HttpResponseException(response([
                'errors' => [
                    "messages" => [
                        "student data not found"
                    ]
                ]
            ], 404));
        }


        return (new StudentResponse($student))->response()->setStatusCode(201);
    }
}
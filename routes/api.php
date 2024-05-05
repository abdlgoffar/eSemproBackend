<?php

use Illuminate\Support\Facades\Route;



Route::post("/users/login", [\App\Http\Controllers\UserController::class, "login"]);

Route::middleware(\App\Http\Middleware\AuthenticationMiddleware::class)->group(function () {
    Route::post("/users/create", [\App\Http\Controllers\UserController::class, "create"]);
    Route::get("/users/get", [\App\Http\Controllers\UserController::class, "get"]);
    Route::get("/users/headStudyPrograms", [\App\Http\Controllers\UserController::class, "getUserHeadStudyProgram"]);
    Route::delete("/users/logout", [\App\Http\Controllers\UserController::class, "logout"]);
    Route::patch("/users/update", [\App\Http\Controllers\UserController::class, "update"]);
    Route::post("/users/students/create", [\App\Http\Controllers\UserController::class, "createStudentUser"]);
    Route::post("/users/supervisors/create", [\App\Http\Controllers\UserController::class, "createSupervisorUser"]);
    Route::post("/users/head-study-programs/create", [\App\Http\Controllers\UserController::class, "createHeadStudyProgramUser"]);
    Route::post("/users/examiners/create", [\App\Http\Controllers\UserController::class, "createExaminerUser"]);
    Route::post("/users/coordinators/create", [\App\Http\Controllers\UserController::class, "createCoordinatorUser"]);
    Route::get("/users/{role}", [\App\Http\Controllers\UserController::class, "getUserByRole"]);


    Route::post('/academic-administrations/create/users/{user_id}', [\App\Http\Controllers\AcademicAdministrationController::class, 'create'])->where('user_id', '[0-9]+');
    Route::patch('/academic-administrations/update/{academic_administration_id}', [\App\Http\Controllers\AcademicAdministrationController::class, 'update'])->where('academic_administration_id', '[0-9]+');

    Route::post('/coordinators/create/users/{user_id}', [\App\Http\Controllers\CoordinatorController::class, 'create'])->where('user_id', '[0-9]+');
    Route::patch('/coordinators/update/{coordinator_id}', [\App\Http\Controllers\CoordinatorController::class, 'update'])->where('coordinator_id', '[0-9]+');
    Route::get('/coordinators/get', [\App\Http\Controllers\CoordinatorController::class, 'get']);

    Route::post('/examiners/create/users/{user_id}', [\App\Http\Controllers\ExaminerController::class, 'create'])->where('user_id', '[0-9]+');
    Route::patch('/examiners/update/{examiner_id}', [\App\Http\Controllers\ExaminerController::class, 'update'])->where('examiner_id', '[0-9]+');
    Route::get('/examiners/get', [\App\Http\Controllers\ExaminerController::class, 'get']);
    Route::get('/examiners/invitations/current', [\App\Http\Controllers\ExaminerController::class, 'getExaminerInvitations']);
  

    Route::post('/supervisors/create/users/{user_id}', [\App\Http\Controllers\SupervisorController::class, 'create'])->where('user_id', '[0-9]+');
    Route::patch('/supervisors/update/{supervisor_id}', [\App\Http\Controllers\SupervisorController::class, 'update'])->where('supervisor_id', '[0-9]+');
    Route::get('/supervisors/get', [\App\Http\Controllers\SupervisorController::class, 'get']);

    Route::post('/head-study-programs/create/users/{user_id}', [\App\Http\Controllers\HeadStudyProgramController::class, 'create'])->where('user_id', '[0-9]+');
    Route::patch('/head-study-programs/update/{head_study_program_id}', [\App\Http\Controllers\HeadStudyProgramController::class, 'update'])->where('head_study_program_id', '[0-9]+');
    Route::get('/head-study-programs/get', [\App\Http\Controllers\HeadStudyProgramController::class, 'get']);
    Route::get('/head-study-programs/{head_study_program_id}/students', [\App\Http\Controllers\HeadStudyProgramController::class, 'getStudentsByHeadStudyProgramId'])->where('head_study_program_id', '[0-9]+');

    Route::post('/students/create/users/{user_id}/head-study-programs/{head_study_program_id}', [\App\Http\Controllers\StudentController::class, 'create'])->whereIn('user_id', ['[0-9]+'])->whereIn('head_study_program_id', ['[0-9]+']);
    Route::post('/students/supervisors', [\App\Http\Controllers\StudentController::class, 'createStudentSupervisor']);
    Route::post('/students/examiners/{student_id}', [\App\Http\Controllers\StudentController::class, 'createStudentExaminer'])->where('student_id', '[0-9]+');
    Route::delete('/students/examiners/{student_id}', [\App\Http\Controllers\StudentController::class, 'deleteStudentExaminer'])->where('student_id', '[0-9]+');
    Route::patch('/students/update/{student_id}/invitations/{invitation_id?}/head-study-programs/{head_study_program_id?}/proposals/{proposal_id?}/seminar-rooms/{seminar_room_id?}', [\App\Http\Controllers\StudentController::class, 'update'])->whereIn('student_id', ['[0-9]+'])->whereIn('invitation_id', ['[0-9]+'])->whereIn('head_study_program_id', ['[0-9]+'])->whereIn('proposal_id', ['[0-9]+'])->whereIn('seminar_room_id', ['[0-9]+']);
    Route::get('/students/{student_id}', [\App\Http\Controllers\StudentController::class, 'getStudentById'])->where('student_id', '[0-9]+');
    Route::get('/students/current', [\App\Http\Controllers\StudentController::class, 'getStudentByUserId']);
    Route::get('/students/proposals/{student_id}', [\App\Http\Controllers\StudentController::class, 'getStudentProposal'])->where('student_id', '[0-9]+');
    Route::get('/students/examiners/all', [\App\Http\Controllers\StudentController::class, 'getStudentsHaveExaminer']);


    Route::post('/proposals/create', [\App\Http\Controllers\ProposalController::class, 'create']);
    Route::delete('/proposals/delete/{proposal_id}', [\App\Http\Controllers\ProposalController::class, 'delete'])->where('proposal_id', '[0-9]+');

    Route::post('/seminar-rooms/create', [\App\Http\Controllers\SeminarRoomController::class, 'create']);
    Route::get('/seminar-rooms/get', [\App\Http\Controllers\SeminarRoomController::class, 'get']);

    Route::post('/proposals-pdf/upload/propsals/{proposal_id}', [\App\Http\Controllers\ProposalPdfController::class, 'upload'])->where('proposal_id', '[0-9]+');
    Route::get('/proposals-pdf/{proposal_id}', [\App\Http\Controllers\ProposalPdfController::class, 'download']);
    Route::delete('/proposals-pdf/delete/{proposal_pdf_id}', [\App\Http\Controllers\ProposalPdfController::class, 'delete'])->where('proposal_pdf_id', '[0-9]+');
   

    Route::post('/invitations-pdf/upload/invitations/{invitation_id}', [\App\Http\Controllers\InvitationPdfController::class, 'upload'])->where('invitation_id', '[0-9]+');
    Route::get('/invitations-pdf/{invitation_id}', [\App\Http\Controllers\InvitationPdfController::class, 'download']);
    Route::delete('/invitations-pdf/delete/{invitation_pdf_id}', [\App\Http\Controllers\InvitationPdfController::class, 'delete'])->where('invitation_pdf_id', '[0-9]+');

    
    Route::post('/invitations/create', [\App\Http\Controllers\InvitationController::class, 'create']);

});
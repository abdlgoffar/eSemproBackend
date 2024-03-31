<?php

use Illuminate\Support\Facades\Route;


Route::post("/users/create", [\App\Http\Controllers\UserController::class, "create"]);
Route::post("/users/login", [\App\Http\Controllers\UserController::class, "login"]);

Route::middleware(\App\Http\Middleware\AuthenticationMiddleware::class)->group(function () {
    Route::get("/users/get", [\App\Http\Controllers\UserController::class, "get"]);
    Route::delete("/users/logout", [\App\Http\Controllers\UserController::class, "logout"]);
    Route::patch("/users/update", [\App\Http\Controllers\UserController::class, "update"]);


    Route::post('/academic-administrations/create/users/{user_id}', [\App\Http\Controllers\AcademicAdministrationController::class, 'create'])->where('user_id', '[0-9]+');
    Route::patch('/academic-administrations/update/{academic_administration_id}', [\App\Http\Controllers\AcademicAdministrationController::class, 'update'])->where('academic_administration_id', '[0-9]+');


    Route::post('/coordinators/create/users/{user_id}', [\App\Http\Controllers\CoordinatorController::class, 'create'])->where('user_id', '[0-9]+');
    Route::patch('/coordinators/update/{coordinator_id}', [\App\Http\Controllers\CoordinatorController::class, 'update'])->where('coordinator_id', '[0-9]+');


    Route::post('/examiners/create/users/{user_id}', [\App\Http\Controllers\ExaminerController::class, 'create'])->where('user_id', '[0-9]+');
    Route::patch('/examiners/update/{examiner_id}', [\App\Http\Controllers\ExaminerController::class, 'update'])->where('examiner_id', '[0-9]+');


    Route::post('/supervisors/create/users/{user_id}', [\App\Http\Controllers\SupervisorController::class, 'create'])->where('user_id', '[0-9]+');
    Route::patch('/supervisors/update/{supervisor_id}', [\App\Http\Controllers\SupervisorController::class, 'update'])->where('supervisor_id', '[0-9]+');
    Route::get('/supervisors/get', [\App\Http\Controllers\SupervisorController::class, 'get']);


    Route::post('/head-study-programs/create/users/{user_id}', [\App\Http\Controllers\HeadStudyProgramController::class, 'create'])->where('user_id', '[0-9]+');
    Route::patch('/head-study-programs/update/{head_study_program_id}', [\App\Http\Controllers\HeadStudyProgramController::class, 'update'])->where('head_study_program_id', '[0-9]+');
    Route::get('/head-study-programs/get', [\App\Http\Controllers\HeadStudyProgramController::class, 'get']);

    Route::post('/students/create/users/{user_id}/head-study-programs/{head_study_program_id}', [\App\Http\Controllers\StudentController::class, 'create'])->whereIn('user_id', ['[0-9]+'])->whereIn('head_study_program_id', ['[0-9]+']);
    Route::post('/students/supervisors', [\App\Http\Controllers\StudentController::class, 'studentSupervisor']);
    Route::patch('/students/update/{student_id}/invitations/{invitation_id?}/head-study-programs/{head_study_program_id?}/proposals/{proposal_id?}', [\App\Http\Controllers\StudentController::class, 'update'])->whereIn('student_id', ['[0-9]+'])->whereIn('invitation_id', ['[0-9]+'])->whereIn('head_study_program_id', ['[0-9]+'])->whereIn('proposal_id', ['[0-9]+']);


    Route::post('/proposals/create', [\App\Http\Controllers\ProposalController::class, 'create']);


    Route::post('/proposals-pdf/upload/propsals/{proposal_id}', [\App\Http\Controllers\ProposalPdfController::class, 'upload'])->where('proposal_id', '[0-9]+');
    
});
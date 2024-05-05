<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;
    protected $table = "students";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        "name",
        "address",
        "phone",
        "nrp"
    ];



    //relationship setting
    
    //a student just one user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    //one student can have one invitation
    public function invitation(): BelongsTo {
        return $this->belongsTo(Invitation::class, "invitation_id", "id");
    }

    //one student can have one head dtudy program
    public function headStudyProgram(): BelongsTo {
        return $this->belongsTo(HeadStudyProgram::class, "head_study_program_id", "id");
    }

    //one student can have one proposal
    public function proposal(): BelongsTo {
        return $this->belongsTo(Proposal::class, "proposal_id", "id");
    }

    //many to many 
    public function supervisors(): BelongsToMany
    {
        return $this->belongsToMany(Supervisor::class, "students_supervisors")->withTimestamps();
    }

    //many to many 
    public function examiners(): BelongsToMany
    {
        return $this->belongsToMany(Examiner::class, "students_examiners")->withTimestamps();
    }


    //one student can have one seminar room
    public function seminarRoom(): BelongsTo {
        return $this->belongsTo(SeminarRoom::class, "seminar_room_id", "id");
    }

}
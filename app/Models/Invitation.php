<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invitation extends Model
{
    use SoftDeletes;
    protected $table = "invitations";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;
  

    protected $fillable = [
        "number",
        "implementation_date",
        "implementation_hours"
    ];



    
    // relationship setting

    
    //a invitation just can have one fild pdf
    public function invitationPdf(): HasOne
    {
        return $this->hasOne(InvitationPdf::class, "invitation_id", "id");
    }

    //one invitation can have many student
    public function students(): HasMany {
        return $this->hasMany(Student::class, "invitation_id", "id");
    }


    //many to many 
    public function examiners(): BelongsToMany
    {
        return $this->belongsToMany(Examiner::class, "examiners_invitations");
    }

    //one invitation for one seminar
    public function seminar(): BelongsTo
    {
        return $this->belongsTo(Seminar::class, "seminar_id", "id");
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(Coordinator::class);
    }


}
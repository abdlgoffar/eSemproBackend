<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proposal extends Model
{
    use SoftDeletes;
    protected $table = "proposals";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        "title",
        "upload_date",
        "semester"
    ];


    //a proposal can have one student
    public function student(): HasOne
    {
        return $this->hasOne(Student::class, "proposal_id", "id");
    }

      
    //a proposal just can have one file pdf
    public function proposalPdf(): HasOne
    {
        return $this->hasOne(ProposalPdf::class, "proposal_id", "id");
    }

     //one proposal can have many evaluation
    public function evaluations(): HasMany {
        return $this->hasMany(Evaluation::class, "proposal_id", "id");
    }

     //one proposal can have one seminar
     public function seminar(): BelongsTo
     {
         return $this->belongsTo(Seminar::class, "seminar_id", "id");
     }

     

}
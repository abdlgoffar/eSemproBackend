<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluation extends Model
{
    use SoftDeletes;
    protected $table = "evaluations";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;
  

    protected $fillable = [
        "value"
    ];

     
    //relationship setting

    //one evaluation just for one proposal
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class, "proposal_id", "id");
    }

    //one evaluation just for one recapitulation
    public function recapitulation(): BelongsTo
    {
        return $this->belongsTo(Recapitulation::class, "recapitulation_id", "id");
    }

    

      
}
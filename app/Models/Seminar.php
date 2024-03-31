<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seminar extends Model
{
    use SoftDeletes;
    protected $table = "seminars";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        "implementation_date",
    ];




    
    //one seminar can have many proposal
    public function proposals(): HasMany 
    {
        return $this->hasMany(Proposal::class, "seminar_id", "id");
    }

    //one seminar can have many invitation 
    public function invitations(): HasMany 
    {
        return $this->hasMany(Invitation::class, "seminar_id", "id");
    }
}
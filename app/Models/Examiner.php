<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Examiner extends Model
{
    use SoftDeletes;
    protected $table = "examiners";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        "name",
        "address",
        "phone"
    ];





    //relationship setting

    //a examiner just one user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    //many to many 
    public function invitations(): BelongsToMany
    {
        return $this->belongsToMany(Invitation::class, "examiners_invitations");
    }

    //many to many 
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, "students_examiners")->withTimestamps();
    }
}
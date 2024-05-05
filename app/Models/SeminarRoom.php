<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeminarRoom extends Model
{
    use SoftDeletes;
    protected $table = "seminar_rooms";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        "name"
    ];





    //one seminar room can have many students
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recapitulation extends Model
{
    use SoftDeletes;
    protected $table = "recapitulations";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;
  

    protected $fillable = [
        "value"
    ];

     
    //relationship setting

    //one recapitulation can have many evaluation
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, "recapitulation_id", "id");
    }


}
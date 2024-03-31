<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvitationPdf extends Model
{
    use SoftDeletes;
    protected $table = "invitation_pdfs";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;
  

    protected $fillable = [
        "name",
        "path"
    ];


    
    //relationship setting

    //a pdf file just to one invitation
    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class, "invitation_id", "id");
    }

   
}
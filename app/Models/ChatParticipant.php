<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatParticipant extends Model
{
    use HasFactory;

    protected $table = "chat_participants";
    protected $quarded = ['id'];
    protected $fillable = ['seeker_id'];





    public function seeker() :BelongsTo
    {
        return $this->belongsTo(Seeker::class,'seeker_id');
    }


}

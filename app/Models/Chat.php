<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;
        protected $table = "chats";
        protected $quarded = ['id'];
        protected $fillable = [
            'otherSeekerId',
            'created_by'
            // Add other fillable attributes here if needed
        ];

        public function participants() : HasMany
        {
            return $this->hasMany(ChatParticipant::class,'chat_id');

        }
        public function messages() : HasMany
        {
            return $this->hasMany(ChatMessage::class,'chat_id');

        }
        public function lastMessages()
        {
            return $this->hasOne(ChatMessage::class,'chat_id')->latest('updated_at');

        }

        public function scopeHasParticipant($query , $seekerId )
        {
            return $query->whereHas('participants' , function($q) use( $seekerId) {
                $q->where('seeker_id', $seekerId) ;
            }) ;

        }


}

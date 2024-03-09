<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = [
        'seeker_id',
        'advisor_id'

    ];
    public function advisor()
    {
        return $this->hasOne(Seeker::class, 'id', 'advisor_id');
    }
    public function seeker()
    {
        return $this->hasOne(Seeker::class, 'id', 'seeker_id');
    }
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'chat_id');
    }
    public function lastMessages()
    {
        return $this->hasOne(ChatMessage::class, 'chat_id')->latest('updated_at');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;
    protected $fillable = [
        'seeker_id',
        'day',
        'available',
        'from',
        'to',
        'total_time',
        'total_break_time',
        'break_from',
        'break_to',
    ];

    // * Relationship
    public function seeker(){
        return $this->belongsTo(Seeker::class);
    }
}

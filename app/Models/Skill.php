<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;
    protected $fillable = ['name','public'];

    // * Relationship

    public function advisors(){
        return $this->belongsToMany(Advisor::class);
    }
}

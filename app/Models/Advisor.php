<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class Advisor extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasRoles;
    protected $guard_name = 'web';
    protected $fillable = [
        'bio',
        'expertise',
        'seeker_id',
        'Offere',
        'approved'
    ];

    //upload advisor_profile_image
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('advisor_profile_image');
    }

    //upload advisor_Intro_video
    public function createMediaProduct(): void
    {
        $this->addMediaCollection('advisor_Intro_video');
    }

    //upload advisor_Certificates_PDF
    public function createMediaCertificates(): void
    {
        $this->addMediaCollection('advisor_Certificates_PDF');
    }

    // * Relationship
    public function seeker()
    {
        return $this->hasOne(Seeker::class);
    }

    public function Day()
    {
        return $this->hasMany(Day::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }
}

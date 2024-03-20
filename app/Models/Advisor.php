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
        'offere',
        'language',
        'country',
        'approved',
        'category_id'

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
        return $this->belongsTo(Seeker::class);
    }

    public function Day()
    {
        return $this->hasMany(Day::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);

        //$projects = $advisor->projects;

        //$advisor = $product->advisor;
    }

}

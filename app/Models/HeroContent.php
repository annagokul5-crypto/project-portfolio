<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroContent extends Model
{
    protected $table = 'hero_content';

    protected $fillable = [
        'name',
        'title',
        'objective',
        'resume_path',
        'updated_by',
    ];
}

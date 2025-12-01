<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'github_url',
        'live_url',
        'technologies',
        'is_featured'
    ];

    protected $casts = [
        'technologies' => 'array'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectScreenshot extends Model
{
    protected $fillable = [
        'project_id',
        'slot',        // 'home', 'navbar', 'about', etc.
        'title',       // 'Home Page'
        'image_path',  // 'screenshots/ecommerce/home.jpg'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);

    }

}

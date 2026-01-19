<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'short_description',
        'description',
        'features',
        'tools',
        'status',
        'github_link',
        'live_link',
        'order_index',
    ];



    protected $casts = [
        'technologies' => 'array'
    ];

    public function screenshots()
    {
        return $this->hasMany(ProjectScreenshot::class);
    }

}

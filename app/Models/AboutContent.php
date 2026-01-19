<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutContent extends Model
{
    protected $table = 'about_content';

    protected $fillable = ['content', 'image_path', 'updated_by'];
}

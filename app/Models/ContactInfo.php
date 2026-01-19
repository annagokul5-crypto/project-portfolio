<?php

// app/Models/ContactInfo.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    protected $table = 'contact_info';

    protected $fillable = [
        'phone',
        'email',
        'whatsapp',
        'linkedin',
        'github',
        'location',
    ];
}


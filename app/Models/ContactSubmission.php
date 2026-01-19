<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected $table = 'contact_submissions';
    protected $fillable = ['name', 'email', 'contact_number', 'subject', 'message', 'submitted_at'];
    protected $dates = ['submitted_at', 'created_at', 'updated_at'];
}

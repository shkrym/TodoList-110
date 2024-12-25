<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalTask extends Model
{
    use HasFactory;

    
    protected $table = 'calendar_tasks';  // Ensure this matches your database table

    protected $fillable = [
        'user_id',
        'content',
        'date',
        'is_done',
    ];}

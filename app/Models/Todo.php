<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Correct import statement for HasFactory

class Todo extends Model
{
    use HasFactory; // Use HasFactory trait to enable factory methods

    protected $fillable = ['user_id', 'content', 'is_done']; // Make sure these match your table columns
}

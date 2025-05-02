<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToDoItem extends Model
{
    protected $fillable = ['user_id', 'title', 'completed'];
}

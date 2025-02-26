<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['title',  'user_id', 'isImportant', 'deadline', 'completed'];

    public function subtasks()
    {
        return $this->hasMany(Subtask::class, 'group_id');
    }
}

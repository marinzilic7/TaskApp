<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    protected $fillable = ['title',  'group_id', 'isImportant', 'deadline', 'completed'];
    use HasFactory;

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'team_id',
        'project_id',
        'member_id',
        'deadline',
        'user_id',
    ];

    public function team_members()
    {
        return $this->belongsTo(TeamMember::class, 'member_id');
    }
}

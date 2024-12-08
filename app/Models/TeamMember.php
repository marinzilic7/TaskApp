<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'team_id'];

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');  // Povezuje se s User modelom putem user_id
    }

}

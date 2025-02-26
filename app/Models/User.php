<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class User extends Model implements Authenticatable
{
    use HasFactory;
    use AuthenticatableTrait;
    protected $fillable = ['firstName', 'lastName', 'email', 'password', 'role', 'image', 'last_online', 'is_online'];


    public function teams()
    {
        return $this->hasMany(Team::class);
    }


    public function user() {
        return $this->belongsTo(User::class, 'member_id', 'id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $table = 'team_member';

    protected $fillable = ['name', 'personal_email', 'institutional_email', 'register_id'];

    public function register()
    {
        return $this->belongsTo(Register::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamCapability extends Model
{
    use HasFactory;

    protected $table = 'team_capabilities';

    protected $fillable = [
        'team_id',
        'capability_type',
        'skill_level',
    ];

    // 🔗 İlişki: capability → team
    public function team()
    {
        return $this->belongsTo(ReliefTeam::class, 'team_id');
    }
}

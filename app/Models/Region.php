<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    /**
     * Mass assignment için izin verilen alanlar
     */
    protected $fillable = [
        'ad',
        'il',
        'ilce',
        'latitude',   // ✅ HARİTA
        'longitude',  // ✅ HARİTA
    ];

    /**
     * İlişkiler
     */

    public function victims()
    {
        return $this->hasMany(Victim::class, 'bolge_id');
    }

    public function needs()
    {
        return $this->hasMany(Need::class, 'bolge_id');
    }

    public function reliefTeams()
    {
        return $this->hasMany(ReliefTeam::class, 'bolge_id');
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class, 'bolge_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'bolge_id');
    }
    /*    public function priorityScore()
{
    return $this->hasOne(RegionPriorityScore::class, 'region_id');
}

public function teamAssignments()
{
    return $this->hasMany(TeamAssignment::class, 'bolge_id');
}*/
}

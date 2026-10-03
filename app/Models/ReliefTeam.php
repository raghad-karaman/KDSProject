<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReliefTeam extends Model
{
    use HasFactory;

    protected $table = 'relief_teams';

    protected $fillable = [
        'ekip_adi',
        'ekip_turu',
        'lider_ad',
        'bolge_id',
        'iletisim',
        'durum',
        'latitude',
        'longitude',
    ];

    // 🔹 Ekip hangi bölgede görevli
    public function region()
    {
        return $this->belongsTo(Region::class, 'bolge_id');
    }

    // 🔹 Dağıtımlar (mevcut yapın)
    public function distributions()
    {
        return $this->hasMany(Distribution::class, 'sorumlu_ekip_id');
    }

    // 🔹 KDS atamaları (ileride kullanacağız)
    /*
    public function assignments()
    {
        return $this->hasMany(TeamAssignment::class, 'team_id');
    }
    */
}

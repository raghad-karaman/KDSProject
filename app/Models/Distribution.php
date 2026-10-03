<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;

    protected $table = 'distribution';
    protected $fillable = ['resource_id', 'bolge_id', 'miktar', 'tarih', 'sorumlu_ekip_id'];

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'bolge_id');
    }

    public function team()
    {
        return $this->belongsTo(ReliefTeam::class, 'sorumlu_ekip_id');
    }
}

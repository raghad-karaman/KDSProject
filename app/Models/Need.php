<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Need extends Model
{
    use HasFactory;

    protected $fillable = ['victim_id', 'bolge_id', 'su_litre', 'gida_paketi', 'cadir', 'ilac_adet', 'oncelik', 'kayit_tarihi'];

    public function victim()
    {
        return $this->belongsTo(Victim::class, 'victim_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'bolge_id');
    }
}
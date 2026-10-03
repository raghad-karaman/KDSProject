<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Victim extends Model
{
    use HasFactory;

    protected $fillable = [
    'ad_soyad','yas','cinsiyet','telefon','adres','bolge_id','kayit_tarihi'
];

    public function region()
    {
        return $this->belongsTo(Region::class, 'bolge_id');
    }

    public function needs()
{
    return $this->hasMany(Need::class, 'victim_id');
}

}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $fillable = ['baslik', 'icerik', 'hedef_kullanici_id', 'oncelik', 'durum', 'olusturma_tarihi'];

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'hedef_kullanici_id');
    }
}
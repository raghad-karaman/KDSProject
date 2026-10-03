<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $fillable = ['rapor_turu', 'bolge_id', 'olusturan_id', 'dosya_yolu', 'olusturma_tarihi'];

    public function region()
    {
        return $this->belongsTo(Region::class, 'bolge_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'olusturan_id');
    }
}
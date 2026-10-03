<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = ['malzeme_adi', 'miktar', 'birim', 'depo_id', 'son_guncelleme'];

    public function distributions()
    {
        return $this->hasMany(Distribution::class, 'resource_id');
    }

}

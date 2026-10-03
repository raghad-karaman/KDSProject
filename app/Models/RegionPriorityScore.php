<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegionPriorityScore extends Model
{
    use HasFactory;

    protected $table = 'region_priority_scores';

    protected $fillable = [
        'region_id',
        'kds_score',
        'priority',
        'required_capabilities',
        'calculated_at',
    ];

    protected $casts = [
        'kds_score' => 'float',
        'required_capabilities' => 'array',
        'calculated_at' => 'datetime',
    ];

    // 🔗 İlişki
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
}

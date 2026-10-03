<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamAssignment extends Model
{
    use HasFactory;

    protected $table = 'team_assignments';

    protected $fillable = [
        'team_id',
        'bolge_id',
        'priority',          // 1=Kritik, 2=Yüksek, 3=Orta, 4=Düşük
        'status',            // SUGGESTED | ASSIGNED | ENROUTE | ARRIVED | COMPLETED | CANCELED
        'assigned_at',
        'completed_at',
        'performance_score',
    ];

    protected $casts = [
        'priority'           => 'integer',
        'assigned_at'        => 'datetime',
        'completed_at'       => 'datetime',
        'performance_score'  => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | 🔗 İlişkiler
    |--------------------------------------------------------------------------
    */

    public function team()
    {
        return $this->belongsTo(ReliefTeam::class, 'team_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'bolge_id');
    }

    /*
    |--------------------------------------------------------------------------
    | 🧠 KDS Yardımcı Fonksiyonları
    |--------------------------------------------------------------------------
    */

    // Öncelik kritik mi?
    public function isCritical(): bool
    {
        return $this->priority === 1;
    }

    // Görev tamamlandı mı?
    public function isCompleted(): bool
    {
        return $this->status === 'COMPLETED';
    }

    /*
    |--------------------------------------------------------------------------
    | ⏱️ Otomatik Zaman Yönetimi
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::saving(function ($assignment) {

            // Görev atandıysa zamanı otomatik set et
            if ($assignment->status === 'ASSIGNED' && is_null($assignment->assigned_at)) {
                $assignment->assigned_at = now();
            }

            // Görev tamamlandıysa bitiş zamanını set et
            if ($assignment->status === 'COMPLETED' && is_null($assignment->completed_at)) {
                $assignment->completed_at = now();
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'applied_at',
        'cancelled_at',
        'answers',
        'checkin_code',
        'certificate_path', // ✅ tambahkan
    ];
    protected $casts = ['answers' => 'array', 'applied_at' => 'datetime', 'cancelled_at' => 'datetime'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cancellable(): bool
    {
        return $this->status !== 'cancelled'
            && $this->event?->starts_at?->isFuture();
    }
}

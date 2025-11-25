<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'organizer_id','category_id','title','slug','excerpt','description',
        'location_type','city','address','starts_at','ends_at',
        'capacity','registration_count','status','published_at','banner_path',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    // Relasi
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function organizer(): BelongsTo { return $this->belongsTo(User::class, 'organizer_id'); }

    // Accessor banner URL
    public function getBannerUrlAttribute(): string
    {
        if ($this->banner_path) {
            if (Str::startsWith($this->banner_path, ['http://','https://'])) {
                return $this->banner_path;
            }
            return Storage::url($this->banner_path);
        }
        return 'https://placehold.co/800x450?text=' . urlencode($this->title);
    }

    // Helper: event terbuka
    public function isOpen(): bool
    {
        $timeOk = $this->ends_at && $this->ends_at->isFuture();
        $capOk  = is_null($this->capacity) || $this->registration_count < $this->capacity;
        return $this->status === 'published' && $timeOk && $capOk;
    }

        // === Helper tampilan ===
    public function getDateHumanAttribute(): string
    {
        return $this->starts_at?->locale('id')->translatedFormat('d F Y') ?? '';
    }

    public function getTimeRangeAttribute(): string
    {
        if (!$this->starts_at || !$this->ends_at) return '';
        return $this->starts_at->format('H:i') . ' - ' . $this->ends_at->format('H:i') . ' WIB';
    }

    public function getLocationHumanAttribute(): string
    {
        if ($this->location_type === 'online') return 'Online';
        $loc = trim(($this->address ? $this->address . ', ' : '') . ($this->city ?? ''));
        return $loc !== '' ? $loc : 'Lokasi diumumkan';
    }

    public function getCapacityLeftAttribute(): ?int
    {
        return is_null($this->capacity) ? null : max($this->capacity - $this->registration_count, 0);
    }

    public function getProgressPercentAttribute(): int
    {
        if (!$this->capacity || $this->capacity <= 0) return 0;
        return (int) round(100 * min($this->registration_count, $this->capacity) / $this->capacity);
    }

    public function getIsClosedAttribute(): bool
    {
        $full = $this->capacity !== null && $this->registration_count >= $this->capacity;
        return $this->status === 'closed'
            || $this->status === 'cancelled'
            || ($this->ends_at && $this->ends_at->isPast())
            || $full;
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(\App\Models\Registration::class);
    }

    public function registrationFor(?int $userId): ?\App\Models\Registration
    {
        if (!$userId) return null;
        return $this->registrations()->where('user_id', $userId)->first();
    }
}

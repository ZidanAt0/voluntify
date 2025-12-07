<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'whatsapp',
        'city',
        'bio',
        'interests',
        'avatar_path',
        'address', // ← tanpa 'phone'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'interests' => 'array',
    ];


    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar_path
            ? \Storage::url($this->avatar_path)
            : 'https://placehold.co/96x96?text=' . (trim($this->name ? mb_substr($this->name, 0, 1) : 'U'));
    }

    public function isSuspended(): bool
    {
        return !is_null($this->suspended_at);
    }


    public function registrations()
    {
        return $this->hasMany(\App\Models\Registration::class);
    }
    public function bookmarks()
    {
        return $this->hasMany(\App\Models\Bookmark::class);
    }
    public function bookmarkedEvents()
    {
        return $this->belongsToMany(\App\Models\Event::class, 'bookmarks')
            ->withPivot('created_at')->withTimestamps();
    }
    public function events()
    {
        return $this->hasMany(\App\Models\Event::class, 'organizer_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ← WAJIB: namespace yang benar

class Category extends Model
{
    use HasFactory; // boleh dipakai; tidak masalah kalau tidak dipanggil

    protected $fillable = ['name','slug'];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataSuaraSah extends Model
{
    use HasFactory;

    protected $keyType = 'string';  // Gunakan string untuk UUID
    public $incrementing = false;  // Nonaktifkan auto-increment karena UUID tidak auto-increment

    protected $fillable = ['nama_pasangan_calon', 'jumlah_suara_sah', 'jumlah_suara_tidak_sah', 'total_suara_sah_dan_tidak_sah'];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->id)) {
                $user->id = (string) Str::uuid();  // Generate UUID saat pembuatan user
            }
        });
    }
}
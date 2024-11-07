<?php

namespace App\Models;

use App\Models\Kelurahan;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kecamatan extends Model
{
    use HasFactory;

    // Mass assignment protection
    protected $fillable = ['kode_kecamatan', 'nama_kecamatan'];

    // Set primary key type to string (for UUID)
    protected $keyType = 'string';

    // Disable auto-incrementing
    public $incrementing = false;

    // Automatically generate UUID when creating a new Kecamatan
    protected static function booted()
    {
        static::creating(function ($kecamatan) {
            if (empty($kecamatan->id)) {
                $kecamatan->id = (string) Str::uuid(); // Generate UUID
            }
        });
    }

    // Relation with Kelurahan
    public function kelurahans()
    {
        return $this->hasMany(Kelurahan::class);
    }


}

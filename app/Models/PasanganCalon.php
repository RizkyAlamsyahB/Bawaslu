<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\DataSuaraSah;
use App\Models\TipePemilihan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PasanganCalon extends Model
{
    use HasFactory;

    use HasUuids;

    protected $table = 'pasangan_calons';

    protected $fillable = [
        'nama_pasangan',
        'nomor_urut',
        'tipe_pemilihan_id'
    ];

    public function tipePemilihan()
    {
        return $this->belongsTo(TipePemilihan::class);
    }

    public function suaraSah()
    {
        return $this->hasOne(DataSuaraSah::class);
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->id)) {
                $user->id = (string) Str::uuid();  // Generate UUID saat pembuatan user
            }
        });
    }
}

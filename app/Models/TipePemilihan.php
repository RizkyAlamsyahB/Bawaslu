<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\JumlahPemilihDpt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipePemilihan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tipe_pemilihans';

    protected $fillable = [
        'nama',
    ];

    public $incrementing = false;

    protected $keyType = 'string';
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->id)) {
                $user->id = (string) Str::uuid();  // Generate UUID saat pembuatan user
            }
        });
    }

    public function jumlahPemilihDpt()
    {
        return $this->hasMany(JumlahPemilihDpt::class, 'tipe_pemilihan_id');
    }
}

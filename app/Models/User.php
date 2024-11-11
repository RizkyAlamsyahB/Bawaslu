<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Tps;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Support\Str;
use App\Models\DataSuaraSah;
use App\Models\JumlahPemilihDpk;
use App\Models\JumlahPemilihDpt;
use App\Models\JumlahDataPemilih;
use App\Models\JumlahPemilihDptb;
use Laravel\Sanctum\HasApiTokens;
use App\Models\PenggunaHakPilihDpk;
use App\Models\PenggunaHakPilihDpt;
use App\Models\PenggunaanSuratSuara;
use App\Models\PenggunaHakPilihDptb;
use App\Models\JumlahPenggunaHakPilih;
use App\Models\JumlahPemilihDisabilitas;
use Illuminate\Notifications\Notifiable;
use App\Models\PenggunaHakPilihDisabilitas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $keyType = 'string'; // Gunakan string untuk UUID
    public $incrementing = false; // Nonaktifkan auto-increment karena UUID tidak auto-increment
    protected $fillable = ['name', 'phone', 'password', 'username', 'role', 'kecamatan_id', 'kelurahan_id', 'tps_id'];
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->id)) {
                $user->id = (string) Str::uuid(); // Generate UUID saat pembuatan user
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id');
    }

    public function tps()
    {
        return $this->belongsTo(Tps::class, 'tps_id');
    }

}

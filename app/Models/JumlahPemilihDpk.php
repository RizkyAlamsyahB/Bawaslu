<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JumlahPemilihDpk extends Model
{
    use HasFactory;

    protected $keyType = 'string';  // Gunakan string untuk UUID
    public $incrementing = false;  // Nonaktifkan auto-increment karena UUID tidak auto-increment

    protected $fillable = ['tipe_pemilihan', 'laki_laki', 'perempuan', 'jumlah'];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->id)) {
                $user->id = (string) Str::uuid();  // Generate UUID saat pembuatan user
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

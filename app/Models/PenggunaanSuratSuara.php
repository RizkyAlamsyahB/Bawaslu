<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\TipePemilihan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenggunaanSuratSuara extends Model
{
    use HasFactory;

    protected $table = 'penggunaan_surat_suara';

    protected $keyType = 'string';  // Gunakan string untuk UUID
    public $incrementing = false;  // Nonaktifkan auto-increment karena UUID tidak auto-increment

    protected $fillable = ['user_id','tipe_pemilihan_id', 'surat_suara_diterima', 'surat_suara_dikembalikan', 'surat_suara_tidak_digunakan', 'surat_suara_digunakan'];

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
    public function tipePemilihan()
    {
        return $this->belongsTo(TipePemilihan::class, 'tipe_pemilihan_id');
    }
}

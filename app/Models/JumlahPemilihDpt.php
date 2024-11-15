<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\TipePemilihan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JumlahPemilihDpt extends Model
{
    use HasFactory;

    protected $table = 'jumlah_pemilih_dpt';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'tipe_pemilihan_id',
        'laki_laki',
        'perempuan',
        'jumlah'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
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

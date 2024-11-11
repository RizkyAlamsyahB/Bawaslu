<?php

namespace App\Models;

use App\Models\Tps;
use App\Models\User;
use App\Models\Kecamatan;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelurahan extends Model
{
    use HasFactory;

    // Mass assignment protection
    protected $fillable = ['kode_kelurahan', 'nama_kelurahan', 'kecamatan_id'];

    // Set primary key type to string (for UUID)
    protected $keyType = 'string';

    // Disable auto-incrementing
    public $incrementing = false;

    // Automatically generate UUID when creating a new Kelurahan
    protected static function booted()
    {
        static::creating(function ($kelurahan) {
            if (empty($kelurahan->id)) {
                $kelurahan->id = (string) Str::uuid(); // Generate UUID
            }
        });
    }

    // Relation with Kecamatan
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    // Relation with TPS
    public function tps()
    {
        return $this->hasMany(Tps::class, 'kelurahan_id');
    }
    public function users() { return $this->hasMany(User::class); }

    public function getKodeUnikAttribute()
{
    return $this->kecamatan->kode_kecamatan . '.' . $this->kode_kelurahan;
}


}

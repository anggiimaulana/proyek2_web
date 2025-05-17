<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    protected $table = 'pendidikan';

    protected $fillable = ['jenis_pendidikan'];

    protected static function booted()
    {
        static::created(function ($pendidikan) {
            self::clearCache($pendidikan);
        });

        static::updated(function ($pendidikan) {
            self::clearCache($pendidikan);
        });

        static::deleted(function ($pendidikan) {
            self::clearCache($pendidikan);
        });
    }

    public function client()
    {
        return $this->hasMany(Nik::class, 'pendidikan');
    }
}

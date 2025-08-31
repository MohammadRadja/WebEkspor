<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Konten extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'konten';

    protected $fillable = ['judul', 'slug', 'jenis', 'kutipan', 'konten', 'gambar', 'meta', 'media', 'tautan', 'status', 'diterbitkan_pada', 'penulis'];

    protected $casts = [
        'meta' => 'array',
        'media' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}

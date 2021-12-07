<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Donasi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_donasi';
    protected $table = 'donasi';

    protected $fillable = [
        'judul',
        'penulis',
        'jumlah_halaman',
        'tahun_terbit',
        'id_user'
    ];

    public function getCreatedAttribute()
    {
        if(!is_null($this->attributes['created_at']))
        {
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAttribute()
    {
        if(!is_null($this->attributes['updated_at']))
        {
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }
}

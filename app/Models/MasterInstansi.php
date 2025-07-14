<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterInstansi extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'last_id',
        'instansi',
        'kepala',
        'alamat',
        'kota',
        'telp',
    ];
}

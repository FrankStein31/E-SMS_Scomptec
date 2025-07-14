<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterKlasifikasi extends Model
{
    Use HasUlids, SoftDeletes;
    protected $fillable = [
        'kodeklasifikasi',
        'klasifikasi',
        'retensi_aktif',
        'retensi_inaktif',
        'keterangan',
        'retensi',
        'parent',
    ];
}

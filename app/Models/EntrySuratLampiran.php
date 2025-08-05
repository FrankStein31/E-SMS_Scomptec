<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class EntrySuratLampiran extends Model
{
    use HasUlids;

    protected $fillable = [
        'entrysurat_id',
        'nama_lampiran',
        'nama_file',
        'size',
        'tgl_upload',
    ];
}

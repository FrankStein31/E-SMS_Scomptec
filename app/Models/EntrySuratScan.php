<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class EntrySuratScan extends Model
{
    use HasUlids;

    // protected $table = 'entri_surat_scans';
    protected $fillable = [
        'entrysurat_id',
        'nourut',
        'nama_scan',
        'nama_file',
        'size',
        'tgl_upload',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratKeluarLampiran extends Model
{
    use HasUlids, SoftDeletes;
    protected $fillable = [
        'lampiran_id',
        'surat_keluar_id',
        'surat_keluar_isi_id',
        'revisi_id',
        'revisi_data_id',
        'nama_lapiran',
        'nama_file',
        'size',
        'tanggal_upload'
    ];
}

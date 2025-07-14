<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratKeluarNotaDinas extends Model
{
    use HasUlids, SoftDeletes;
    protected $fillable = [
        'suratkeluar_id',
        'surat_keluar_isi_id',
        'revisi_id',
        'revisi_data_id',
        'nourut_riw',
        'nourut_kirim',
        'userid_pembuat',
        'user_id_pembuat',
        'satkerid_pembuat',
        'userid_tujuan',
        'user_id_tujuan',
        'satkerid_tujuan',
        'userid_final',
        'user_id_final',
        'satkerid_final',
        'dibaca',
        'last_sent',
        'isfinal',
        'status',
        'tgl_update',
        'tgl_final',
        'status_lama',
    ];
}

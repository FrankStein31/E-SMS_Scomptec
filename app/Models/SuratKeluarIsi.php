<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MasterJenisSurat;
use App\Models\User;

class SuratKeluarIsi extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'suratkeluar_id',
        'revisi_id',
        'revisi_data_id',
        'tgl_revisi',
        'jenis_id',
        'no_generate',
        'nosurat',
        'kodeklasifikasi',
        'tgl_surat',
        'hal',
        'jml_lampiran',
        'sifat',
        'kepada',
        'isi',
        'tembusan',
        'jenisref_id',
        'referensi_id',
        'entry_surat_isi_id',
        'ttd_nama',
        'ttd_id',
        'user_ttd_id',
        'isfinal',
        'userid_pembuat',
        'user_id_pembuat',
        'satkerid_pembuat',
        'userid_tujuan',
        'user_id_tujuan',
        'satkerid_tujuan',
        'status',
        'dibaca',
        'last_sent',
        'userid_final',
        'user_id_final',
        'satkerid_final',
    ];

    public function jenis()
    {
        return $this->belongsTo(MasterJenisSurat::class,  'jenis_id', 'last_id');
    }
    // relasi kepada: jika field berupa json/array, return null
    public function kepadaUser()
    {
        if (is_numeric($this->kepada)) {
            return $this->belongsTo(User::class, 'kepada');
        }
        return null;
    }

    public function klasifikasi()
    {
        return $this->belongsTo(\App\Models\MasterKlasifikasi::class, 'kodeklasifikasi', 'kodeklasifikasi');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DraftSurat extends Model
{
    protected $fillable = [
        'disposisi',
        'entrisurat_id',
        'parent_id',
        'kepada',
        'tgl_disposisi',
        'tgl_remiten',
        'isi',
        'tindakan',
        'userid_pembuat',
        'userid_tujuan',
        'file_original',
        'file_rename',
        'file_size',

    ];
}

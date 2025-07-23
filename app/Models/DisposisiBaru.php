<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisposisiBaru extends Model
{
    protected $table = 'disposisis_baru';

    protected $fillable = [
        'entrysurat_id',
        'kepada',
        'catatan',
        'status',
    ];

    public function tindakans()
    {
        return $this->belongsToMany(
            MasterTindakanDisposisi::class,
            'disposisis_baru_tindakans',
            'disposisis_baru_id', // kolom foreign key di pivot
            'tindakan_id'          // kolom foreign key tujuan
        );
    }

    public function entrysurat()
    {
        return $this->belongsTo(EntrySuratIsi::class, 'entrysurat_id');
    }
}

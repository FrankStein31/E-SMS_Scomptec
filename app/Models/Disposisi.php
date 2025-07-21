<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disposisi extends Model
{
    protected $table = 'disposisi_isis'; 

    protected $keyType = 'string'; 
    public $incrementing = false;

    protected $fillable = [
        'entrysurat_id',
        'parent_id',
        'kodeklasifikasi',
        'kepada',
        'hal',
        'tgl_disposisi',
        'tgl_remitten',
        'status',
        'isi',
        'tindakan',
        'userid_pembuat',
        'satkerid_pembuat',
        'terdisposisi',
        'mig_nourut',
        'mig_satkerasalid',
        'mig_satkertujuanid',
        'mig_terbaca',
        'mig_nourutasal',
    ];

    /**
     * Relasi ke EntrySuratIsi
     */
    // app/Models/Disposisi.php

    public function entrysurat()
    {
        return $this->belongsTo(EntrySuratIsi::class, 'entrysurat_id');
    }



    /**
     * Relasi ke User sebagai pembuat
     */
    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userid_pembuat');
    }

    public function parent()
    {
        return $this->belongsTo(Disposisi::class, 'parent_id');
    }


    protected $casts = [
        'tgl_disposisi' => 'date',
        'tgl_remitten' => 'date'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\MasterJenisSurat;


// protected $table = 'entry_surat_isis'; // atau nama tabelnya
class EntrySuratIsi extends Model
{
    use HasUlids;
    protected $fillable = [
        'jenis_id',
        'nomor_surat',
        'kode_klasifikasi',
        'hal',
        'kepada',
        'dari',
        'alamat',
        'tgl_surat',
        'tgl_diterima',
        'tgl_diarahkan',
        'sifat',
        'isi',
        'tembusan',
        'isfinal',
        'created_by',
        'satkerid_pembuat',
        'jumlah_lampiran',
        'referensi_id',
        'noagenda',
        'tgl_update',
        'updated_by',
        'satkerid_update',
        'terdisposisi',

    ];

    /**
     * Get the createdBy that owns the EntrySuratIsi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the jenis that owns the EntrySuratIsi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenis(): BelongsTo
    {
        return $this->belongsTo(MasterJenisSurat::class, 'jenis_id', 'last_id');
    }

    /**
     * Get all of the tujuanSurat for the EntrySuratIsi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tujuanSurat(): HasMany
    {
        return $this->hasMany(EntrySuratTujuan::class, 'entrysurat_id', 'id');
    }

    public function tujuan(): HasMany
    {
        return $this->hasMany(EntrySuratTujuan::class, 'entrysurat_id', 'id');
    }


    // App\Models\EntrySuratIsi.php
    public function klasifikasi(): BelongsTo
    {
        return $this->belongsTo(MasterKlasifikasi::class, 'kode_klasifikasi', 'kodeklasifikasi');
    }

    /**
     * Get all of the comments for the EntrySuratIsi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function FileScan(): HasMany
    {
        return $this->hasMany(EntrySuratScan::class, 'entrysurat_id', 'id')->orderBy('nourut');
    }

    /**
     * Get all disposisi for this entry surat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function disposisis(): HasMany
    {
        return $this->hasMany(DisposisiBaru::class, 'entrysurat_id', 'id');
    }

    /**
     * Get the latest disposisi with highest priority action for this user
     */
    public function getLatestDisposisiForUser($userId)
    {
        return $this->disposisis()
            ->with('tindakans')
            ->whereRaw("FIND_IN_SET(?, kepada)", [$userId])
            ->latest()
            ->first();
    }

    /**
     * Get priority indicator based on disposisi actions
     */
    public function getPriorityIndicatorForUser($userId)
    {
        $disposisi = $this->getLatestDisposisiForUser($userId);

        if (!$disposisi || $disposisi->tindakans->isEmpty()) {
            return null;
        }

        $highPriorityActions = ['Segera Dibalas', 'Tindak Lanjuti'];
        $mediumPriorityActions = ['Supaya Diwakili', 'Perhatikan', 'Dikoordinasikan'];

        foreach ($disposisi->tindakans as $tindakan) {
            if (in_array($tindakan->tindakan, $highPriorityActions)) {
                return [
                    'level' => 'high',
                    'icon' => 'fa-exclamation-triangle',
                    'color' => 'danger',
                    'text' => $tindakan->tindakan
                ];
            }
        }

        foreach ($disposisi->tindakans as $tindakan) {
            if (in_array($tindakan->tindakan, $mediumPriorityActions)) {
                return [
                    'level' => 'medium',
                    'icon' => 'fa-exclamation-circle',
                    'color' => 'warning',
                    'text' => $tindakan->tindakan
                ];
            }
        }

        // Low priority or info actions
        $firstAction = $disposisi->tindakans->first();
        return [
            'level' => 'low',
            'icon' => 'fa-info-circle',
            'color' => 'info',
            'text' => $firstAction->tindakan
        ];
    }
}

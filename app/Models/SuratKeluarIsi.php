<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MasterJenisSurat;
use App\Models\User; // Assuming you have a User model

class SuratKeluarIsi extends Model
{
    use HasUlids, SoftDeletes;

    // Define the table name explicitly if it doesn't follow Laravel's naming convention (plural snake_case)
    protected $table = 'surat_keluar_isis';

    // Since you're using HasUlids, the primary key is a string and not auto-incrementing.
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     * Ensure all fields that can be set via mass assignment (e.g., in Controller's create/update) are listed here.
     * Added 'file_lampiran' which was used in the view.
     * Removed 'userid_pembuat', 'userid_tujuan', 'userid_final' if 'user_id_pembuat', 'user_id_tujuan', 'user_id_final' are the actual foreign keys to the User model.
     */
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
        'kepada', // Stored as JSON array, so keep it fillable for direct saving
        'isi',
        'tembusan', // Stored as JSON array
        'jenisref_id',
        'referensi_id',
        'entry_surat_isi_id',
        'ttd_nama',
        'ttd_id',
        'user_ttd_id',
        'isfinal',
        'user_id_pembuat', // Assuming this is the actual foreign key
        'satkerid_pembuat',
        'user_id_tujuan', // Assuming this is the actual foreign key
        'satkerid_tujuan',
        'status',
        'dibaca',
        'last_sent',
        'user_id_final', // Assuming this is the actual foreign key
        'satkerid_final',
        'file_lampiran', // Added based on previous view code
        // 'up', 'nama', 'jabatan', 'satker', 'referensi', 'penandatangan'
        // If these are actual columns in the 'surat_keluar_isi' table and not dynamic, add them here.
        // Otherwise, they might be derived or from other relationships not explicitly shown here.
    ];

    /**
     * The attributes that should be cast to native types.
     * This is crucial for automatic conversion of JSON strings to PHP arrays/objects,
     * and date strings to Carbon instances.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_revisi' => 'date',
        'tgl_surat' => 'date',      // Automatically convert to Carbon instance
        'kepada' => 'array',        // Automatically decode JSON string to PHP array
        'tembusan' => 'array',      // Automatically decode JSON string to PHP array
        'last_sent' => 'datetime',  // Automatically convert to Carbon instance
        'isfinal' => 'boolean',     // Cast boolean-like database values to true/false
    ];

    /**
     * Define the relationship to MasterJenisSurat.
     * Assumes 'jenis_id' in surat_keluar_isi refers to 'id' in master_jenis_surat.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenis()
    {
        return $this->belongsTo(MasterJenisSurat::class, 'jenis_id', 'id');
    }

    /**
     * Relationship to the User who created this surat.
     * Assumes 'user_id_pembuat' is the foreign key to the 'id' column of the User model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'user_id_pembuat', 'id');
    }

    /**
     * Relationship to the User this surat is intended for (main recipient).
     * This relationship conflicts with 'kepada' being a JSON array.
     * If 'kepada' contains a JSON array of multiple recipients, this relationship
     * should likely be removed or renamed if it's meant for a single primary recipient ID.
     * If 'kepada_id' is a separate column for a single main recipient, then keep it.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // public function kepadaUser() // Renamed to avoid conflict with the 'kepada' attribute/column
    // {
    //     // This assumes you have a 'kepada_id' foreign key in your table
    //     // If 'kepada' column itself is the foreign key (unlikely with JSON), clarify.
    //     return $this->belongsTo(User::class, 'kepada_id', 'id');
    // }

    /**
     * Relationship to the User who finalized this surat.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function finalizer()
    {
        return $this->belongsTo(User::class, 'user_id_final', 'id');
    }

    /**
     * Accessor to get combined names from the 'kepada' JSON array.
     * This is useful for displaying a comma-separated list of recipients.
     * Example 'kepada' structure: [{"id":3, "name":"Admin"},{"id":1, "name":"Biro Pemerintahan dan Otonomi Daerah"}]
     *
     * @return string
     */
    public function getKepadaNamaAttribute(): string
    {
        // Check if 'kepada' is an array and not empty
        if (is_array($this->kepada) && !empty($this->kepada)) {
            // Pluck the 'name' from each item and implode them into a string
            return collect($this->kepada)->pluck('name')->implode(', ');
        }
        return '-'; // Default if no data
    }

    /**
     * Accessor to get formatted 'kepada' details (name and jabatan) for detailed views.
     * Each recipient will be on a new line.
     *
     * @return string
     */
    public function getKepadaDetailAttribute(): string
    {
        if (is_array($this->kepada) && !empty($this->kepada)) {
            $details = collect($this->kepada)->map(function ($item) {
                $name = $item['name'] ?? 'Nama Tidak Ada';
                $jabatan = $item['jabatan'] ?? 'Jabatan Tidak Ada';
                return "{$name} ({$jabatan})";
            })->implode('<br>'); // Use <br> for HTML line breaks
            return $details;
        }
        return '-';
    }

    /**
     * Accessor to get combined names from the 'tembusan' JSON array.
     * Assumes 'tembusan' has a similar structure to 'kepada'.
     *
     * @return string
     */
    public function getTembusanNamaAttribute(): string
    {
        if (is_array($this->tembusan) && !empty($this->tembusan)) {
            return collect($this->tembusan)->pluck('name')->implode(', ');
        }
        return '-';
    }

    // You might consider adding more accessors if 'up', 'nama', 'jabatan', 'satker', 'referensi', 'penandatangan'
    // are not direct columns but derived from other fields or relationships.
    // For example, if 'nama' refers to the name of 'pembuat':
    // public function getNamaAttribute() {
    //     return $this->pembuat->name ?? '-';
    // }
}

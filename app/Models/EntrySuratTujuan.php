<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class EntrySuratTujuan extends Model
{
    use HasUlids;
    
    protected $fillable = [
        'satkerid_tujuan',
        'dibaca',
        'is_tembusan',
        'entrysurat_id',
        'userid_tujuan',
    ];
}

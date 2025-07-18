<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterSatker extends Model
{
    use HasUlids, SoftDeletes;
    protected $fillable = [
        'satkerid',
        'kodesatker',
        'satker',
        'eselon',
        'userid',
    ];
}

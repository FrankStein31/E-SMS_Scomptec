<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class MasterTindakanDisposisi extends Model
{
    use HasUlids;
    protected $fillable = [
        'tindakan',
        'satkerid',
    ];
}

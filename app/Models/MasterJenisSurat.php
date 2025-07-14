<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterJenisSurat extends Model
{
    Use HasUlids, SoftDeletes;
    protected $fillable = [
        'name',
        'last_id'
    ];
}

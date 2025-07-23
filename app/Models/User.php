<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'username',
        'fullname',
        'jabatan',
        'satkerid',
        'user_id_pembuat',
        'nip',
        'usergroupid',
        'email',
        'email_verified_at',
        'last_notif',
        'pangkat',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function masterSatker()
    {
        return $this->hasOne(MasterSatker::class, 'userid', 'id');
    }

    public function satker()
    {
        return $this->belongsTo(\App\Models\MasterSatker::class, 'satkerid', 'satkerid');
    }

    public function disposisiDibuat(): HasMany
    {
        return $this->hasMany(Disposisi::class, 'userid_pembuat');
    }
}

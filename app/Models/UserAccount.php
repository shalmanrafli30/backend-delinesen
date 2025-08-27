<?php

// app/Models/UserAccount.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class UserAccount extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'user_account';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // ✅ pakai nama kolom timestamp milik tabelmu
    const CREATED_AT = 'user_created';
    const UPDATED_AT = 'user_updated';

    protected $fillable = [
        'user_id',
        'user_name',
        'user_full_name',
        'user_password',
        'role_id',
    ];

    protected $hidden = ['user_password'];

    public function getAuthPassword()
    {
        return $this->user_password;
    }
}


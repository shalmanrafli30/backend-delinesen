<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StsWish extends Model
{
    protected $table = 'sts_wishes';
    protected $primaryKey = 'wishes_id';
    public $incrementing = false;       // PK varchar
    protected $keyType = 'string';

    const CREATED_AT = 'wishes_created';
    const UPDATED_AT = 'wishes_updated';

    protected $fillable = [
        'wishes_id', 'wishes_sender', 'wishes_account', 'wishes', 'sts_id'
    ];

    protected $casts = [
        'wishes_created' => 'datetime',
        'wishes_updated' => 'datetime',
    ];

    public function sts()
    {
        return $this->belongsTo(StsInformation::class, 'sts_id', 'sts_id');
    }
}


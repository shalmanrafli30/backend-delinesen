<?php

// app/Models/StsInformation.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StsInformation extends Model
{
    protected $table = 'sts_info';
    protected $primaryKey = 'sts_id';
    public $incrementing = false;     // pk string
    protected $keyType = 'string';

    // pakai kolom custom untuk timestamp
    const CREATED_AT = 'sts_created';
    const UPDATED_AT = 'sts_updated';

    protected $fillable = ['sts_id', 'sts_name'];
}

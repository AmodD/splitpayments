<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submerchant extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'DBA_Name',
        'GSTN',
        'Bank_Name',
        'IFSC',
        'Account_Type',
        'Account_Number',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}

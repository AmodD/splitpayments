<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submerchant extends Model
{
    use HasFactory;
    
    protected $fillable = [
      'tenant_id',
      'paymentgateway_id',
      'status',
        'dba_name',
        'gstn',
        'bank_name',
        'ifsc',
        'account_type',
        'account_number',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function paymentgateway(): BelongsTo
    {
        return $this->belongsTo(Paymentgateway::class);
    }
}

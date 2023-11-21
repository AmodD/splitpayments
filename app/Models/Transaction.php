<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
  use HasFactory;

    public function transactiondevicedetail(): HasOne
    {
        return $this->hasOne(Transactiondevicedetail::class);
    }

    public function transactionvalidity(): HasOne
    {
        return $this->hasOne(Transactionvalidity::class);
     }

    public function transactionstatuses(): HasMany
    {
        return $this->hasMany(Transactionstatus::class);
    }
}

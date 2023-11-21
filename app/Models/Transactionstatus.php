<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactionstatus extends Model
{
  use HasFactory;

      public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

}

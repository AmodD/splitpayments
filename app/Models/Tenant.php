<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    public function submerchants(): HasMany
    {
        return $this->hasMany(Submerchant::class);
    }
}

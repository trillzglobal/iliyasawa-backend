<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainStore extends Model
{
    use HasFactory;

    protected $guarded = [];


    // Define the one-to-many relationship with TransactionDetail
    public function transactionDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TransactionDetails::class);
    }
}

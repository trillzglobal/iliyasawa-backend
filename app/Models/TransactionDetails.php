<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetails extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Define the many-to-one relationship with Transaction
//    public function transaction()
//    {
//        return $this->belongsTo(Transaction::class);
//    }

    // Define the many-to-one relationship with Product
    public function mainStore()
    {
        return $this->belongsTo(MainStore::class, 'product_id');
    }
}

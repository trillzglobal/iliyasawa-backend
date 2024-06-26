<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'transaction_detail_ids'=>'array',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetails::class);
    }


    public function getDetailsAttribute()
    {
        return TransactionDetails::whereIn('id', $this->transaction_detail_ids)->with('mainStore')->get();
    }
}

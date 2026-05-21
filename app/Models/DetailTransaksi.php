<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $guarded = [];

    public function transaksi() {
        return $this->belongsTo(Transaksi::class);
    }

    public function products() {
        return $this->belongsTo(Product::class);
    }
}

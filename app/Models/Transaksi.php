<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $guarded = [];

    public function customers() {
        return $this->belongsTo(Customer::class);
    }

    public function pembayaran() {
        return $this->hasOne(Pembayaran::class);
    }

    public function detail() {
        return $this->hasMany(DetailTransaksi::class);
    }   

}

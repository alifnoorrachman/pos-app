<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'stok',
        'harga'
    ];

    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}

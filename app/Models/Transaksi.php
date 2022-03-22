<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
  protected $table = 'transaksi';

  protected $primaryKey = 'id_transaksi';

  protected $fillable = [
      'id_transaksi',
      'id_member',
      'tanggal',
      'tanggal_bayar',
      'batas_waktu',
      'status',
      'dibayar',
      'id_user',
  ];

  protected $hidden = [
      'created_at',
      'updated_at'
  ];
}

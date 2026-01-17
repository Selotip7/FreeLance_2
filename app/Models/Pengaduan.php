<?php

namespace App\Models;

use illuminate\Database\Eloquent\Factories\HasFactory;
use illuminate\Database\Eloquent\Model;

class Pengaduan extends Model{

  use HasFactory;
  
  protected $table ='pengaduan';

  protected $primaryKey= 'id_pengaduan';

  protected $fillable = [
    'id_user',
    'judul_laporan',
    'deskripsi',
    'kategori',
    'gambar',
    'status',
    'tgl_pengaduan',
    'catatan',
  ];

  public function user(){
    return $this->belongsTo(User::class,'id_user','id');
  }

}


?>
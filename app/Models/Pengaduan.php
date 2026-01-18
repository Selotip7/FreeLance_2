<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Pengaduan extends Model{

  use HasFactory;
  
  protected $table ='pengaduans';

  protected $primaryKey= 'id'; //harus sesuai dengan di db

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
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $table = 'pengajuans';
    protected $fillable = [
        'number','tgl_awal','tgl_akhir','tgl_input','tgl_acc','user_input',
        'user_acc','status','jumlah_cuti','keterangan','keterangan_acc',

    ];
}

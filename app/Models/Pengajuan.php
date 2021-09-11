<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\NewPengajuanNotification;
use App\Notifications\NewPersetujuanNotification;

use Notification;
class Pengajuan extends Model
{
    use Notifiable;

    protected $table = 'pengajuans';
    protected $fillable = [
        'number','tgl_awal','tgl_akhir','tgl_input','tgl_acc','user_input',
        'user_acc','status','jumlah_cuti','keterangan','keterangan_acc',

    ];
    public $timestamps = false;
    protected $keyType = 'string';
    protected $primaryKey = 'number';
    const CREATED_AT = 'tgl_input';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_acc');
    }



}

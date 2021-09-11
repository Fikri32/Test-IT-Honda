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
    public $timestamps = false;
    protected $keyType = 'string';
    protected $primaryKey = 'number';
    const CREATED_AT = 'tgl_input';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_acc');
    }

    // public static function generateNumber()
    // {
    //     parent::boot();

    //         // dd($no);
    //     static::creating(function($model){
    //             $no = 'PC201900000';
    //             $prefix = substr($no,0,6);
    //             $postfix = substr($no,-5);
    //             $last = Pengajuan::latest('tgl_input')->first();
    //             if($last->exists())
    //             {
    //                 $number = $prefix.str_pad(intval($postfix) + 1,5,0,STR_PAD_LEFT);
    //                 $model->number = $number;
    //             }


    //     });

    // }

}

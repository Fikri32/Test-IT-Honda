<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    protected $table = 'cutis';
    protected $fillable = [
        'id_user','sisa_cuti','tahun'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}

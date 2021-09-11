<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cuti;
use App\Models\Pengajuan;
use Carbon\Carbon;
use Auth;
use Datatables;
use Notification;
use App\Notifications\NewPersetujuanNotification;

class PersetujuanCutiController extends Controller
{
    public function index(Request $request)
    {

        return view('persetujuan_cuti.index');
    }

    public function data()
    {
        $data = Pengajuan::where('status','=',null);
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('user_input',function($data){
                    if($data->user != null)
                    {
                        return $data->user->name;
                    }
                })
                ->addColumn('action',function($row){
                    $aksi = '
                    <a href = "javascript:void(0)" data-id="'.$row->number.'" id="detail" class = " detail btn btn-sm btn-outline-primary mb-10">Detail</a>
                    ';
                    return $aksi;
                })
                ->rawColumns(['action'])
                ->make(true);
    }
    public function detail($id)
    {
        $data = Pengajuan::find($id);
        return $data;
    }
    public function accept(Request $request,$id)
    {
        $persetujuan = Pengajuan::find($id);
        $persetujuan->status = $request->get('status',0);
        $persetujuan->keterangan_acc = $request->keterangan_acc;
        $persetujuan->user_acc = Auth::user()->id;
        $persetujuan->tgl_acc = Carbon::now();
        $persetujuan->update();

        if($persetujuan and $persetujuan->status == 1)
        {
            $cuti = Cuti::where('id_user',$persetujuan->user_input)->first();
            // dd($cuti);
            $cuti->sisa_cuti = $cuti->sisa_cuti - $persetujuan->jumlah_cuti;

            $cuti->save();
        }else{
            $cuti = Cuti::where('id_user',$persetujuan->user_input)->first();
            // dd($cuti);
            $cuti->sisa_cuti = $cuti->sisa_cuti;

            $cuti->save();
        }

        $user = new User;
        $user->name = User::select('name')
                    ->where('id','=',Auth::user()->id)
                    ->pluck('name')
                    ->first();


        $karyawan = User::whereHas('roles', function($q){
                    $q->where('name', 'karyawan');
        })->get();
        Notification::send($karyawan,new NewPersetujuanNotification($persetujuan,$user));

        return response()->json([

            'data' => $persetujuan->user_input
        ],200);
    }
}

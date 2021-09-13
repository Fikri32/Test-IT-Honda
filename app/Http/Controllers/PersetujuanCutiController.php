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
     public function list(Request $request)
    {

        return view('persetujuan_cuti.list');
    }

    public function data()
    {
        $data = Pengajuan::where('status','=',null);
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('user_input',function($data){
                    return $data->pengaju->name;
                })
                ->addColumn('status',function($data){
                    if($data->status == null)
                    {
                        $status = '<span class="badge badge-primary"><i class="fa fa-spinner mr-5"></i>WAITING !</span>';
                        return $status;
                    }


                })
                ->addColumn('action',function($row){
                    $aksi = '
                    <a href = "javascript:void(0)" data-id="'.$row->number.'" id="detail" class = " detail btn btn-sm btn-outline-primary mb-10">Detail</a>
                    ';
                    return $aksi;
                })
                ->rawColumns(['action','status'])
                ->make(true);
    }

    public function listdata()
    {
        $data = Pengajuan::all();
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('user_input',function($data){
                    return $data->pengaju->name;
                })
                ->addColumn('status',function($pengajuan){
                    if($pengajuan->status == '1')
                    {
                        $status = '<span class="badge badge-success"><i class="fa fa-check mr-5"></i>ACCEPTED !</span>';
                        return $status;
                    }else if($pengajuan->status == '0'){
                        $status = '<span class="badge badge-danger"><i class="fa fa-times-circle mr-5"></i>Rejected</span>';
                        return $status;
                    }else{
                        $status = '<span class="badge badge-primary"><i class="fa fa-spinner mr-5"></i>WAITING !</span>';
                        return $status;
                    }

                })
                ->rawColumns(['status'])
                ->make(true);
    }

    public function detail($id)
    {
        $data = Pengajuan::find($id);
        // $karyawan = User::where('id','=',$data->user_input)->get();
        // dd($karyawan);
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

        $user = new User;
        $user->name = User::select('name')
                    ->where('id','=',Auth::user()->id)
                    ->pluck('name')
                    ->first();


        $karyawan = User::where('id',$persetujuan->user_input)->get();
        Notification::send($karyawan,new NewPersetujuanNotification($persetujuan,$user));

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



        return response()->json([

            'data' => $persetujuan->user_input
        ],200);
    }
}

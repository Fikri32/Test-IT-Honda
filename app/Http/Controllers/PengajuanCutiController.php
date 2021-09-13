<?php

namespace App\Http\Controllers;

use App\Notifications\NewPengajuanNotification;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\User;
use App\Models\Cuti;
use Auth;
use Datatables;
use Datetime;
use Notification;
use DB;

class PengajuanCutiController extends Controller
{
    public function index()
    {

        // $date1 = new Datetime("01-01-2021 00:00:00");
        // $date2 = new Datetime("02-01-2021 24:00:00");
        // $inv = $date1->diff($date2);
        // $days = $inv->format('%a');
        // dd($inv);

        return view('pengajuan_cuti.index');
    }
    public function data()
    {
        $pengajuan = Pengajuan::where('user_input','=',Auth::user()->id)->get();
        return Datatables::of($pengajuan)
                ->addIndexColumn()
                ->addColumn('user_acc',function($pengajuan){
                    if($pengajuan->user != null)
                    {
                        return $pengajuan->user->name;
                    }

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
                ->addColumn('action',function($row){
                    if($row->status == null)
                    {
                        $aksi = '
                        <a href = "javascript:void(0)" data-id="'.$row->number.'" id="edit" class = " edit btn btn-sm btn-flat btn-outline-warning">Edit</a>
                        <a href = "javascript:void(0)" data-id="'.$row->number.'" id="delete" class = " delete btn btn-sm btn-flat btn-outline-danger">Delete</a>
                        ';
                        return $aksi;
                    }else{
                        $aksi = '

                        <a href = "javascript:void(0)" data-id="'.$row->number.'" id="delete" class = " delete btn btn-sm btn-flat btn-outline-danger">Delete</a>
                        ';
                        return $aksi;
                    }

                })
                ->rawColumns(['action','status'])
                ->make(true);
    }

    public function getCuti()
    {
        $cuti = Cuti::where('id_user',Auth::user()->id)->first();
        $data = $cuti->sisa_cuti;

        return response()->json($data);
    }

    public function getStatus()
    {
        $status = Pengajuan::where('user_input',Auth::user()->id)->latest('tgl_input')->first();
        return response()->json($status);

    }

    public static function generateNumber()
    {

        // $number = Pengajuan::select('number')
        //             ->orderBy('tgl_input','desc')
        //             ->first();
        // $no = 'PC201900000';
        // $prefix = substr($no,0,6);
        // $angka = last(explode(" ",$no));
        // $number = $prefix.str_pad(intval($angka) + 1,5,0,STR_PAD_LEFT);
        // dd($number);
        // if($number == $number)
        // {
        //     $number = $prefix.str_pad($number + 2,5,0,STR_PAD_LEFT);
        // }

        // dd($new);
        $data = Pengajuan::select('number')
                        ->orderBy('created_at','desc')
                        ->pluck('number')
                        ->first();
        // dd($data);
        $number = substr($data,6,11);
        $new = str_pad(intval($number) + 1, 5, 0, STR_PAD_LEFT); //increment the number by 1 and pad with 0 in left.

        $data = 'PC2109'.$new;
        // dd($data);
        return $data;

    }


    public function store(Request $request)
    {
        $pengajuan = new Pengajuan;
        $pengajuan->number = $this->generateNumber();
        $pengajuan->tgl_awal = $request->tgl_awal;
        $pengajuan->tgl_akhir = $request->tgl_akhir;
        $pengajuan->user_input = Auth::user()->id;
        $pengajuan->keterangan = $request->keterangan;
        $pengajuan->jumlah_cuti = $request->jumlah_cuti;
        $pengajuan->save();

        $user = new User;
        $user->name = User::select('name')
                    ->where('id','=',Auth::user()->id)
                    ->pluck('name')
                    ->first();


        $admin = User::whereHas('roles', function($q){
                    $q->where('name', 'HRD');
        })->get();
        Notification::send($admin,new NewPengajuanNotification($pengajuan,$user));
        // dd($pengajuan->user);

        return response()->json([

        ],200);
    }

    public function edit($id)
    {
        $data = Pengajuan::find($id);
        return $data;
    }

    public function update(Request $request,$id)
    {
        $pengajuan = Pengajuan::find($id);
        // $pengajuan->number = $this->generateNumber();
        $pengajuan->tgl_awal = $request->tgl_awal;
        $pengajuan->tgl_akhir = $request->tgl_akhir;
        $pengajuan->user_input = Auth::user()->id;
        $pengajuan->keterangan = $request->keterangan;
        $pengajuan->jumlah_cuti = $request->jumlah_cuti;
        $pengajuan->save();

        return response()->json([

        ],200);
    }

    public function delete($number)
    {
        $data = Pengajuan::destroy($number);
        return $data;
    }
}

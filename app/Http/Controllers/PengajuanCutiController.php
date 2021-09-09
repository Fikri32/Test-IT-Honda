<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\User;
use App\Models\Cuti;
use Auth;
use Datatables;
use Datetime;

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
        $pengajuan = Pengajuan::all();
        return Datatables::of($pengajuan)
                ->addIndexColumn()
                ->addColumn('user_acc',function($pengajuan){
                    if($pengajuan->user != null)
                    {
                        return $pengajuan->user->name;
                    }

                })
                ->addColumn('action',function($row){
                    $aksi = '
                    <a href = "javascript:void(0)" data-id="'.$row->number.'" id="edit" class = " edit btn btn-sm btn-outline-warning mb-10">Edit</a>
                    <a href = "javascript:void(0)" data-id="'.$row->number.'" id="delete" class = " delete btn btn-sm btn-outline-danger">Delete</a>
                    ';
                    return $aksi;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function store(Request $request)
    {
        $pengajuan = new Pengajuan;
        $pengajuan->number = rand();
        $pengajuan->tgl_awal = $request->tgl_awal;
        $pengajuan->tgl_akhir = $request->tgl_akhir;
        $pengajuan->user_input = Auth::user()->id;
        $pengajuan->keterangan = $request->keterangan;
        $pengajuan->jumlah_cuti = $request->jumlah_cuti;
        $pengajuan->save();

        $cuti = Cuti::where('id_user',Auth::user()->id)->first();
        // dd($cuti);
        $cuti->sisa_cuti = $cuti->sisa_cuti - $request->jumlah_cuti;
        $cuti->save();

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
        $pengajuan->number = rand();
        $pengajuan->tgl_awal = $request->tgl_awal;
        $pengajuan->tgl_akhir = $request->tgl_akhir;
        $pengajuan->user_input = Auth::user()->id;
        $pengajuan->keterangan = $request->keterangan;
        $pengajuan->jumlah_cuti = $request->jumlah_cuti;
        $pengajuan->save();

        $cuti = Cuti::where('id_user',Auth::user()->id)->first();
        // dd($cuti);
        $cuti->sisa_cuti = $cuti->sisa_cuti - $request->jumlah_cuti;
        $cuti->save();
        return response()->json([

        ],200);
    }

    public function delete($number)
    {
        $data = Pengajuan::destroy($number);
        return $data;
    }
}

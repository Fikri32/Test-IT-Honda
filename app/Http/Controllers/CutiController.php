<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuti;
use App\Models\User;
use Datatables;

class CutiController extends Controller
{
    public function index()
    {
        return view('cuti.index');
    }

    public function data()
    {
        $data = Cuti::all();
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('nama',function($data){
                    return $data->user->name;
                    // dd($data->user->name);
                })
                ->addColumn('action',function($row){
                    $aksi = '
                    <a href = "javascript:void(0)" data-id="'.$row->id.'" id="edit" class = " edit btn btn-warning btn-sm">Edit</a>
                    <a href = "javascript:void(0)" data-id="'.$row->id.'" id="delete" class = " delete btn btn-danger btn-sm">Delete</a>
                    ';
                    return $aksi;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function getkaryawan()
    {
        $karyawan = User::role('karyawan')->pluck('name','id');
        return response()->json($karyawan);
    }

    public function store(Request $request)
    {
        $data = Cuti::create($request->all());
        return response()->json([
            'fail' => 'true'
        ],200);
    }

    public function edit($id)
    {
        $data = Cuti::find($id);

        $default = $data->user;
        //  dd($default->id);
        $karyawan = User::role('karyawan')->pluck('name','id');
        $new = [
            'data'=>$data,
            'karyawan' => $karyawan,
            'default' => $default
        ];
        return $new;
    }

    public function update(Request $request,$id)
    {
        $data = $request->except('_method','_token');
        $cuti = Cuti::where('id',$id)->update($data);
        return response()->json([
            'fail' => 'true',
        ],200);
    }

    public function delete($id)
    {
        $data = Cuti::destroy($id);
        return $data;
    }
}

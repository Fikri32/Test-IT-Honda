<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use Datatables;

class PengajuanCutiController extends Controller
{
    public function index()
    {
        return view('pengajuan.index');
    }

    public function store(Requests $request)
    {
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
        return response()->json([

        ],200);
    }

    public function delete($id)
    {
        $data = Pengajuan::destory($id);
        return $data;
    }
}

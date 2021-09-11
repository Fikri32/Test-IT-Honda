@extends('layouts.master')
@section('content')
 <div class="block  ml-auto mr-auto">
    <div class="block-header block-header-default">
        <h3 class="block-title">Pengajuan Cuti Index</h3>
        <div class="block-options">
            <button type="button" class="btn-block-option">
                <i class="si si-wrench"></i>
            </button>
        </div>
    </div>
    <div class="block-content">
        @role('hrd')
            @foreach ($notification as $item)
                <p>"ADA PENGAJUAN CUTI DARI {{$item->data['name']}}"</p>
            @endforeach
        @endrole
        @role('karyawan')
             @foreach ($notification as $item)
                <p>"ADA PERSETUJUAN CUTI DARI {{$item->data['name']}}"</p>
            @endforeach
        @endrole
    </div>
</div>
@endsection

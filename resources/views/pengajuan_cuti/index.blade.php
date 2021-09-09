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
        <a class="btn btn-primary m-3" id="tambah" name="tambah" href="javascript:;" data-toggle="modal" data-target="#pengajuanModal">
            Add Pengajuan Cuti
        </a>
        <table class="table table-bordered table-vcenter no-footer" id="pengajuan">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Tanggal Awal</th>
                <th>Tanggal Akhir</th>
                <th>Cuti</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Acc By</th>
                <th>Keterangan Acc</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
    </div>
</div>
<div id="pengajuanModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Pengajuan Cuti</h4>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <form autocomplete="off" action="" method="post" name="frm_add" id="frm_add">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label class="col-12" for="example-daterange1">Tanggal Cuti</label>
                        <div class="col-lg-12">
                            <div class="input-daterange input-group js-datepicker-enabled" data-date-format="yyyy/mm/dd" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                <input type="text" class="form-control" id="tgl_awal" name="tgl_awal" placeholder="From" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                <span class="input-group-addon font-w600">to</span>
                                <input type="text" class="form-control" id="tgl_akhir" name="tgl_akhir" placeholder="To" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="">Sisa Cuti : 12</label>
                                <label for="">Jumah Cuti</label>
                                <input type="text" class="form-control" id="jumlah_cuti" name="jumlah_cuti">
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="form-group">
                                <label for="">Keterangan</label>
                                <textarea name="keterangan" class="form-control" id="keterangan" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="saveBtn" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        var idEdit = " ";
        console.log (idEdit)

        $('#tgl_awal').datepicker({
            format : 'yyyy/mm/dd'
        });

         $('#tgl_akhir').datepicker({
            format : 'yyyy/mm/dd'
        });

        $('#tambah').click(function () {

            $('#frm_add').trigger("reset");

            $('#pengajuanModal').modal('show');

        });

    // Data
        var table = $('#pengajuan').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('pengajuan.data') }}",
                columns: [
                    { data: 'number', name: 'number' },
                    { data: 'tgl_awal', name: 'tgl_awal' },
                    { data: 'tgl_akhir', name: 'tgl_akhiri' },
                    { data: 'jumlah_cuti', name: 'jumlah_cuti'},
                    { data: 'keterangan', name: 'keterangan' },
                    { data: 'status', name: 'status' },
                    { data: 'user_acc', name: 'user_acc' },
                    { data: 'keterangan_acc', name: 'keterangan_acc' },
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ]
        });
    // End Data

    // Store Data
        $('#saveBtn').click(function(){
            var url;
            var type;
            // var update = '{{ route("cuti.update",":id") }}'
            if(idEdit === " ")
            {
                url = "{{ route('pengajuan.store') }}"
                type = "POST"
            }else{
                url = 'http://localhost:8000/pengajuan/update/'+idEdit
                type = "PUT"
            }
            $.ajax({
                headers : {
                    'X-CSRF-TOKEN' : "{{csrf_token()}}"
                },
                type : type,
                url : url,
                data : $('#frm_add').serialize(),
                success : function(response){
                    if(response.fail != false)
                    {
                        Swal.fire({
                            title : 'Berhasil !',
                            icon: 'success',
                            text  : 'Data Pengajuan Berhasil Di Tambah',
                            showConfirmButton : true
                        })
                    }else{
                        Swal.fire({
                            title : 'Gagal !',
                            text  : 'Periksa Kembali Form Input',
                            icon  : 'error',
                            showConfirmButton : true
                        })
                    }
                    idEdit = " ";
                    $('#frm_add').trigger("reset");
                    $('#pengajuanModal').modal('hide');
                    table.draw()
                }
            })
        });
    // End Store Data

    // EDIT DATA
        $('body').on('click','.edit',function(){
            var id = $(this).attr('data-id');
            var url = '{{ route("pengajuan.edit",":id") }}'
            url = url.replace(':id',id)

            $.ajax({
                type : 'GET',
                url : url,
                success:function(data){
                    idEdit = data.number;
                    $('#pengajuanModal').modal('show');
                    $('#tgl_awal').val(data.tgl_awal);
                    $('#tgl_akhir').val(data.tgl_akhir);
                    $('#keterangan').val(data.keterangan);
                    $('#jumlah_cuti').val(data.jumlah_cuti);
                    }
            })
        })
    // END EDIT
    // Delete Data
        $('body').on('click','.delete',function(){
            var id = $(this).attr('data-id');
            var url = '{{ route("pengajuan.delete", ":id") }}';
            url = url.replace(':id', id );
            console.log(id)
            Swal.fire({
                title : 'Anda Yakin ?',
                text  : 'Data Yang Sudah Dihapus Tidak Akan Bisa Dikembalikan',
                icon  : 'warning',
                showConfirmButton : true,
                showCancelButton : true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Tidak, Batalkan!',
                allowOutsideClick: false,
            })
            .then((result)=>{
                if(result.value) {
                    $.ajax({
                        headers:{
                            'X-CSRF-TOKEN' : '{{csrf_token()}}'
                        },
                        type : 'DELETE',
                        url:url,
                        success:function(response){
                            if(response.fail != false)
                            {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    icon : 'success',
                                    text : 'Data Berhasil Di Hapus',
                                    showConfirmButton :true
                                })
                            }else{
                                Swal.fire({
                                    title: 'Gagal!',
                                    icon : 'error',
                                    text : 'Data Gagal Di Hapus',
                                    showConfirmButton :true
                                })
                            }
                            table.draw()
                        }
                    })
                }else{
                    Swal.close()
                }
            })
        })
    // End Delete


    })





</script>
@endpush

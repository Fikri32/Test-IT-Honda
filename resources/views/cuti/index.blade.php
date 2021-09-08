@extends('layouts.master')

@section('content')
 <div class="block  ml-auto mr-auto">
    <div class="block-header block-header-default">
        <h3 class="block-title">Cuti Master Index</h3>
        <div class="block-options">
            <button type="button" class="btn-block-option">
                <i class="si si-wrench"></i>
            </button>
        </div>
    </div>
    <div class="block-content">
        <a class="btn btn-primary m-3" id="tambah" name="tambah" href="javascript:;" data-toggle="modal" data-target="#cutiModal">
            Add Cuti
        </a>
        <table class="table table-bordered" id="cuti">
        <thead>
            <tr>
                <th>id</th>
                <th>Nama</th>
                <th>Sisa Cuti</th>
                <th>Tahun</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
    </div>
</div>
<div id="cutiModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Cuti</h4>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <form action="" method="post" name="frm_add" id="frm_add">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="">Nama Karyawan</label>
                        <select name="id_user" id="id_user" class="id_user form-control">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Jumlah Cuti</label>
                        <input type="text" class="form-control" name="sisa_cuti" id="sisa_cuti" >
                    </div>
                    <div class="form-group">
                        <label for="">Tahun</label>
                        <input type="text" class="form-control" name="tahun" id="tahun" >
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
        var idEdit = 0;

        $('#tambah').click(function () {

            $('#frm_add').trigger("reset");

            $('#cutiModal').modal('show');

        });

    // Data
        var table = $('#cuti').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('cuti.data') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'nama', name: 'nama' },
                    { data: 'sisa_cuti', name: 'sisa-cuti' },
                    { data: 'tahun', name: 'tahun' },
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ]
        });
    // End Data

    // Get Karyawan
        $.ajax({
            url:"{{ route('cuti.karyawan') }}",
            type:'GET',
            success:function(res){
                $("#id_user").empty();
                $("#id_user").append('<option>---Pilih Karyawan---</option>');
                $.each(res,function(id,nama){
                    $("#id_user").append('<option value="'+id+'">'+nama+'</option>');
                });
            }
        })
    //End Get Karyawan

    // Store Data
        $('#saveBtn').click(function(){
            var url;
            var type;
            var update = '{{ route("cuti.update",":id") }}'
            if(idEdit == 0)
            {
                url = "{{ route('cuti.store') }}"
                type = "POST"
            }else{
                url = 'http://127.0.0.1:8000/cuti/update/'+idEdit
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
                            text  : 'Data Master Cuti Berhasil Di Tambah',
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
                    idEdit = 0;
                    $('#frm_add').trigger("reset");
                    $('#cutiModal').modal('hide');
                    table.draw()
                }
            })
        });
    // End Store Data

    // EDIT DATA
        $('body').on('click','.edit',function(){
            var id = $(this).attr('data-id');
            var url = '{{ route("cuti.edit",":id") }}'
            url = url.replace(':id',id)

            $.ajax({
                type : 'GET',
                url : url,
                success:function(data){
                    idEdit = data.id;
                    var nama = data.nama
                    $('#cutiModal').modal('show');
                    $('#sisa_cuti').val(data.sisa_cuti);
                    $('#tahun').val(data.tahun);
                    $.each(res,function(id,nama){
                        $("#id_user").append('<option value="'+id+'">'+nama+'</option>');
                    });
                    }
            })
        })
    // END EDIT
    // Delete Data
        $('body').on('click','.delete',function(){
            var id = $(this).attr('data-id');
            var url = '{{ route("cuti.delete", ":id") }}';
            url = url.replace(':id', id );
            console.log(id)
            Swal.fire({
                title : 'Anda Yakin ?',
                text  : 'Data Yang Sudah Dihapus Tidak Akan Bisa Dikembalikan',
                icon  : 'warning',
                showConfirmButton : true,
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

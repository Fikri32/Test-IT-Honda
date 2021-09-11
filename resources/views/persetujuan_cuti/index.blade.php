@extends('layouts.master')

@section('content')
 <div class="block  ml-auto mr-auto">
    <div class="block-header block-header-default">
        <h3 class="block-title">Persetujuan Cuti Index</h3>
        <div class="block-options">
            <button type="button" class="btn-block-option">
                <i class="si si-wrench"></i>
            </button>
        </div>
    </div>
    <div class="block-content">
        <table class="table table-bordered" id="persetujuan">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Tanggal Awal</th>
                <th>Tanggal Akhir</th>
                <th>Cuti(Hari)</th>
                <th>Keterangan</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
    </div>
</div>
<div id="persetujuanModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Persetujuan Cuti</h4>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <form autocomplete="off" action="" method="post" name="frm_add" id="frm_add">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label class="col-12" for="example-daterange1">Tanggal Cuti</label>
                        <div class="col-lg-12">
                            <div class="input-daterange input-group js-datepicker-enabled" data-date-format="yyyy/mm/dd" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                <input type="text" class="form-control" id="tgl_awal" name="tgl_awal" placeholder="From" data-week-start="1" data-autoclose="true" data-today-highlight="true" disabled>
                                <span class="input-group-addon font-w600">to</span>
                                <input type="text" class="form-control" id="tgl_akhir" name="tgl_akhir" placeholder="To" data-week-start="1" data-autoclose="true" data-today-highlight="true" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label id="sisa_cuti" for=""></label>
                                <label for="">Jumah Cuti</label>
                                <input type="hidden" class="form-control" id="jml_cuti" name="jml_cuti">
                                <input type="text" class="form-control" id="jumlah_cuti" name="jumlah_cuti" readonly>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="form-group">
                                <label for="">Keterangan</label>
                                <textarea name="keterangan" class="form-control" id="keterangan" cols="5" rows="4" disabled></textarea>
                            </div>
                        </div>
                        <hr>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label class="col-12">Persetujuan</label>
                                <div class="col-12">
                                    <label class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="status" name="status" value="1" checked="">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Ya</span>
                                    </label>
                                    <label class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="status" name="status" value="0">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Tidak</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="form-group">
                                <label for="">Keterangan Diterima</label>
                                <textarea name="keterangan_acc" class="form-control" id="keterangan_acc" cols="5" rows="4"></textarea>
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
        var idEdit = 0;

         $('body').on('click','.detail',function(){
            var id = $(this).attr('data-id');
            var url = '{{ route("persetujuan.detail",":id") }}'
            url = url.replace(':id',id)

            $.ajax({
                type : 'GET',
                url : url,
                success:function(data){
                    idEdit = data.number;
                    $('#persetujuanModal').modal('show');
                    $('#tgl_awal').val(data.tgl_awal);
                    $('#tgl_akhir').val(data.tgl_akhir);
                    $('#keterangan').val(data.keterangan);
                    $('#jumlah_cuti').val(data.jumlah_cuti);
                    }
            })
        })

    // Data
        var table = $('#persetujuan').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('persetujuan.data') }}",
                columns: [
                    { data: 'number', name: 'number' },
                    { data: 'tgl_awal', name: 'tgl_awal' },
                    { data: 'tgl_akhir', name: 'tgl_akhiri' },
                    { data: 'jumlah_cuti', name: 'jumlah_cuti'},
                    { data: 'keterangan', name: 'keterangan' },
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
                // url = "{{ route('pengajuan.store') }}"
                type = "POST"
            }else{
                url = 'http://localhost:8000/persetujuan/accept/'+idEdit
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
                    $('#persetujuanModal').modal('hide');
                    table.draw()
                }
            })
        });
    // End Store Data
    });





</script>
@endpush

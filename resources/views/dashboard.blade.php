@extends('layouts.master')
@section('content')
 <div class="block  ml-auto mr-auto">
    <div class="block-header block-header-default">
        <h3 class="block-title">Welcome {{Auth::user()->name}}</h3>
        <div class="block-options">
            <button type="button" class="btn-block-option">
                <i class="si si-wrench"></i>
            </button>
        </div>
    </div>
    <div class="block-content">
        @role('hrd')
            @foreach ($notification as $item)
            <div class=" alert js-animation-object animated lightSpeedIn">
                <div class="col-md-12">
                    <!-- Success Alert -->
                    <div class="alert alert-primary alert-dismissable" data-animation-class="lightSpeedIn" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h3 class="alert-heading font-size-h4 font-w400">Pengajuan Cuti Baru Dari {{$item->data['name']}} </h3>
                        <p class="mb-0"><a class="alert-link link-effect mark-as-read" data-id="{{$item->id}}" href="#" >Baca Dan Tandai!</a>!</p>
                    </div>
                    <!-- END Success Alert -->
                </div>
            </div>
            @endforeach
        @endrole
        @role('karyawan')

           @foreach ($data as $item)
            <div class="js-animation-object animated lightSpeedIn">
                <div class="col-md-12">
                    <!-- Success Alert -->
                    <div class="alert alert-success alert-dismissable" data-animation-class="lightSpeedIn" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h3 class="alert-heading font-size-h4 font-w400">Validasi Pengajuan Cuti Oleh {{$item->data['name']}} </h3>
                        <p class="mb-0"><a class="alert-link link-effect mark-as-read" data-id="{{$item->id}}" href="#" >Baca Dan Tandai!</a>!</p>
                    </div>
                    <!-- END Success Alert -->
                </div>
            </div>
            @endforeach

        @endrole
    </div>
</div>
@endsection
@push('scripts')
@role('hrd')
    <script>
    $('document').ready(function(){
         function sendMarkRequest(id = null) {
        return $.ajax("{{ route('mark.home') }}", {
              headers : {
                    'X-CSRF-TOKEN' : "{{csrf_token()}}"
                },
            method: 'POST',
            data: {
                id
            }
        });
    }
    $(function() {
        $('.mark-as-read').click(function() {
            let request = sendMarkRequest($(this).data('id'));
            request.done(() => {
                 $(this).parents('div.alert').remove();
                 window.location = "{{ route('persetujuan.index') }}"
            });
        });
        // $('#mark-all').click(function() {
        //     let request = sendMarkRequest();
        //     request.done(() => {

        //     })
        // });
    });
    })
</script>
@endrole
@role('karyawan')
    <script>
    $('document').ready(function(){
         function sendMarkRequest(id = null) {
        return $.ajax("{{ route('mark.home') }}", {
              headers : {
                    'X-CSRF-TOKEN' : "{{csrf_token()}}"
                },
            method: 'POST',
            data: {
                id
            }
        });
    }
    $(function() {
        $('.mark-as-read').click(function() {
            let request = sendMarkRequest($(this).data('id'));
            request.done(() => {
                 $(this).parents('div.alert').remove();
                 window.location = "{{ route('pengajuan.index') }}"
            });
        });
        // $('#mark-all').click(function() {
        //     let request = sendMarkRequest();
        //     request.done(() => {

        //     })
        // });
    });
    })
</script>
@endrole
@endpush

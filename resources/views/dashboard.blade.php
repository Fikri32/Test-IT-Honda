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
            @forelse($notification as $item)
                <div class="alert alert-primary js-animation-object animated lightSpeedIn" role="alert">
                    <h3 class="alert-heading font-size-h4 font-w400">[{{$item->created_at}}] Pengajuan Cuti Baru Dari {{$item->data['name']}} </h3>
                </div>
                @if($loop->last)
                    <a href="#" class="btn btn-primary btn-sm btn-flat link-effect mb-3" id="mark-all">
                        Baca Dan Tandai Semua Pesan
                    </a>
                @endif

            @empty
                There are no new notifications
            @endforelse
        @endrole
        @role('karyawan')
            @forelse($notification as $item)
                <div class="alert alert-primary js-animation-object animated lightSpeedIn" role="alert">
                    <h3 class="alert-heading font-size-h4 font-w400">[{{$item->created_at}}] Status Pengajuan Telah Di Update Oleh {{$item->data['name']}} </h3>
                    <p class="mb-0"><a class="alert-link link-effect mark-as-read" data-id="{{$item->id}}" href="#" >Tandai Sudah Dibaca </a>!</p>

                </div>
                @if($loop->last)
                    <a href="#" class="btn btn-primary btn-sm btn-flat link-effect mb-3" id="mark-all">
                        Baca Dan Tandai Semua Pesan
                    </a>
                @endif

                @empty
                There are no new notifications
            @endforelse
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
            });
        });
        $('#mark-all').click(function() {
            let request = sendMarkRequest();
            request.done(() => {
                $('div.alert').remove();
                window.location = "{{ route('persetujuan.index') }}"
            })
        });
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
        $('#mark-all').click(function() {
            let request = sendMarkRequest();
            request.done(() => {
                $('div.alert').remove();
                window.location = "{{ route('pengajuan.index') }}"
            })
        });
    });
    })
</script>
@endrole
@endpush

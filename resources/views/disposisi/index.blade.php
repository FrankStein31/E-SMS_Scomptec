@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb start -->
            <!-- <div class="row m-1">
                <div class="col-12 ">
                    <h5 class="main-title">Disposisi</h5>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li class="">
                            <a class="f-s-14 f-w-500" href="#">
                                <span>
                                    Home
                                </span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Disposisi</a>
                        </li>
                    </ul>
                </div>
            </div> -->
            <!-- Breadcrumb end -->

            @include('layout.alert')

            <!-- Blank start -->
            <div class="row">
                <!-- Default Card start -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>Disposisi Surat</h5>
                                </div>
                                <div class="col text-end">
                                    {{-- <a href="{{ route('entrisurat.index') }}" class="btn btn-info btn-sm">Daftar Entri
                                        Surat</a> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                {!! $dataTable->table(['id' => 'disposisi-table']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Default Card end -->
            </div>
            <!-- Blank end -->
        </div>
    </main>
@endsection

@push('js')
    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
    
    <script>
        $(document).ready(function() {
            // Handle row click untuk navigasi ke detail
            $('#disposisi-table tbody').on('click', 'tr', function() {
                var data = $('#disposisi-table').DataTable().row(this).data();
                if (data && data.entrysurat_id) {
                    window.location.href = '{{ route("disposisi.show", ":id") }}'.replace(':id', data.entrysurat_id);
                }
            });
            
            // Add hover cursor pointer style
            $('#disposisi-table tbody').on('mouseenter', 'tr', function() {
                $(this).css('cursor', 'pointer');
            });
        });
    </script>
@endpush

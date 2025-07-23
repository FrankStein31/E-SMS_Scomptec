@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <div class="row m-1">
                <div class="col-12 ">
                    <h5 class="main-title">Daftar Draft Surat</h5>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li class="">
                            <a class="f-s-14 f-w-500" href="#">
                                <span>
                                    Home
                                </span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Draft Surat</a>
                        </li>
                    </ul>
                </div>
            </div>
            @include('layout.alert')

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>List Draft Surat</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                {{ $dataTable->table(['id' => 'table-id']) }}
                                {{-- <table class="table table-sm table-hover table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Sifat</th>
                                            <th scope="col">Jenis</th>
                                            <th scope="col">Hal</th>
                                            <th scope="col">Tgl. Surat</th>
                                            <th scope="col">Klasifikasi</th>
                                            <th scope="col">Kepada</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($drafts as $index => $draft)
                                            <tr data-href='{{ route('draft-surat.show', $draft['id']) }}'
                                                class="clickable-row">
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    @if ($draft->sifat == 1)
                                                        <span class="badge bg-danger">Penting</span>
                                                    @elseif($draft->sifat == 2)
                                                        <span class="badge bg-warning">Rahasia</span>
                                                    @else
                                                        <span class="badge bg-info">Biasa</span>
                                                    @endif
                                                </td>
                                                <td>{{ $draft->jenis->name }}</td>
                                                <td>{{ $draft->hal }}</td>
                                                <td>{{ $draft->tgl_surat }}</td>
                                                <td>{{ $draft->kodeklasifikasi }}</td>
                                                <td>
                                                    @php
                                                        $kepada = json_decode($draft->kepada);
                                                        $names = [];
                                                        foreach ($kepada as $k) {
                                                            $data = json_decode($k);
                                                            $names[] = $data->name;
                                                        }
                                                        echo implode(', ', $names);
                                                    @endphp
                                                </td>
                                                <td>
                                                    <a href="{{ route('draft-surat.edit', $draft['id']) }}"
                                                        class="btn btn-primary btn-sm">Edit</a>
                                                    <form action="{{ route('draft-surat.destroy', $draft['id']) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus draft surat ini?')">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">Belum ada draft surat
                                                    yang tersedia.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table> --}}
                            </div>

                            {{-- Pagination area --}}
                            @if ($drafts instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <div class="d-flex justify-content-between align-items-center pt-3 mt-3">
                                    <nav aria-label="Page navigation">
                                        {{ $drafts->links('pagination::bootstrap-5') }}
                                    </nav>
                                    <div class="text-muted small fw-bold">
                                        Menampilkan {{ $drafts->firstItem() }} - {{ $drafts->lastItem() }} dari
                                        {{ $drafts->total() }} Data
                                    </div>
                                </div>
                            @else
                                {{-- Fallback for when pagination is not used but still want a footer --}}
                                <div class="d-flex justify-content-between align-items-center pt-3 mt-3">
                                    <div class="text-muted small fw-bold">
                                        Menampilkan {{ count($drafts) }} Data
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        const showUrl = "{{ url('/draft-surat') }}/";

        $(document).ready(function() {
            $(document).on('click', '.btnDetail', function() {
                var id = $(this).data('id');
                window.location.href = showUrl + id;
            });
        });

        // Tombol Hapus
        $(document).on('click', '.btnHapus', function() {
            var id = $(this).data('id');
            if (confirm('Yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: '/draft-surat/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Data berhasil dihapus');
                        $('#your-datatable-id').DataTable().ajax.reload(); // reload table
                    },
                    error: function(xhr) {
                        alert('Gagal menghapus data');
                    }
                });
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.clickable-row').click(function() {
                window.location = $(this).data('href');
            });
        });
    </script>
@endpush

@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <h4 class="main-title">Detail Draft Surat</h4>

            <div class="card">
                <div class="card-body">
                    <h5><strong>Judul:</strong> {{ $draft->judul }}</h5>
                    <hr>
                    <p>{{ $draft->isi }}</p>

                    <div class="mt-3">
                        <a href="{{ route('draft-surat.edit', $draft->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('draft_surat.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

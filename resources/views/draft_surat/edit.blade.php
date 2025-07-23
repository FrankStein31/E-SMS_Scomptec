@extends('layout.main')

@section('content')
<main>
    <div class="container-fluid">
        <h5 class="main-title">Edit Draft Surat</h5>

        @include('layout.alert')

        <div class="card">
            <div class="card-body">
                <form action="{{ route('draft-surat.update', $draft->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label>Judul</label>
                        <input type="text" name="judul" class="form-control form-control-sm" value="{{ $draft->judul }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Isi</label>
                        <textarea name="isi" class="form-control form-control-sm" rows="5" required>{{ $draft->isi }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-warning btn-sm">Update</button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
@extends('layout.main')

@section('content')
<main>
    <div class="container-fluid">
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Tambah Draft Surat</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li><a class="f-s-14 f-w-500" href="#">Home</a></li>
                    <li class="active"><a class="f-s-14 f-w-500" href="#">Draft Surat</a></li>
                </ul>
            </div>
        </div>

        @include('layout.alert')

        <div class="card">
            <div class="card-body">
                <form action="{{ route('draft-surat.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Judul</label>
                        <input type="text" name="judul" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label>Isi</label>
                        <textarea name="isi" class="form-control form-control-sm" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
@extends('layout.main')

@section('content')
    <div class="container">
        <h4>Daftar Draft Surat</h4>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Isi</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drafts as $index => $draft)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $draft['judul'] }}</td>
                        <td>{{ $draft['isi'] }}</td>
                        <td>{{ $draft['waktu'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada draft.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

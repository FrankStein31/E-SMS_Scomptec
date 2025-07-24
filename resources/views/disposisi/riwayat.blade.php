@extends('layout.main')

@section('content')
<main>
    <div class="container-fluid">
        <div class="row m-1">
            <div class="col-12">
                <h5 class="main-title">Riwayat Surat & Disposisi</h5>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="active">
                        <a class="f-s-14 f-w-500" href="#">Riwayat Surat</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Info Surat Masuk</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm mb-3">
                            <tr><th>Dari</th><td>{{ $surat->dari }}</td></tr>
                            <tr><th>Kepada</th><td>{{ $surat->kepada }}</td></tr>
                            <tr><th>Tanggal</th><td>{{ $surat->tgl_surat ? $surat->tgl_surat : '-' }} {{ $surat->created_at ? $surat->created_at->format('H:i') : '' }}</td></tr>
                        </table>
                        <h5>Riwayat Disposisi</h5>
                        <table class="table table-bordered table-hover table-sm align-middle">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th style="width:40px">No</th>
                                    <th style="min-width:180px">Dari</th>
                                    <th style="min-width:200px">Kepada</th>
                                    <th style="width:110px">Tanggal</th>
                                    <th style="width:80px">Jam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayat as $i => $item)
                                <tr>
                                    <td class="text-center">{{ $i+1 }}</td>
                                    <td>
                                        @php
                                            $dariUser = null;
                                            if(isset($item->dari_id)) {
                                                $dariUser = \App\Models\User::find($item->dari_id);
                                            }
                                        @endphp
                                        <div>
                                            <strong>{{ $dariUser ? $dariUser->fullname : '-' }}</strong>
                                            @if($dariUser)
                                                @if($dariUser->jabatan)
                                                    <div class="text-muted small">{{ $dariUser->jabatan }}</div>
                                                @endif
                                                @if($dariUser->masterSatker && $dariUser->masterSatker->satker)
                                                    <div class="text-muted small">{{ $dariUser->masterSatker->satker }}</div>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $kepadaArr = array_unique(explode(',', $item->kepada));
                                            $kepadaLabels = [];
                                            foreach($kepadaArr as $uid) {
                                                $u = \App\Models\User::find($uid);
                                                if($u) {
                                                    $kepadaLabels[] = '<li><strong>'.$u->fullname.'</strong><br><span class=\'text-muted small\'>'.($u->jabatan ?? '-') .'</span><br><span class=\'text-muted small\'>'.($u->masterSatker ? $u->masterSatker->satker : '-') .'</span></li>';
                                                } else {
                                                    $kepadaLabels[] = '<li>'.$uid.'</li>';
                                                }
                                            }
                                        @endphp
                                        <ul class="mb-0 ps-3">
                                            @foreach($kepadaArr as $uid)
                                                @php $u = \App\Models\User::find($uid); @endphp
                                                <li>
                                                    <strong>{{ $u ? $u->fullname : $uid }}</strong>
                                                    @if($u)
                                                        @if($u->jabatan)
                                                            <div class="text-muted small">{{ $u->jabatan }}</div>
                                                        @endif
                                                        @if($u->masterSatker && $u->masterSatker->satker)
                                                            <div class="text-muted small">{{ $u->masterSatker->satker }}</div>
                                                        @endif
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="text-center">{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') : '-' }}</td>
                                    <td class="text-center">{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('H:i') : '-' }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center">Belum ada riwayat disposisi</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection 
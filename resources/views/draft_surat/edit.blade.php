@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb start -->
            <div class="row m-1">
                <div class="col-12">
                    <h5 class="main-title">Edit Surat</h5>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li><a class="f-s-14 f-w-500" href="#">Home</a></li>
                        <li><a class="f-s-14 f-w-500" href="{{ route('draft_surat.index') }}">Daftar Surat</a></li>
                        <li class="active"><a class="f-s-14 f-w-500" href="#">Edit Surat</a></li>
                    </ul>
                </div>
            </div>
            <!-- Breadcrumb end -->

            @include('layout.alert')

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Form Edit Surat</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('draft-surat.update', $draft->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">No. Surat</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="nosurat" type="text"
                                            value="{{ $draft->nosurat ?? 'Auto Generate' }}" readonly>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Klasifikasi</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="kodeklasifikasi" type="text"
                                            required value="{{ $draft->kodeklasifikasi }}">
                                        <small class="text-muted">Pengamatan, Pentipaan, dan Pengavadan terhadap Pejabat,
                                            Kantar dan Rumah Dinas</small>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Tanggal Surat</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" type="date" name="tgl_surat" required
                                            value="{{ $draft->tgl_surat }}">
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Hal</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="hal" type="text" required
                                            value="{{ $draft->hal }}">
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Isi Surat</label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea name="isi_surat" rows="6" class="form-control">{{ $draft->isi_surat }}</textarea>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Tembusan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="tembusan" type="text"
                                            value="
                                                @php
$tembusanRaw = $draft->tembusan ?? '[]';
                                            $tembusanList = json_decode($tembusanRaw, true);
                                            $tembusanList = is_array($tembusanList) ? $tembusanList : [];
                                            $names = [];
                                            foreach ($tembusanList as $t) {
                                                $data = json_decode($t, true);
                                                $names[] = $data['name'] ?? '';
                                            }
                                            echo implode(', ', $names); @endphp">
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Referensi</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="referensi" type="text"
                                            value="{{ $draft->referensi }}">
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Permohonan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="permohonan" type="text"
                                            value="{{ $draft->permohonan }}">
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Lampiran File</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="file" name="lampiran" class="form-control form-control-sm">
                                        @if ($draft->lampiran)
                                            <div class="mt-2">
                                                <span class="badge bg-light text-dark">
                                                    <i class="fas fa-paperclip me-1"></i> {{ basename($draft->lampiran) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-9">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="{{ route('draft_surat.index') }}" class="btn btn-secondary">Batal</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection

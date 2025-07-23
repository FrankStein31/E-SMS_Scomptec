@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb start -->
            <div class="row m-1">
                <div class="col-12 ">
                    <h5 class="main-title">Buat Surat</h5>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li class="">
                            <a class="f-s-14 f-w-500" href="#">
                                <span>
                                    Home
                                </span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Buat Surat</a>
                        </li>
                    </ul>
                </div>
            </div>
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
                                    <h5>Form Buat Surat</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('buatsurat.store') }}" class="app-form" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Jenis Surat</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select
                                            class="select-example form-select form-select-sm select-basic @error('jenis_id') is-invalid @enderror"
                                            name="jenis_id" required>
                                            <option value="">Pilih Jenis Surat</option>
                                            @foreach ($jenisSurat as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('jenis_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">No. Surat</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm @error('nosurat') is-invalid @enderror"
                                            name="nosurat" type="text" required value="{{ old('nosurat') }}">
                                        @error('nosurat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Klasifikasi</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select
                                            class="select-example form-select form-select-sm select-basic @error('klasifikasi') is-invalid @enderror"
                                            name="klasifikasi" required>
                                            <option value="">Pilih Jenis Klasifikasi</option>
                                            @foreach ($klasifikasi as $item)
                                                <option value="{{ $item->kodeklasifikasi }}"
                                                    {{ old('klasifikasi') == $item->kodeklasifikasi ? 'selected' : '' }}>
                                                    {{ $item->kodeklasifikasi }} - {{ $item->klasifikasi }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('klasifikasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Tgl Surat</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm @error('tgl_surat') is-invalid @enderror"
                                            type="date" name="tgl_surat" required>
                                        @error('tgl_surat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Hal</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm @error('hal') is-invalid @enderror"
                                            name="hal" type="text" required>
                                        @error('hal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Sifat</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-select form-select-sm @error('sifat') is-invalid @enderror"
                                            name="sifat" required>
                                            <option value="">Pilih Sifat Surat</option>
                                            <option value="1">Penting</option>
                                            <option value="2">Rahasia</option>
                                            <option value="3">Biasa</option>
                                        </select>
                                        @error('sifat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Lampiran</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm @error('lampiran') is-invalid @enderror"
                                            name="lampiran" type="number" value="0" required>
                                        @error('lampiran')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">File Lampiran</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="file"
                                            class="form-control form-control-sm @error('lampiran_file') is-invalid @enderror"
                                            name="lampiran_file[]" multiple>
                                        @error('lampiran_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Bisa upload multiple file</small>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Kepada</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control select-1 @error('kepada') is-invalid @enderror"
                                            name="kepada[]" multiple="multiple" required>
                                            @foreach ($users as $item)
                                                <option
                                                    value="{{ json_encode(['id' => $item['id'], 'name' => $item['FullName'], 'jabatan' => $item['Jabatan2']]) }}"
                                                    {{ old('kepada') && in_array($item['id'], old('kepada')) ? 'selected' : '' }}>
                                                    {{ $item['FullName'] }} - {{ $item['Jabatan2'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kepada')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Bisa pilih lebih dari satu penerima</small>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Isi Surat</label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea id="editor" name="isi" class="form-control @error('isi') is-invalid @enderror"></textarea>
                                        @error('isi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Tembusan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input
                                            class="form-control form-control-sm @error('tembusan') is-invalid @enderror"
                                            name="tembusan" type="text">
                                        @error('tembusan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Referensi</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input
                                            class="form-control form-control-sm @error('referensi') is-invalid @enderror"
                                            name="referensi" type="number" min="0"
                                            placeholder="Masukkan nomor referensi (opsional)">
                                        @error('referensi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Penandatangan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select
                                            class="select-example form-select form-select-sm select-basic @error('penandatangan') is-invalid @enderror"
                                            name="penandatangan" required>
                                            <option value="">Pilih Penanda Tangan</option>
                                            @foreach ($userTtd as $item)
                                                <option value="{{ $item->id }}">{{ $item->fullname }} -
                                                    {{ $item->jabatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('penandatangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label"></label>
                                    </div>
                                    <div class="col-md-9">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush

@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb start -->
            <div class="row m-1">
                <div class="col-12 ">
                    <h4 class="main-title">Buat Surat</h4>
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
                                <div class="col text-end">
                                    {{-- <a href="{{ route('entrisurat.index') }}" class="btn btn-info btn-sm">Daftar Entri
                                        Surat</a> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('buatsurat.store') }}" class="app-form" method="post">
                                @csrf
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Jenis Surat</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="select-example form-select form-select-sm select-basic"
                                            name="jenis_id">
                                            <option value="">Pilih Jenis Surat</option>
                                            @foreach ($jenisSurat as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">No. Surat</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="no_surat" type="text">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Klasifikasi</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="select-example form-select form-select-sm select-basic"
                                            name="klasifikasi">
                                            <option value="">Pilih Jenis Klasifikasi</option>
                                            @foreach ($klasifikasi as $item)
                                                <option value="{{ $item->id }}">{{ $item->kodeklasifikasi }} -
                                                    {{ $item->klasifikasi }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Tgl Surat</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" type="date" name="tgl_surat">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Hal</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="hal" type="text">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Kepada</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control select-1" name="kepada[]" multiple="multiple">
                                            <option value="">Pilih Kepada</option>
                                            @foreach ($users as $item)
                                                <option value="{{ $item['id'] }}">{{ $item['FullName'] }} -
                                                    {{ $item['Jabatan2'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label"></label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea name="x" id="editor" class="form-control text-editor" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Tembusan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="tembusan" type="text">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Referensi</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="referensi" type="text">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Penandatangan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="select-example form-select form-select-sm select-basic"
                                            name="penandatangan">
                                            <option value="">Pilih Penanda Tangan</option>
                                            @foreach ($userTtd as $item)
                                                <option value="{{ $item->id }}">{{ $item->fullname }} -
                                                    {{ $item->jabatan }}
                                                </option>
                                            @endforeach
                                        </select>
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
        const editors = new Map();

        function createEditor(textarea) {
            ClassicEditor
                .create(textarea)
                .then(editor => {
                    editors.set(textarea, editor);
                })
                .catch(error => {
                    console.error(error);
                });
        }

        function destroyEditor(textarea) {
            const editor = editors.get(textarea);
            if (editor) {
                editor.destroy()
                    .then(() => {
                        editors.delete(textarea);
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        }

        document.querySelectorAll('.text-editor').forEach(textarea => {
            createEditor(textarea);
        });
    </script>
@endpush

@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb start -->
            <div class="row m-1">
                <div class="col-12 ">
                    <h5 class="main-title">Kotak Masuk</h5>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li class="">
                            <a class="f-s-14 f-w-500" href="#">
                                <span>
                                    Home
                                </span>
                            </a>
                        </li>
                        <li class="">
                            <a class="f-s-14 f-w-500" href="#">
                                <span>
                                    Kotak Masuk
                                </span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Detail</a>
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
                                    <h5>Kotak Masuk Surat</h5>
                                </div>
                                <div class="col text-end">
                                    {{-- <a href="{{ route('entrisurat.index') }}" class="btn btn-info btn-sm">Daftar Entri
                                        Surat</a> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                No. Agenda
                                            </th>
                                            <td>
                                                {{ $data->noagenda }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Sifat
                                            </th>
                                            <td>
                                                {{ sifatSurat($data->sifat) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Jenis
                                            </th>
                                            <td>
                                                {{ $data->jenis->name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                No. Surat
                                            </th>
                                            <td>
                                                {{ $data->nomor_surat }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Dari
                                            </th>
                                            <td>
                                                {{ $data->dari }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Tujuan
                                            </th>
                                            <td>
                                                {{ $data->kepada }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Hal
                                            </th>
                                            <td>
                                                {{ $data->hal }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Unit Pengentri
                                            </th>
                                            <td>
                                                {{ $data->createdBy->fullname }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Tanggal
                                            </th>
                                            <td>
                                                {{ $data->created_at->format('d-m-Y') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <br>

                                <form action="{{ route('kotakmasuk.store') }}" class="app-form" method="post">
                                    @csrf
                                    <input type="hidden" name="entrysurat_id" value="{{ $id }}">
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
                                            <label class="form-label">Remitten</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input class="form-control form-control-sm" type="date" name="remitten">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-2">
                                            <label class="form-label">Tindakan</label>
                                        </div>
                                        <div class="col-md-9">
                                            @foreach ($master_tindakan_disposisi as $item)
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="tindakan[]"
                                                        value="{{ $item->id }}" id="disposisi_{{ $item->id }}">
                                                    <label class="form-check-label" for="disposisi_{{ $item->id }}">
                                                        {{ $item->tindakan }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-2">
                                            <label class="form-label"></label>
                                        </div>
                                        <div class="col-md-9">
                                            <textarea name="content" id="editor" class="form-control text-editor" rows="10"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-2">
                                            <label class="form-label"></label>
                                        </div>
                                        <div class="col-md-9">
                                            <button type="submit" class="btn btn-primary">Kirim</button>
                                        </div>
                                    </div>
                                </form>
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


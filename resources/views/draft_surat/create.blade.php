@extends('layout.main') {{-- Pastikan 'layout.main' sudah memuat Bootstrap 5 & Font Awesome --}}

@section('content')
    <main>
        <div class="container-fluid mt-4"> {{-- Menggunakan container-fluid untuk lebar penuh --}}
            <div class="row justify-content-center"> {{-- Pusatkan konten di tengah --}}
                <div class="col-md-10"> {{-- Sesuaikan lebar kolom agar form tidak terlalu lebar --}}
                    <div class="card shadow-sm border-0 rounded-lg"> {{-- Styling modern untuk card --}}
                        <div
                            class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0 text-primary fw-bold">Tambah Draft Surat</h4> {{-- Judul dengan styling --}}
                            <a href="{{ route('draft_surat.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-arrow-left fa-fw me-1"></i> Kembali
                            </a> {{-- Tombol Kembali di pojok kanan atas --}}
                        </div>
                        <div class="card-body p-4"> {{-- Padding lebih untuk body card --}}

                            @include('layout.alert') {{-- Include alert Anda --}}

                            <form action="{{ route('draft-surat.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                {{-- JENIS SURAT --}}
                                <div class="mb-3">
                                    <label for="jenis_surat" class="form-label fw-bold">JENIS SURAT:</label>
                                    <select class="form-select form-select-sm" id="jenis_surat" name="jenis_surat" required>
                                        <option value="">Pilih Jenis Surat</option>
                                        <option value="Nota Dinas"
                                            {{ old('jenis_surat') == 'Nota Dinas' ? 'selected' : '' }}>Nota Dinas</option>
                                        <option value="Surat Undangan"
                                            {{ old('jenis_surat') == 'Surat Undangan' ? 'selected' : '' }}>Surat Undangan
                                        </option>
                                        {{-- Tambahkan opsi lain sesuai kebutuhan --}}
                                    </select>
                                    @error('jenis_surat')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- NO SURAT --}}
                                <div class="mb-3">
                                    <label for="no_surat" class="form-label fw-bold">NO SURAT:</label>
                                    <input type="text" class="form-control form-control-sm" id="no_surat"
                                        name="no_surat" value="{{ old('no_surat') }}" placeholder="Masukkan Nomor Surat"
                                        required>
                                    @error('no_surat')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- KLASIFIKASI --}}
                                <div class="mb-3">
                                    <label for="klasifikasi" class="form-label fw-bold">KLASIFIKASI:</label>
                                    <select class="form-select form-select-sm" id="klasifikasi" name="klasifikasi" required>
                                        <option value="">Pilih Klasifikasi</option>
                                        <option value="UMUM" {{ old('klasifikasi') == 'UMUM' ? 'selected' : '' }}>UMUM
                                        </option>
                                        <option value="RAHASIA" {{ old('klasifikasi') == 'RAHASIA' ? 'selected' : '' }}>
                                            RAHASIA</option>
                                        <option value="PENTING" {{ old('klasifikasi') == 'PENTING' ? 'selected' : '' }}>
                                            PENTING</option>
                                        {{-- Tambahkan opsi lain dari database jika ada --}}
                                    </select>
                                    @error('klasifikasi')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- TANGGAL SURAT --}}
                                <div class="mb-3">
                                    <label for="tanggal_surat" class="form-label fw-bold">TANGGAL SURAT:</label>
                                    <div class="input-group input-group-sm">
                                        <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat"
                                            value="{{ old('tanggal_surat') }}" required>
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    @error('tanggal_surat')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- HAL --}}
                                <div class="mb-3">
                                    <label for="hal" class="form-label fw-bold">HAL:</label>
                                    <input type="text" class="form-control form-control-sm" id="hal" name="hal"
                                        value="{{ old('hal') }}" placeholder="Perihal surat" required>
                                    @error('hal')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- SIFAT SURAT --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">SIFAT SURAT:</label>
                                    <div class="d-flex flex-wrap gap-3"> {{-- Gunakan flexbox untuk layout radio --}}
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="sifat_surat"
                                                id="sifatBiasa" value="Biasa"
                                                {{ old('sifat_surat', 'Biasa') == 'Biasa' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="sifatBiasa">Biasa</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="sifat_surat"
                                                id="sifatPenting" value="Penting"
                                                {{ old('sifat_surat') == 'Penting' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="sifatPenting">Penting</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="sifat_surat"
                                                id="sifatRahasia" value="Rahasia"
                                                {{ old('sifat_surat') == 'Rahasia' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="sifatRahasia">Rahasia</label>
                                        </div>
                                    </div>
                                    @error('sifat_surat')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- KEPADA (Rich Text Editor) --}}
                                {{-- KEPADA --}}
                                <div class="mb-3">
                                    <label for="kepada" class="form-label fw-bold">KEPADA:</label>
                                    <textarea id="summernote" name="kepada" class="form-control">{{ old('kepada') }}</textarea>
                                    @error('kepada')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- TEMBUSAN --}}
                                <div class="mb-3">
                                    <label for="tembusan" class="form-label fw-bold">TEMBUSAN:</label>
                                    <input type="text" class="form-control form-control-sm" id="tembusan"
                                        name="tembusan" value="{{ old('tembusan') }}"
                                        placeholder="Tembusan Surat (jika ada)">
                                    @error('tembusan')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- REFERENSI --}}
                                <div class="mb-3">
                                    <label for="referensi" class="form-label fw-bold">REFERENSI:</label>
                                    <input type="text" class="form-control form-control-sm" id="referensi"
                                        name="referensi" value="{{ old('referensi') }}"
                                        placeholder="Referensi Surat (jika ada)">
                                    @error('referensi')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- PENANDATANGAN --}}
                                <div class="mb-3">
                                    <label for="penandatangan" class="form-label fw-bold">PENANDATANGAN:</label>
                                    <input type="text" class="form-control form-control-sm" id="penandatangan"
                                        name="penandatangan" value="{{ old('penandatangan', 'BPSDM') }}"
                                        placeholder="Nama penandatangan">
                                    @error('penandatangan')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- LAMPIRKAN FILE --}}
                                <div class="mb-4">
                                    <label for="lampirkan_file" class="form-label fw-bold">Lampirkan File:</label>
                                    <div class="input-group input-group-sm">
                                        <input type="file" class="form-control" id="lampirkan_file"
                                            name="lampirkan_file" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx">
                                        <label class="input-group-text" for="lampirkan_file">
                                            <i class="fas fa-paperclip me-1"></i> Pilih Dokumen
                                        </label>
                                    </div>
                                    <div class="form-text text-muted mt-1">Format: Gambar, PDF, Word, Excel. Maks ukuran:
                                        5MB.</div>
                                    @error('lampirkan_file')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Action Buttons --}}
                                <div class="d-flex justify-content-end border-top pt-3">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-save me-1"></i> Simpan
                                    </button>
                                    <button type="button" class="btn btn-success me-2"> {{-- Tombol Kirim --}}
                                        <i class="fas fa-paper-plane me-1"></i> Kirim
                                    </button>
                                    <button type="button" class="btn btn-secondary me-2">
                                        <i class="fas fa-times me-1"></i> Batal
                                    </button>
                                    <button type="button" class="btn btn-info text-white"> {{-- Tombol Cetak --}}
                                        <i class="fas fa-print me-1"></i> Cetak
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Script untuk inisialisasi Rich Text Editor (Contoh TinyMCE) --}}
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#summernote').summernote({
                    placeholder: 'Tulis isi surat di sini...',
                    tabsize: 2,
                    height: 200,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ]
                });
            });
        </script>
    @endpush
@endsection

@extends('layouts.app') {{-- Make sure 'layouts.app' includes Bootstrap 5 and Font Awesome 6 --}}

@section('contents')
    <div class="content-wrapper">
        <div class="row justify-content-center"> {{-- Center the content slightly if it gets too wide --}}
            <div class="col-md-10 grid-margin"> {{-- Adjusted column width for better form layout --}}
                <div class="card shadow-sm border-0 rounded-lg"> {{-- Added shadow and border radius for modern look --}}
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0 text-primary">Entri Surat</h4> {{-- Styled title --}}
                        <a href="{{ route('entrisurat.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-list fa-fw me-1"></i> Daftar
                        </a> {{-- 'Daftar' button on top right --}}
                    </div>
                    <div class="card-body p-4"> {{-- Added padding --}}
                        {{-- Added enctype for file upload --}}
                        {{-- <form method="POST" action="{{ route('entrisurat.store') }}" enctype="multipart/form-data"> --}}
                            @csrf

                            {{-- NO SURAT --}}
                            <div class="form-group mb-3">
                                <label for="no_surat" class="form-label fw-bold">NO SURAT:</label>
                                <input type="text" class="form-control form-control-sm" id="no_surat" name="no_surat"
                                    placeholder="Masukkan Nomor Surat" required>
                            </div>

                            {{-- HAL --}}
                            <div class="form-group mb-3">
                                <label for="hal" class="form-label fw-bold">HAL:</label>
                                <input type="text" class="form-control form-control-sm" id="hal" name="hal"
                                    placeholder="Masukkan Perihal Surat" required>
                            </div>

                            {{-- KLASIFIKASI --}}
                            <div class="form-group mb-3">
                                <label for="klasifikasi" class="form-label fw-bold">KLASIFIKASI:</label>
                                <select class="form-select form-select-sm" id="klasifikasi" name="klasifikasi" required>
                                    <option value="">Pilih Klasifikasi</option>
                                    <option value="umum">Umum</option>
                                    <option value="rahasia">Rahasia</option>
                                    <option value="penting">Penting</option>
                                    {{-- Add more options as needed, potentially from a database loop --}}
                                </select>
                            </div>

                            {{-- KEPADA --}}
                            <div class="form-group mb-3">
                                <label for="kepada" class="form-label fw-bold">KEPADA:</label>
                                <input type="text" class="form-control form-control-sm" id="kepada" name="kepada"
                                    placeholder="Ditujukan Kepada" required>
                            </div>

                            {{-- DARI --}}
                            <div class="form-group mb-3">
                                <label for="dari" class="form-label fw-bold">DARI:</label>
                                <input type="text" class="form-control form-control-sm" id="dari" name="dari"
                                    placeholder="Dari Siapa Surat Berasal" required>
                            </div>

                            {{-- ALAMAT --}}
                            <div class="form-group mb-3">
                                <label for="alamat" class="form-label fw-bold">ALAMAT:</label>
                                <input type="text" class="form-control form-control-sm" id="alamat" name="alamat"
                                    placeholder="Alamat Pengirim/Penerima" required>
                            </div>

                            <hr class="my-4"> {{-- Separator for date and type --}}

                            {{-- TANGGAL SURAT & TANGGAL TERIMA --}}
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_surat" class="form-label fw-bold">TANGGAL SURAT:</label>
                                        <div class="input-group input-group-sm">
                                            <input type="date" class="form-control" id="tanggal_surat"
                                                name="tanggal_surat" required>
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_terima" class="form-label fw-bold">TANGGAL TERIMA:</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" id="tanggal_terima"
                                                name="tanggal_terima" value="{{ date('d/m/Y') }}" readonly>
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- JENIS SURAT --}}
                            <div class="form-group mb-3">
                                <label for="jenis_surat" class="form-label fw-bold">JENIS SURAT:</label>
                                <select class="form-select form-select-sm" id="jenis_surat" name="jenis_surat" required>
                                    <option value="surat_masuk">Surat Masuk</option>
                                    <option value="surat_keluar">Surat Keluar</option>
                                    {{-- Add more options as needed --}}
                                </select>
                            </div>

                            {{-- REFERENSI --}}
                            <div class="form-group mb-3">
                                <label for="referensi" class="form-label fw-bold">REFERENSI:</label>
                                <input type="text" class="form-control form-control-sm" id="referensi"
                                    name="referensi" placeholder="Referensi Surat (jika ada)">
                            </div>

                            {{-- SIFAT SURAT --}}
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">SIFAT SURAT:</label>
                                <div class="d-flex flex-wrap gap-3"> {{-- Use d-flex and gap for spacing radio buttons --}}
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sifat_surat"
                                            id="sifatPenting" value="Penting" required>
                                        <label class="form-check-label" for="sifatPenting">Penting</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sifat_surat"
                                            id="sifatRahasia" value="Rahasia">
                                        <label class="form-check-label" for="sifatRahasia">Rahasia</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sifat_surat"
                                            id="sifatBiasa" value="Biasa" checked> {{-- Default checked --}}
                                        <label class="form-check-label" for="sifatBiasa">Biasa</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sifat_surat"
                                            id="sifatPribadi" value="Pribadi">
                                        <label class="form-check-label" for="sifatPribadi">Pribadi</label>
                                    </div>
                                </div>
                            </div>

                            {{-- LAMPIRAN --}}
                            <div class="form-group mb-3">
                                <label for="lampiran" class="form-label fw-bold">LAMPIRAN:</label>
                                <input type="text" class="form-control form-control-sm" id="lampiran"
                                    name="lampiran" placeholder="Jumlah atau Jenis Lampiran">
                            </div>

                            {{-- ISI RINGKAS --}}
                            <div class="form-group mb-3">
                                <label for="isi_ringkas" class="form-label fw-bold">ISI RINGKAS:</label>
                                <textarea class="form-control form-control-sm" id="isi_ringkas" name="isi_ringkas" rows="3"
                                    placeholder="Ringkasan Isi Surat" required></textarea>
                            </div>

                            {{-- TEMBUSAN --}}
                            <div class="form-group mb-3">
                                <label for="tembusan" class="form-label fw-bold">TEMBUSAN:</label>
                                <input type="text" class="form-control form-control-sm" id="tembusan"
                                    name="tembusan" placeholder="Tembusan Surat (jika ada)">
                            </div>

                            {{-- UPLOAD HASIL SCAN --}}
                            <div class="form-group mb-4">
                                <label for="scan_file" class="form-label fw-bold">Upload Hasil SCAN:</label>
                                <div class="input-group input-group-sm">
                                    <input type="file" class="form-control" id="scan_file" name="scan_file"
                                        accept="image/*,.pdf">
                                    <label class="input-group-text" for="scan_file">
                                        <i class="fas fa-upload me-1"></i> Pilih File
                                    </label>
                                </div>
                                <div class="form-text text-muted mt-1">Format: Gambar (JPG, PNG) atau PDF.</div>
                            </div>

                            {{-- Action Buttons at the bottom --}}
                            <div class="d-flex justify-content-end pt-3 border-top"> {{-- Align buttons to the right --}}
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-paper-plane me-1"></i> Kirim
                                </button>
                                <button type="button" class="btn btn-info text-white me-2">
                                    <i class="fas fa-redo me-1"></i> Scan Surat
                                </button> {{-- Changed to info for better visibility --}}
                                <button type="reset" class="btn btn-secondary">
                                    <i class="fas fa-eraser me-1"></i> Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

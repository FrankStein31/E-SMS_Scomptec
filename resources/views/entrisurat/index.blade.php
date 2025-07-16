@extends('layouts.app') {{-- Pastikan 'layouts.app' memuat Bootstrap 5 & Font Awesome --}}

@section('contents')
    <div class="container-fluid mt-4">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0 text-primary fw-bold">Entri Surat [Daftar]</h4>
                <a href="{{ route('entrisurat.create') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-plus fa-fw me-1"></i> Entri Baru
                </a>
            </div>
            <div class="card-body p-3"> {{-- Padding disesuaikan untuk kontrol di atas tabel --}}
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    {{-- Left Action Buttons --}}
                    <div class="d-flex flex-wrap gap-2 mb-2 mb-md-0">
                        <button class="btn btn-primary btn-sm" title="Tambah">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </button>
                        <button class="btn btn-warning text-dark btn-sm" title="Ubah">
                            <i class="fas fa-edit me-1"></i> Ubah
                        </button>
                        <button class="btn btn-danger btn-sm" title="Hapus">
                            <i class="fas fa-trash-alt me-1"></i> Hapus
                        </button>
                        <button class="btn btn-info text-white btn-sm" title="Valid">
                            <i class="fas fa-check-circle me-1"></i> Valid
                        </button>
                        <button class="btn btn-secondary btn-sm" title="Tidak Valid">
                            <i class="fas fa-times-circle me-1"></i> Tidak Valid
                        </button>
                        <button class="btn btn-dark btn-sm" title="Baca">
                            <i class="fas fa-eye me-1"></i> Baca
                        </button>
                    </div>

                    {{-- Right Filter and Action Buttons --}}
                    <div class="d-flex flex-wrap gap-2">
                        <div class="input-group input-group-sm w-auto flex-grow-1"> {{-- Filter Input --}}
                            <span class="input-group-text bg-light"><i class="fas fa-filter"></i></span>
                            <input type="text" class="form-control" placeholder="Filter Nama :">
                        </div>
                        {{-- Buttons on the right, duplicate Tambah/Ubah/Hapus as in image --}}
                        <button class="btn btn-primary btn-sm d-none d-md-inline-block" title="Tambah">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </button>
                        <button class="btn btn-warning text-dark btn-sm d-none d-md-inline-block" title="Ubah">
                            <i class="fas fa-edit me-1"></i> Ubah
                        </button>
                        <button class="btn btn-danger btn-sm d-none d-md-inline-block" title="Hapus">
                            <i class="fas fa-trash-alt me-1"></i> Hapus
                        </button>
                    </div>
                </div>

                {{-- Table for Surat List --}}
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 5%;">No</th>
                                <th scope="col" style="width: 8%;">No Agenda</th>
                                <th scope="col" style="width: 8%;">Sifat</th>
                                <th scope="col" style="width: 10%;">Jenis</th>
                                <th scope="col" style="width: 12%;">No Surat</th>
                                <th scope="col" style="width: 15%;">Dari</th>
                                <th scope="col" style="width: 15%;">Tujuan</th>
                                <th scope="col" style="width: 15%;">Hal</th>
                                <th scope="col" style="width: 12%;">Unit Pengentri</th>
                                <th scope="col" style="width: 8%;">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Contoh data. Ganti ini dengan loop dari data surat Anda ($suratList) --}}
                            @php
                                $suratList = [
                                    (object)['no' => 1, 'no_agenda' => '17004', 'sifat' => 'Biasa', 'jenis' => 'Surat Masuk', 'no_surat' => '400.2/24/109.4/...', 'dari' => 'Dinas Pemberdayaan Perempuan Perlindungan Anak dan Kependudukan Prov Jatim', 'tujuan' => 'Asisten Administrasi Umum,', 'hal' => 'Penyelenggaraan PHI Prov Jatim Ke 96 Tahun 2024', 'unit_pengentri' => 'Sub Bagian Persuratan dan Arsip', 'tanggal' => '15/10/2024'],
                                    (object)['no' => 2, 'no_agenda' => '17003', 'sifat' => 'Biasa', 'jenis' => 'Surat Masuk', 'no_surat' => '--', 'dari' => 'PUJI ASTUTIK JI. Hanjing 448 RT 05 RW 01 Kepung Kab. Kediri Telp. 0823 3451 1250', 'tujuan' => 'Asisten Pemerintahan dan Kesejahteraan Rakyat,', 'hal' => 'Maraknya Gelaran Betle Sound', 'unit_pengentri' => 'Sub Bagian Persuratan dan Arsip', 'tanggal' => '15/10/2024'],
                                    (object)['no' => 3, 'no_agenda' => '17002', 'sifat' => 'Biasa', 'jenis' => 'Surat Masuk', 'no_surat' => '400.15.1/KL-Prov.Jatim/X/2024', 'dari' => 'Komisi Informasi Prov. Jawa Timur', 'tujuan' => 'Yth. Bp. Pj. Gubernur Jawa Timur,', 'hal' => 'Permohonan Audiensi', 'unit_pengentri' => 'Sub Bagian Persuratan dan Arsip', 'tanggal' => '15/10/2024'],
                                    (object)['no' => 4, 'no_agenda' => '17001', 'sifat' => 'Biasa', 'jenis' => 'Surat Masuk', 'no_surat' => '975/Pulo.G/2024/PN...', 'dari' => 'Pengadilan Negeri Surabaya', 'tujuan' => 'Asisten Pemerintahan dan Kesejahteraan Rakyat,', 'hal' => 'Relaas panggilan kepada Turut Tergugat II (Surat Tercatat)', 'unit_pengentri' => 'Sub Bagian Persuratan dan Arsip', 'tanggal' => '15/10/2024'],
                                    (object)['no' => 5, 'no_agenda' => '17000', 'sifat' => 'Biasa', 'jenis' => 'Surat Masuk', 'no_surat' => '100.3.1/1750/35.09...', 'dari' => 'Sekretariat Dewan Perwakilan Rakyat Daerah Kabupaten Jember', 'tujuan' => 'Biro Pemerintahan dan Otonomi Panaruma', 'hal' => 'Penyampaian Risalah Rapat Daerah', 'unit_pengentri' => 'Sub Bagian Persuratan dan Arsip', 'tanggal' => '15/10/2024'],
                                    (object)['no' => 6, 'no_agenda' => '16999', 'sifat' => 'Biasa', 'jenis' => 'Surat Masuk', 'no_surat' => '003/LM/LMG/X/2024', 'dari' => 'Lidik Media.Com', 'tujuan' => 'Asisten Perekonomian dan Pembangunan,', 'hal' => 'Permohonan Konfirmasi Terkait Diduga Lemahnya Pengawasan Pembangunan Saluran Air dan Brongrong atau Diduga Tidak Sesuai Spek', 'unit_pengentri' => 'Sub Bagian Persuratan dan Arsip', 'tanggal' => '15/10/2024'],
                                    (object)['no' => 7, 'no_agenda' => '16998', 'sifat' => 'Biasa', 'jenis' => 'Surat Masuk', 'no_surat' => '51/SRT/X/2024', 'dari' => 'Ikatan Keluarga Pensiunan Pegawai Pemerintah Provinsi Jatim', 'tujuan' => 'Pj. Sekretaris Daerah Jawa Timur, Asisten Pemerintahan dan Kesejahteraan Rakyat,', 'hal' => 'Bulletin Wreda Wara', 'unit_pengentri' => 'Sub Bagian Persuratan dan Arsip', 'tanggal' => '15/10/2024'],
                                    (object)['no' => 8, 'no_agenda' => '16997', 'sifat' => 'Biasa', 'jenis' => 'Surat Masuk', 'no_surat' => '400.7/14347/102.2/..', 'dari' => 'Dinas Kesehatan Prov. Jawa Timur', 'tujuan' => 'Asisten Administrasi Umum,', 'hal' => 'Permohonan Audiensi', 'unit_pengentri' => 'Sub Bagian Persuratan dan Arsip', 'tanggal' => '15/10/2024'],
                                    (object)['no' => 9, 'no_agenda' => '16996', 'sifat' => 'Biasa', 'jenis' => 'Surat Masuk', 'no_surat' => '800.1.2.1/8964/35..', 'dari' => 'Bupati Malang', 'tujuan' => 'Asisten Administrasi Umum', 'hal' => 'Usul Prodh Instansi u.n. Sdr.', 'unit_pengentri' => 'Sub Bagian Persuratan dan Arsip', 'tanggal' => '15/10/2024'],
                                    (object)['no' => 10, 'no_agenda' => '16995', 'sifat' => 'Biasa', 'jenis' => 'Surat Masuk', 'no_surat' => '800/17169/213.1.2/2024', 'dari' => 'Dinas Perpustakaan dan Arsip Prov. Jatim', 'tujuan' => 'Asisten Administrasi Umum', 'hal' => 'Laporan Hasil Verifikasi Data Perencanaan Kepegawaian Tahun 2024', 'unit_pengentri' => 'Sub Bagian Persuratan dan Arsip', 'tanggal' => '15/10/2024'],
                                ];
                            @endphp

                            @forelse($suratList as $surat) {{-- Ubah $suratList dengan variabel dari controller Anda --}}
                                <tr class="{{ $loop->even ? 'bg-light' : '' }}"> {{-- Baris genap lebih terang --}}
                                    <td>{{ $surat->no }}</td>
                                    <td>{{ $surat->no_agenda }}</td>
                                    <td>{{ $surat->sifat }}</td>
                                    <td>{{ $surat->jenis }}</td>
                                    <td>{{ $surat->no_surat }}</td>
                                    <td>{{ $surat->dari }}</td>
                                    <td>{{ $surat->tujuan }}</td>
                                    <td>{{ $surat->hal }}</td>
                                    <td>{{ $surat->unit_pengentri }}</td>
                                    <td>{{ $surat->tanggal }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">Data tidak ditemukan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination / Footer Area --}}
                <div class="card-footer d-flex flex-wrap justify-content-between align-items-center bg-white border-top pt-3">
                    {{-- Left Pagination Controls --}}
                    <nav aria-label="Page navigation" class="mb-2 mb-md-0">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-angle-double-left"></i></a></li>
                            <li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-angle-left"></i></a></li>
                            <li class="page-item disabled">
                                <span class="page-link text-muted">Hal 1 dari 8202</span> {{-- Ganti dengan data pagination asli --}}
                            </li>
                            <li class="page-item"><a class="page-link" href="#"><i class="fas fa-angle-right"></i></a></li>
                            <li class="page-item"><a class="page-link" href="#"><i class="fas fa-angle-double-right"></i></a></li>
                            <li class="page-item ms-2"> {{-- Tombol refresh --}}
                                <a class="page-link" href="#"><i class="fas fa-sync-alt"></i></a>
                            </li>
                        </ul>
                    </nav>

                    {{-- Right Page Info --}}
                    <div class="text-muted small">
                        Menampilkan 1 - 20 dari 164033
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
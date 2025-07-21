@php
    // Hitung child berdasarkan pola kodesatker
    $childCount = \App\Models\MasterSatker::where('kodesatker', '!=', $kodesatker)
        ->where('kodesatker', 'like', $kodesatker . '%')
        ->whereRaw('LENGTH(kodesatker) > ?', [strlen($kodesatker)])
        ->count();

    // Cek 5 data induk pertama
    $parentIds = \App\Models\MasterSatker::orderBy('created_at')->take(5)->pluck('id')->toArray();
@endphp

@if (in_array($id, $parentIds))
    <button class="btn btn-warning btn-sm" disabled>Edit</button>
    <button class="btn btn-danger btn-sm" disabled>Hapus</button>
@elseif ($childCount > 0)
    <button class="btn btn-warning btn-sm" disabled>Edit</button>
    <button class="btn btn-danger btn-sm" disabled title="Hapus child-nya dulu">Hapus</button>
@else
    <!-- Tombol Edit -->
    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
        data-bs-target="#editModal-{{ $id }}">
        Edit
    </button>
    <!-- Modal Edit -->
    <div class="modal fade" id="editModal-{{ $id }}" tabindex="-1"
        aria-labelledby="editModalLabel-{{ $id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('unitkerja.update', $id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel-{{ $id }}">Edit Unit Kerja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Unit Kerja:</label>
                            <div class="col-sm-9">
                                <input type="text" name="satker" class="form-control" value="{{ $satker }}"
                                    required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Kode Satker:</label>
                            <div class="col-sm-9">
                                <input type="text" name="kodesatker" class="form-control"
                                    value="{{ $kodesatker }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Tombol Hapus -->
    <form action="{{ route('unitkerja.destroy', $id) }}" method="POST" style="display:inline;"
        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
    </form>
@endif

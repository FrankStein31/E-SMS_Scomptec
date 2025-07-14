<?php

namespace App\Repositories;

use App\Models\SuratKeluarIsi;
use App\Models\SuratKeluarLampiran;
use App\Models\SuratKeluarRiwayat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InsertSuratKeluar
{
    function insertSuratKeluar($request)
    {
        // dd($request->all());
        return DB::transaction(function () use ($request) {
            $suratKeluarId = SuratKeluarIsi::where('id', $request['surat_keluar_isi_id'])->first()->id ?? null;
            $isRevisi = $request['isrevisi'] ?? 0;

            $userPembuat = Auth::user() ?? User::find(190);
            // dd($userPembuat->masterSatker);
            $ttdUser = User::findOrFail($request['penandatangan']);

            if ($suratKeluarId == null|| $isRevisi == 1) {

                if ($suratKeluarId == 0) {
                    $surat = SuratKeluarIsi::create([
                        'jenis_id' => $request['jenis_id'],
                        'nosurat' => $request['no_surat'],
                        'kodeklasifikasi' => $request['klasifikasi'],
                        'tgl_surat' => $request['tgl_surat'],
                        'hal' => $request['hal'],
                        'jml_lampiran' => $request['lampiran'],
                        'sifat' => $request['sifat'],
                        'kepada' => $request['kepada'],
                        'isi' => $request['isi'],
                        'tembusan' => $request['tembusan'],
                        'referensi_id' => $request['referensi'],
                        // 'ttd_id' => $request['penandatangan'],
                        'user_ttd_id' => $ttdUser->id,
                        'ttd_nama' => $ttdUser->fullname,
                        'user_id_pembuat' => $userPembuat->id,
                        'satkerid_pembuat' => $userPembuat->masterSatker->kodesatker ?? null,
                        'status' => 1,
                        'isfinal' => 1,
                        'tgl_revisi' => now(),
                    ]);
                    $revisiId = 1;
                } else {
                    $surat = SuratKeluarIsi::where('surat_keluar_isi_id', $suratKeluarId)->firstOrFail();

                    $newRevisiId = SuratKeluarIsi::where('surat_keluar_isi_id', $suratKeluarId)->max('revisi_id') + 1;

                    SuratKeluarIsi::where('surat_keluar_isi_id', $suratKeluarId)->update(['isfinal' => 0]);
                    SuratKeluarRiwayat::where('surat_keluar_isi_id', $suratKeluarId)->update(['isfinal' => 0]);

                    $surat = SuratKeluarIsi::create([
                        // 'suratkeluar_id' => $suratKeluarId,
                        'surat_keluar_isi_id' => $suratKeluarId,
                        'revisi_id' => $newRevisiId,
                        'revisi_data_id' => $newRevisiId,
                        'jenis_id' => $request['jenis_id'],
                        'nosurat' => $request['nosurat'],
                        'kodeklasifikasi' => $request['klasifikasi'],
                        'tgl_surat' => $request['tgl_surat'],
                        'hal' => $request['hal'],
                        'jml_lampiran' => $request['lampiran'],
                        'sifat' => $request['sifat'],
                        'kepada' => $request['kepada'],
                        'isi' => $request['isi'],
                        'tembusan' => $request['tembusan'],
                        'referensi_id' => $request['referensiid'],
                        'ttd_id' => $request['ttd_id'],
                        'user_ttd_id' => $ttdUser->id,
                        'ttd_nama' => $ttdUser->fullname,
                        'userid_pembuat' => $request['userid_pembuat'],
                        'user_id_pembuat' => $userPembuat->id,
                        'satkerid_pembuat' => $userPembuat->satkerid,
                        'status' => 1,
                        'isfinal' => 1,
                        'tgl_revisi' => now(),
                    ]);
                    $revisiId = $newRevisiId;
                }

                $lastId = $surat->surat_keluar_isi_id ?? $surat->id;

                $nourutRiw = SuratKeluarRiwayat::where('surat_keluar_isi_id', $lastId)->orWhere('surat_keluar_isi_id', $lastId)->max('nourut_riw') + 1;
                $nourutKirim = SuratKeluarRiwayat::where('surat_keluar_isi_id', $lastId)->orWhere('surat_keluar_isi_id', $lastId)->where('revisi_id', $revisiId)->max('nourut_kirim') + 1;

                SuratKeluarRiwayat::create([
                    // 'suratkeluar_id' => $lastId,
                    'surat_keluar_isi_id' => $lastId,
                    'revisi_id' => $revisiId,
                    'revisi_data_id' => $lastId,
                    'nourut_riw' => $nourutRiw,
                    'nourut_kirim' => $nourutKirim,
                    // 'userid_pembuat' => $request['user_id_pembuat'],
                    'user_id_pembuat' => $surat->user->id,
                    'satkerid_pembuat' => $userPembuat->satkerid,
                    'tgl_update' => now(),
                    'isfinal' => 1,
                ]);
            } else {
                $surat = SuratKeluarIsi::where('surat_keluar_isi_id', $suratKeluarId)->where('surat_keluar_isi_id', $suratKeluarId)->where('isfinal', 1)->firstOrFail();
                $surat->update([
                    'jenis_id' => $request['jenis_id'],
                    'nosurat' => $request['no_surat'],
                    'kodeklasifikasi' => $request['klasifikasi'],
                    'tgl_surat' => $request['tgl_surat'],
                    'hal' => $request['hal'],
                    'jml_lampiran' => $request['lampiran'],
                    'sifat' => $request['sifat'],
                    'kepada' => $request['kepada'],
                    'isi' => $request['isi'],
                    'tembusan' => $request['tembusan'],
                    'referensi_id' => $request['referensi'],
                    // 'ttd_id' => $request['ttd_id'],
                    'user_ttd_id' => $ttdUser->id,
                    'ttd_nama' => $ttdUser->fullname,
                    // 'userid_pembuat' => $request['userid_pembuat'],
                    'user_id_pembuat' => $surat->user_id_pembuat,
                    'satkerid_pembuat' => $userPembuat->satkerid,
                    'tgl_revisi' => now(),
                ]);

                SuratKeluarRiwayat::where('surat_keluar_isi_id', $suratKeluarId)
                    ->where('isfinal', 1)
                    ->update([
                        'tgl_update' => now(),
                        'satkerid_pembuat' => $userPembuat->satkerid,
                    ]);

                $revisiId = $surat->revisi_id;

                SuratKeluarLampiran::where('surat_keluar_isi_id', $suratKeluarId)->where('revisi_data_id', $revisiId)->delete();
            }

            if (!empty($request['lampiran_file'])) {
                foreach ($request['lampiran_file'] as $index => $fileOriginal) {
                    if (!empty($fileOriginal)) {
                        SuratKeluarLampiran::create([
                            // 'suratkeluar_id' => $surat->surat_keluar_isi_id ?? $surat->id,
                            'surat_keluar_isi_id' => $surat->surat_keluar_isi_id ?? $surat->id,
                            'revisi_id' => $revisiId,
                            'revisi_data_id' => $surat->surat_keluar_isi_id ?? $surat->id,
                            'nama_lapiran' => Storage::disk('public_uploads')->put('surat_keluar', $fileOriginal),
                            'nama_file' => $fileOriginal->getClientOriginalName(),
                            'size' => $fileOriginal->getClientOriginalName(),
                            'tgl_upload' => now(),
                        ]);
                    }
                }
            }

            return [
                'surat_keluar_isi_id' => $surat->surat_keluar_isi_id ?? $surat->id,
                'revisi_data_id' => $revisiId,
            ];
        });
    }
}

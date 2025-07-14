<?php

namespace App\Repositories;

use App\Models\SuratKeluarIsi;
use App\Models\SuratKeluarRiwayat;
use App\Models\SuratKeluarNotaDinas;
use App\Models\SuratKeluarLampiran;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InsertNotaDinas
{
    function insertNotadinas($request)
    {
        return DB::transaction(function () use ($request) {
            // dd($request->all());
            $isRevisi = $request['is_revisi'];
            $suratKeluarId = SuratKeluarIsi::where('id', $request['surat_keluar_isi_id'])->first()->id ?? null;
            $revisiId = 1;

            $userPembuat = Auth::user() ?? 190;
            $ttdUser = User::where('id', $request['penandatangan'])->first();
            $satkerIdPembuat = $userPembuat->satkerid ?? null;
            $ttdNama = $ttdUser->fullname ?? null;

            $kepada = "";
            foreach ($request['kepada'] as $key => $value) {
                $user = User::find($value);
                $kepada .= $user->fullname . ",";
            }

            // dd($suratKeluarId);

            if ($suratKeluarId == 0 || $isRevisi == 1) {
                if ($suratKeluarId == 0) {
                    $suratKeluarId = (SuratKeluarIsi::max('suratkeluar_id') ?? 0) + 1;
                } else {
                    $revisiId = (SuratKeluarIsi::where('surat_keluar_isi_id', $suratKeluarId)->max('revisi_id') ?? 0) + 1;

                    SuratKeluarIsi::where('surat_keluar_isi_id', $suratKeluarId)->update(['isfinal' => 0]);
                    SuratKeluarRiwayat::where('surat_keluar_isi_id', $suratKeluarId)->update(['isfinal' => 0]);
                }

                // dd($revisiId);

                // Insert ke suratkeluar_isi
                $surat = SuratKeluarIsi::create([
                    'suratkeluar_id'     => $suratKeluarId,
                    // 'surat_keluar_isi_id' => $suratKeluarId ?? null,
                    'revisi_id'          => $revisiId,
                    // 'revisi_data_id'     => $suratKeluarId ?? null,
                    'tgl_revisi'         => now(),
                    'jenis_id'           => 2,
                    'nosurat'            => $request['no_surat'],
                    'kodeklasifikasi'    => $request['kodeklasifikasi'],
                    'tgl_surat'          => $request['tgl_surat'],
                    'hal'                => $request['hal'],
                    'jml_lampiran'       => $request['lampiran'],
                    'sifat'              => $request['sifat'],
                    'kepada'             => $kepada,
                    'isi'                => $request['isi'],
                    'tembusan'           => $request['tembusan'],
                    'referensi_id'       => $request['referensiid'],
                    'ttd_id'             => $request['ttd_id'],
                    'user_ttd_id'        => $ttdUser->id,
                    'ttd_nama'           => $ttdNama,
                    // 'userid_pembuat'     => $userPembuat->id,
                    'user_id_pembuat' => $userPembuat->id,
                    'satkerid_pembuat'   => $satkerIdPembuat,
                    'status'             => 1,
                    'isfinal'            => 1,
                ]);

                // Insert ke riwayat
                $nourutRiw = (SuratKeluarRiwayat::where('surat_keluar_isi_id', $suratKeluarId)->max('nourut_riw') ?? 0) + 1;
                $nourutKirim = (SuratKeluarRiwayat::where('surat_keluar_isi_id', $suratKeluarId)->where('revisi_id', $revisiId)->max('nourut_kirim') ?? 0) + 1;

                SuratKeluarRiwayat::create([
                    // 'suratkeluar_id'   => $suratKeluarId,
                    // 'surat_keluar_isi_id'   => $suratKeluarId,
                    'revisi_id'        => $revisiId,
                    // 'revisi_id'        => $revisiId,
                    'nourut_riw'       => $nourutRiw,
                    'nourut_kirim'     => $nourutKirim,
                    // 'userid_pembuat'   => $userPembuat->id,
                    'user_id_pembuat'   => $userPembuat->id,
                    'satkerid_pembuat' => $satkerIdPembuat,
                    'tgl_update'       => now(),
                    'isfinal'          => 1,
                ]);
            } else {
                // Update suratkeluar_isi
                SuratKeluarIsi::where('surat_keluar_isi_id', $suratKeluarId)->where('isfinal', 1)->update([
                    'tgl_revisi'       => now(),
                    'nosurat'          => $request['no_surat'],
                    'kodeklasifikasi'  => $request['kodeklasifikasi'],
                    'tgl_surat'        => $request['tgl_surat'],
                    'hal'              => $request['hal'],
                    'jml_lampiran'     => $request['lampiran'],
                    'sifat'            => $request['sifat'],
                    'kepada'           => $kepada,
                    'isi'              => $request['isi'],
                    'tembusan'         => $request['tembusan'],
                    'referensi_id'     => $request['referensiid'],
                    // 'ttd_id'           => $request['ttd_id'],
                    'user_ttd_id'      => $ttdUser->id,
                    'ttd_nama'         => $ttdNama,
                    'userid_pembuat'   => $userPembuat->id,
                    'user_id_pembuat'   => $userPembuat->id,
                    'satkerid_pembuat' => $satkerIdPembuat,
                ]);

                // Update riwayat
                SuratKeluarRiwayat::where('surat_keluar_isi_id', $suratKeluarId)->where('isfinal', 1)->update([
                    'tgl_update' => now(),
                    'satkerid_pembuat' => $satkerIdPembuat
                ]);

                $revisiId = SuratKeluarIsi::where('surat_keluar_isi_id', $suratKeluarId)->where('isfinal', 1)->value('revisi_id');

                SuratKeluarLampiran::where('surat_keluar_isi_id', $suratKeluarId)->where('revisi_id', $revisiId)->delete();
                SuratKeluarNotaDinas::where('surat_keluar_isi_id', $suratKeluarId)->where('revisi_id', $revisiId)->delete();
            }

            // dd($surat);
            // Handle tujuan
            // $tujuanList = explode(',', $request['kepada']);
            foreach ($request['kepada'] as $tujuan) {
                $tujuanUser = User::where('id', $tujuan)->first();
                SuratKeluarNotaDinas::create([
                    // 'suratkeluar_id'   => $suratKeluarId,
                    'surat_keluar_isi_id' => $surat->id,
                    'revisi_id'        => $revisiId,
                    'nourut_riw'       => $nourutRiw,
                    // 'revisi_data_id'        => $revisiId,
                    // 'userid_tujuan'    => $tujuan,
                    // 'userid_tujuan'    => $tujuan,
                    'userid_pembuat'   => $userPembuat->id,
                    'user_id_pembuat'    => $tujuanUser->id,
                    'user_id_tujuan'    => $tujuanUser->id,
                    'satkerid_pembuat'    => $tujuanUser->masterSatker->kodesatker,
                    'satkerid_tujuan'  => $tujuanUser->satkerid ?? null,
                ]);
            }

            if (!empty($request['lampiran_file'])) {
                foreach ($request['lampiran_file'] as $index => $fileOriginal) {
                    if (!empty($fileOriginal)) {
                        SuratKeluarLampiran::create([
                            // 'suratkeluar_id' => $suratKeluarId,
                            'surat_keluar_isi_id' => $surat->id ?? $suratKeluarId,
                            // 'revisi_id' => $revisiId,
                            'revisi_data_id' => $surat->id,
                            'nama_lapiran' => Storage::disk('public_uploads')->put('surat_keluar', $fileOriginal),
                            'nama_file' => $fileOriginal->getClientOriginalName(),
                            'size' => $fileOriginal->getClientOriginalName(),
                            'tgl_upload' => now(),
                        ]);
                    }
                }
            }

            return [
                'surat_keluar_isi_id' => $suratKeluarId,
                'revisi_data_id' => $revisiId,
            ];
        });
    }
}

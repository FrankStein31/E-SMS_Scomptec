<?php

if (!function_exists('sifatSurat')) {
    function sifatSurat($sifat)
    {
        switch ($sifat) {
            case 1:
                return 'Penting';
            case 2:
                return 'Rahasia';
            case 3:
                return 'Biasa';
            case 4:
                return 'Pribadi';
            default:
                return 'Tidak Diketahui';
        }
    }
}

if (!function_exists('getListTahun')) {
    function getListTahun($start = 2015) {
        $now = (int)date('Y');
        $tahun = [];
        for ($i = $now; $i >= $start; $i--) {
            $tahun[] = $i;
        }
        return $tahun;
    }
} 
<?php

if (!function_exists('sifatSurat')) {
    function sifatSurat($angka)
    {
        switch ($angka) {
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

if (! function_exists('getListTahun')) {
	function getListTahun()
	{
		$x = intval(date("Y"));
		$len = 2;
		$lang = 10;
		$tahun = [];

		for ($i = ($x - $len); $i <= ($x + $lang); $i++) {
			$tahun[$i] = $i;
		}

		return $tahun;
	}
}
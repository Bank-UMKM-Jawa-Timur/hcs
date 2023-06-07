<?php

namespace App\Enum;

enum KategoriPenonaktifan: string
{
    case RESIGN = 'Mengundurkan Diri';
    case PENSIUN = 'Pensiun';
    case MENINGGAL = 'Meninggal Dunia';
    case PHK = 'Pemutusan Hubungan Kerja (PHK)';
    case TIDAKDIPERPANJANG = 'Tidak Diperpanjang';
    case KONTRAKPERPANJANGAN = 'Kontrak Perpanjangan';
}

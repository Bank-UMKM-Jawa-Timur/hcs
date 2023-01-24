<?php

namespace App\Enum;

enum KategoriPenonaktifan: string
{
    case RESIGN = 'Resign';
    case PENSIUN = 'Pensiun';
    case MENINGGAL = 'Meninggal Dunia';
    case PHK = 'Pemutusan Hubungan Kerja (PHK)';
}

<?php

namespace App\Enum;

enum StatusKaryawan:string
{
    case TETAP = 'Tetap';
    case IKJP = 'IKJP';
    case KP = 'Kontrak Perpanjangan';
    case NONAKTIF = 'Nonaktif';
}
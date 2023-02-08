<?php

namespace App\Enum;

enum BackupType: string
{
    case BACKUPS = 'backups';
    case ROLLBACKS = 'rollbacks';
}

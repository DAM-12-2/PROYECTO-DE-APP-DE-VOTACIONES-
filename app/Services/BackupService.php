<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class BackupService
{
    public function downloadBackup(): ?string
    {
    }

    public function restoreBackup(UploadedFile $file): array
    {
    }
}

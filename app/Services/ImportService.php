<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImportService
{
    private FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
    }

    public function importFromExcel(UploadedFile $file): array
    {
    }

    public function importFromCsv(UploadedFile $file): array
    {
    }

    public function isPadronBlocked(): bool
    {
    }
}

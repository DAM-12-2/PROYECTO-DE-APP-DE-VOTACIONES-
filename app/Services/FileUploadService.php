<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    public function uploadPartyImage(UploadedFile $file, string $prefix): string
    {
        $filename = $prefix . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('img/logos'), $filename);

        return 'img/logos/' . $filename;
    }

    public function uploadInstitutionLogo(UploadedFile $file): string
    {
        $filename = 'institution_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('img/logos'), $filename);

        return 'img/logos/' . $filename;
    }
}

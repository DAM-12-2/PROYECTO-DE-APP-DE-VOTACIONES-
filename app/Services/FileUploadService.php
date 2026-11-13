<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class FileUploadService
{
    public function uploadPartyImage(UploadedFile $file, string $prefix): string
    {
        $directorio = public_path('img/logos');
        File::ensureDirectoryExists($directorio);

        $extension = strtolower($file->extension() ?: $file->getClientOriginalExtension());
        $filename = $prefix . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $file->move($directorio, $filename);

        return 'img/logos/' . $filename;
    }

    public function uploadPartyImageContents(string $contents, string $prefix, string $extension): string
    {
        $directorio = public_path('img/logos');
        File::ensureDirectoryExists($directorio);

        $extension = strtolower($extension) ?: 'png';
        $filename = $prefix . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        File::put($directorio . DIRECTORY_SEPARATOR . $filename, $contents);

        return 'img/logos/' . $filename;
    }

    public function uploadInstitutionLogo(UploadedFile $file): string
    {
        $directorio = public_path('img/logos');
        File::ensureDirectoryExists($directorio);

        $filename = 'institution_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $file->getClientOriginalExtension();
        $file->move($directorio, $filename);

        return 'img/logos/' . $filename;
    }
}

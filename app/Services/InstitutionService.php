<?php

namespace App\Services;

use App\DTOs\InstitutionSettings;

class InstitutionService
{
    private const CACHE_KEY = 'institution_settings';
    private const CACHE_TTL = 3600;

    public function getSettings(): InstitutionSettings
    {
    }

    public function invalidateCache(): void
    {
    }

    public function getSetting(string $nombre, mixed $default = null): ?string
    {
    }

    public function setSetting(string $nombre, mixed $detalle): \App\Models\Setting
    {
    }

    public function getMultipleSettings(array $names): array
    {
    }
}

<?php

namespace App\Services;

use App\DTOs\InstitutionSettings;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class InstitutionService
{
    private const CACHE_KEY = 'institution_settings';
    private const CACHE_TTL = 3600;

    public function getSettings(): InstitutionSettings
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            $settings = Setting::whereIn('nombre', [
                'institucion_nombre',
                'institucion_logo',
            ])->pluck('detalle', 'nombre')->toArray();

            return InstitutionSettings::fromQuery($settings);
        });
    }

    public function invalidateCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function getSetting(string $nombre, mixed $default = null): ?string
    {
        return Setting::where('nombre', $nombre)->value('detalle') ?? $default;
    }

    public function setSetting(string $nombre, mixed $detalle): Setting
    {
        $setting = Setting::firstOrCreate(
            ['nombre' => $nombre],
            ['detalle' => $detalle]
        );
        $setting->update(['detalle' => $detalle]);
        $this->invalidateCache();
        return $setting;
    }

    public function getMultipleSettings(array $names): array
    {
        return Setting::whereIn('nombre', $names)
            ->pluck('detalle', 'nombre')
            ->toArray();
    }
}

<?php

namespace App\DTOs;

class InstitutionSettings
{
    public function __construct(
        public readonly string $nombre,
        public readonly ?string $logoPath,
    ) {
    }

    public static function fromQuery(array $settings): self
    {
        return new self(
            nombre: $settings['institucion_nombre'] ?? 'Institución',
            logoPath: $settings['institucion_logo'] ?? null,
        );
    }
}

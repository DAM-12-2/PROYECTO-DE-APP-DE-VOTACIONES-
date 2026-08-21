<?php

namespace App\Helpers;

class NumeroALetras
{
    private static $unidades = [
        '', 'un', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve',
        'diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciséis',
        'diecisiete', 'dieciocho', 'diecinueve', 'veinte', 'veintiún',
        'veintidós', 'veintitrés', 'veinticuatro', 'veinticinco', 'veintiséis',
        'veintisiete', 'veintiocho', 'veintinueve',
    ];

    private static $decenas = [
        '', 'diez', 'veinte', 'treinta', 'cuarenta', 'cincuenta',
        'sesenta', 'setenta', 'ochenta', 'noventa',
    ];

    private static $centenas = [
        '', 'ciento', 'doscientos', 'trescientos', 'cuatrocientos',
        'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos',
    ];

    public static function convertir($numero): string
    {
        $numero = (int) $numero;

        if ($numero === 0) return 'cero';
        if ($numero === 100) return 'cien';

        $resultado = '';

        if ($numero >= 1000) {
            $miles = (int) ($numero / 1000);
            $resultado = ($miles === 1 ? 'mil' : self::convertir($miles) . ' mil');
            $numero %= 1000;
            if ($numero > 0) $resultado .= ' ';
        }

        if ($numero >= 100) {
            $cent = (int) ($numero / 100);
            $resultado .= self::$centenas[$cent];
            $numero %= 100;
            if ($numero > 0) $resultado .= ' ';
        }

        if ($numero >= 30) {
            $dec = (int) ($numero / 10);
            $resultado .= self::$decenas[$dec];
            $numero %= 10;
            if ($numero > 0) $resultado .= ' y ';
        } elseif ($numero >= 20) {
            $resultado .= self::$unidades[$numero];
            $numero = 0;
        }

        if ($numero > 0 && $numero < 20) {
            $resultado .= self::$unidades[$numero];
        }

        return trim($resultado);
    }
}
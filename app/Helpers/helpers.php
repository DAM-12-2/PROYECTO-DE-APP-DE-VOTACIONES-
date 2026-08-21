<?php

if (!function_exists('num2letras')) {
    function num2letras(int $numero): string
    {
        return \App\Helpers\NumeroALetras::convertir($numero);
    }
}

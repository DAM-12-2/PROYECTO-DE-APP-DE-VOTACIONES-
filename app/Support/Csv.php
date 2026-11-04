<?php

namespace App\Support;

class Csv
{
    /**
     * Neutraliza fórmulas al abrir el CSV en Excel/LibreOffice.
     */
    public static function sanitize(mixed $value): string
    {
        $value = (string) ($value ?? '');
        $value = str_replace(["\r", "\n", "\t"], ' ', $value);

        if ($value !== '' && preg_match('/^[=+\-@]/', ltrim($value))) {
            $value = "'" . $value;
        }

        return $value;
    }

    public static function download(string $filename, array $headers, iterable $rows)
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, array_map([self::class, 'sanitize'], $headers));

            foreach ($rows as $row) {
                fputcsv($out, array_map([self::class, 'sanitize'], $row));
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'no-store',
        ]);
    }
}

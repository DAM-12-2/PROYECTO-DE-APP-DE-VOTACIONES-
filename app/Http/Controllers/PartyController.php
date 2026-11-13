<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePartyRequest;
use App\Http\Requests\UpdatePartyRequest;
use App\Models\Party;
use App\Services\BitacoraService;
use App\Services\FileUploadService;
use Exception;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use ZipArchive;

class PartyController extends Controller
{
    private BitacoraService $bitacoraService;
    private FileUploadService $fileUploadService;

    public function __construct(BitacoraService $bitacoraService, FileUploadService $fileUploadService)
    {
        $this->bitacoraService = $bitacoraService;
        $this->fileUploadService = $fileUploadService;
    }

    public function index(Request $request)
    {
        try {
            $parties = Party::all();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $parties,
                ], 200);
            }

            return view('admin.parties', compact('parties'));
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cargar los partidos: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withErrors('Error al cargar los partidos: ' . $e->getMessage());
        }
    }

    public function store(StorePartyRequest $request)
    {
        try {
            $data = $request->validated();

            foreach (['bandera', 'fotopresidente'] as $campo) {
                if ($request->hasFile($campo)) {
                    $data[$campo] = $this->fileUploadService->uploadPartyImage($request->file($campo), $campo);
                }
            }

            $party = Party::create($data);

            $this->bitacoraService->registrar('Creación de partido', 'Se creó un nuevo partido ID: ' . $party->id);

            return redirect()->route('admin.parties')->with('success', 'Partido creado exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al crear el partido: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $party = Party::findOrFail($id);

            return view('admin.parties_edit', compact('party'));
        } catch (Exception $e) {
            return redirect()->route('admin.parties')->withErrors('Partido no encontrado: ' . $e->getMessage());
        }
    }

    public function update(UpdatePartyRequest $request, $id)
    {
        try {
            $party = Party::findOrFail($id);
            $data = $request->validated();

            foreach (['bandera', 'fotopresidente'] as $campo) {
                if ($request->hasFile($campo)) {
                    $anterior = public_path((string) $party->{$campo});
                    if ($party->{$campo} && is_file($anterior)) {
                        @unlink($anterior);
                    }

                    $data[$campo] = $this->fileUploadService->uploadPartyImage($request->file($campo), $campo);
                }
            }

            $party->update($data);

            $this->bitacoraService->registrar('Actualización de partido', 'Se actualizó el partido ID: ' . $party->id);

            return redirect()->route('admin.parties')->with('success', 'Partido actualizado exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al actualizar el partido: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $party = Party::findOrFail($id);
            $party->delete();

            $this->bitacoraService->registrar('Eliminación de partido', 'Se eliminó el partido ID: ' . $party->id);

            return redirect()->route('admin.parties')->with('success', 'Partido eliminado exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al eliminar el partido: ' . $e->getMessage());
        }
    }

    public function importPhotos(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|file|mimes:zip|max:20480',
        ]);

        $zip = new ZipArchive();
        if ($zip->open($request->file('zip_file')->getRealPath()) !== true) {
            return redirect()->route('admin.parties')->with('error', 'No se pudo abrir el archivo ZIP.');
        }

        if ($zip->numFiles > 120) {
            $zip->close();
            return redirect()->route('admin.parties')->with('error', 'El ZIP contiene demasiados archivos. El límite es 120.');
        }

        $entradas = [];
        $manifiesto = null;
        $errores = [];
        $ignorados = 0;
        $totalDescomprimido = 0;

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            if (!$stat || str_ends_with($stat['name'], '/')) {
                continue;
            }

            $nombre = basename($stat['name']);
            $extension = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

            if (in_array($extension, ['csv', 'xls', 'xlsx'], true) && str_starts_with(strtolower($nombre), 'manifest')) {
                $contenido = $zip->getFromIndex($i);
                if ($contenido !== false) {
                    $manifiesto = ['contenido' => $contenido, 'extension' => $extension];
                }
                continue;
            }

            if (!in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
                $ignorados++;
                continue;
            }

            if ((int) ($stat['size'] ?? 0) > 2 * 1024 * 1024) {
                $errores[] = $nombre . ': supera los 2 MB.';
                continue;
            }

            $contenido = $zip->getFromIndex($i);
            if ($contenido === false) {
                $errores[] = $nombre . ': no se pudo leer.';
                continue;
            }

            $info = @getimagesizefromstring($contenido);
            if (!$info || !in_array($info['mime'] ?? '', ['image/jpeg', 'image/png'], true)) {
                $errores[] = $nombre . ': no es una imagen JPG o PNG válida.';
                continue;
            }

            $totalDescomprimido += strlen($contenido);
            if ($totalDescomprimido > 40 * 1024 * 1024) {
                $zip->close();
                return redirect()->route('admin.parties')->with('error', 'El contenido descomprimido supera los 40 MB.');
            }

            $entradas[$nombre] = [
                'contenido' => $contenido,
                'extension' => $info['mime'] === 'image/png' ? 'png' : 'jpg',
            ];
        }

        $zip->close();

        try {
            $instrucciones = $manifiesto
                ? $this->leerManifiestoFotos($manifiesto['contenido'], $manifiesto['extension'])
                : $this->inferirInstruccionesFotos($entradas, $ignorados);
        } catch (\Throwable $e) {
            return redirect()->route('admin.parties')->with('error', 'No se pudo leer el manifiesto: ' . $e->getMessage());
        }

        $actualizados = 0;
        foreach ($instrucciones as $instruccion) {
            $archivo = basename((string) ($instruccion['archivo'] ?? ''));
            $siglas = trim((string) ($instruccion['siglas'] ?? ''));
            $tipo = strtolower(trim((string) ($instruccion['tipo'] ?? '')));

            if ($archivo === '' || $siglas === '' || !in_array($tipo, ['bandera', 'presidente'], true)) {
                $errores[] = 'Fila de manifiesto incompleta.';
                continue;
            }

            if (!isset($entradas[$archivo])) {
                $errores[] = $archivo . ': no está dentro del ZIP o no es una imagen válida.';
                continue;
            }

            $party = Party::whereRaw('LOWER(siglas) = ?', [mb_strtolower($siglas)])->first();
            if (!$party) {
                $errores[] = $siglas . ': partido no encontrado.';
                continue;
            }

            $campo = $tipo === 'bandera' ? 'bandera' : 'fotopresidente';
            $rutaAnterior = $party->{$campo} ? public_path((string) $party->{$campo}) : null;

            $party->{$campo} = $this->fileUploadService->uploadPartyImageContents(
                $entradas[$archivo]['contenido'],
                $campo,
                $entradas[$archivo]['extension']
            );
            $party->save();

            if ($rutaAnterior && is_file($rutaAnterior)) {
                @unlink($rutaAnterior);
            }

            $actualizados++;
        }

        $mensaje = "Fotos actualizadas: {$actualizados}. Ignoradas: {$ignorados}. Errores: " . count($errores) . '.';

        $this->bitacoraService->registrar(
            'Importación masiva de fotos de partidos',
            "Actualizadas: {$actualizados}. Errores: " . count($errores)
        );

        if ($errores !== []) {
            return redirect()->route('admin.parties')->with('error', $mensaje)->with('photo_import_errors', array_slice($errores, 0, 8));
        }

        return redirect()->route('admin.parties')->with('success', $mensaje);
    }

    private function inferirInstruccionesFotos(array $entradas, int &$ignorados): array
    {
        $instrucciones = [];

        foreach (array_keys($entradas) as $nombre) {
            if (preg_match('/^(.+?)[_-](bandera|presidente)\.(jpe?g|png)$/i', $nombre, $partes)) {
                $instrucciones[] = [
                    'archivo' => $nombre,
                    'siglas' => $partes[1],
                    'tipo' => strtolower($partes[2]),
                ];
            } else {
                $ignorados++;
            }
        }

        return $instrucciones;
    }

    private function leerManifiestoFotos(string $contenido, string $extension): array
    {
        if ($extension === 'csv') {
            $contenido = preg_replace('/^\xEF\xBB\xBF/', '', $contenido);
            $primeraLinea = strtok($contenido, "\r\n") ?: '';
            $delimitador = substr_count($primeraLinea, ';') > substr_count($primeraLinea, ',') ? ';' : ',';

            $handle = fopen('php://temp', 'r+');
            fwrite($handle, $contenido);
            rewind($handle);

            $filas = [];
            while (($fila = fgetcsv($handle, 0, $delimitador)) !== false) {
                $filas[] = $fila;
            }
            fclose($handle);

            return $this->mapearManifiestoFotos($filas);
        }

        $temporal = tempnam(sys_get_temp_dir(), 'manifiesto_fotos_') . '.' . $extension;
        file_put_contents($temporal, $contenido);

        try {
            $filas = IOFactory::load($temporal)->getActiveSheet()->toArray(null, true, true, false);
        } finally {
            @unlink($temporal);
        }

        return $this->mapearManifiestoFotos($filas);
    }

    private function mapearManifiestoFotos(array $filas): array
    {
        foreach (array_slice($filas, 0, 10, true) as $indice => $fila) {
            $encabezados = array_map(
                fn ($valor) => preg_replace('/[^a-z0-9]/', '', strtolower(trim((string) $valor))) ?? '',
                (array) $fila
            );

            $archivo = array_search('archivo', $encabezados, true);
            $siglas = array_search('siglas', $encabezados, true);
            $tipo = array_search('tipo', $encabezados, true);

            if ($archivo !== false && $siglas !== false && $tipo !== false) {
                $resultado = [];
                foreach (array_slice($filas, $indice + 1, null, true) as $filaDatos) {
                    $filaDatos = (array) $filaDatos;
                    $resultado[] = [
                        'archivo' => (string) ($filaDatos[$archivo] ?? ''),
                        'siglas' => (string) ($filaDatos[$siglas] ?? ''),
                        'tipo' => (string) ($filaDatos[$tipo] ?? ''),
                    ];
                }

                return $resultado;
            }
        }

        return [];
    }
}

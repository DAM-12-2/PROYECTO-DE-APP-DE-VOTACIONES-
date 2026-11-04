<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Events\StudentUpdated;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        $busqueda = trim((string) $request->input('q'));
        if ($busqueda !== '') {
            $query->where(function ($q) use ($busqueda) {
                $q->where('identificacion', 'like', "%{$busqueda}%")
                    ->orWhere('nombre', 'like', "%{$busqueda}%")
                    ->orWhere('apellidos', 'like', "%{$busqueda}%");
            });
        }

        $students = $query->orderBy('apellidos')->orderBy('nombre')->paginate(20)->withQueryString();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $students->items(),
            ], 200);
        }

        return view('admin.students', compact('students', 'busqueda'));
    }

    public function store(StoreStudentRequest $request)
    {
        $student = Student::create($request->validated());
        broadcast(new StudentUpdated($student));

        return back()->with('success', 'Estudiante registrado exitosamente.');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('admin.students_edit', compact('student'));
    }

    public function update(UpdateStudentRequest $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->update($request->validated());
        broadcast(new StudentUpdated($student));

        return back()->with('success', 'Estudiante actualizado exitosamente.');
    }

    public function destroy($id)
    {
        Student::findOrFail($id)->delete();
        return back()->with('success', 'Estudiante eliminado exitosamente.');
    }

    public function desbloquear(Request $request, $id, \App\Services\BitacoraService $bitacora)
    {
        $estudiante = Student::findOrFail($id);

        if (!$estudiante->voto) {
            return back()->with('error', 'Este estudiante no ha votado; no requiere desbloqueo.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($estudiante) {
            $estudiante->voto = false;
            $estudiante->save();
            \App\Models\Vote::where('student_id', $estudiante->id)->delete();
        });

        $bitacora->registrar('desbloqueo de estudiante', $estudiante->identificacion);

        return back()->with('success', "Se desbloqueó a {$estudiante->nombre} {$estudiante->apellidos} y se anuló su voto.");
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt,xls,xlsx|max:10240',
        ]);

        $file = $request->file('csv_file');
        $extension = strtolower((string) $file->getClientOriginalExtension());

        try {
            $filas = in_array($extension, ['xls', 'xlsx'], true)
                ? $this->leerExcel($file->getRealPath())
                : $this->leerCsv($file->getRealPath());
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo leer el archivo. Verifique que sea un CSV o Excel válido.',
            ], 422);
        }

        $columnas = $this->mapearColumnas($filas);

        $insertados = 0;
        $rechazados = 0;
        $limite = 10000;

        foreach ($filas as $indice => $fila) {
            if ($indice <= $columnas['fila']) {
                continue;
            }

            if ($insertados + $rechazados >= $limite) {
                break;
            }

            $fila = array_map(fn ($valor) => is_scalar($valor) ? (string) $valor : '', (array) $fila);

            if (trim(implode('', $fila)) === '') {
                continue;
            }

            $identificacion = $this->limpiar($this->celda($fila, $columnas['cedula']), 20, true);
            $nombre = $this->limpiar($this->celda($fila, $columnas['nombre']), 80);

            if ($columnas['apellidos'] !== null) {
                $apellidos = $this->limpiar($this->celda($fila, $columnas['apellidos']), 80);
            } else {
                $apellidos = $this->limpiar(trim(
                    $this->celda($fila, $columnas['apellido1']) . ' ' . $this->celda($fila, $columnas['apellido2'])
                ), 80);
            }

            $seccion = $this->limpiar($this->celda($fila, $columnas['seccion']), 10);

            if ($identificacion === '' || $nombre === '') {
                $rechazados++;
                continue;
            }

            Student::updateOrCreate(
                ['identificacion' => $identificacion],
                [
                    'nombre' => $nombre,
                    'apellidos' => $apellidos,
                    'seccion' => $seccion !== '' ? $seccion : null,
                ]
            );

            $insertados++;
        }

        return response()->json([
            'success' => true,
            'message' => "Se procesaron {$insertados} estudiantes. Filas rechazadas: {$rechazados}.",
            'insertados' => $insertados,
            'rechazados' => $rechazados,
        ], 200);
    }

    private function leerCsv(string $ruta): array
    {
        $contenido = (string) file_get_contents($ruta);

        if (!mb_check_encoding($contenido, 'UTF-8')) {
            $contenido = mb_convert_encoding($contenido, 'UTF-8', 'Windows-1252');
        }

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

        return $filas;
    }

    private function leerExcel(string $ruta): array
    {
        return IOFactory::load($ruta)->getActiveSheet()->toArray(null, true, true, false);
    }

    private function mapearColumnas(array $filas): array
    {
        $porDefecto = [
            'fila' => -1,
            'cedula' => 0,
            'nombre' => 1,
            'apellidos' => 2,
            'apellido1' => null,
            'apellido2' => null,
            'seccion' => 3,
        ];

        foreach (array_slice($filas, 0, 10, true) as $indice => $fila) {
            $encabezados = array_map(fn ($valor) => $this->normalizar((string) $valor), (array) $fila);

            $cedula = $this->buscarColumna($encabezados, ['cedula', 'identificacion', 'cedulaidentidad', 'documento']);
            $nombre = $this->buscarColumna($encabezados, ['nombre', 'nombres', 'nombrecompleto']);

            if ($cedula === null || $nombre === null) {
                continue;
            }

            return [
                'fila' => $indice,
                'cedula' => $cedula,
                'nombre' => $nombre,
                'apellidos' => $this->buscarColumna($encabezados, ['apellidos', 'apellido']),
                'apellido1' => $this->buscarColumna($encabezados, ['primerapellido', 'apellido1', 'apellidopaterno']),
                'apellido2' => $this->buscarColumna($encabezados, ['segundoapellido', 'apellido2', 'apellidomaterno']),
                'seccion' => $this->buscarColumna($encabezados, ['seccion', 'grupo', 'nivel']) ?? -1,
            ];
        }

        return $porDefecto;
    }

    private function buscarColumna(array $encabezados, array $nombres): ?int
    {
        foreach ($nombres as $nombre) {
            $posicion = array_search($nombre, $encabezados, true);

            if ($posicion !== false) {
                return $posicion;
            }
        }

        return null;
    }

    private function normalizar(string $valor): string
    {
        $valor = strtolower(trim($valor));
        $valor = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü'], ['a', 'e', 'i', 'o', 'u', 'n', 'u'], $valor);

        return preg_replace('/[^a-z0-9]/', '', $valor) ?? '';
    }

    private function celda(array $fila, ?int $indice): string
    {
        return ($indice === null || $indice < 0 || !array_key_exists($indice, $fila)) ? '' : (string) $fila[$indice];
    }

    private function limpiar(string $valor, int $max, bool $soloDigitos = false): string
    {
        $valor = trim($valor);
        $valor = str_replace("\xEF\xBB\xBF", '', $valor);

        if (!mb_check_encoding($valor, 'UTF-8')) {
            $valor = mb_convert_encoding($valor, 'UTF-8', 'Windows-1252');
        }

        $valor = strip_tags($valor);
        $valor = preg_replace('/\s+/', ' ', $valor);

        if ($soloDigitos) {
            $valor = preg_replace('/\D/', '', $valor);
        }

        return mb_substr(trim($valor), 0, $max);
    }

    public function export()
    {
        $fileName = 'padron_' . now()->format('Y-m-d') . '.csv';

        return \App\Support\Csv::download(
            $fileName,
            $this->encabezadosPadron(),
            $this->filasPadron()
        );
    }

    public function exportXlsx()
    {
        $filas = $this->filasPadron();
        $total = $filas->count();
        $ultimaFila = 4 + $total;

        $spreadsheet = new Spreadsheet();
        $hoja = $spreadsheet->getActiveSheet();
        $hoja->setTitle('Padrón');
        $hoja->setShowGridLines(false);

        $logo = public_path('img/logo.png');
        if (file_exists($logo)) {
            $dibujo = new Drawing();
            $dibujo->setPath($logo);
            $dibujo->setCoordinates('A1');
            $dibujo->setOffsetX(8);
            $dibujo->setOffsetY(6);
            $dibujo->setHeight(52);
            $dibujo->setWorksheet($hoja);
        }

        $hoja->mergeCells('B1:F1');
        $hoja->setCellValue('B1', 'PADRÓN ELECTORAL');
        $hoja->getStyle('B1')->getFont()->setSize(20)->setBold(true)->setColor(new Color('FF001E40'));

        $hoja->mergeCells('B2:F2');
        $hoja->setCellValue('B2', 'CTP Ambientalista Isaías Retana Arias');
        $hoja->getStyle('B2')->getFont()->setSize(11)->setColor(new Color('FF555555'));

        $hoja->mergeCells('B3:F3');
        $hoja->setCellValue('B3', 'Generado el ' . now()->format('d/m/Y H:i') . '  ·  Total: ' . $total . ' estudiantes');
        $hoja->getStyle('B3')->getFont()->setSize(9)->setItalic(true)->setColor(new Color('FF777777'));

        $hoja->fromArray($this->encabezadosPadron(), null, 'A4');

        $encabezado = $hoja->getStyle('A4:F4');
        $encabezado->getFont()->setBold(true)->setSize(11)->setColor(new Color('FFFFFFFF'));
        $encabezado->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('001E40');
        $encabezado->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $encabezado->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('FF0A7A3D'));
        $hoja->getRowDimension(4)->setRowHeight(26);

        foreach ($filas as $indice => $datos) {
            $numero = 5 + $indice;
            $hoja->setCellValueExplicit('A' . $numero, (string) $datos[0], DataType::TYPE_STRING);
            $hoja->fromArray(array_slice($datos, 1), null, 'B' . $numero);
            $hoja->getRowDimension($numero)->setRowHeight(19);

            $rango = "A{$numero}:F{$numero}";
            $hoja->getStyle($rango)->getFont()->setSize(10);
            $hoja->getStyle($rango)->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('FFD5D8DD'));
            $hoja->getStyle($rango)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            if ($indice % 2 === 1) {
                $hoja->getStyle($rango)->getFill()
                    ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F7F9FB');
            }

            $hoja->getStyle("A{$numero}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $hoja->getStyle("E{$numero}:F{$numero}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $estado = $hoja->getStyle("F{$numero}")->getFont();
            if ($datos[5] === 'Votó') {
                $estado->setBold(true)->setColor(new Color('FF0A7A3D'));
            } else {
                $estado->setColor(new Color('FF9A6700'));
            }
        }

        foreach (['A' => 16, 'B' => 21, 'C' => 21, 'D' => 24, 'E' => 11, 'F' => 14] as $columna => $ancho) {
            $hoja->getColumnDimension($columna)->setWidth($ancho);
        }

        $hoja->setAutoFilter("A4:F{$ultimaFila}");
        $hoja->freezePane('A5');
        $hoja->getSheetView()->setZoomScale(100);

        $hoja->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $hoja->getPageSetup()->setFitToWidth(1);
        $hoja->getPageSetup()->setFitToHeight(0);
        $hoja->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(4, 4);

        $spreadsheet->getProperties()
            ->setCreator('Sistema Electoral CTP AIRA')
            ->setTitle('Padrón Electoral')
            ->setDescription('Padrón electoral generado por el Sistema Electoral del CTP AIRA');

        $writer = new Xlsx($spreadsheet);
        $nombre = 'padron_' . now()->format('Y-m-d_H-i') . '.xlsx';

        return response()->streamDownload(function () use ($writer, $spreadsheet) {
            $writer->save('php://output');
            $spreadsheet->disconnectWorksheets();
        }, $nombre, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0, no-cache, no-store, must-revalidate',
        ]);
    }

    private function encabezadosPadron(): array
    {
        return ['Cédula', 'Primer apellido', 'Segundo apellido', 'Nombre', 'Sección', 'Estado'];
    }

    private function filasPadron(): \Illuminate\Support\Collection
    {
        return Student::query()
            ->orderBy('seccion')
            ->orderBy('apellidos')
            ->orderBy('nombre')
            ->limit(20000)
            ->get()
            ->map(function (Student $student) {
                [$primerApellido, $segundoApellido] = $this->separarApellidos((string) $student->apellidos);

                return [
                    $student->identificacion,
                    $primerApellido,
                    $segundoApellido,
                    $student->nombre,
                    $student->seccion,
                    $student->voto ? 'Votó' : 'Pendiente',
                ];
            });
    }

    private function separarApellidos(string $apellidos): array
    {
        $partes = preg_split('/\s+/', trim($apellidos), 2) ?: [];

        return [$partes[0] ?? '', $partes[1] ?? ''];
    }

    public function search(Request $request)
    {
        $identificacion = $request->input('identificacion');

        $student = Student::where('identificacion', $identificacion)->first();

        if ($student) {
            return response()->json([
                'success' => true,
                'data' => $student
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Estudiante no encontrado'
        ], 404);
    }
}

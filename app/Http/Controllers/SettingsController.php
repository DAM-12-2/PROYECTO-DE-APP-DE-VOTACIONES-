<?php

namespace App\Http\Controllers;

use App\Services\InstitutionService;
use App\Services\TallySealService;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private InstitutionService $institutionService;
    private TallySealService $tallySealService;

    public function __construct(InstitutionService $institutionService, TallySealService $tallySealService)
    {
        $this->institutionService = $institutionService;
        $this->tallySealService = $tallySealService;
    }

    public function settings()
    {
        $settings = $this->institutionService->getMultipleSettings([
            'institucion_nombre',
            'institucion_logo',
        ]);

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'institucion_nombre' => 'required|string|max:255',
        ]);

        $this->institutionService->setSetting('institucion_nombre', $validated['institucion_nombre']);

        return back()->with('success', 'Configuración guardada correctamente.');
    }

    public function toggleEleccion(Request $request)
    {
        $current = Setting::where('nombre', 'eleccion_abierta')->value('detalle') ?? '0';
        $new = $current === '1' ? '0' : '1';
        Setting::updateOrCreate(['nombre' => 'eleccion_abierta'], ['detalle' => $new]);

        if ($new === '0') {
            $selladas = $this->tallySealService->sealAll();

            return back()->with('success', "Elección cerrada. Se selló el conteo de {$selladas} mesas para verificación de integridad.");
        }

        return back()->with('success', 'Elección abierta.');
    }

    public function resetVotos(Request $request)
    {
        \App\Models\Vote::truncate();
        $this->institutionService->setSetting('votos_reseteados', '1');

        return back()->with('success', 'Votos reiniciados correctamente.');
    }

    public function resetCompleto(Request $request)
    {
        \App\Models\Vote::truncate();
        \App\Models\Bitacora::truncate();
        $this->institutionService->setSetting('votos_reseteados', '1');

        return back()->with('success', 'Reseteo completo realizado.');
    }

    public function backupDownload(Request $request)
    {
        $dbPath = database_path('database.sqlite');
        return response()->download($dbPath, 'backup-' . now()->format('Y-m-d-His') . '.sqlite');
    }

    public function backupRestore(Request $request)
    {
        return back()->with('error', 'Restauración no implementada aún.');
    }
}

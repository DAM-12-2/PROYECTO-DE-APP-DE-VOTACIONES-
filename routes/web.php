<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\UrnaController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\IncidenteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TeeController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\JrvController;
use App\Http\Controllers\TribunalController;

Route::get('/kiosko', function () {
    return view('voting.urna');
})->name('kiosko');

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:tee'])->prefix('tribunal')->group(function () {
    Route::get('/', [TribunalController::class, 'index'])->name('tribunal.dashboard');
    Route::get('/estudiantes', [TribunalController::class, 'estudiantes'])->name('tribunal.estudiantes');
    Route::get('/configuracion', [TribunalController::class, 'configuracion'])->name('tribunal.configuracion');
});

Route::middleware(['auth', 'role:admin,tee'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'dashboardData'])->name('admin.dashboard.data');

    Route::get('/estudiantes', [StudentController::class, 'index'])->name('admin.students');
    Route::post('/estudiantes', [StudentController::class, 'store']);
    Route::get('/estudiantes/{id}/edit', [StudentController::class, 'edit'])->name('admin.students.edit');
    Route::put('/estudiantes/{id}', [StudentController::class, 'update'])->name('admin.students.update');
    Route::delete('/estudiantes/{id}', [StudentController::class, 'destroy']);
    Route::post('/estudiantes/importar', [StudentController::class, 'import'])->name('admin.students.import');

    Route::get('/partidos', [PartyController::class, 'index'])->name('admin.parties');
    Route::post('/partidos', [PartyController::class, 'store']);
    Route::get('/partidos/{id}/edit', [PartyController::class, 'edit'])->name('admin.parties.edit');
    Route::put('/partidos/{id}', [PartyController::class, 'update'])->name('admin.parties.update');
    Route::delete('/partidos/{id}', [PartyController::class, 'destroy']);

    Route::get('/urnas', [UrnaController::class, 'index'])->name('admin.urnas');
    Route::post('/urnas', [UrnaController::class, 'store']);
    Route::get('/urnas/{id}/edit', [UrnaController::class, 'edit'])->name('admin.urnas.edit');
    Route::put('/urnas/{id}', [UrnaController::class, 'update'])->name('admin.urnas.update');
    Route::delete('/urnas/{id}', [UrnaController::class, 'destroy']);

    Route::get('/resultados', [ResultController::class, 'resultados'])->middleware('block.reports')->name('admin.resultados');
    Route::get('/resultados/exportar-csv', [ResultController::class, 'exportarResultadosCsv'])->middleware('block.reports')->name('admin.resultados.export_csv');

    Route::get('/configuracion', [SettingsController::class, 'settings'])->name('admin.settings');
    Route::post('/configuracion', [SettingsController::class, 'updateSettings']);

    Route::post('/eleccion/toggle', [SettingsController::class, 'toggleEleccion'])->middleware('role:admin')->name('admin.eleccion.toggle');

    Route::middleware('role:admin')->group(function () {
        Route::post('/reset/votos', [SettingsController::class, 'resetVotos'])->name('admin.reset.votos');
        Route::post('/reset/completo', [SettingsController::class, 'resetCompleto'])->name('admin.reset.completo');
    });

    Route::get('/mesas', [MesaController::class, 'index'])->name('admin.mesas');
    Route::post('/mesas', [MesaController::class, 'store'])->name('admin.mesas.store');
    Route::get('/mesas/{id}/edit', [MesaController::class, 'edit'])->name('admin.mesas.edit');
    Route::put('/mesas/{id}', [MesaController::class, 'update'])->name('admin.mesas.update');
    Route::delete('/mesas/{id}', [MesaController::class, 'destroy'])->name('admin.mesas.destroy');
    Route::post('/mesas/{id}/secciones', [MesaController::class, 'storeSeccion'])->name('admin.secciones.store');
    Route::delete('/secciones/{id}', [MesaController::class, 'destroySeccion'])->name('admin.secciones.destroy');
    Route::post('/secciones/mover', [MesaController::class, 'moverSeccion'])->name('admin.secciones.mover');

    Route::get('/candidatos', [CandidatoController::class, 'index'])->name('admin.candidatos');
    Route::post('/puestos', [CandidatoController::class, 'storePuesto'])->name('admin.puestos.store');
    Route::get('/puestos/{id}/edit', [CandidatoController::class, 'editPuesto'])->name('admin.puestos.edit');
    Route::put('/puestos/{id}', [CandidatoController::class, 'updatePuesto'])->name('admin.puestos.update');
    Route::delete('/puestos/{id}', [CandidatoController::class, 'destroyPuesto'])->name('admin.puestos.destroy');
    Route::post('/candidatos', [CandidatoController::class, 'storeCandidato'])->name('admin.candidatos.store');
    Route::delete('/candidatos/{id}', [CandidatoController::class, 'destroyCandidato'])->name('admin.candidatos.destroy');

    Route::get('/incidentes', [IncidenteController::class, 'index'])->name('admin.incidentes');
    Route::post('/incidentes', [IncidenteController::class, 'store'])->name('admin.incidentes.store');
    Route::delete('/incidentes/{id}', [IncidenteController::class, 'destroy'])->name('admin.incidentes.destroy');

    Route::get('/bitacora', function () {
        return view('admin.bitacora');
    })->name('admin.bitacora');

    Route::get('/respaldar', [SettingsController::class, 'backupDownload'])->name('admin.backup');
    Route::get('/ayuda', function () {
        return view('admin.help');
    })->name('admin.help');

    Route::middleware('role:admin')->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('admin.usuarios.store');
        Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('admin.usuarios.edit');
        Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('admin.usuarios.update');
        Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/respaldo/descargar', [SettingsController::class, 'backupDownload'])->name('admin.backup.download');
        Route::post('/respaldo/restaurar', [SettingsController::class, 'backupRestore'])->name('admin.backup.restore');
    });

    Route::get('/reportes', [ReportController::class, 'index'])->name('admin.reportes.index');
    Route::get('/reportes/padron', [ReportController::class, 'padron'])->name('admin.reportes.padron');
    Route::get('/reportes/padron-firmas', [ReportController::class, 'padronFirmas'])->name('admin.reportes.padron_firmas');
    Route::get('/reportes/conteo-cero', [ReportController::class, 'conteoCero'])->name('admin.reportes.conteo_cero');
    Route::get('/reportes/acta-apertura', [ReportController::class, 'actaApertura'])->name('admin.reportes.acta_apertura');
    Route::get('/reportes/acta-cierre', [ReportController::class, 'actaCierre'])->middleware('block.reports')->name('admin.reportes.acta_cierre');
    Route::get('/reportes/acta-resultados', [ReportController::class, 'actaResultados'])->middleware('block.reports')->name('admin.reportes.acta_resultados');
    Route::get('/reportes/resultados', [ReportController::class, 'resultados'])->middleware('block.reports')->name('admin.reportes.resultados');
    Route::get('/reportes/padron-votos', [ReportController::class, 'padronVotos'])->middleware('block.reports')->name('admin.reportes.padron_votos');
    Route::get('/reportes/incidentes', [ReportController::class, 'incidentes'])->name('admin.reportes.incidentes');
    Route::get('/reportes/carteles/{id}', [ReportController::class, 'carteles'])->name('admin.reportes.carteles');
    Route::get('/reportes/instrucciones', [ReportController::class, 'instrucciones'])->name('admin.reportes.instrucciones');

    Route::get('/reportes/consulta-popular/resumen', [ReportController::class, 'consultaPopularResumen'])->name('admin.reportes.consulta_resumen');
    Route::get('/reportes/consulta-popular/por-mesa', [ReportController::class, 'consultaPopularPorMesa'])->name('admin.reportes.consulta_por_mesa');
    Route::get('/reportes/consulta-popular/por-seccion', [ReportController::class, 'consultaPopularPorSeccion'])->name('admin.reportes.consulta_por_seccion');

    Route::get('/tee', [TeeController::class, 'index'])->name('admin.tee');
    Route::post('/tee', [TeeController::class, 'store'])->name('admin.tee.store');
    Route::delete('/tee/{id}', [TeeController::class, 'destroy'])->name('admin.tee.destroy');

    Route::post('/cambiar-password', [UsuarioController::class, 'changePassword'])->name('admin.change_password');

    Route::post('/api/urna/activar', [UrnaController::class, 'activar'])->middleware('throttle:60,1');
    Route::post('/api/urna/desactivar', [UrnaController::class, 'desactivar'])->middleware('throttle:60,1');
    Route::get('/api/students/search', [StudentController::class, 'search'])->middleware('throttle:120,1');
    Route::get('/api/ganador', [ResultController::class, 'apiVerificarGanador'])->middleware('throttle:30,1');
});

Route::middleware(['auth', 'role:jrv'])->prefix('jrv')->group(function () {
    Route::get('/', [JrvController::class, 'index'])->name('jrv.index');
    Route::get('/api/buscar', [JrvController::class, 'searchStudents'])->middleware('throttle:120,1');
    Route::post('/api/activar-urna', [JrvController::class, 'activarUrna'])->middleware('throttle:60,1');
    Route::post('/api/desactivar-urna', [JrvController::class, 'desactivarUrna'])->middleware('throttle:60,1');
    Route::post('/api/votar', [VoteController::class, 'store'])->middleware('throttle:60,1');
});

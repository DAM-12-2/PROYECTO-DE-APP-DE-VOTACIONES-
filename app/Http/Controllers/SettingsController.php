<?php

namespace App\Http\Controllers;

use App\Services\BitacoraService;
use App\Services\BackupService;
use App\Services\ElectionService;
use App\Services\FileUploadService;
use App\Services\InstitutionService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private BitacoraService $bitacoraService;
    private ElectionService $electionService;
    private InstitutionService $institutionService;
    private FileUploadService $fileUploadService;
    private BackupService $backupService;

    public function __construct(BitacoraService $bitacoraService, ElectionService $electionService, InstitutionService $institutionService, FileUploadService $fileUploadService, BackupService $backupService)
    {
    }

    public function settings()
    {
    }

    public function updateSettings(Request $request)
    {
    }

    public function toggleEleccion(Request $request)
    {
    }

    public function resetVotos(Request $request)
    {
    }

    public function resetCompleto(Request $request)
    {
    }

    public function backupDownload()
    {
    }

    public function backupRestore(Request $request)
    {
    }
}

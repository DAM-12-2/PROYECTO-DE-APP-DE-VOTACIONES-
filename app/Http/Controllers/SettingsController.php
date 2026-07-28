<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function settings()
    {
        return view('admin.settings');
    }

    public function updateSettings(Request $request)
    {
        return back();
    }

    public function toggleEleccion(Request $request)
    {
        return back();
    }

    public function resetVotos(Request $request)
    {
        return back();
    }

    public function resetCompleto(Request $request)
    {
        return back();
    }

    public function backupDownload(Request $request)
    {
        return view('admin.backup');
    }

    public function backupRestore(Request $request)
    {
        return back();
    }
}

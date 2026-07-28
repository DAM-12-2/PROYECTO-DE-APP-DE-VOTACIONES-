<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.students');
    }

    public function store(Request $request)
    {
        return back();
    }

    public function edit($id)
    {
        return view('admin.students_edit');
    }

    public function update(Request $request, $id)
    {
        return back();
    }

    public function destroy($id)
    {
        return back();
    }

    public function import(Request $request)
    {
        return back();
    }

    public function search(Request $request)
    {
        return response()->json([]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TransactionProductImport;

class ImportController extends Controller
{
    public function showImportForm()
    {
        return view('import.form');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new TransactionProductImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data imported successfully.');
    }
}
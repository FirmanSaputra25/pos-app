<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

class SalesReportController extends Controller
{
    public function export(Request $request)
    {
        return Excel::download(new SalesReportExport($request->month, $request->year), 'sales_report.xlsx');
    }
}
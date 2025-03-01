<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    public function export(Request $request)
    {
        $exportDate = Carbon::now()->format('Y-m-d'); // Ambil tanggal hari ini
        $fileName = "sales_report_{$exportDate}.xlsx";

        return Excel::download(new SalesReportExport($request->start_date, $request->end_date), $fileName);
    }
}

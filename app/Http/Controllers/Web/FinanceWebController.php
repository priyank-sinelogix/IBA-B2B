<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LedgerEntry;
use Illuminate\Http\Request;

class FinanceWebController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->user()->company;

        $ledgerEntries = LedgerEntry::where('company_id', $company->id)
            ->latest()->paginate(20);

        return view('finance.index', compact('company', 'ledgerEntries'));
    }

    public function downloadStatement(Request $request)
    {
        // TODO: generate PDF via barryvdh/laravel-dompdf or similar,
        // store record in `statements` table, then stream/download.
        abort(501, 'Statement generation not yet implemented.');
    }
}

<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Company;
use App\Models\LedgerEntry;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $query = LedgerEntry::with('company');
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }
        $entries = $query->latest()->paginate(20);
        $companies = Company::orderBy('name')->get();

        return view('admin.finance.index', compact('entries', 'companies'));
    }

    public function create()
    {
        $companies = Company::orderBy('name')->get();
        $orders = Order::orderBy('order_no')->get();
        return view('admin.finance.form', compact('companies', 'orders'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'type' => 'required|in:invoice,payment,credit_note,debit_note',
            'reference_no' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:1000',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        DB::transaction(function () use ($data, &$entry) {
            $company = Company::lockForUpdate()->findOrFail($data['company_id']);

            // invoice/debit_note increase what the client owes; payment/credit_note reduce it
            $signedAmount = in_array($data['type'], ['invoice', 'debit_note'])
                ? $data['amount']
                : -$data['amount'];

            $newBalance = (float) $company->current_balance + $signedAmount;

            $entry = LedgerEntry::create([
                ...$data,
                'balance_after' => $newBalance,
            ]);

            $company->update(['current_balance' => $newBalance]);
        });

        AuditLog::record('ledger.entry_created', $entry, null, $entry->only('type', 'amount', 'balance_after'));

        return redirect('/admin/finance')->with('success', 'Ledger entry recorded.');
    }

    public function destroy(LedgerEntry $entry)
    {
        // Deliberately no delete — financial records should stay immutable for audit purposes.
        // Post a reversing entry (credit_note/debit_note) instead.
        abort(405, 'Ledger entries are immutable. Post a reversing entry instead.');
    }
}

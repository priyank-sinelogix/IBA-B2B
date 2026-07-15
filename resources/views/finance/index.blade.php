@extends('layouts.admin')
@section('title', 'Finance')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Account Statement</h3></div>
            <div class="card-body">
                <div class="text-muted small">Current Balance</div>
                <div class="h3 text-success">USD {{ number_format($company->current_balance ?? 48750.60, 2) }}</div>
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Credit Limit: USD {{ number_format($company->credit_limit ?? 100000, 2) }}</span>
                    <span>{{ $company->creditUsedPercent() ?? 51 }}% Used</span>
                </div>
                <div class="progress mb-3" style="height:8px;">
                    <div class="progress-bar bg-success" style="width: {{ $company->creditUsedPercent() ?? 51 }}%"></div>
                </div>
                <a href="{{ url('/finance/statement/download') }}" class="btn btn-outline-primary btn-sm w-100"><i class="fas fa-download mr-1"></i> Download Statement</a>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Ledger Entries</h3></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Date</th><th>Type</th><th>Reference</th><th>Description</th><th class="text-right">Amount</th><th class="text-right">Balance</th></tr></thead>
                    <tbody>
                    @forelse($ledgerEntries ?? [] as $entry)
                        <tr>
                            <td>{{ $entry->created_at->format('d M Y') }}</td>
                            <td class="text-capitalize">{{ str_replace('_',' ',$entry->type) }}</td>
                            <td>{{ $entry->reference_no }}</td>
                            <td>{{ $entry->description }}</td>
                            <td class="text-right">{{ number_format($entry->amount, 2) }}</td>
                            <td class="text-right">{{ number_format($entry->balance_after, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted p-4">
                            No records yet. Connect <code>$ledgerEntries</code> from <code>FinanceWebController@index</code>.
                        </td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('admin.layouts.admin')
@section('title', 'Finance / Ledger')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Ledger Entries</h3>
        <a href="{{ url('/admin/finance/create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus mr-1"></i> New Entry</a>
    </div>
    <div class="card-body p-0">
        <form class="d-flex p-3 border-bottom" method="GET">
            <select name="company_id" class="form-control" style="max-width:220px;" onchange="this.form.submit()">
                <option value="">All Clients</option>
                @foreach($companies as $c)
                    <option value="{{ $c->id }}" {{ request('company_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </form>
        <table class="table table-hover mb-0">
            <thead><tr><th>Date</th><th>Client</th><th>Type</th><th>Reference</th><th>Description</th><th class="text-right">Amount</th><th class="text-right">Balance After</th></tr></thead>
            <tbody>
            @forelse($entries as $entry)
                <tr>
                    <td>{{ $entry->created_at->format('d M Y') }}</td>
                    <td>{{ $entry->company->name }}</td>
                    <td class="text-capitalize">{{ str_replace('_',' ',$entry->type) }}</td>
                    <td>{{ $entry->reference_no }}</td>
                    <td>{{ $entry->description }}</td>
                    <td class="text-right">{{ number_format($entry->amount, 2) }}</td>
                    <td class="text-right">{{ number_format($entry->balance_after, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted p-4">No ledger entries yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $entries->links() }}</div>
</div>
@endsection

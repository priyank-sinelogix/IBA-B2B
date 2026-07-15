@extends('admin.layouts.admin')
@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="card p-3">
            <div class="text-muted small">Total Client Companies</div>
            <div class="h3 mb-0">{{ $stats['companies'] ?? 0 }}</div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="card p-3">
            <div class="text-muted small">Samples Pending Review</div>
            <div class="h3 mb-0">{{ $stats['samples_pending'] ?? 0 }}</div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="card p-3">
            <div class="text-muted small">Active Orders (All Clients)</div>
            <div class="h3 mb-0">{{ $stats['active_orders'] ?? 0 }}</div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="card p-3">
            <div class="text-muted small">Shipments In Transit</div>
            <div class="h3 mb-0">{{ $stats['shipments_in_transit'] ?? 0 }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title">Recent Sample Submissions — All Clients</h3></div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Sample Code</th><th>Client</th><th>Style</th><th>Status</th><th>Submitted</th></tr></thead>
            <tbody>
            @forelse($recentSamples ?? [] as $sample)
                <tr>
                    <td>{{ $sample->sample_code }}</td>
                    <td>{{ $sample->company->name }}</td>
                    <td>{{ $sample->style_name }}</td>
                    <td>
                        @if($sample->status == 'pending') <span class="badge badge-pending">Pending</span>
                        @elseif($sample->status == 'approved') <span class="badge badge-approved">Approved</span>
                        @else <span class="badge badge-changes">Changes Requested</span> @endif
                    </td>
                    <td>{{ optional($sample->submitted_at)->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted p-4">
                    No records yet. Connect <code>$recentSamples</code> from <code>Admin\Web\DashboardController</code>.
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

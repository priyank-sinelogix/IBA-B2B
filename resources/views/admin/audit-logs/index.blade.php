@extends('admin.layouts.admin')
@section('title', 'Audit Logs')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Full Activity Audit Trail</h3></div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Date</th><th>User</th><th>Client</th><th>Action</th><th>Subject</th><th>IP</th></tr></thead>
            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                    <td>{{ $log->user->name ?? 'System' }}</td>
                    <td>{{ $log->company->name ?? '—' }}</td>
                    <td><code>{{ $log->action }}</code></td>
                    <td>{{ $log->subject_type }} #{{ $log->subject_id }}</td>
                    <td class="text-muted small">{{ $log->ip_address }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted p-4">No activity recorded yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $logs->links() }}</div>
</div>
@endsection

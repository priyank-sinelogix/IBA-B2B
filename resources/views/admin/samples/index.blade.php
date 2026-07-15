@extends('admin.layouts.admin')
@section('title', 'Samples')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Samples</h3>
        <a href="{{ url('/admin/samples/create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus mr-1"></i> New Sample</a>
    </div>
    <div class="card-body p-0">
        <form class="d-flex p-3 border-bottom" method="GET">
            <select name="company_id" class="form-control mr-2" style="max-width:220px;" onchange="this.form.submit()">
                <option value="">All Clients</option>
                @foreach($companies as $c)
                    <option value="{{ $c->id }}" {{ request('company_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            <select name="status" class="form-control mr-2" style="max-width:200px;" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
                <option value="changes_requested" {{ request('status')=='changes_requested'?'selected':'' }}>Changes Requested</option>
            </select>
        </form>

        <table class="table table-hover mb-0">
            <thead><tr><th></th><th>Code</th><th>Client</th><th>Style</th><th>Status</th><th>Submitted</th><th></th></tr></thead>
            <tbody>
            @forelse($samples as $sample)
                <tr>
                    <td><img src="{{ $sample->latestVersion?->signedImageUrl() ?? 'https://via.placeholder.com/44' }}" width="40" height="40" style="object-fit:cover;border-radius:6px;"></td>
                    <td>{{ $sample->sample_code }}</td>
                    <td>{{ $sample->company->name }}</td>
                    <td>{{ $sample->style_name }}</td>
                    <td>
                        @if($sample->status == 'pending') <span class="badge badge-pending">Pending</span>
                        @elseif($sample->status == 'approved') <span class="badge badge-approved">Approved</span>
                        @else <span class="badge badge-changes">Changes Requested</span> @endif
                    </td>
                    <td>{{ optional($sample->submitted_at)->format('d M Y') }}</td>
                    <td>
                        <a href="{{ url('/admin/samples/'.$sample->id.'/edit') }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form method="POST" action="{{ url('/admin/samples/'.$sample->id) }}" class="d-inline" onsubmit="return confirm('Delete this sample?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted p-4">No samples yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $samples->links() }}</div>
</div>
@endsection

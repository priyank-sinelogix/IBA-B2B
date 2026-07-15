@extends('layouts.admin')
@section('title', 'Sampling & Approvals')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Samples</h3>
        <div>
            <a href="?status=pending" class="btn btn-sm {{ request('status')=='pending' ? 'btn-iba' : 'btn-outline-secondary' }}">Pending</a>
            <a href="?status=approved" class="btn btn-sm {{ request('status')=='approved' ? 'btn-iba' : 'btn-outline-secondary' }}">Approved</a>
            <a href="?status=changes_requested" class="btn btn-sm {{ request('status')=='changes_requested' ? 'btn-iba' : 'btn-outline-secondary' }}">Changes Requested</a>
            <a href="?" class="btn btn-sm btn-outline-secondary">All</a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th></th><th>Sample Code</th><th>Style</th><th>Fabric</th><th>Color</th><th>Status</th><th>Submitted</th><th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($samples ?? [] as $sample)
                <tr>
                    <td><img src="{{ $sample->latestVersion->signedImageUrl() ?? 'https://via.placeholder.com/48' }}" width="44" height="44" style="object-fit:cover;border-radius:8px;"></td>
                    <td><a href="{{ url('/samples/'.$sample->id) }}">{{ $sample->sample_code }}</a></td>
                    <td>{{ $sample->style_name }}</td>
                    <td>{{ $sample->fabric }}</td>
                    <td>{{ $sample->color }}</td>
                    <td>
                        @if($sample->status == 'pending') <span class="badge badge-pending">Pending</span>
                        @elseif($sample->status == 'approved') <span class="badge badge-approved">Approved</span>
                        @else <span class="badge badge-changes">Changes Requested</span> @endif
                    </td>
                    <td>{{ optional($sample->submitted_at)->format('d M Y') }}</td>
                    <td>
                        <a href="{{ url('/samples/'.$sample->id) }}" class="btn btn-sm btn-outline-secondary">Open</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted p-4">
                    No records yet. This view expects <code>$samples</code> (paginated) from <code>SampleWebController@index</code>.
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ ($samples ?? null)?->links() }}
    </div>
</div>
@endsection

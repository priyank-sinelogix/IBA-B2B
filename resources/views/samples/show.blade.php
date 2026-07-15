@extends('layouts.admin')
@section('title', $sample->sample_code ?? 'Sample Detail')

@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Current Version</h3></div>
            <div class="card-body text-center">
                <img src="{{ $sample->latestVersion->signedImageUrl() ?? 'https://via.placeholder.com/300' }}" class="img-fluid rounded mb-3" style="max-height:280px;object-fit:cover;">
                <h5>{{ $sample->style_name }}</h5>
                <p class="text-muted mb-1">Fabric: {{ $sample->fabric }}</p>
                <p class="text-muted">Color: {{ $sample->color }}</p>

                <div class="d-flex justify-content-center mt-3">
                    <form method="POST" action="{{ url('/samples/'.$sample->id.'/approve') }}" class="mr-2">
                        @csrf
                        <button class="btn btn-success"><i class="fas fa-check mr-1"></i> Approve</button>
                    </form>
                    <button class="btn btn-outline-danger" data-toggle="modal" data-target="#reviseModal">
                        <i class="fas fa-redo mr-1"></i> Request Revision
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3 class="card-title">Version History</h3></div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($sample->versions ?? [] as $version)
                    <li class="list-group-item d-flex align-items-center">
                        <img src="{{ $version->signedImageUrl() }}" width="40" height="40" style="object-fit:cover;border-radius:6px;" class="mr-3">
                        <div>
                            <div class="font-weight-bold small">Version {{ $version->version_no }}</div>
                            <div class="text-muted small">{{ $version->notes }}</div>
                        </div>
                        <span class="text-muted small ml-auto">{{ $version->created_at->format('d M Y') }}</span>
                    </li>
                    @empty
                    <li class="list-group-item text-muted text-center">No versions yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Comments & Approval History</h3></div>
            <div class="card-body" style="max-height:520px;overflow-y:auto;">
                @forelse($sample->comments ?? [] as $comment)
                <div class="d-flex mb-3">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mr-3" style="width:36px;height:36px;flex-shrink:0;">
                        {{ substr($comment->user->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <div class="font-weight-bold small">
                            {{ $comment->user->name ?? 'User' }}
                            @if($comment->action == 'approve') <span class="badge badge-approved ml-1">Approved</span>
                            @elseif($comment->action == 'revise') <span class="badge badge-changes ml-1">Revision Requested</span>
                            @endif
                        </div>
                        <div class="text-muted small">{{ $comment->comment }}</div>
                        <div class="text-muted" style="font-size:.75rem;">{{ $comment->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center">No comments yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Revise Modal -->
<div class="modal fade" id="reviseModal">
    <div class="modal-dialog">
        <form method="POST" action="{{ url('/samples/'.$sample->id.'/revise') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request Revision — {{ $sample->sample_code }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <textarea name="comment" class="form-control" rows="4" placeholder="Describe the changes needed..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-iba">Send Revision Request</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

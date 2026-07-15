@extends('admin.layouts.admin')
@section('title', $sample->exists ? 'Edit Sample' : 'New Sample')

@section('content')
<div class="card col-lg-7 p-0">
    <div class="card-header"><h3 class="card-title">{{ $sample->exists ? 'Edit' : 'New' }} Sample</h3></div>
    <form method="POST" action="{{ $sample->exists ? url('/admin/samples/'.$sample->id) : url('/admin/samples') }}" enctype="multipart/form-data">
        @csrf
        @if($sample->exists) @method('PUT') @endif
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <div class="form-group">
                <label>Client Company</label>
                <select name="company_id" class="form-control" required>
                    <option value="">-- Select --</option>
                    @foreach($companies as $c)
                        <option value="{{ $c->id }}" {{ old('company_id', $sample->company_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            @unless($sample->exists)
            <div class="form-group">
                <label>Sample Code (unique)</label>
                <input type="text" name="sample_code" class="form-control" value="{{ old('sample_code') }}" placeholder="SMP-0248" required>
            </div>
            @endunless

            <div class="form-row">
                <div class="form-group col-6">
                    <label>Style Name</label>
                    <input type="text" name="style_name" class="form-control" value="{{ old('style_name', $sample->style_name) }}" required>
                </div>
                <div class="form-group col-3">
                    <label>Fabric</label>
                    <input type="text" name="fabric" class="form-control" value="{{ old('fabric', $sample->fabric) }}" placeholder="Piqué 220 GSM">
                </div>
                <div class="form-group col-3">
                    <label>Color</label>
                    <input type="text" name="color" class="form-control" value="{{ old('color', $sample->color) }}">
                </div>
            </div>

            <div class="form-group">
                <label>{{ $sample->exists ? 'Upload New Version (optional — resets to Pending)' : 'Sample Image' }}</label>
                <input type="file" name="image" class="form-control-file" accept="image/*" {{ $sample->exists ? '' : 'required' }}>
            </div>

            <div class="form-group">
                <label>Version Notes</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="What changed in this version...">{{ old('notes') }}</textarea>
            </div>

            @if($sample->exists && $sample->versions->count())
            <div class="mt-3">
                <label class="d-block">Existing Versions</label>
                @foreach($sample->versions as $v)
                    <span class="badge badge-light border mr-1">v{{ $v->version_no }}</span>
                @endforeach
            </div>
            @endif
        </div>
        <div class="card-footer">
            <button class="btn btn-primary">Save</button>
            <a href="{{ url('/admin/samples') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>

@if($sample->exists && $sample->comments->count())
<div class="card col-lg-7 p-0 mt-3">
    <div class="card-header"><h3 class="card-title">Client Comments & Revision Requests</h3></div>
    <div class="card-body">
        @foreach($sample->comments as $comment)
        <div class="d-flex mb-3 {{ $comment->action == 'revise' ? 'p-2' : '' }}" style="{{ $comment->action == 'revise' ? 'background:#fff5f2;border-radius:8px;' : '' }}">
            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mr-3" style="width:36px;height:36px;flex-shrink:0;">
                {{ substr($comment->user->name ?? 'U', 0, 1) }}
            </div>
            <div>
                <div class="font-weight-bold small">
                    {{ $comment->user->name ?? 'User' }}
                    @if($comment->action == 'approve') <span class="badge badge-approved ml-1">Approved</span>
                    @elseif($comment->action == 'revise') <span class="badge badge-changes ml-1">Requested Revision</span>
                    @endif
                </div>
                <div class="text-muted small">{{ $comment->comment }}</div>
                <div class="text-muted" style="font-size:.75rem;">{{ $comment->created_at->diffForHumans() }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection

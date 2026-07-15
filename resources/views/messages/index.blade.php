@extends('layouts.admin')
@section('title', 'Messages')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Recent Communication</h3></div>
    <div class="card-body p-0">
        @forelse($messages ?? [] as $msg)
        <div class="d-flex p-3 border-bottom">
            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mr-3" style="width:38px;height:38px;">
                {{ substr($msg->sender->name ?? 'U', 0, 1) }}
            </div>
            <div class="flex-grow-1">
                <div class="font-weight-bold small">{{ $msg->sender->name ?? 'User' }}
                    @if($msg->linked_type)
                        <span class="badge badge-light text-capitalize ml-1">{{ $msg->linked_type }} #{{ $msg->linked_id }}</span>
                    @endif
                </div>
                <div class="text-muted small">{{ $msg->body }}</div>
            </div>
            <div class="text-muted small">{{ $msg->created_at->diffForHumans() }}</div>
        </div>
        @empty
        <div class="p-4 text-center text-muted">
            No records yet. Connect <code>$messages</code> from <code>MessageWebController@index</code>.
        </div>
        @endforelse
    </div>

    <div class="card-footer">
        <form method="POST" action="{{ url('/messages') }}" class="d-flex">
            @csrf
            <input type="text" name="body" class="form-control mr-2" placeholder="Type a message...">
            <button class="btn btn-iba">Send</button>
        </form>
    </div>
</div>
@endsection

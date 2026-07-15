@extends('admin.layouts.admin')
@section('title', 'Staff & Client Users')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Users</h3>
        <a href="{{ url('/admin/users/create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus mr-1"></i> Add User</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Company</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge badge-light border text-capitalize">{{ str_replace('_',' ',$user->role) }}</span></td>
                    <td>{{ $user->company->name ?? '—' }}</td>
                    <td>{!! $user->is_active ? '<span class="badge badge-approved">Active</span>' : '<span class="badge badge-changes">Inactive</span>' !!}</td>
                    <td>
                        <a href="{{ url('/admin/users/'.$user->id.'/edit') }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form method="POST" action="{{ url('/admin/users/'.$user->id) }}" class="d-inline" onsubmit="return confirm('Delete this user?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted p-4">No users yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $users->links() }}</div>
</div>
@endsection

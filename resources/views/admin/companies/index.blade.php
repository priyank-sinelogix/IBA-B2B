@extends('admin.layouts.admin')
@section('title', 'Client Companies')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Client Companies</h3>
        <a href="{{ url('/admin/companies/create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus mr-1"></i> Add Company</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Name</th><th>Code</th><th>Users</th><th>Credit Limit</th><th>Balance</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse($companies as $company)
                <tr>
                    <td>{{ $company->name }}</td>
                    <td>{{ $company->code }}</td>
                    <td>{{ $company->users_count }}</td>
                    <td>USD {{ number_format($company->credit_limit, 2) }}</td>
                    <td>USD {{ number_format($company->current_balance, 2) }}</td>
                    <td>{!! $company->is_active ? '<span class="badge badge-approved">Active</span>' : '<span class="badge badge-changes">Inactive</span>' !!}</td>
                    <td>
                        <a href="{{ url('/admin/companies/'.$company->id.'/edit') }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form method="POST" action="{{ url('/admin/companies/'.$company->id) }}" class="d-inline" onsubmit="return confirm('Delete this company?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted p-4">No companies yet — click "Add Company" to onboard your first client.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $companies->links() }}</div>
</div>
@endsection

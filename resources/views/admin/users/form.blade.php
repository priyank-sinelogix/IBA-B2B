@extends('admin.layouts.admin')
@section('title', $user->exists ? 'Edit User' : 'Add User')

@section('content')
<div class="card col-lg-6 p-0">
    <div class="card-header"><h3 class="card-title">{{ $user->exists ? 'Edit' : 'New' }} User</h3></div>
    <form method="POST" action="{{ $user->exists ? url('/admin/users/'.$user->id) : url('/admin/users') }}">
        @csrf
        @if($user->exists) @method('PUT') @endif
        <div class="card-body">
            @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="form-group">
                <label>Password {{ $user->exists ? '(leave blank to keep current)' : '' }}</label>
                <input type="password" name="password" class="form-control" {{ $user->exists ? '' : 'required' }}>
            </div>
            <div class="form-group">
                <label>Designation</label>
                <input type="text" name="designation" class="form-control" value="{{ old('designation', $user->designation) }}" placeholder="Procurement Manager">
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" id="roleSelect" class="form-control" required onchange="document.getElementById('companyField').style.display = this.value==='customer' ? 'block' : 'none'">
                    <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Customer (Client Portal)</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin (IBA Staff)</option>
                    <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>
            <div class="form-group" id="companyField" style="display: {{ old('role', $user->role ?? 'customer') == 'customer' ? 'block' : 'none' }};">
                <label>Client Company</label>
                <select name="company_id" class="form-control">
                    <option value="">-- Select --</option>
                    @foreach($companies as $c)
                        <option value="{{ $c->id }}" {{ old('company_id', $user->company_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="isActive">Active</label>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary">Save</button>
            <a href="{{ url('/admin/users') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

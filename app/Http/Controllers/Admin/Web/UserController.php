<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('company')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $user = new User();
        $companies = Company::orderBy('name')->get();
        return view('admin.users.form', compact('user', 'companies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'string', Password::min(8)],
            'designation' => 'nullable|string|max:255',
            'role' => 'required|in:customer,admin,super_admin',
            'company_id' => 'nullable|exists:companies,id|required_if:role,customer',
            'is_active' => 'boolean',
        ]);

        $data['password'] = bcrypt($data['password']);
        // Staff (admin/super_admin) never belong to a client company
        if ($data['role'] !== 'customer') {
            $data['company_id'] = null;
        }

        $user = User::create($data);
        AuditLog::record('user.created', $user, null, $user->only('email', 'role'));

        return redirect('/admin/users')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        $companies = Company::orderBy('name')->get();
        return view('admin.users.form', compact('user', 'companies'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => ['nullable', 'string', Password::min(8)],
            'designation' => 'nullable|string|max:255',
            'role' => 'required|in:customer,admin,super_admin',
            'company_id' => 'nullable|exists:companies,id|required_if:role,customer',
            'is_active' => 'boolean',
        ]);

        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        if ($data['role'] !== 'customer') {
            $data['company_id'] = null;
        }

        $before = $user->only('name', 'email', 'role', 'is_active');
        $user->update($data);

        AuditLog::record('user.updated', $user, $before, $user->only('name', 'email', 'role', 'is_active'));

        return redirect('/admin/users')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', "You can't delete your own account.");
        }

        AuditLog::record('user.deleted', $user, $user->only('email', 'role'), null);
        $user->delete();

        return back()->with('success', 'User deleted.');
    }
}

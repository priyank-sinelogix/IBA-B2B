<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::withCount('users')->latest()->paginate(15);
        return view('admin.companies.index', compact('companies'));
    }

    public function create()
    {
        $company = new Company();
        return view('admin.companies.form', compact('company'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Company::create($data);

        return redirect('/admin/companies')->with('success', 'Client company created.');
    }

    public function edit(Company $company)
    {
        return view('admin.companies.form', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $data = $this->validated($request, $company->id);
        $company->update($data);

        return redirect('/admin/companies')->with('success', 'Client company updated.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return back()->with('success', 'Client company deleted.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:companies,code'.($ignoreId ? ",$ignoreId" : ''),
            'credit_limit' => 'required|numeric|min:0',
            'current_balance' => 'required|numeric',
            'is_active' => 'boolean',
        ]);
    }
}

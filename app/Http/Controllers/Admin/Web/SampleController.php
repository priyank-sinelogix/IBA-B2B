<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Company;
use App\Models\Sample;
use App\Models\SampleVersion;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sample::with(['company', 'latestVersion']);

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $samples = $query->latest('submitted_at')->paginate(15);
        $companies = Company::orderBy('name')->get();

        return view('admin.samples.index', compact('samples', 'companies'));
    }

    public function create()
    {
        $sample = new Sample();
        $companies = Company::orderBy('name')->get();
        return view('admin.samples.form', compact('sample', 'companies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'sample_code' => 'required|string|max:100|unique:samples,sample_code',
            'style_name' => 'required|string|max:255',
            'fabric' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:100',
            'image' => 'required|image|max:5120',
            'notes' => 'nullable|string|max:1000',
        ]);

        $sample = Sample::create([
            'company_id' => $data['company_id'],
            'sample_code' => $data['sample_code'],
            'style_name' => $data['style_name'],
            'fabric' => $data['fabric'] ?? null,
            'color' => $data['color'] ?? null,
            'status' => 'pending',
            'submitted_by' => $request->user()->id,
            'submitted_at' => now(),
        ]);

        $this->storeVersion($request, $sample, 1);

        AuditLog::record('sample.created', $sample, null, $sample->only('sample_code', 'status'));

        return redirect('/admin/samples')->with('success', 'Sample created and sent to client for approval.');
    }

    public function edit(Sample $sample)
    {
        $companies = Company::orderBy('name')->get();
        $sample->load(['versions', 'comments.user']);
        return view('admin.samples.form', compact('sample', 'companies'));
    }

    public function update(Request $request, Sample $sample)
    {
        $data = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'style_name' => 'required|string|max:255',
            'fabric' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:5120',
            'notes' => 'nullable|string|max:1000',
        ]);

        $before = $sample->only('style_name', 'fabric', 'color', 'status');

        $sample->update([
            'company_id' => $data['company_id'],
            'style_name' => $data['style_name'],
            'fabric' => $data['fabric'] ?? null,
            'color' => $data['color'] ?? null,
        ]);

        // Uploading a new image = new version + resets status to pending for re-approval
        if ($request->hasFile('image')) {
            $nextVersion = ($sample->versions()->max('version_no') ?? 0) + 1;
            $this->storeVersion($request, $sample, $nextVersion);
            $sample->update(['status' => 'pending']);
        }

        AuditLog::record('sample.updated', $sample, $before, $sample->fresh()->only('style_name', 'fabric', 'color', 'status'));

        return redirect('/admin/samples')->with('success', 'Sample updated.');
    }

    public function destroy(Sample $sample)
    {
        AuditLog::record('sample.deleted', $sample, $sample->only('sample_code', 'status'), null);
        $sample->delete();

        return back()->with('success', 'Sample deleted.');
    }

    private function storeVersion(Request $request, Sample $sample, int $versionNo): void
    {
        $path = $request->file('image')->store('samples/'.$sample->id, 'public');

        SampleVersion::create([
            'sample_id' => $sample->id,
            'version_no' => $versionNo,
            'image_path' => $path,
            'notes' => $request->input('notes'),
            'uploaded_by' => $request->user()->id,
        ]);
    }
}

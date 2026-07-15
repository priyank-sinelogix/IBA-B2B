<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Sample;
use App\Models\SampleComment;
use Illuminate\Http\Request;

class SampleWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Sample::with('latestVersion')->where('company_id', $request->user()->company_id);

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $samples = $query->latest('submitted_at')->paginate(15);

        return view('samples.index', compact('samples'));
    }

    public function show(Request $request, Sample $sample)
    {
        $this->authorizeCompany($request, $sample);
        $sample->load(['versions', 'comments.user']);

        return view('samples.show', compact('sample'));
    }

    public function approve(Request $request, Sample $sample)
    {
        $this->authorizeCompany($request, $sample);

        $before = $sample->only('status');
        $sample->update(['status' => 'approved']);

        SampleComment::create([
            'sample_id' => $sample->id,
            'sample_version_id' => $sample->latestVersion?->id,
            'user_id' => $request->user()->id,
            'comment' => $request->input('comment', ''),
            'action' => 'approve',
        ]);

        AuditLog::record('sample.approved', $sample, $before, $sample->only('status'));

        return back()->with('success', 'Sample approved.');
    }

    public function requestRevision(Request $request, Sample $sample)
    {
        $this->authorizeCompany($request, $sample);
        $request->validate(['comment' => 'required|string|max:2000']);

        $before = $sample->only('status');
        $sample->update(['status' => 'changes_requested']);

        SampleComment::create([
            'sample_id' => $sample->id,
            'sample_version_id' => $sample->latestVersion?->id,
            'user_id' => $request->user()->id,
            'comment' => $request->input('comment'),
            'action' => 'revise',
        ]);

        AuditLog::record('sample.revision_requested', $sample, $before, $sample->only('status'));

        return back()->with('success', 'Revision request sent.');
    }

    private function authorizeCompany(Request $request, Sample $sample): void
    {
        if ($sample->company_id !== $request->user()->company_id) {
            abort(403, 'Forbidden');
        }
    }
}

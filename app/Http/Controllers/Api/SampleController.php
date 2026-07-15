<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Sample;
use App\Models\SampleComment;
use App\Notifications\SampleStatusChanged;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    // GET /api/portal/samples
    public function index(Request $request)
    {
        $companyId = $request->attributes->get('company_id');

        $samples = Sample::with(['latestVersion'])
            ->where('company_id', $companyId) // hard scope - no cross-tenant leakage
            ->latest('submitted_at')
            ->paginate(20);

        return response()->json($samples);
    }

    // GET /api/portal/samples/{sample}
    public function show(Request $request, Sample $sample)
    {
        $this->authorizeCompany($request, $sample);

        $sample->load(['versions', 'comments.user']);

        return response()->json($sample);
    }

    // POST /api/portal/samples/{sample}/approve
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

        // Notify internal IBA team
        // Notification::send($iba_admins, new SampleStatusChanged($sample));

        return response()->json(['message' => 'Sample approved', 'sample' => $sample]);
    }

    // POST /api/portal/samples/{sample}/revise
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

        return response()->json(['message' => 'Revision requested', 'sample' => $sample]);
    }

    /**
     * Belt-and-suspenders check: even though route model binding + queries
     * are already scoped, this blocks direct-ID access to another company's record.
     */
    private function authorizeCompany(Request $request, Sample $sample): void
    {
        if ($sample->company_id !== $request->attributes->get('company_id')) {
            abort(403, 'Forbidden');
        }
    }
}

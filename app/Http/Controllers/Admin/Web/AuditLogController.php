<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with(['company', 'user'])->latest('created_at')->paginate(30);
        return view('admin.audit-logs.index', compact('logs'));
    }
}

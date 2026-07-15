<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Sample;
use App\Models\Order;
use App\Models\Message;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $companyId = $request->user()->company_id;
        $company = $request->user()->company;

        $stats = [
            'samples_pending' => Sample::where('company_id', $companyId)->where('status', 'pending')->count(),
            'active_orders' => Order::where('company_id', $companyId)->where('current_stage', '!=', 'dispatched')->count(),
            'balance' => $company->current_balance ?? 0,
            'credit_limit' => $company->credit_limit ?? 0,
            'credit_used_pct' => $company->creditUsedPercent() ?? 0,
            'shipments_in_transit' => \App\Models\Shipment::where('company_id', $companyId)->where('status', 'in_transit')->count(),
        ];

        $pendingSamples = Sample::with('latestVersion')
            ->where('company_id', $companyId)
            ->whereIn('status', ['pending', 'changes_requested'])
            ->latest('submitted_at')->take(5)->get();

        $orders = Order::where('company_id', $companyId)->latest()->take(5)->get();

        $recentMessages = Message::with('sender')
            ->where('company_id', $companyId)
            ->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'pendingSamples', 'orders', 'recentMessages'));
    }
}

<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Order;
use App\Models\Sample;
use App\Models\Shipment;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'companies' => Company::count(),
            'samples_pending' => Sample::where('status', 'pending')->count(),
            'active_orders' => Order::where('current_stage', '!=', 'dispatched')->count(),
            'shipments_in_transit' => Shipment::where('status', 'in_transit')->count(),
        ];

        $recentSamples = Sample::with('company')->latest('submitted_at')->take(8)->get();

        return view('admin.dashboard', compact('stats', 'recentSamples'));
    }
}

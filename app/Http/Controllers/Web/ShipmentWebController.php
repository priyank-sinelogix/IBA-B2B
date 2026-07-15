<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;

class ShipmentWebController extends Controller
{
    public function index(Request $request)
    {
        $shipments = Shipment::where('company_id', $request->user()->company_id)
            ->latest('status_updated_at')->paginate(15);

        return view('shipments.index', compact('shipments'));
    }
}

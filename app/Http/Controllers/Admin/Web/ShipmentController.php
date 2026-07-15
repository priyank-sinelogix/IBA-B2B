<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Company;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\ShipmentTrackingEvent;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipment::with('company');
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }
        $shipments = $query->latest('status_updated_at')->paginate(15);
        $companies = Company::orderBy('name')->get();

        return view('admin.shipments.index', compact('shipments', 'companies'));
    }

    public function create()
    {
        $shipment = new Shipment();
        $companies = Company::orderBy('name')->get();
        $orders = Order::orderBy('order_no')->get();
        return view('admin.shipments.form', compact('shipment', 'companies', 'orders'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['status_updated_at'] = now();
        $shipment = Shipment::create($data);

        ShipmentTrackingEvent::create([
            'shipment_id' => $shipment->id,
            'status' => $shipment->status,
            'location' => $shipment->origin,
            'remarks' => 'Shipment created',
            'event_at' => now(),
        ]);

        AuditLog::record('shipment.created', $shipment, null, $shipment->only('awb_number', 'status'));

        return redirect('/admin/shipments')->with('success', 'Shipment created.');
    }

    public function edit(Shipment $shipment)
    {
        $companies = Company::orderBy('name')->get();
        $orders = Order::orderBy('order_no')->get();
        $shipment->load('trackingEvents');
        return view('admin.shipments.form', compact('shipment', 'companies', 'orders'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $data = $this->validated($request, $shipment->id);
        $before = $shipment->only('status');

        if ($before['status'] !== $data['status']) {
            $data['status_updated_at'] = now();
            ShipmentTrackingEvent::create([
                'shipment_id' => $shipment->id,
                'status' => $data['status'],
                'location' => $request->input('event_location'),
                'remarks' => $request->input('event_remarks'),
                'event_at' => now(),
            ]);
            AuditLog::record('shipment.status_changed', $shipment, $before, ['status' => $data['status']]);
        }

        $shipment->update($data);

        return redirect('/admin/shipments')->with('success', 'Shipment updated.');
    }

    public function destroy(Shipment $shipment)
    {
        AuditLog::record('shipment.deleted', $shipment, $shipment->only('awb_number'), null);
        $shipment->delete();

        return back()->with('success', 'Shipment deleted.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'company_id' => 'required|exists:companies,id',
            'order_id' => 'nullable|exists:orders,id',
            'awb_number' => 'required|string|max:100',
            'carrier' => 'required|string|max:100',
            'origin' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'status' => 'required|in:booked,in_transit,arrived_at_port,delivered',
        ]);
    }
}

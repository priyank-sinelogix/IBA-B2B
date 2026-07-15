<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Company;
use App\Models\Order;
use App\Models\OrderStageLog;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private array $stages = ['cutting', 'sewing', 'qc_inspection', 'packing', 'dispatched'];

    public function index(Request $request)
    {
        $query = Order::with('company');
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }
        $orders = $query->latest()->paginate(15);
        $companies = Company::orderBy('name')->get();

        return view('admin.orders.index', compact('orders', 'companies'));
    }

    public function create()
    {
        $order = new Order();
        $companies = Company::orderBy('name')->get();
        return view('admin.orders.form', compact('order', 'companies', 'stages'))
            ->with('stages', $this->stages);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $order = Order::create($data);

        OrderStageLog::create([
            'order_id' => $order->id,
            'stage' => $order->current_stage,
            'changed_by' => $request->user()->id,
            'changed_at' => now(),
        ]);

        AuditLog::record('order.created', $order, null, $order->only('order_no', 'current_stage'));

        return redirect('/admin/orders')->with('success', 'Order created.');
    }

    public function edit(Order $order)
    {
        $companies = Company::orderBy('name')->get();
        $order->load('stageLogs');
        return view('admin.orders.form', compact('order', 'companies'))
            ->with('stages', $this->stages);
    }

    public function update(Request $request, Order $order)
    {
        $data = $this->validated($request, $order->id);
        $before = $order->only('current_stage');

        $order->update($data);

        if ($before['current_stage'] !== $order->current_stage) {
            OrderStageLog::create([
                'order_id' => $order->id,
                'stage' => $order->current_stage,
                'changed_by' => $request->user()->id,
                'changed_at' => now(),
            ]);
            AuditLog::record('order.stage_changed', $order, $before, $order->only('current_stage'));
        }

        return redirect('/admin/orders')->with('success', 'Order updated.');
    }

    public function destroy(Order $order)
    {
        AuditLog::record('order.deleted', $order, $order->only('order_no'), null);
        $order->delete();

        return back()->with('success', 'Order deleted.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'company_id' => 'required|exists:companies,id',
            'order_no' => 'required|string|max:100|unique:orders,order_no'.($ignoreId ? ",$ignoreId" : ''),
            'style_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'current_stage' => 'required|in:'.implode(',', $this->stages),
            'eta' => 'nullable|date',
        ]);
    }
}

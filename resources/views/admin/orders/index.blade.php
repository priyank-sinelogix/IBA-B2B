@extends('admin.layouts.admin')
@section('title', 'Orders')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Orders</h3>
        <a href="{{ url('/admin/orders/create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus mr-1"></i> New Order</a>
    </div>
    <div class="card-body p-0">
        <form class="d-flex p-3 border-bottom" method="GET">
            <select name="company_id" class="form-control" style="max-width:220px;" onchange="this.form.submit()">
                <option value="">All Clients</option>
                @foreach($companies as $c)
                    <option value="{{ $c->id }}" {{ request('company_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </form>
        <table class="table table-hover mb-0">
            <thead><tr><th>Order No.</th><th>Client</th><th>Style</th><th>Stage</th><th>Qty</th><th>ETA</th><th></th></tr></thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->order_no }}</td>
                    <td>{{ $order->company->name }}</td>
                    <td>{{ $order->style_name }}</td>
                    <td><span class="badge badge-info text-capitalize">{{ str_replace('_',' ',$order->current_stage) }}</span></td>
                    <td>{{ number_format($order->quantity) }} Pcs</td>
                    <td>{{ optional($order->eta)->format('d M Y') }}</td>
                    <td>
                        <a href="{{ url('/admin/orders/'.$order->id.'/edit') }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form method="POST" action="{{ url('/admin/orders/'.$order->id) }}" class="d-inline" onsubmit="return confirm('Delete this order?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted p-4">No orders yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $orders->links() }}</div>
</div>
@endsection

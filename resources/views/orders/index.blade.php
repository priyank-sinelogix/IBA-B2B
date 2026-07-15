@extends('layouts.admin')
@section('title', 'Orders')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Active Orders</h3></div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>Order No.</th><th>Style Name</th><th>Stage</th><th>Quantity</th><th>ETA</th></tr></thead>
            <tbody>
            @forelse($orders ?? [] as $order)
                <tr>
                    <td>{{ $order->order_no }}</td>
                    <td>{{ $order->style_name }}</td>
                    <td><span class="badge badge-info text-capitalize">{{ str_replace('_',' ',$order->current_stage) }}</span></td>
                    <td>{{ number_format($order->quantity) }} Pcs</td>
                    <td>{{ optional($order->eta)->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted p-4">
                    No records yet. Connect <code>$orders</code> from <code>OrderWebController@index</code>.
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ ($orders ?? null)?->links() }}</div>
</div>
@endsection

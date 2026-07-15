@extends('admin.layouts.admin')
@section('title', 'Shipments')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Shipments</h3>
        <a href="{{ url('/admin/shipments/create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus mr-1"></i> New Shipment</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>AWB</th><th>Client</th><th>Carrier</th><th>Origin</th><th>Destination</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse($shipments as $shipment)
                <tr>
                    <td>{{ $shipment->awb_number }}</td>
                    <td>{{ $shipment->company->name }}</td>
                    <td>{{ $shipment->carrier }}</td>
                    <td>{{ $shipment->origin }}</td>
                    <td>{{ $shipment->destination }}</td>
                    <td><span class="badge badge-info text-capitalize">{{ str_replace('_',' ',$shipment->status) }}</span></td>
                    <td>
                        <a href="{{ url('/admin/shipments/'.$shipment->id.'/edit') }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form method="POST" action="{{ url('/admin/shipments/'.$shipment->id) }}" class="d-inline" onsubmit="return confirm('Delete this shipment?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted p-4">No shipments yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $shipments->links() }}</div>
</div>
@endsection

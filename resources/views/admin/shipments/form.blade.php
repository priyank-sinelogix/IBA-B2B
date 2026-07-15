@extends('admin.layouts.admin')
@section('title', $shipment->exists ? 'Edit Shipment' : 'New Shipment')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card p-0">
            <div class="card-header"><h3 class="card-title">{{ $shipment->exists ? 'Edit' : 'New' }} Shipment</h3></div>
            <form method="POST" action="{{ $shipment->exists ? url('/admin/shipments/'.$shipment->id) : url('/admin/shipments') }}">
                @csrf
                @if($shipment->exists) @method('PUT') @endif
                <div class="card-body">
                    @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

                    <div class="form-group">
                        <label>Client Company</label>
                        <select name="company_id" class="form-control" required>
                            <option value="">-- Select --</option>
                            @foreach($companies as $c)
                                <option value="{{ $c->id }}" {{ old('company_id', $shipment->company_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Linked Order (optional)</label>
                        <select name="order_id" class="form-control">
                            <option value="">-- None --</option>
                            @foreach($orders as $o)
                                <option value="{{ $o->id }}" {{ old('order_id', $shipment->order_id) == $o->id ? 'selected' : '' }}>{{ $o->order_no }} — {{ $o->style_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>AWB / Tracking No.</label>
                        <input type="text" name="awb_number" class="form-control" value="{{ old('awb_number', $shipment->awb_number) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Carrier</label>
                        <input type="text" name="carrier" class="form-control" value="{{ old('carrier', $shipment->carrier) }}" placeholder="MAERSK / MSC / CMA CGM" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label>Origin</label>
                            <input type="text" name="origin" class="form-control" value="{{ old('origin', $shipment->origin) }}">
                        </div>
                        <div class="form-group col-6">
                            <label>Destination</label>
                            <input type="text" name="destination" class="form-control" value="{{ old('destination', $shipment->destination) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            @foreach(['booked','in_transit','arrived_at_port','delivered'] as $s)
                                <option value="{{ $s }}" {{ old('status', $shipment->status ?? 'booked') == $s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if($shipment->exists)
                    <hr>
                    <p class="text-muted small mb-1">If you change the status above, these fields log the tracking event:</p>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label>Event Location</label>
                            <input type="text" name="event_location" class="form-control" placeholder="e.g. Los Angeles Port">
                        </div>
                        <div class="form-group col-6">
                            <label>Remarks</label>
                            <input type="text" name="event_remarks" class="form-control" placeholder="e.g. Cleared customs">
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary">Save</button>
                    <a href="{{ url('/admin/shipments') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @if($shipment->exists)
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Tracking Timeline</h3></div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($shipment->trackingEvents as $event)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span class="text-capitalize font-weight-bold">{{ str_replace('_',' ',$event->status) }}</span>
                                <span class="text-muted small">{{ $event->event_at->format('d M Y, h:i A') }}</span>
                            </div>
                            <div class="text-muted small">{{ $event->location }} — {{ $event->remarks }}</div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted text-center">No tracking events yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

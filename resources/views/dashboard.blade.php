@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

<!-- Stat cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-white p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box mr-3" style="background:#e9fbf3;color:#0fb98a;width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-tshirt"></i>
                </div>
                <div>
                    <div class="text-muted small">Samples Awaiting Approval</div>
                    <div class="h3 mb-0">{{ $stats['samples_pending'] ?? 8 }}</div>
                </div>
            </div>
            <a href="{{ url('/samples?status=pending') }}" class="small">View All</a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-white p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box mr-3" style="background:#e8f1fd;color:#2563eb;width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-box"></i>
                </div>
                <div>
                    <div class="text-muted small">Active Orders</div>
                    <div class="h3 mb-0">{{ $stats['active_orders'] ?? 12 }}</div>
                </div>
            </div>
            <a href="{{ url('/orders') }}" class="small">View All</a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-white p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box mr-3" style="background:#e9fbf3;color:#0fb98a;width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div>
                    <div class="text-muted small">Account Balance</div>
                    <div class="h4 mb-0">USD {{ number_format($stats['balance'] ?? 48750.60, 2) }}</div>
                </div>
            </div>
            <a href="{{ url('/finance') }}" class="small">View Statement</a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-white p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box mr-3" style="background:#f2ecfe;color:#7c3aed;width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-truck"></i>
                </div>
                <div>
                    <div class="text-muted small">Shipments In Transit</div>
                    <div class="h3 mb-0">{{ $stats['shipments_in_transit'] ?? 5 }}</div>
                </div>
            </div>
            <a href="{{ url('/shipments') }}" class="small">View All</a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Pending Approvals -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Pending Approvals</h3>
                <a href="{{ url('/samples') }}" class="small">View All</a>
            </div>
            <div class="card-body p-0">
                @forelse($pendingSamples ?? [] as $sample)
                <div class="d-flex align-items-center p-3 border-bottom">
                    <img src="{{ $sample->latestVersion->signedImageUrl() ?? 'https://via.placeholder.com/60' }}" width="56" height="56" style="object-fit:cover;border-radius:8px;" class="mr-3">
                    <div class="flex-grow-1">
                        <div class="font-weight-bold">{{ $sample->sample_code }} | {{ $sample->style_name }}</div>
                        <div class="text-muted small">Fabric: {{ $sample->fabric }}</div>
                        <div class="text-muted small">Color: {{ $sample->color }}</div>
                        <div class="text-muted small">Submitted on {{ optional($sample->submitted_at)->format('d M Y') }}</div>
                    </div>
                    <span class="badge badge-{{ $sample->status == 'pending' ? 'pending' : 'changes' }} mr-3">
                        {{ $sample->status == 'pending' ? 'Pending' : 'Changes Requested' }}
                    </span>
                    <form method="POST" action="{{ url('/samples/'.$sample->id.'/approve') }}" class="mr-2">@csrf
                        <button class="btn btn-sm btn-outline-success">Approve</button>
                    </form>
                    <a href="{{ url('/samples/'.$sample->id) }}" class="btn btn-sm btn-outline-secondary">Revise</a>
                </div>
                @empty
                <div class="p-4 text-center text-muted">No sample data yet — connect the SampleController query here.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Order Tracking -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Order Tracking</h3>
                <a href="{{ url('/orders') }}" class="small">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Order No.</th><th>Style Name</th><th>Stage</th><th>Qty</th><th>ETA</th></tr></thead>
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
                        <tr><td colspan="5" class="text-center text-muted p-4">No orders yet — connect the OrderController query here.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Account Statement -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Account Statement</h3></div>
            <div class="card-body">
                <div class="text-muted small">Current Balance</div>
                <div class="h3 text-success">USD {{ number_format($stats['balance'] ?? 48750.60, 2) }}</div>
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Credit Limit: USD {{ number_format($stats['credit_limit'] ?? 100000, 2) }}</span>
                    <span>{{ $stats['credit_used_pct'] ?? 51 }}% Used</span>
                </div>
                <div class="progress mb-3" style="height:8px;">
                    <div class="progress-bar bg-success" style="width: {{ $stats['credit_used_pct'] ?? 51 }}%"></div>
                </div>
                <a href="{{ url('/finance/statement/download') }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-download mr-1"></i> Download Statement</a>
            </div>
        </div>
    </div>

    <!-- Recent Communication -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Recent Communication</h3>
                <a href="{{ url('/messages') }}" class="small">View All</a>
            </div>
            <div class="card-body p-0">
                @forelse($recentMessages ?? [] as $msg)
                <div class="d-flex p-3 border-bottom">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mr-3" style="width:38px;height:38px;">
                        {{ substr($msg->sender->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="font-weight-bold small">{{ $msg->sender->name ?? 'User' }}</div>
                        <div class="text-muted small">{{ $msg->body }}</div>
                    </div>
                    <div class="text-muted small">{{ $msg->created_at->diffForHumans() }}</div>
                </div>
                @empty
                <div class="p-4 text-center text-muted">No messages yet — connect the MessageController query here.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

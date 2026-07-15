<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'company_id', 'order_id', 'awb_number', 'carrier', 'origin',
        'destination', 'status', 'status_updated_at',
    ];

    protected $casts = [
        'status_updated_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function trackingEvents()
    {
        return $this->hasMany(ShipmentTrackingEvent::class)->orderBy('event_at');
    }
}

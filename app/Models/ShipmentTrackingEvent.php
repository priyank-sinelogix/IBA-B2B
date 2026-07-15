<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentTrackingEvent extends Model
{
    protected $fillable = ['shipment_id', 'status', 'location', 'remarks', 'event_at'];

    protected $casts = ['event_at' => 'datetime'];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}

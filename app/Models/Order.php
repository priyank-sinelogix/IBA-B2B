<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'company_id', 'order_no', 'style_name', 'quantity', 'current_stage', 'eta',
    ];

    protected $casts = [
        'eta' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function stageLogs()
    {
        return $this->hasMany(OrderStageLog::class)->orderBy('changed_at');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}

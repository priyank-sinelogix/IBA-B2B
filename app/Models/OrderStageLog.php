<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStageLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['order_id', 'stage', 'changed_by', 'changed_at'];

    protected $casts = ['changed_at' => 'datetime'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

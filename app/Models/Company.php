<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'credit_limit', 'current_balance', 'logo_path', 'is_active',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function samples()
    {
        return $this->hasMany(Sample::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function ledgerEntries()
    {
        return $this->hasMany(LedgerEntry::class);
    }

    public function creditUsedPercent(): float
    {
        if ((float) $this->credit_limit <= 0) {
            return 0;
        }

        return round(((float) $this->current_balance / (float) $this->credit_limit) * 100, 1);
    }
}

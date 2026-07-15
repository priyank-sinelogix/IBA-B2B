<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'company_id', 'user_id', 'action', 'subject_type', 'subject_id', 'changes', 'ip_address', 'created_at',
    ];

    protected $casts = [
        'changes' => 'array',
        'created_at' => 'datetime',
    ];

    public static function record(string $action, Model $subject, ?array $before = null, ?array $after = null): self
    {
        return static::create([
            'company_id' => $subject->company_id ?? null,
            'user_id' => auth()->id(),
            'action' => $action,
            'subject_type' => class_basename($subject),
            'subject_id' => $subject->id,
            'changes' => ['before' => $before, 'after' => $after],
            'ip_address' => request()?->ip(),
            'created_at' => now(),
        ]);
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}

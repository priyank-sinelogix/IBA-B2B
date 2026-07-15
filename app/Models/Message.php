<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'company_id', 'sender_id', 'linked_type', 'linked_id', 'body', 'is_read', 'attachment_path',
    ];

    protected $casts = ['is_read' => 'boolean'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // e.g. Message::forLinked('sample', $sampleId, $companyId)
    public function scopeForLinked($query, string $type, int $id, int $companyId)
    {
        return $query->where('company_id', $companyId)
            ->where('linked_type', $type)
            ->where('linked_id', $id)
            ->orderBy('created_at');
    }
}

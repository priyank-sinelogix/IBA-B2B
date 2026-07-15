<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleComment extends Model
{
    protected $fillable = [
        'sample_id', 'sample_version_id', 'user_id', 'comment', 'action',
    ];

    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

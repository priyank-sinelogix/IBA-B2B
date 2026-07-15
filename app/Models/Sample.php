<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    protected $fillable = [
        'company_id', 'sample_code', 'style_name', 'fabric', 'color',
        'status', 'submitted_by', 'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function versions()
    {
        return $this->hasMany(SampleVersion::class)->orderBy('version_no');
    }

    public function latestVersion()
    {
        return $this->hasOne(SampleVersion::class)->latestOfMany('version_no');
    }

    public function comments()
    {
        return $this->hasMany(SampleComment::class)->latest();
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}

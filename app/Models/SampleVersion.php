<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleVersion extends Model
{
    protected $fillable = [
        'sample_id', 'version_no', 'image_path', 'fabric_swatch_path', 'notes', 'uploaded_by',
    ];

    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Returns a temporary signed URL instead of a public path (private S3 disk)
    public function signedImageUrl(int $minutes = 15): string
    {
        return \Storage::disk('s3')->temporaryUrl($this->image_path, now()->addMinutes($minutes));
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    protected $fillable = [
        'user_id',
        'file',
        'row_count',
        'row_processed',
        'status'
    ];

    protected $casts = [
        'status' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUploadedTimeCounterAttribute(): string
    {
        return $this->created_at->diffForHumans(Carbon::now());
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            0 => 'Pending',
            1 => 'Processing',
            2 => 'Failed',
            3 => 'Completed',
        };
    }
}

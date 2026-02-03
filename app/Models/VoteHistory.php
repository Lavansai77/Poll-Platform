<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoteHistory extends Model
{
    protected $fillable = [
        'poll_id',
        'option_id',
        'ip_address',
        'status',
        'released_at',
    ];

    protected $casts = [
        'released_at' => 'datetime',
    ];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(PollOption::class, 'option_id');
    }
}

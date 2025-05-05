<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestChange extends Model
{
    protected $table = 'request_changes';

    protected $fillable = [
        'changeable_type',
        'changeable_id',
        'category',
        'notes',
        'status',
        'changes',
        'created_by',
        'approved_by',
        'approved_at',
        'is_archived',
        'archived_at',
        'client_id'
    ];

    protected $casts = [
        'changes' => 'array',
        'approved_at' => 'datetime',
        'archived_at' => 'datetime',
        'is_archived' => 'boolean'
    ];

    public function changeable(): MorphTo
    {
        return $this->morphTo();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public static function statuses(): array
    {
        return [
            'pending' => 'Pending',
            'request-revision' => 'Request Revision',
            'approved' => 'Approved',
            'rejected' => 'Rejected'
        ];
    }
}
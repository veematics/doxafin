<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_alias',
        'company_code',
        'company_address',
        'npwp',
        'website',
        'assign_to',
        'payment_terms', // Added this line
        'notes',
        'created_by',
        'assigned_at',
        'created_by'
    ];

    protected $casts = [
        'social_profiles' => 'array',
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assign_to');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_at');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
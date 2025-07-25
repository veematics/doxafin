<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Note extends Model
{
    protected $table = 'notes';
    protected $primaryKey = 'notesID';
    public $timestamps = true;
    
    protected $fillable = [
        'notesCategory',
        'notesObjID',
        'notesTitle',
        'notesSummary',
        'notesDetail',
        'notesBy',
        'notesHide'
    ];

    protected $casts = [
        'notesHide' => 'boolean'
    ];

    /**
     * Get the created_at attribute with Asia/Jakarta timezone
     */
    public function getCreatedAtAttribute($value)
    {
        if ($value) {
            return $this->asDateTime($value)->setTimezone('Asia/Jakarta');
        }
        return $value;
    }

    /**
     * Get the updated_at attribute with Asia/Jakarta timezone
     */
    public function getUpdatedAtAttribute($value)
    {
        if ($value) {
            return $this->asDateTime($value)->setTimezone('Asia/Jakarta');
        }
        return $value;
    }

    /**
     * Get the owning noteable model (polymorphic relationship).
     * This allows notes to be attached to any model like PurchaseOrder, Invoice, etc.
     */
    public function noteable(): MorphTo
    {
        return $this->morphTo('noteable', 'notesCategory', 'notesObjID');
    }

    /**
     * Scope to get visible notes only
     */
    public function scopeVisible($query)
    {
        return $query->where('notesHide', 0)->orWhereNull('notesHide');
    }

    /**
     * Scope to get hidden notes only
     */
    public function scopeHidden($query)
    {
        return $query->where('notesHide', 1);
    }

    /**
     * Scope to filter by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('notesCategory', $category);
    }
}
<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InboxMessage extends Model
{
    use HasFactory;
    // use SoftDeletes; // Uncomment this if using soft deletes in migration

    protected $fillable = [
        'sent_to',
        'subject',
        'message',
        'priority_status',
        'message_category', // Add this line
        'sent_from',
        'message_parent_id',
        'is_read',
        'priority_status',
        'sent_from',
        'sent_to',
        'deleted_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Get the user who sent the message.
     * Returns null if it's a system message.
     */
    public function sender(): BelongsTo
    {
        // Assuming your User model is in App\Models\User
        return $this->belongsTo(User::class, 'sent_from');
    }

    /**
     * Get the user who received the message.
     */
    public function recipient(): BelongsTo
    {
        // Assuming your User model is in App\Models\User
        return $this->belongsTo(User::class, 'sent_to');
    }

    /**
     * Get the parent message this message is a reply to.
     */
    public function parentMessage(): BelongsTo
    {
        return $this->belongsTo(InboxMessage::class, 'message_parent_id');
    }

    /**
     * Get the replies to this message.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(InboxMessage::class, 'message_parent_id');
    }

    /**
     * Scope a query to only include messages for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('sent_to', $userId);
    }

    /**
     * Check if the message is from the system.
     */
    public function isSystemMessage(): bool
    {
        return is_null($this->sent_from);
    }

     /**
     * Check if the message can be replied to.
     * (Not a system message)
     */
    public function canBeRepliedTo(): bool
    {
        return !$this->isSystemMessage();
    }

    /**
     * Check if the message can be deleted by the recipient.
     * (Only if it's unread)
     */
    public function canBeDeleted(): bool
    {
        return !$this->is_read;
    }


}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';
    protected $primaryKey = 'logID';
    
    protected $fillable = [
        'logCategory',
        'logAction',
        'logObjID',
        'logBy',
        'logNotes'
    ];

    protected $casts = [
        'logObjID' => 'integer',
    ];

    /**
     * Get formatted created date in GMT+7
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') : 'N/A';
    }

    /**
     * Get category name (now stored as string)
     */
    public function getCategoryNameAttribute()
    {
        return $this->logCategory ?? 'Unknown';
    }

    /**
     * Get action name (now stored as string)
     */
    public function getActionNameAttribute()
    {
        return $this->logAction ?? 'Unknown';
    }

    /**
     * Scope to filter by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('logCategory', $category);
    }

    /**
     * Scope to filter by action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('logAction', $action);
    }

    /**
     * Scope to filter by object ID
     */
    public function scopeByObject($query, $objId)
    {
        return $query->where('logObjID', $objId);
    }

    /**
     * Scope to filter by user
     */
    public function scopeByUser($query, $user)
    {
        return $query->where('logBy', $user);
    }

    /**
     * Relationship to Purchase Order when logCategory is 'Purchase Order'
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'logObjID', 'poID')
                    ->where('logCategory', 'Purchase Order');
    }

    /**
     * Get the related object based on category
     */
    public function getRelatedObject()
    {
        switch ($this->logCategory) {
            case 'Purchase Order':
                return $this->purchaseOrder;
            // Add more cases for other categories as needed
            default:
                return null;
        }
    }
}
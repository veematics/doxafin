<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageCategory extends Model
{
    protected $fillable = ['messageCategoryName'];

    public function messages()
    {
        return $this->hasMany(InboxMessage::class, 'message_category_id');
    }
}
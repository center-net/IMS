<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'action',
        'route',
        'method',
        'ip',
        'user_agent',
        'model_type',
        'model_id',
        'context',
        'old_values',
        'new_values',
        'message',
        'locale',
    ];

    protected $casts = [
        'context' => 'array',
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * The user associated with this log entry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The related model (subject) referenced by model_type/model_id.
     */
    public function subject()
    {
        return $this->morphTo(null, 'model_type', 'model_id');
    }
}

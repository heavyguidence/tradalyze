<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PositionScreenshot extends Model
{
    protected $fillable = ['position_id', 'path', 'original_name'];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }
}

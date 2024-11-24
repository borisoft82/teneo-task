<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Calendar extends Model
{
    use HasFactory;
    
    protected $fillable = ['date', 'absence_id', 'absence_period'];

    protected $casts = [
        'date' => 'datetime:Y-m-d',
    ];

    public function absence(): BelongsTo
    {
        return $this->belongsTo(Absence::class);
    }
}

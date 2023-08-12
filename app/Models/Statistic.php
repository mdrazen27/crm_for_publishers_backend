<?php

namespace App\Models;

use App\Traits\FilterByPublisher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Statistic extends Model
{
    use HasFactory, FilterByPublisher;

    protected $casts = [
        'count' => 'integer',
        'date' => 'date:m.d.Y'
    ];

    protected $fillable = [
        'count',
        'date',
        'publisher_id',
        'country',
        'advertisement_id',
    ];


    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class);
    }
}
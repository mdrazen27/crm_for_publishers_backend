<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Statistic extends Model
{
    use HasFactory;

    //todo check whether single model can communicate with two DBs one being MySql and other Redis

    protected $casts = [
        'count' => 'integer',
        'date' => 'date:m.d.Y'
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publisher extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        'name',
        'user_id',
        'active',
    ];

    public $casts = [
        'active' => 'boolean'
    ];

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function advertisements(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }

    public function statistics(): HasMany
    {
        return $this->hasMany(Statistic::class);
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function delete()
    {
        $this->user->delete();
        foreach ($this->advertisements as $advertisement){
            $advertisement->delete();
        }
        return parent::delete();
    }
}

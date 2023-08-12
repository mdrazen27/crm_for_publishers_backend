<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function publisher(): HasOne
    {
        return $this->hasOne(Publisher::class);
    }

    public function isAdmin(): bool
    {
        return $this->role_id === 1;
    }

    public function isPublisher(): bool
    {
        return $this->role_id === 2;
    }

    public function createJwtToken(): string
    {
        return JWT::encode([
                'user_id' => $this->id,
                'iss' => config('app.url'),
                'iat' => Carbon::now()->timestamp,
                'exp' => Carbon::now()->addWeek()->timestamp
            ]
            , config('jwt.key'), 'HS256');
    }


    public function delete()
    {
        $this->email = Hash::make(20)."-". $this->id . "@scrambled.com";
        $this->save();
        return parent::delete();
    }
}

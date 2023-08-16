<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::viaRequest('jwt', function (Request $request) {
            return $this->authenticateUser($request->bearerToken());
        });
    }

    private function authenticateUser(?string $bearerToken): ?User
    {
        if (!$bearerToken) {
            return null;
        }
        try {
            $tokenPayload = JWT::decode($bearerToken, new Key(config('jwt.key'), 'HS256'));
            $user = User::firstWhere('id', $tokenPayload->user_id);
            if ($user->isAdmin() || $user->publisher->isActive()) {
                return $user;
            }
            return null;
        } catch (\Exception|\Error $e) {
            Log::error($e);
            return null;
        }
    }
}

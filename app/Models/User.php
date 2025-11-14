<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_GYM_ADMIN = 'gym_admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Guard name used by the roles/permissions package.
     *
     * @var string
     */
    protected string $guard_name = 'web';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Register a new user and assign the provided role.
     */
    public static function registerWithRole(array $attributes, ?string $role = null): self
    {
        $user = static::create(Arr::only($attributes, ['name', 'email', 'password']));

        if ($role !== null) {
            $user->assignRole($role);
        }

        return $user->load('roles');
    }

    /**
     * Attempt to authenticate a user with the provided credentials.
     */
    public static function attemptLogin(string $email, string $password): ?self
    {
        $user = static::where('email', $email)->first();

        if (! $user instanceof self) {
            return null;
        }

        return Hash::check($password, $user->getAuthPassword()) ? $user : null;
    }

    /**
     * Issue a Sanctum token for SPA access.
     */
    public function issueToken(string $tokenName = 'spa-token'): string
    {
        return $this->createToken($tokenName)->plainTextToken;
    }

    /**
     * Remove the current access token if it exists.
     */
    public function revokeCurrentAccessToken(): void
    {
        $token = $this->currentAccessToken();

        if ($token !== null) {
            $token->delete();
        }
    }

    /**
     * Determine if the user can reach dashboard endpoints.
     */
    public function canAccessDashboard(): bool
    {
        return $this->hasAnyRole([self::ROLE_SUPER_ADMIN, self::ROLE_GYM_ADMIN]);
    }
}

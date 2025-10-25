<?php

namespace App\Models\Abstract;

use App\Traits\Models\Blockable;
use App\Traits\Models\HasLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

/**
 * This is the basic abstract class of the consumer model.
 * The model expanding this class contains basic fields and methods of consumer in the system.
 */
abstract class Consumer extends Authenticatable
{
    use Blockable, HasLog;

    // These fields should have every consumer
    protected $fillable = [
        'type',
        'permissions', // See app.consumer.types.moderator.permits
        'settings', // See app.consumer.types.moderator.settings
        'email',
        'verify_email_token',
        'last_activity_at',
        'password',
        'log',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'last_activity_at' => 'datetime',
        ];
    }

    /**
     * Boot method for Consumer base functionality.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            // Hashed password if specified (not seeder)
            $password = request('password');
            if ($password) {
                $model->password = Hash::make($password);
            }

            // Set default permissions
            if (!$model->permissions) {
                $model->permissions = json_encode(config("app.consumers.types.{$model->type}.permits", []));
            }

            // Set default settings
            if (!$model->settings) {
                $model->settings = json_encode(config("app.consumers.types.{$model->type}.settings", []));
            }
        });
    }

    ##########################################
    ## Consumer Settings
    ##########################################

    /**
     * Consumer settings
     *
     * @return Attribute
     */
    protected function settings(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value ?? '', true),
        );
    }

    /**
     * Called without a parameter will return array of all settings.
     * Pass the parameter in dot notation to get the desired settings or null.
     *
     * @param string|null $dotTargetKey
     * @param mixed $default
     * @return mixed
     */
    public function getConsumerSettings(string|null $dotTargetKey = null, mixed $default = null): mixed
    {
        $settings = $this->user()->settings;
        if (empty($dotTargetKey)) return $settings;
        return data_get($settings, $dotTargetKey, $default);
    }

    ##########################################
    ## Consumer Permissions
    ##########################################

    /**
     * Consumer permissions
     *
     * @return Attribute
     */
    protected function permissions(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value ?? '', true),
        );
    }

    /**
     * The level of Consumer
     * permission from maximum value
     *
     * @return string
     */
    public function permissionsLevel(): string
    {
        $maxLevel = (
            count((array) config('app.consumers.permissions.abilities')) *
            count((array) config('app.consumers.permissions.scopes'))
        );
        $level = strlen(
            array_reduce(
                $this->permissions,
                fn($carry, $val) => $carry . $val
            )
        );

        return "$level/$maxLevel";
    }

    /**
     * Check permission of consumer
     * to manage in scope with abilities. By default,
     * all abilities should be in admin scope permissions.
     * If $boolean is 'or', at least one of the abilities
     * should be
     *
     * @param string $scope
     * @param string $abilities
     * @param string $boolean 'and' || 'or'
     * @return bool
     * @see /config/app.php consumers
     *
     */
    public function isPermits(
        string $scope,
        string $abilities,
        string $boolean = 'and'
    ): bool
    {
        if (
            ! key_exists($scope, $this->permissions) ||
            ! in_array($boolean, ['and', 'or'], true) ||
            empty($abilities)
        ) {
            throw new \InvalidArgumentException;
        }

        $abilities = str_split($abilities);
        $permissions = str_split($this->permissions[$scope]);

        if ($boolean === 'or') return (bool) array_intersect($abilities, $permissions);

        return !array_diff($abilities, $permissions);
    }

    ##########################################
    ## Consumer Activity
    ##########################################

    /**
     * Retrieve consumer last activity datetime (online)
     *
     * @return Carbon|null
     */
    public function lastActivity(): Carbon|null
    {
        return $this->last_activity_at;
    }

    /**
     * Refresh consumer last activity datetime (online)
     *
     * @return Authenticatable
     */
    public function refreshActivity(): Authenticatable
    {
        $this->last_activity_at = now();
        $this->saveQuietly();
        return $this;
    }

    /**
     * Check whether an active consumer is now (online)
     *
     * @return bool
     */
    public function isActive(): bool
    {
        $lastActivity = $this->lastActivity();
        if (!$lastActivity) {
            return false;
        }

        $expiryMinutes = config("app.consumers.types.{$this->type}.activityExpiry", 3);
        $minutesAgo = abs(now()->diffInMinutes($lastActivity));
        return $minutesAgo <= $expiryMinutes;
    }

    ##########################################
    ## Other
    ##########################################

    /**
     * Get the name attribute by transforming the email.
     *
     * @return Attribute
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => '@' . explode('@', $this->email)[0],
        );
    }

    /**
     * Is mail confirmed
     *
     * @return bool
     */
    public function isVerifiedEmail(): bool
    {
        return !$this->verify_email_token;
    }
}

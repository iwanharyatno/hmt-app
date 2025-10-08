<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Constant keys for all configurable settings
     */
    public const HMT_DESCRIPTION = 'hmt_description';
    public const HMT_PRIVACY = 'hmt_privacy';
    public const LS_DESCRIPTION = 'ls_description';
    public const LS_PRIVACY = 'ls_privacy';
    public const HMT_DURATION = 'hmt_duration';

    /**
     * Get a setting value by key
     */
    public static function getValue(string $key, $default = null): ?string
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    /**
     * Set or update a setting value
     */
    public static function setValue(string $key, $value): self
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Get all system keys (useful for seeding or initialization)
     */
    public static function allKeys(): array
    {
        return [
            self::HMT_DESCRIPTION,
            self::HMT_PRIVACY,
            self::LS_DESCRIPTION,
            self::LS_PRIVACY,
            self::HMT_DURATION
        ];
    }
}

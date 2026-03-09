<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class CommaSeparated implements CastsAttributes
{
    /**
     * Cast the given value (from DB string to array).
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (is_null($value) || $value === '') {
            return [];
        }

        return array_map('trim', explode(',', $value));
    }

    /**
     * Prepare the given value for storage (from array to comma-separated string).
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if (is_array($value)) {
            $filtered = array_filter(array_map('trim', $value), fn ($v) => $v !== '');

            return empty($filtered) ? null : implode(',', $filtered);
        }

        return (string) $value;
    }
}

<?php

namespace Spotler\Services;

use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;
use stdClass;

class Properties
{
    public static function get(array $list, string $find): ?stdClass
    {
        foreach ($list as $property) {
            if ($property->name === $find) {
                return $property;
            }
        }
        return null;
    }



    #[Pure]
    public static function getPermissions(array $list): ?stdClass
    {
        return self::get($list, 'permissions');
    }



    public static function modifyPermission(Collection $permissions, int $bit, bool $enabled): Collection
    {
        return $permissions->map(function ($item, $key) use ($bit, $enabled) {
            if ($item->bit === $bit) {
                $item->enabled = $enabled;
            }
            return $item;
        });
    }
}

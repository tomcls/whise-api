<?php

/*
 * This file is part of the fw4/whise-api library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Whise\Api\Enums;

abstract class Enum
{
    /**
     * @var array
     */
    protected static $constants = [];

    /**
     * @var array
     */
    protected static $values = [];

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Get all possible values for this enum, with names as keys
     *
     * @return array Associative array with constant name as key and its value
     */
    public static function assoc(): array
    {
        $classname = get_called_class();
        if (!array_key_exists($classname, static::$constants)) {
            static::$constants[$classname] = (new \ReflectionClass($classname))->getConstants();
        }
        return static::$constants[$classname];
    }

    /**
     * Get all possible values for this enum
     *
     * @return array Array of values
     */
    public static function all(): array
    {
        $classname = get_called_class();
        if (!array_key_exists($classname, static::$values)) {
            static::$values[$classname] = [];
            $assoc = self::assoc();
            array_walk_recursive($assoc, function ($value) use ($classname) {
                static::$values[$classname][] = $value;
            });
        }
        return static::$values[$classname];
    }
}

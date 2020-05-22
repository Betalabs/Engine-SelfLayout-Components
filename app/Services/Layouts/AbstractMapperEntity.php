<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts;


use Illuminate\Support\Str;

abstract class AbstractMapperEntity
{
    /**
     * Parse array data from layout packages configuration json files.
     *
     * @param array $data
     *
     * @return static
     */
    public static function fromArray(array $data)
    {
        $instance = resolve(static::class);

        foreach ($data as $configuration => $value) {
            if (is_array($value)) {
                $value = collect($value);
            }

            $setterMethod = "set".Str::studly($configuration);
            if (method_exists($instance, $setterMethod)) {
                $instance->{$setterMethod}($value);
            }
        }

        return $instance;
    }
}
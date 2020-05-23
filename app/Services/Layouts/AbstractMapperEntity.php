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
        $instance->fill($data);

        return $instance;
    }

    /**
     * Fill data in self attributes using correspondent setters and resolving
     * related data in new mapper entities instances or primary values.
     *
     * @param array $data
     */
    protected function fill(array $data)
    {
        foreach ($data as $configuration => $value) {
            if (is_array($value)) {
                $value = $this->resolveArrayValue($configuration, $value);
            }

            $setterMethod = "set".Str::studly($configuration);
            if (method_exists($this, $setterMethod)) {
                $this->{$setterMethod}($value);
            }
        }
    }

    /**
     * @param string $configuration
     * @param array  $data
     *
     * @return mixed|\Betalabs\EngineSelfLayoutComponents\Services\Layouts\AbstractMapperEntity
     */
    private function resolveArrayValue(string $configuration, array $data)
    {
        return collect($data)->map(function($value) use ($configuration) {
            if (is_array($value)) {
                // Is an array of arrays (means array of objects)
                $this->resolveRelatedMapperEntityInstance(
                    $configuration,
                    $value
                );
            }

            return $value;
        });
    }

    /**
     * Resolve value of $value variable as a pointer finding and
     * instantiating a new mapper entity and fill data from value array.
     *
     * @param string $configuration
     * @param array  $value
     */
    private function resolveRelatedMapperEntityInstance(
        string $configuration,
        array &$value
    ) {
        $currentMapperEntityNamespace = substr(get_class($this), 0, strrpos(get_class($this), '\\'));
        $relatedClassName = Str::studly(Str::singular($configuration));
        $relatedClassNamespace = Str::studly(Str::plural($configuration));
        $relatedClass = $currentMapperEntityNamespace."\\{$relatedClassNamespace}\\{$relatedClassName}";

        if (class_exists($relatedClass)) {
            tap(new $relatedClass, function ($mapperEntity) use (&$value) {
                if ($mapperEntity instanceof AbstractMapperEntity) {
                    $value = $mapperEntity::fromArray($value);
                }
            });
        }
    }
}
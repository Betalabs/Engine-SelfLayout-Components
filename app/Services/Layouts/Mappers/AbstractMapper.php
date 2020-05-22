<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers;


use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Layout;
use Illuminate\Support\Facades\File;

abstract class AbstractMapper
{
    /**
     * Turn layouts configurations jsons into internal Layout instances
     * collection.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function parseLayouts()
    {
        $configurationsJsons = $this->parseLayoutsConfigurationsJsons();
        $layouts = collect();
        foreach ($configurationsJsons as $configurationsJson) {
            $layouts->push(Layout::fromJson($configurationsJson));
        }

        return $layouts;
    }

    /**
     * Parse vendor layouts configurations json files into collection of jsons.
     *
     * @return \Illuminate\Support\Collection
     */
    private function parseLayoutsConfigurationsJsons()
    {
        $jsons = collect();
        foreach ($this->retrieveLayouts() as $layout => $packageName) {
            $json = $this->loadConfigurationJson($packageName);
            $jsons->put($layout, $json);
        }

        return $jsons;
    }

    /**
     * Load configuration json file from vendor package.
     *
     * @param string $packageName
     *
     * @return \stdClass
     */
    private function loadConfigurationJson(string $packageName)
    {
        $fileContent = File::get(__DIR__."/{$packageName}/configuration.json");
        return json_decode($fileContent);
    }

    /**
     * Retrieve from all layouts configurations.
     *
     * @return array
     */
    abstract protected function retrieveLayouts();

}
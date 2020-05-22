<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Layouts\Mappers\PackageConfigurationsFileDoesNotExistsException;
use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Layouts\Mappers\PackageConfigurationsJsonInvalidContentException;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Layout;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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
        $configurations = $this->parseLayoutsConfigurations();
        $layouts = collect();
        foreach ($configurations as $configuration) {
            $layouts->push(Layout::fromArray($configuration));
        }

        return $layouts;
    }

    /**
     * Parse vendor layouts configurations json files into collection of jsons.
     *
     * @return \Illuminate\Support\Collection
     */
    private function parseLayoutsConfigurations()
    {
        $configurations = collect();
        foreach ($this->retrieveLayouts() as $layout => $packageName) {
            try {
                $configuration = $this->loadConfigurationFileContent($packageName);
                $configurations->put($layout, $configuration);
            } catch (PackageConfigurationsFileDoesNotExistsException $e) {
                Log::info($e->getMessage());
            } catch (PackageConfigurationsJsonInvalidContentException $e) {
                Log::info($e->getMessage());
            }
        }

        return $configurations;
    }

    /**
     * Load configuration json file from vendor package.
     *
     * @param string $packageName
     *
     * @return \stdClass
     */
    private function loadConfigurationFileContent(string $packageName)
    {
        $configurationsFilePath = base_path("vendor/{$packageName}/configuration.json");
        if (!File::exists($configurationsFilePath)) {
            throw new PackageConfigurationsFileDoesNotExistsException(
                "Package {$packageName} configurations.json file does not exists in `{$configurationsFilePath}`"
            );
        }

        $fileContent = File::get(base_path("vendor/{$packageName}/configuration.json"));
        $decodedFileContent = json_decode($fileContent, true);
        if (null === $decodedFileContent && JSON_ERROR_NONE !== json_last_error()) {
            throw new PackageConfigurationsJsonInvalidContentException(
                "Invalid json content on file `{$configurationsFilePath}`: ".json_last_error_msg()
            );
        }

        return $decodedFileContent;
    }

    /**
     * Retrieve from all layouts configurations.
     *
     * @return array
     */
    abstract protected function retrieveLayouts();

}
<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Assets;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\PackageVersions\Accessor as PackageVersionsAccessor;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Mapper
{
    const IMAGES = 'images';
    const SCRIPTS = 'scripts';
    const STYLES = 'styles';
    const FONTS = 'fonts';
    const VIDEOS = 'videos';

    const VALID_SIZE = 'size';
    const VALID_MIME_TYPES = 'mime-types';
    const VALID_EXTENSIONS = 'extensions';

    /** @var string */
    private $packageName;
    /** @var string */
    private $packagePath;

    /**
     * Perform uploads of images, scripts, styles, fonts and videos from
     * vendor path to storage disk.
     *
     * @param string                                                               $packageName
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout $internalLayout
     */
    public function map(string $packageName, Layout $internalLayout)
    {
        $this->packageName = $packageName;
        $this->packagePath = base_path("vendor/{$this->packageName}");

        $this->mapFiles($internalLayout->getImagesPath(), self::IMAGES);
        $this->mapFiles($internalLayout->getScriptsPath(), self::SCRIPTS);
        $this->mapFiles($internalLayout->getStylesPath(), self::STYLES);
        $this->mapFiles($internalLayout->getFontsPath(), self::FONTS);
        $this->mapFiles($internalLayout->getVideosPath(), self::VIDEOS);
    }

    /**
     * Map files retrieving a list of them and upload each ones.
     *
     * @param string $path
     * @param string $validationPattern
     */
    private function mapFiles(string $path, string $validationPattern)
    {
        $files = $this->retrieveFilesFromVendor($path, $validationPattern);
        $this->uploadFiles($files);
    }

    /**
     * Perform file uploads to disk from an array of files.
     *
     * @param array  $files
     */
    private function uploadFiles(array $files)
    {
        foreach ($files as $file) {
            $absoluteFilePath = base_path("vendor/{$file}");
            $contents = File::get($absoluteFilePath);

            Storage::put($file, $contents);
        }
    }

    /**
     * Get files instances collections from vendor path.
     *
     * @param string $path
     * @param string $validationPattern
     *
     * @return array
     */
    private function retrieveFilesFromVendor(
        string $path,
        string $validationPattern
    ) {
        $files = Storage::disk('vendor')->files("{$this->packageName}/{$path}");
        return $this->onlyValid($validationPattern, $files);
    }

    /**
     * Filtering files list to get only which attends respective requirements.
     *
     * @param string $validationPattern
     * @param array  $files
     *
     * @return array
     */
    private function onlyValid(string $validationPattern, array $files)
    {
        $validations = config("layouts.assets-validations.{$validationPattern}");

        return array_filter($files, function ($file) use ($validations) {
            return $this->isValid($file, $validations);
        });
    }

    /**
     * Call validation methods for a single file to assert if is valid.
     *
     * @param string $file
     * @param array  $validations
     *
     * @return bool
     */
    private function isValid(string $file, array $validations)
    {
        $absoluteFilePath = base_path("vendor/{$file}");
        $valid = true;

        foreach ($validations as $validation => $requirementValue) {
            $validationMethod = "validate".Str::studly($validation);
            $validationResult = $this->{$validationMethod}(
                $absoluteFilePath,
                $requirementValue
            );

            if (false === $validationResult) {
                $valid = false;
                Log::info(
                    'File `'.$absoluteFilePath.'` not passing in validation `'.$validation.'`'
                );
            }
        }

        return $valid;
    }

    /**
     * Validate file size. Requirement value could be a numeric value or an
     * array of size range.
     *
     * @param string          $file
     * @param number|number[] $requirementValue
     *
     * @return bool
     */
    protected function validateSize(string $file, $requirementValue)
    {
        $size = File::size($file);

        if (is_array($requirementValue)) {
            list($from, $to) = $requirementValue;
            return $size >= $from
                && $size <= $to;
        }
        if (is_numeric($requirementValue)) {
            return $size < $requirementValue;
        }

        return false;
    }

    /**
     * Validate file mime-type. Requirement value could be a string mime-type
     * or an array of possible mime-types.
     *
     * @param string            $file
     * @param string|string[]   $requirementValue
     *
     * @return bool
     */
    protected function validateMimeTypes(string $file, $requirementValue)
    {
        $mime = File::mimeType($file);

        if (is_array($requirementValue)) {
            return in_array($mime, $requirementValue);
        }
        if (is_string($requirementValue)) {
            return $mime === $requirementValue;
        }

        return false;
    }

    /**
     * Validate file extension. Requirement value could be a string extension
     * or an array of possible file extensions.
     *
     * @param string            $file
     * @param string|string[]   $requirementValue
     *
     * @return bool
     */
    protected function validateExtensions(string $file, $requirementValue)
    {
        $extension = File::extension($file);

        if (is_array($requirementValue)) {
            return in_array($extension, $requirementValue);
        }
        if (is_string($requirementValue)) {
            return $extension === $requirementValue;
        }

        return false;
    }
}
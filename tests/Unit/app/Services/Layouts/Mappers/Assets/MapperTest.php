<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Layouts\Mappers\Assets;


use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Assets\Mapper;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Assets\MimeTypes\Images as ImagesMimeTypes;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Assets\MimeTypes\Fonts as FontsMimeTypes;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MapperTest extends TestCase
{
    public function testMapShouldUploadAssetsFilesFromVendorToDisk()
    {
        $layout = \Mockery::mock(Layout::class);
        $layout->shouldReceive('getImagesPath')
            ->once()
            ->andReturn('path/to/images');
        $layout->shouldReceive('getScriptsPath')
            ->once()
            ->andReturn('path/to/scripts');
        $layout->shouldReceive('getStylesPath')
            ->once()
            ->andReturn('path/to/styles');
        $layout->shouldReceive('getFontsPath')
            ->once()
            ->andReturn('path/to/fonts');
        $layout->shouldReceive('getVideosPath')
            ->once()
            ->andReturn('path/to/videos');

        $vendorFileSystem = \Mockery::mock(Filesystem::class);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/images")
            ->andReturn([
                'package-vendor/package1/path/to/images/image1.png',
                'package-vendor/package1/path/to/images/image2.jpg',
                'package-vendor/package1/path/to/images/image3.jpeg',
                'package-vendor/package1/path/to/images/image4.gif'
            ]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/scripts")
            ->andReturn([
                'package-vendor/package1/path/to/scripts/script1.js',
                'package-vendor/package1/path/to/scripts/script2.js'
            ]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/styles")
            ->andReturn([
                'package-vendor/package1/path/to/styles/style1.css',
                'package-vendor/package1/path/to/styles/style2.css'
            ]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/fonts")
            ->andReturn([
                'package-vendor/package1/path/to/fonts/font1.woff',
                'package-vendor/package1/path/to/fonts/font2.woff2',
                'package-vendor/package1/path/to/fonts/font3.ttf',
                'package-vendor/package1/path/to/fonts/font4.otf'
            ]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/videos")
            ->andReturn([
                'package-vendor/package1/path/to/videos/video1.mp4',
                'package-vendor/package1/path/to/videos/video2.mp4',
                'package-vendor/package1/path/to/videos/video3.mp4'
            ]);

        // Mocking images files mimeType consult response
        File::shouldReceive('mimeType')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/images/image1.png'))
            ->andReturn(ImagesMimeTypes::PNG);
        File::shouldReceive('mimeType')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/images/image2.jpg'))
            ->andReturn(ImagesMimeTypes::JPEG);
        File::shouldReceive('mimeType')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/images/image3.jpeg'))
            ->andReturn(ImagesMimeTypes::JPEG);
        File::shouldReceive('mimeType')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/images/image4.gif'))
            ->andReturn(ImagesMimeTypes::GIF);
        // Mocking videos files mimeType consult response
        File::shouldReceive('mimeType')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/videos/video1.mp4'))
            ->andReturn('video/mp4');
        File::shouldReceive('mimeType')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/videos/video2.mp4'))
            ->andReturn('video/mp4');
        File::shouldReceive('mimeType')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/videos/video3.mp4'))
            ->andReturn('video/mp4');
        // Mocking fonts files mimeType consult response
        File::shouldReceive('mimeType')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/fonts/font1.woff'))
            ->andReturn(FontsMimeTypes::FONT_WOFF);
        File::shouldReceive('mimeType')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/fonts/font2.woff2'))
            ->andReturn(FontsMimeTypes::APPLICATION_FONT_WOFF2);
        File::shouldReceive('mimeType')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/fonts/font3.ttf'))
            ->andReturn(FontsMimeTypes::APPLICATION_X_FONT_TTF);
        File::shouldReceive('mimeType')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/fonts/font4.otf'))
            ->andReturn(FontsMimeTypes::FONT_OTF);

        // Mocking images files size consult response
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/images/image1.png'))
            ->andReturn(123);
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/images/image2.jpg'))
            ->andReturn(123);
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/images/image3.jpeg'))
            ->andReturn(123);
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/images/image4.gif'))
            ->andReturn(123);
        // Mocking scripts files size consult response
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/scripts/script1.js'))
            ->andReturn(123);
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/scripts/script2.js'))
            ->andReturn(123);
        // Mocking styles files size consult response
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/styles/style1.css'))
            ->andReturn(123);
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/styles/style2.css'))
            ->andReturn(123);
        // Mocking fonts files size consult response
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/fonts/font1.woff'))
            ->andReturn(123);
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/fonts/font2.woff2'))
            ->andReturn(123);
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/fonts/font3.ttf'))
            ->andReturn(123);
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/fonts/font4.otf'))
            ->andReturn(123);
        // Mocking videos files size consult response
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/videos/video1.mp4'))
            ->andReturn(123);
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/videos/video2.mp4'))
            ->andReturn(123);
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/videos/video3.mp4'))
            ->andReturn(123);

        // Mocking file extension consult response
        File::shouldReceive('extension')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/scripts/script1.js'))
            ->andReturn('js');
        File::shouldReceive('extension')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/scripts/script2.js'))
            ->andReturn('js');
        File::shouldReceive('extension')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/styles/style1.css'))
            ->andReturn('css');
        File::shouldReceive('extension')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/styles/style2.css'))
            ->andReturn('css');

        // Mocking image files get contents responses
        File::shouldReceive('get')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/images/image1.png'))
            ->andReturn('a');
        File::shouldReceive('get')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/images/image2.jpg'))
            ->andReturn('b');
        File::shouldReceive('get')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/images/image3.jpeg'))
            ->andReturn('c');
        File::shouldReceive('get')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/images/image4.gif'))
            ->andReturn('d');
        // Mocking scripts files get contents responses
        File::shouldReceive('get')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/scripts/script1.js'))
            ->andReturn('hello');
        File::shouldReceive('get')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/scripts/script2.js'))
            ->andReturn('world');
        // Mocking styles files get contents responses
        File::shouldReceive('get')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/styles/style1.css'))
            ->andReturn('hello');
        File::shouldReceive('get')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/styles/style2.css'))
            ->andReturn('world');
        // Mocking fonts files get contents responses
        File::shouldReceive('get')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/fonts/font1.woff'))
            ->andReturn('a');
        File::shouldReceive('get')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/fonts/font2.woff2'))
            ->andReturn('b');
        File::shouldReceive('get')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/fonts/font3.ttf'))
            ->andReturn('c');
        File::shouldReceive('get')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/fonts/font4.otf'))
            ->andReturn('d');
        // Mocking videos files get contents responses
        File::shouldReceive('get')
            ->never()
            ->with(base_path('vendor/package-vendor/package1/path/to/videos/video1.mp4'))
            ->andReturn('a');
        File::shouldReceive('get')
            ->never()
            ->with(base_path('vendor/package-vendor/package1/path/to/videos/video2.mp4'))
            ->andReturn('b');
        File::shouldReceive('get')
            ->never()
            ->with(base_path('vendor/package-vendor/package1/path/to/videos/video3.mp4'))
            ->andReturn('c');

        Storage::shouldReceive('disk')
            ->with('vendor')
            ->times(5)
            ->andReturn($vendorFileSystem);

        // Mocking images files uploads
        Storage::shouldReceive('put')
            ->once()
            ->with(
                'package-vendor/package1/path/to/images/image1.png',
                'a'
            );
        Storage::shouldReceive('put')
            ->once()
            ->with(
                'package-vendor/package1/path/to/images/image2.jpg',
                'b'
            );
        Storage::shouldReceive('put')
            ->once()
            ->with(
                'package-vendor/package1/path/to/images/image3.jpeg',
                'c'
            );
        Storage::shouldReceive('put')
            ->once()
            ->with(
                'package-vendor/package1/path/to/images/image4.gif',
                'd'
            );
        // Mocking scripts files uploads
        Storage::shouldReceive('put')
            ->once()
            ->with(
                'package-vendor/package1/path/to/scripts/script1.js',
                'hello'
            );
        Storage::shouldReceive('put')
            ->once()
            ->with(
                'package-vendor/package1/path/to/scripts/script2.js',
                'world'
            );
        // Mocking styles files uploads
        Storage::shouldReceive('put')
            ->once()
            ->with(
                'package-vendor/package1/path/to/styles/style1.css',
                'hello'
            );
        Storage::shouldReceive('put')
            ->once()
            ->with(
                'package-vendor/package1/path/to/styles/style2.css',
                'world'
            );
        // Mocking fonts files uploads
        Storage::shouldReceive('put')
            ->once()
            ->with(
                'package-vendor/package1/path/to/fonts/font1.woff',
                'a'
            );
        Storage::shouldReceive('put')
            ->once()
            ->with(
                'package-vendor/package1/path/to/fonts/font2.woff2',
                'b'
            );
        Storage::shouldReceive('put')
            ->once()
            ->with(
                'package-vendor/package1/path/to/fonts/font3.ttf',
                'c'
            );
        Storage::shouldReceive('put')
            ->once()
            ->with(
                'package-vendor/package1/path/to/fonts/font4.otf',
                'd'
            );
        Storage::shouldReceive('put')
            ->never()
            ->withSomeOfArgs(['package-vendor/package1/path/to/videos/video1.mp4']);
        Storage::shouldReceive('put')
            ->never()
            ->withSomeOfArgs(['package-vendor/package1/path/to/videos/video2.mp4']);
        Storage::shouldReceive('put')
            ->never()
            ->withSomeOfArgs(['package-vendor/package1/path/to/videos/video3.mp4']);

        $mapper = new Mapper();
        $mapper->map('package-vendor/package1', $layout);
    }

    public function testMapShouldNotUploadImageByInvalidMimeType()
    {
        $layout = \Mockery::mock(Layout::class);
        $layout->shouldReceive('getImagesPath')
            ->once()
            ->andReturn('path/to/images');
        $layout->shouldReceive('getScriptsPath')
            ->once()
            ->andReturn('path/to/scripts');
        $layout->shouldReceive('getStylesPath')
            ->once()
            ->andReturn('path/to/styles');
        $layout->shouldReceive('getFontsPath')
            ->once()
            ->andReturn('path/to/fonts');
        $layout->shouldReceive('getVideosPath')
            ->once()
            ->andReturn('path/to/videos');

        $vendorFileSystem = \Mockery::mock(Filesystem::class);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/images")
            ->andReturn([
                'package-vendor/package1/path/to/images/image1.png'
            ]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/scripts")
            ->andReturn([]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/styles")
            ->andReturn([]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/fonts")
            ->andReturn([]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/videos")
            ->andReturn([]);

        // Mocking images files mimeType consult response
        File::shouldReceive('mimeType')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/images/image1.png'))
            ->andReturn('invalid-mime');

        // Mocking images files size consult response
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/images/image1.png'))
            ->andReturn(123);

        // Mocking image files get contents responses
        File::shouldReceive('get')
            ->never()
            ->with(base_path('vendor/package-vendor/package1/path/to/images/image1.png'));

        Storage::shouldReceive('disk')
            ->with('vendor')
            ->times(5)
            ->andReturn($vendorFileSystem);

        // Mocking images files uploads
        Storage::shouldReceive('put')
            ->never()
            ->with(
                'package-vendor/package1/path/to/images/image1.png',
                'a'
            );

        $mapper = new Mapper();
        $mapper->map('package-vendor/package1', $layout);
    }

    public function testMapShouldNotUploadImageByInvalidSize()
    {
        $layout = \Mockery::mock(Layout::class);
        $layout->shouldReceive('getImagesPath')
            ->once()
            ->andReturn('path/to/images');
        $layout->shouldReceive('getScriptsPath')
            ->once()
            ->andReturn('path/to/scripts');
        $layout->shouldReceive('getStylesPath')
            ->once()
            ->andReturn('path/to/styles');
        $layout->shouldReceive('getFontsPath')
            ->once()
            ->andReturn('path/to/fonts');
        $layout->shouldReceive('getVideosPath')
            ->once()
            ->andReturn('path/to/videos');

        $vendorFileSystem = \Mockery::mock(Filesystem::class);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/images")
            ->andReturn([
                'package-vendor/package1/path/to/images/image1.png'
            ]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/scripts")
            ->andReturn([]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/styles")
            ->andReturn([]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/fonts")
            ->andReturn([]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/videos")
            ->andReturn([]);

        // Mocking images files mimeType consult response
        File::shouldReceive('mimeType')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/images/image1.png'))
            ->andReturn(ImagesMimeTypes::PNG);

        // Mocking images files size consult response
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/images/image1.png'))
            ->andReturn(
                1 + config('layouts.assets-validations.'.Mapper::IMAGES.'.'.Mapper::VALID_SIZE)
            );

        // Mocking image files get contents responses
        File::shouldReceive('get')
            ->never()
            ->with(base_path('vendor/package-vendor/package1/path/to/images/image1.png'));

        Storage::shouldReceive('disk')
            ->with('vendor')
            ->times(5)
            ->andReturn($vendorFileSystem);

        // Mocking images files uploads
        Storage::shouldReceive('put')
            ->never()
            ->with(
                'package-vendor/package1/path/to/images/image1.png',
                'a'
            );

        $mapper = new Mapper();
        $mapper->map('package-vendor/package1', $layout);
    }

    public function testMapShouldNotUploadScriptByInvalidExtension()
    {
        $layout = \Mockery::mock(Layout::class);
        $layout->shouldReceive('getImagesPath')
            ->once()
            ->andReturn('path/to/images');
        $layout->shouldReceive('getScriptsPath')
            ->once()
            ->andReturn('path/to/scripts');
        $layout->shouldReceive('getStylesPath')
            ->once()
            ->andReturn('path/to/styles');
        $layout->shouldReceive('getFontsPath')
            ->once()
            ->andReturn('path/to/fonts');
        $layout->shouldReceive('getVideosPath')
            ->once()
            ->andReturn('path/to/videos');

        $vendorFileSystem = \Mockery::mock(Filesystem::class);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/images")
            ->andReturn([]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/scripts")
            ->andReturn([
                'package-vendor/package1/path/to/scripts/script1.js'
            ]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/styles")
            ->andReturn([]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/fonts")
            ->andReturn([]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/videos")
            ->andReturn([]);

        // Mocking scripts files size consult response
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/scripts/script1.js'))
            ->andReturn(123);

        // Mocking file extension consult response
        File::shouldReceive('extension')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/scripts/script1.js'))
            ->andReturn('jotaessi');

        // Mocking scripts files get contents responses
        File::shouldReceive('get')
            ->never()
            ->with(base_path('vendor/package-vendor/package1/path/to/scripts/script1.js'));

        Storage::shouldReceive('disk')
            ->with('vendor')
            ->times(5)
            ->andReturn($vendorFileSystem);

        // Mocking scripts files uploads
        Storage::shouldReceive('put')
            ->never()
            ->with(
                'package-vendor/package1/path/to/scripts/script1.js',
                'hello'
            );

        $mapper = new Mapper();
        $mapper->map('package-vendor/package1', $layout);
    }

    public function testMapShouldNotUploadScriptByInvalidSize()
    {
        $layout = \Mockery::mock(Layout::class);
        $layout->shouldReceive('getImagesPath')
            ->once()
            ->andReturn('path/to/images');
        $layout->shouldReceive('getScriptsPath')
            ->once()
            ->andReturn('path/to/scripts');
        $layout->shouldReceive('getStylesPath')
            ->once()
            ->andReturn('path/to/styles');
        $layout->shouldReceive('getFontsPath')
            ->once()
            ->andReturn('path/to/fonts');
        $layout->shouldReceive('getVideosPath')
            ->once()
            ->andReturn('path/to/videos');

        $vendorFileSystem = \Mockery::mock(Filesystem::class);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/images")
            ->andReturn([]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/scripts")
            ->andReturn([
                'package-vendor/package1/path/to/scripts/script1.js'
            ]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/styles")
            ->andReturn([]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/fonts")
            ->andReturn([]);
        $vendorFileSystem->shouldReceive('files')
            ->once()
            ->with("package-vendor/package1/path/to/videos")
            ->andReturn([]);

        // Mocking scripts files size consult response
        File::shouldReceive('size')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/scripts/script1.js'))
            ->andReturn(
                1 + config('layouts.assets-validations.'.Mapper::SCRIPTS.'.'.Mapper::VALID_SIZE)
            );

        // Mocking file extension consult response
        File::shouldReceive('extension')
            ->once()
            ->with(base_path('vendor/package-vendor/package1/path/to/scripts/script1.js'))
            ->andReturn('js');

        // Mocking scripts files get contents responses
        File::shouldReceive('get')
            ->never()
            ->with(base_path('vendor/package-vendor/package1/path/to/scripts/script1.js'));

        Storage::shouldReceive('disk')
            ->with('vendor')
            ->times(5)
            ->andReturn($vendorFileSystem);

        // Mocking scripts files uploads
        Storage::shouldReceive('put')
            ->never()
            ->with(
                'package-vendor/package1/path/to/scripts/script1.js',
                'hello'
            );

        $mapper = new Mapper();
        $mapper->map('package-vendor/package1', $layout);
    }

    public function testMapShouldNotUploadStyleByInvalidExtension()
    {
        // TODO
    }

    public function testMapShouldNotUploadStyleByInvalidSize()
    {
        // TODO
    }

    public function testMapShouldNotUploadFontByInvalidMimeType()
    {
        // TODO
    }

    public function testMapShouldNotUploadFontByInvalidSize()
    {
        // TODO
    }

    public function testMapShouldNotUploadAnyVideo()
    {
        // TODO
    }
}
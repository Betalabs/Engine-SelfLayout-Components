<?php

use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Assets\Mapper as AssetsMapper;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Assets\MimeTypes\Images as ImagesMimeTypes;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Assets\MimeTypes\Fonts as FontsMimeTypes;

return [

    /*
    | Here all available layouts must be registered declaring his name and
    | package name exactly like vendor path. This is used to map them into
    | Engine instances with their components.
    | After Engine team approves layout release, the package will be registered
    | here.
    */
    'available' => [
        'basic-theme' => 'betalabs/engine-basic-theme'
    ],

    /*
    | Here all unavailable layouts must be registered declaring his name and
    | package name exactly like vendor path. This is used to remove each them
    | from Engine instances with their components.
    */
    'unavailable' => [
        // 'foo' => 'vendor/foo',
        // 'bar' => 'vendor/bar'
    ],

    /*
     | Define all validations to assert each asset file to be ready to upload.
     */
    'assets-validations' => [
        /*
         | Validations to images files.
         */
        AssetsMapper::IMAGES => [
            /*
             | Valid images mime-types.
             */
            AssetsMapper::VALID_MIME_TYPES => [
                ImagesMimeTypes::JPEG,
                ImagesMimeTypes::BMP,
                ImagesMimeTypes::GIF,
                ImagesMimeTypes::PNG,
                ImagesMimeTypes::SVG_XML,
                ImagesMimeTypes::ICO
            ],
            /*
             | Valid images size limit (10mb).
             */
            AssetsMapper::VALID_SIZE => 10 * 1000000
        ],

        /*
         | Validations to scripts files.
         */
        AssetsMapper::SCRIPTS => [
            /*
             | Valid scripts extensions.
             */
            AssetsMapper::VALID_EXTENSIONS => [
                'js'
            ],
            /*
             | Valid scripts size limit (15mb).
             */
            AssetsMapper::VALID_SIZE => 15 * 1000000
        ],

        /*
         | Validations to styles files.
         */
        AssetsMapper::STYLES => [
            /*
             | Valid styles extensions.
             */
            AssetsMapper::VALID_EXTENSIONS => [
                'css',
                'scss',
                'less',
                'sass'
            ],
            /*
             | Valid styles size limit (15mb).
             */
            AssetsMapper::VALID_SIZE => 15 * 1000000
        ],

        /*
         | Validations to fonts files.
         */
        AssetsMapper::FONTS => [
            /*
             | Valid fonts mime-types.
             */
            AssetsMapper::VALID_MIME_TYPES => [
                FontsMimeTypes::APPLICATION_FONT_WOFF,
                FontsMimeTypes::APPLICATION_FONT_WOFF2,
                FontsMimeTypes::FONT_WOFF,
                FontsMimeTypes::FONT_WOFF2,
                FontsMimeTypes::APPLICATION_X_FONT_TTF,
                FontsMimeTypes::FONT_TTF,
                FontsMimeTypes::APPLICATION_X_FONT_OTF,
                FontsMimeTypes::FONT_OTF,
                FontsMimeTypes::APPLICATION_VND_MS_FONTOBJECT,
                FontsMimeTypes::IMAGE_SVG_XML,
                FontsMimeTypes::APPLICATION_OCTET_STREAM
            ],
            /*
             | Valid fonts size limit (5mb).
             */
            AssetsMapper::VALID_SIZE => 5 * 1000000
        ],

        /*
         | Valid file mime-types and sizes for videos assets. Any videos can
         | be uploaded.
         */
        AssetsMapper::VIDEOS => [
            AssetsMapper::VALID_MIME_TYPES => [],
            AssetsMapper::VALID_SIZE => 0
        ],
    ]

];

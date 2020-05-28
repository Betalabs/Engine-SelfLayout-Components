<?php
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Assets\Mapper as AssetsMapper;

return [

    /*
    | Here all available layouts must be registered declaring his name and
    | package name exactly like vendor path. This is used to map them into
    | Engine instances with their components.
    | After Engine team approves layout release, the package will be registered
    | here.
    */
    'available' => [
        'seiko' => 'betalabs/seiko'
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
        AssetsMapper::IMAGES => [

        ],
        AssetsMapper::IMAGES => [

        ],
        AssetsMapper::IMAGES => [

        ],
        AssetsMapper::IMAGES => [

        ],
        AssetsMapper::IMAGES => [

        ],
        AssetsMapper::IMAGES => [

        ],
    ]

];
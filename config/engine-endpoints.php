<?php

return [
    'resources' => [
        'Creator',
        'Destroyer',
        'Updater',
        'Indexer',
        'Shower',
        'Structure'
    ],
    'endpoints' => [
        'Betalabs\LaravelHelper\Services\Engine\Channel' => 'channels',
        'Betalabs\LaravelHelper\Services\Engine\Form' => 'forms',
        'Betalabs\LaravelHelper\Services\Engine\ExtraField' => 'extra-fields',
        'Betalabs\LaravelHelper\Services\Engine\ExtraFieldType' => 'extra-field-types',
        'Betalabs\LaravelHelper\Services\Engine\FormExtraField' => 'forms/{formId}/extra-fields',
        'Betalabs\LaravelHelper\Services\Engine\Entity' => 'entities',
        'Betalabs\LaravelHelper\Services\Engine\VirtualEntityRecord' => 'virtual-entities/{virtualEntity}/records',
        'Betalabs\LaravelHelper\Services\Engine\VirtualEntity' => 'virtual-entities',
        'Betalabs\LaravelHelper\Services\Engine\Categories' => 'categories',
        'Betalabs\LaravelHelper\Services\Engine\Notifications' => 'notifications',
        'Betalabs\LaravelHelper\Services\Engine\Price' => 'prices/{type}',
        'Betalabs\LaravelHelper\Services\Engine\FieldMap' => 'field-maps',
        'Betalabs\LaravelHelper\Services\Engine\Client' => 'clients',
        'Betalabs\LaravelHelper\Services\Engine\PaymentMethod' => 'payment-methods',
        'Betalabs\LaravelHelper\Services\Engine\Order' => 'orders',
        'Betalabs\LaravelHelper\Services\Engine\Event' => 'events',
        'Betalabs\LaravelHelper\Services\NFe\TaxInvoice' => 'apps/{registryId}/wormhole/tax-invoices',
        'Betalabs\LaravelHelper\Services\Engine\AppRegistry' => 'apps'
    ]
];
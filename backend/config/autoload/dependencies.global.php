<?php

declare(strict_types=1);

use Docsdangit\Backend\Handler;
use Docsdangit\Backend\Repository;
use Docsdangit\Backend\Service;

return [
    // Provides application-wide services.
    // We recommend using fully-qualified class names whenever possible as
    // service names.
    'dependencies' => [
        // Use 'aliases' to alias a service name to another service. The
        // key is the alias name, the value is the service to which it points.
        'aliases' => [
            // Fully\Qualified\ClassOrInterfaceName::class => Fully\Qualified\ClassName::class,
        ],
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables' => [
			Service\PostDocsEntryRequestBodyConverter::class => Service\PostDocsEntryRequestBodyConverter::class,
            // Fully\Qualified\InterfaceName::class => Fully\Qualified\ClassName::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories' => [
			Handler\PostEntityHandler::class => Handler\PostEntityHandlerFactory::class,
	        Service\Repository::class => Repository\MySQLFactory::class
        ],
    ],
];

<?php

use Docsdangit\Backend\Kernel;

require_once dirname(__DIR__).'/vendor/autoload.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};

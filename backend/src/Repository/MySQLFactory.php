<?php

declare(strict_types=1);

namespace Docsdangit\Backend\Repository;

use PDO;
use Psr\Container\ContainerInterface;

class MySQLFactory
{
    public function __invoke(ContainerInterface $container): MySQL
    {
        return new MySQL(new PDO('mysql:dbname=docsdangit;host=db', 'root', 'sUpErSeCrEt'));
    }
}

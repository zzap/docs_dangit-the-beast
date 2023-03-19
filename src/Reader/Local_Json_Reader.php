<?php declare(strict_types=1);

namespace Docsdangit\Reader;

final class Local_Json_Reader implements ReaderInterface
{
    public function read(): array
    {
        try {
            return json_decode(file_get_contents(__DIR__ . '/../../data/wpcli-commands.json'), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable) {
            exit('JSON File could not be parsed');
        }

    }
}

<?php
namespace Docsdangit\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Ingest extends Command
{
    protected function configure()
    {
        $this->setName('ingest')
            ->setDescription("Ingest docs")
            ->setHelp(<<<EOT
Ingest docs from different sources.

Usage:
<info>docsdangit ingest</info>
EOT);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Done ✅');
        return Command::SUCCESS;
    }
}

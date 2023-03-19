<?php
namespace Docsdangit\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Docsdangit\Parsers\WordPress_Docs;
use Docsdangit\Parsers\WP_CLI;
use Docsdangit\Parsers\PHP_Docs;

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
        $wp_docs = new WordPress_Docs();
        $wp_docs->parse();
        $wp_docs = new WP_CLI();
        $wp_docs->parse();
        $wp_docs = new PHP_Docs();
        $wp_docs->parse();

        $output->writeln('Done ✅');
        return Command::SUCCESS;
    }
}

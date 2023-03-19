<?php

namespace Docsdangit\Command;

use Docsdangit\Parsers\ParserInterface;
use Docsdangit\Reader\ReaderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class Ingest extends Command
{
    public function __construct(
        private readonly ReaderInterface $reader,
        private readonly ParserInterface $parser
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('ingest')
            ->setDescription("Ingest docs")
            ->setHelp(<<<EOT
Ingest docs from different sources.

Usage:
<info>bin/docsdangit ingest</info>
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Reading Json from local file');

        $commentsData = $this->reader->read();
        $count = count($commentsData);

        $io->text(sprintf("Processing Data. Found%s items", $count));
        $this->parser->parse($commentsData);


        return Command::SUCCESS;
    }
}

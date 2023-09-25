<?php

declare(strict_types=1);

namespace App\Commands\Import;

use Adeliom\WP\CLI\Commands\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportContentCommand extends Command
{
    protected $signature = 'import:content';

    protected SymfonyStyle $io;

    private ?string $content = null;

    private null|string|int $id = null;

    protected function configure(): void
    {
        $this->addOption('id', 'i', InputOption::VALUE_OPTIONAL, 'ID de l’élément à importer/écraser');
        $this->addOption('content', 'c', InputOption::VALUE_REQUIRED, 'Type de contenu à importer');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title('Import de contenus');

        if ($content = $input->getOption('content')) {
            $this->content = $content;
        }

        if ($id = $input->getOption('id')) {
            $this->id = is_numeric($id) ? (int)$id : $id;
        }

        return 0;
    }
}

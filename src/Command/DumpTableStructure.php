<?php

namespace Avid\CandidateChallenge\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class DumpTableStructure extends Command
{
    function __construct(Connection $connection)
    {
        $this->connection = $connection;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('dump:tables')->setDescription('Dump the current database table structures to a directory')
        ->addOption('directory', 'd', InputOption::VALUE_REQUIRED, 'Where to dump the sql', __DIR__ . '/../../resources/sql')
        ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Display tables only');
    }

    protected function execute(InputInterface $input, OutputInterface $output, Connection $connection = NULL)
    {
        $output->writeln('');

        $output->write('<info>Dumping the table structures</info>');

        if ($input->getOption('dry-run')) {
            $output->write(' <comment>(dry run only)</comment>');
        }

        $output->writeln('');

        $dir = $input->getOption('directory');

        /**
         * @var AbstractSchemaManager $sm
         * @var AbstractPlatform $p
         */
        $sm = $this->connection->getSchemaManager();
        $p = $sm->getDatabasePlatform();

        $output->writeln("Platform: <comment>{$p->getName()}</comment>");

        $tbls = $sm->listTables();

        /** @var TableHelper $ot */
        $ot = $this->getHelper('table');
        $ot->setHeaders(['tables']);

        $ot->addRows(
            array_map(function (Table $table) {
                return [$table->getName()];
                },
            $tbls)
        );

        $ot->render($output);

        $output->writeln('');

        foreach ($tbls as $tbl) {
            $output->writeln("<info>{$tbl->getName()}</info>");

            /** @var TableHelper $outputTable */
            $ot = $this->getHelper('table');
            $ot->setHeaders([
                    'column',
                      'type',
                     'length',
                      'not null']
            );

            $cols = $tbl->getColumns();
            foreach ($cols as $c) {
                $ot->addRow(
                    [$c->getName(), $c->getType(), $c->getLength(), $c->getNotnull()]
                );
            }

            $ot->render($output);
            $output->writeln('');

            /** @var AbstractPlatform $p */
            $p = $sm->getDatabasePlatform();
            $sql = $p->getCreateTableSQL($tbl);

            if ($input->getOption('dry-run') == FALSE) {
                $data = "";
                foreach ($sql as $stmt) {
                    $data .= $stmt . ';';
                }

                $path = $dir . '/' . $tbl->getName() . '.sql';
                if ($realPath = realpath($path)) {
                    $path = $realPath;
                }

                $output->writeln("<info>Writing $path</info>");
                file_put_contents(
                    $path,
                    \SqlFormatter::format($data, false)
                );
            }
        }

        $output->writeln('');
    }
}

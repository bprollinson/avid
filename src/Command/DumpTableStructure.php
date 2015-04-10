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
    const DRY_RUN_HEADER = ' <comment>(dry run only)</comment>';

    const HEADER_COLUMN = 'column';
    const HEADER_TYPE = 'type';
    const HEADER_LENGTH = 'length';
    const HEADER_NOT_NULL = 'not null';

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

    /**
     * Appends the header content to the provided output interface instance
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param AbstractPlatform $p
     *
     * @return void
     */
    private function output_header(InputInterface $input, OutputInterface $output, AbstractPlatform $p) {
        $output->writeln('');

        $output->write('<info>Dumping the table structures</info>');

        if ($input->getOption('dry-run')) {
            $output->write(self::DRY_RUN_HEADER);
        }

        $output->writeln('');

        $output->writeln("Platform: <comment>{$p->getName()}</comment>");
    }

    /**
     * Initializes the table headers
     * @param array $tbls
     *
     * @return void
     */
    private function initialize_table_helper($tbls) {
        $ot = $this->getHelper('table');
        $ot->setHeaders(['tables']);

        $ot->addRows(
            array_map(function (Table $table) {
                return [$table->getName()];
                },
            $tbls)
        );

        return $ot;
    }

    /**
     * Appends the core table information to the provided output interface
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param \Doctrine\DBAL\Schema\Table $tbl
     * @param AbstractSchemaManager $sm
     * @param string $dir
     *
     * @return void
     */
    private function output_table_info(InputInterface $input, OutputInterface $output, \Doctrine\DBAL\Schema\Table $tbl,
                                       AbstractSchemaManager $sm, $dir) {
        $output->writeln("<info>{$tbl->getName()}</info>");

        /** @var TableHelper $outputTable */
        $ot = $this->getHelper('table');
        $ot->setHeaders([
            self::HEADER_COLUMN,
            self::HEADER_TYPE,
            self::HEADER_LENGTH,
            self::HEADER_NOT_NULL]
        );

        $cols = $tbl->getColumns();
        foreach ($cols as $c) {
            $ot->addRow(
                [$c->getName(), $c->getType(), $c->getLength(), $c->getNotnull()]
            );
        }

        $ot->render($output);
        $output->writeln('');

        if ($input->getOption('dry-run') == FALSE) {
            /** @var AbstractPlatform $p */
            $p = $sm->getDatabasePlatform();
            $sql = $p->getCreateTableSQL($tbl);

            $this->write_to_file($output, $tbl, $sql, $dir);
        }
    }

    /**
     * Writes the collected output content to the provided file path
     * @param OutputInterface $output
     * @param \Doctrine\DBAL\Schema\Table $tbl
     * @param array $sql
     * @param string $dir
     *
     * @return void
     */
    private function write_to_file(OutputInterface $output, \Doctrine\DBAL\Schema\Table $tbl, $sql, $dir) {
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

    protected function execute(InputInterface $input, OutputInterface $output, Connection $connection = NULL)
    {
        /**
         * @var AbstractSchemaManager $sm
         * @var AbstractPlatform $p
         */
        $sm = $this->connection->getSchemaManager();
        $p = $sm->getDatabasePlatform();

        $this->output_header($input, $output, $p);

        $tbls = $sm->listTables();

        /** @var TableHelper $ot */
        $ot = $this->initialize_table_helper($tbls);

        $ot->render($output);

        $output->writeln('');

        $dir = $input->getOption('directory');
        foreach ($tbls as $tbl) {
            $this->output_table_info($input, $output, $tbl, $sm, $dir);
        }

        $output->writeln('');
    }
}

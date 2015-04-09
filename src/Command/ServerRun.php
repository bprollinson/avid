<?php

namespace Avid\CandidateChallenge\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class ServerRun extends Command
{
    const NAME = 'server:run';

    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Start the development server')
            ->addOption('env', 'e', InputOption::VALUE_REQUIRED, 'Environment to run the web server in', 'dev');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Server running on <info>http://localhost:8000/</info>');

        $builder = new ProcessBuilder([
            PHP_BINARY,
            '-S',
            'localhost:8000',
            '-t',
            'web',
            sprintf('web/%s.php', $input->getOption('env'))
        ]);


        $builder->setTimeout(null);

        $builder->getProcess()->run(
            function ($type, $buffer) use ($output) {
                $output->write($buffer);
            }
        );
    }
}

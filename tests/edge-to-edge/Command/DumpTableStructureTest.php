<?php

namespace Avid\CandidateChallenge\Command;

use Avid\CandidateChallenge\EdgeToEdgeTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @covers \Avid\CandidateChallenge\Command\DumpTableStructure
 *
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class DumpTableStructureTest extends EdgeToEdgeTestCase
{
    /**
     * @var DumpTableStructure
     */
    private $SUT;

    /**
     * @var CommandTester
     */
    private $commandTester;

    /**
     * @var string
     */
    private $fixtures;

    protected function setUp()
    {
        parent::setUp();

        $this->fixtures = __DIR__ . '/fixtures/sql';

        mkdir($this->fixtures);

        $this->SUT = new DumpTableStructure($this->getDatabase());

        $application = new Application();
        $application->add($this->SUT);

        $this->commandTester = new CommandTester($this->SUT);
    }

    protected function tearDown()
    {
        $this->deleteDumpedSql();
    }

    /**
     * @return array
     */
    protected function getSql()
    {
        return ['members.sql'];
    }

    /** @test */
    public function it_should_not_create_files_on_a_dry_fun()
    {
        $this->executeDryRun();

        $this->assertOutputContains('(dry run only)');
        $this->assertOutputNotContains(sprintf('Writing %s/members.sql', $this->fixtures));
        $this->assertFileNotExists($this->fixtures . '/members.sql');
    }

    /** @test */
    public function it_should_dump_the_tables_sql()
    {
        $this->execute();

        $actualFile = $this->fixtures . '/members.sql';
        $expectedFile = $this->fixtures . '/../expectedSql/members.sql';

        $this->assertOutputContains(sprintf('Writing %s/members.sql', $this->fixtures));
        $this->assertFileEquals($expectedFile, $actualFile);
        $this->assertFileExists($actualFile);
    }

    /** @test */
    public function it_should_display_the_tables_list()
    {
        $this->executeDryRun();

        $display = <<<DISPLAY
+---------+
| tables  |
+---------+
| members |
+---------+
DISPLAY;

        $this->assertOutputContains(str_replace("\n", PHP_EOL, $display));
    }

    /** @test */
    public function it_should_output_the_table_and_its_columns()
    {
        $this->executeDryRun();

        $display = <<<DISPLAY
members
+---------------+---------+--------+----------+
| column        | type    | length | not null |
+---------------+---------+--------+----------+
| members       |         |        |          |
| id            | Integer |        | 1        |
| username      | String  | 32     | 1        |
| password      | String  | 64     | 1        |
| country       | String  | 6      | 1        |
| province      | String  | 7      | 1        |
| city          | String  | 25     | 1        |
| postal_code   | String  | 7      | 1        |
| date_of_birth | String  | 10     | 1        |
| limits        | String  | 25     | 1        |
| height        | String  | 5      | 1        |
| weight        | String  | 7      | 1        |
| body_type     | String  | 16     | 1        |
| ethnicity     | String  | 16     | 1        |
| email         | String  | 50     | 1        |
+---------------+---------+--------+----------+
DISPLAY;

        $this->assertOutputContains(str_replace("\n", PHP_EOL, $display));
    }

    /** @test */
    public function it_should_notify_when_it_beings()
    {
        $this->executeDryRun();

        $this->assertOutputContains('Dumping the table structures');
    }

    /** @test */
    public function it_should_display_the_platform()
    {
        $this->executeDryRun();

        $this->assertOutputContains('Platform: sqlite');
    }

    /**
     * @return int
     */
    private function executeDryRun()
    {
        return $this->execute(['--dry-run' => true]);
    }

    /**
     * @param array $input
     *
     * @return int
     */
    private function execute(array $input = [])
    {
        return $this->commandTester->execute(
            array_merge(
                ['--directory' => $this->fixtures],
                $input
            )
        );
    }

    private function deleteDumpedSql()
    {
        $files = new \RecursiveDirectoryIterator($this->fixtures);
        $files->setFlags(\RecursiveDirectoryIterator::SKIP_DOTS);

        foreach ($files as $file) {
            unlink($file);
        }

        rmdir($this->fixtures);
    }

    /**
     * @param string $text
     */
    private function assertOutputContains($text)
    {
        $this->assertContains($text, $this->commandTester->getDisplay());
    }

    /**
     * @param string $text
     */
    private function assertOutputNotContains($text)
    {
        $this->assertNotContains($text, $this->commandTester->getDisplay());
    }
}

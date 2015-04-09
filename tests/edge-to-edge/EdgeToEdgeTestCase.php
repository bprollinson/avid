<?php

namespace Avid\CandidateChallenge;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use PHPUnit_Extensions_Database_DB_IDatabaseConnection;
use PHPUnit_Extensions_Database_DataSet_IDataSet;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
abstract class EdgeToEdgeTestCase extends \PHPUnit_Extensions_Database_TestCase
{
    /**
     * @var Connection
     */
    private static $doctrine;

    /**
     * @var PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    private $connection;

    /**
     * Defines a set of SQL files from the resources/sql directory to be loaded for the initial database setup
     *
     * @return array
     */
    protected function getSql()
    {
        return [];
    }
    
    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    final protected function getConnection()
    {
        if (!$this->connection) {
            if (!self::$doctrine) {
                self::$doctrine = $this->getDoctrineConnection();

                $this->setupDatabase();
            }

            $this->connection = $this->createDefaultDbConnection(self::$doctrine->getWrappedConnection(), ':memory:');
        }

        return $this->connection;
    }

    /**
     * @return Connection
     */
    protected static function getDatabase()
    {
        return self::$doctrine;
    }

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        $fixtures = "{$this->getTestClassDirectory()}/fixtures/{$this->getTestClass()}.yml";

        if (file_exists($fixtures)) {
            return $this->getYamlDataSet($fixtures);
        }

        return new \PHPUnit_Extensions_Database_DataSet_DefaultDataSet();
    }

    /**
     * @param string $path
     *
     * @return \PHPUnit_Extensions_Database_DataSet_YamlDataSet
     */
    protected function getYamlDataSet($path)
    {
        return new \PHPUnit_Extensions_Database_DataSet_YamlDataSet($path);
    }

    /**
     * @return Connection
     */
    private function getDoctrineConnection()
    {
        return DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ]);
    }

    /**
     * Loads a set of *.sql files to setup the initial database table structure
     */
    private function setupDatabase()
    {
        $sql = $this->getSql();
        $connection = $this->getDatabase();

        foreach ($sql as $file) {
            $connection->exec(file_get_contents(__DIR__ . '/../../resources/sql/' . $file));
        }
    }

    /**
     * Gets the test class name based on the assumption that it ends with "Test"
     *
     * @return string
     */
    private function getTestClass()
    {
        $class = new \ReflectionClass($this);

        return substr($class->getShortName(), 0, -4);
    }

    /**
     * @return string
     */
    private function getTestClassDirectory()
    {
        $class = new \ReflectionClass($this);

        return dirname($class->getFileName());
    }
}

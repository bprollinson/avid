<?php

namespace Avid\CandidateChallenge\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
abstract class DoctrineRepository implements Repository
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Connection
     */
    protected function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    protected function createQueryBuilder()
    {
        return $this->connection->createQueryBuilder();
    }

    /**
     * @param \Doctrine\DBAL\Query\QueryBuilder $queryBuilder
     *
     * @return Statement
     */
    protected function execute(QueryBuilder $queryBuilder)
    {
        return $queryBuilder->execute();
    }

    /**
     * @param int $first
     * @param int $max
     *
     * @return QueryBuilder
     */
    protected function getBaseQuery($first = 0, $max = null)
    {
        $qb = $this->createQueryBuilder();

        $qb
            ->select('*')
            ->from($this->getTableName(), $this->getAlias());

        $qb = $this->setLimit($qb, $first, $max);

        return $qb;
    }

    /**
     * @return string
     */
    abstract protected function getTableName();

    /**
     * @return string
     */
    abstract protected function getAlias();

    /**
     * @param array $row
     *
     * @return object
     */
    abstract protected function hydrate(array $row);

    /**
     * @param array[] $result
     *
     * @return object[]
     */
    protected function hydrateAll(array $result)
    {
        $objects = [];

        foreach ($result as $row) {
            $objects[] = $this->hydrate($row);
        }

        return $objects;
    }

    /**
     * @param QueryBuilder $qb
     * @param int $first
     * @param int $max
     *
     * @return QueryBuilder
     */
    private function setLimit(QueryBuilder $qb, $first, $max)
    {
        if ($max) {
            $qb->setFirstResult($first);
            $qb->setMaxResults($max);
        }

        return $qb;
    }
}

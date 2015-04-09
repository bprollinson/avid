<?php

namespace Avid\CandidateChallenge\Repository;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
interface Repository extends \Countable
{
    /**
     * @param object $object
     *
     * @return bool
     */
    public function add($object);

    /**
     * @param $object
     *
     * @return int Affected rows
     */
    public function update($object);

    /**
     * @param object $object
     *
     * @return bool
     */
    public function remove($object);

    /**
     * @param int $first
     * @param int $max
     *
     * @return object
     */
    public function findAll($first = 0, $max = null);
}

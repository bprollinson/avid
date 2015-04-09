<?php

namespace Avid\CandidateChallenge\Model;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class Weight
{
    /**
     * @var string
     */
    private $weight;

    /**
     * @param string $weight
     */
    public function __construct($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->weight;
    }
}

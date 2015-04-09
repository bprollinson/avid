<?php

namespace Avid\CandidateChallenge\Model;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class Height
{
    /**
     * @var string
     */
    private $height;

    /**
     * @param string $height
     */
    public function __construct($height)
    {
        $this->height = $height;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->height;
    }
}

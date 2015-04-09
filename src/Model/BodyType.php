<?php

namespace Avid\CandidateChallenge\Model;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class BodyType
{
    /**
     * @return array
     */
    public static function all()
    {
        return [
            'Slim',
            'Fit',
            'Muscular',
            'Average/medium',
            'Shapely toned',
            'A few extra pounds',
            'Full sized',
            'Zaftig (Voluptuous/Curvy)'
        ];
    }
}

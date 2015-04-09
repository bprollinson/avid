<?php

namespace Avid\CandidateChallenge\Model;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class Ethnicity
{
    /**
     * @return array
     */
    public static function all()
    {
        return [
            'Caucasian (white)',
            'African American (black)',
            'Asian',
            'Hispanic',
            'First Nations',
            'East Indian',
            'Middle Eastern',
            'Other',
            'Rather Not Say',
        ];
    }
}

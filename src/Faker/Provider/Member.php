<?php

namespace Avid\CandidateChallenge\Faker\Provider;

use Faker\Provider\Base;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class Member extends Base
{
    protected static $gtaCities = [
        'Ajax',
        'Aurora',
        'Brampton',
        'Brock',
        'Burlington',
        'Caledon',
        'Clarington',
        'East Gwillimbury',
        'Georgina',
        'Halton Hills',
        'King',
        'Markham',
        'Milton',
        'Mississauga',
        'Newmarket',
        'Oakville',
        'Oshawa',
        'Pickering',
        'Richmond Hill',
        'Scugog',
        'Toronto',
        'Uxbridge',
        'Vaughn',
        'Whitby',
        'Whitechurch-Stouffville'
    ];

    protected static $limits = [
        'Something Short Term',
        'Something Long Term',
        'Cyber Affair / Erotic Chat',
        'Whatever Excites Me',
        'Anything Goes',
        'Undecided',
    ];

    protected static $bodyTypes = [
        'Slim',
        'Fit',
        'Muscular',
        'Average/medium',
        'Shapely toned',
        'A few extra pounds',
        'Full sized',
        'Zaftig (Voluptuous/Curvy)'
    ];

    protected static $ethnicities = [
        'Caucasian (white)',
        'African American (black)',
        'Asian',
        'Hispanic',
        'First Nations',
        'East Indian',
        'Middle Eastern',
        'Other',
        'Rather Not Say'
    ];

    /**
     * @return string
     */
    public function gtaCity()
    {
        return static::randomElement(static::$gtaCities);
    }

    /**
     * @return string
     */
    public function limits()
    {
        return static::randomElement(static::$limits);
    }

    /**
     * @return string
     */
    public function height()
    {
        return sprintf('%d\' %d"', rand(4, 6), rand(1, 11));
    }

    /**
     * @return string
     */
    public function weight()
    {
        return sprintf('%d lbs', static::randomElement(range(80, 150, 5)));
    }

    /**
     * @return string
     */
    public function bodyType()
    {
        return static::randomElement(static::$bodyTypes);
    }

    /**
     * @return string
     */
    public function ethnicity()
    {
        return static::randomElement(static::$ethnicities);
    }
}

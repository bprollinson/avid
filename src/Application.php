<?php

namespace Avid\CandidateChallenge;

use Doctrine\DBAL\Connection;
use Silex\Application\FormTrait;
use Silex\Application\TwigTrait;
use Silex\Application\UrlGeneratorTrait;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class Application extends \Silex\Application
{
    const ENV_DEV = 'dev';
    const ENV_PROD = 'prod';

    /**
     * @return Connection
     */
    public function getDoctrine()
    {
        return $this['db'];
    }
}

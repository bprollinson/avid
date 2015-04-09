<?php

namespace Avid\CandidateChallenge\DependencyInjection;

use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * @property UrlGenerator $urlGenerator
 *
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
trait UrlGeneratorTrait
{
    /**
     * @param string $route
     * @param mixed $parameters
     *
     * @return string
     */
    private function path($route, $parameters = array())
    {
        return $this->urlGenerator->generate($route, $parameters);
    }
}

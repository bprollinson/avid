<?php

namespace Avid\CandidateChallenge\DependencyInjection;

use Symfony\Component\HttpFoundation\Response;

/**
 * @property \Twig_Environment $twig
 *
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
trait TwigTrait
{
    /**
     * @param string $view
     * @param array $parameters
     * @param Response $response
     *
     * @return Response
     */
    private function render($view, array $parameters = array(), Response $response = null)
    {
        $response = $response ?: new Response();

        $response->setContent($this->twig->render($view, $parameters));

        return $response;
    }
}

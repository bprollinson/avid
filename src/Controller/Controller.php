<?php

namespace Avid\CandidateChallenge\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
abstract class Controller
{
    /**
     * @param string $url
     * @param int $status
     *
     * @return RedirectResponse
     */
    protected function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * @param int $statusCode
     * @param string $message
     * @param array $headers
     *
     * @throws HttpException
     */
    protected function abort($statusCode, $message = '', array $headers = [])
    {
        throw new HttpException($statusCode, $message, null, $headers);
    }
}

<?php

namespace Avid\CandidateChallenge;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
interface Service
{
    const TWIG = 'twig';
    const URL_GENERATOR = 'url_generator';
    const FORM_FACTORY = 'form.factory';
    const SESSION = 'session';
    const CONTROLLERS_FACTORY = 'controllers_factory';
    const DATABASE = 'db';
}

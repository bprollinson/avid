<?php

namespace Avid\CandidateChallenge;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */

/**
 * @param string $shortName service/action
 *
 * @return string
 */
function controller($shortName)
{
    list($service, $action) = explode('/', $shortName);

    return sprintf('controller.%s:%sAction', $service, $action);
}

/**
 * @param string $serviceId
 *
 * @return string
 */
function converter($serviceId)
{
    return sprintf('%s:convert', $serviceId);
}

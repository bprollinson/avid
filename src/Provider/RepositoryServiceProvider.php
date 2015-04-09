<?php

namespace Avid\CandidateChallenge\Provider;

use Avid\CandidateChallenge\Repository\DoctrineMemberRepository;
use Avid\CandidateChallenge\Request\ParamConverter\MemberConverter;
use Avid\CandidateChallenge\Service;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class RepositoryServiceProvider implements ServiceProviderInterface
{
    const REPOSITORY_MEMBER = 'repository.member';
    const CONVERTER_MEMBER = 'converter.member';

    /**
     * @param Application $app An Application instance
     */
    public function register(Application $app)
    {
        $app[self::REPOSITORY_MEMBER] = function () use ($app) {
            return new DoctrineMemberRepository($app[Service::DATABASE]);
        };

        $app[self::CONVERTER_MEMBER] = $app->share(
            function () use ($app) {
                return new MemberConverter($app[self::REPOSITORY_MEMBER]);
            }
        );
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }
}

<?php

namespace Avid\CandidateChallenge\Provider;

use Avid\CandidateChallenge\Controller\MemberController;
use Avid\CandidateChallenge\Controller\SearchController;
use Avid\CandidateChallenge\Service;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Avid\CandidateChallenge as app;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class CandidateChallengeControllerProvider implements ControllerProviderInterface, ServiceProviderInterface
{
    const CONTROLLER_MEMBER = 'controller.member';
    const CONTROLLER_SEARCH = 'controller.search';

    const ROUTE_SEARCH = 'search';
    const ROUTE_MANAGE = 'manage';
    const ROUTE_PROFILE = 'profile';
    const ROUTE_MANAGE_MEMBERS = 'manage.members';
    const ROUTE_MEMBER_ADD = 'member.add';
    const ROUTE_MEMBER_DELETE = 'member.delete';

    /**
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app[Service::CONTROLLERS_FACTORY];

        $controllers
            ->get('/{page}', app\controller(SearchController::ACTION_SEARCH))
            ->value('page', 1)
            ->assert('page', '\d+')
            ->bind(self::ROUTE_SEARCH);

        $controllers
            ->get('/manage', function (Application $app) {
                /** @var UrlGenerator $urlGenerator */
                $urlGenerator = $app[Service::URL_GENERATOR];

                return $app->redirect($urlGenerator->generate(self::ROUTE_MANAGE_MEMBERS));
            })
            ->bind(self::ROUTE_MANAGE);

        $controllers
            ->get('/manage/members', app\controller(MemberController::ACTION_MANAGE))
            ->bind(self::ROUTE_MANAGE_MEMBERS);

        $controllers
            ->get('/{username}', app\controller(MemberController::ACTION_PROFILE))
            ->convert('member', app\converter(RepositoryServiceProvider::CONVERTER_MEMBER))
            ->bind(self::ROUTE_PROFILE);

        $controllers
            ->match('/manage/members/add', app\controller(MemberController::ACTION_ADD))
            ->method('GET|POST')
            ->bind(self::ROUTE_MEMBER_ADD);

        $controllers
            ->post('/member/{username}/delete', app\controller(MemberController::ACTION_DELETE))
            ->convert('member', app\converter(RepositoryServiceProvider::CONVERTER_MEMBER))
            ->bind(self::ROUTE_MEMBER_DELETE);

        return $controllers;
    }

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app[self::CONTROLLER_MEMBER] = $app->share(function () use ($app) {
            return new MemberController(
                $app[Service::TWIG],
                $app[Service::URL_GENERATOR],
                $app[Service::FORM_FACTORY],
                $app[Service::SESSION],
                $app[RepositoryServiceProvider::REPOSITORY_MEMBER]
            );
        });

        $app[self::CONTROLLER_SEARCH] = $app->share(function () use ($app) {
            return new SearchController(
                $app[Service::TWIG],
                $app[Service::URL_GENERATOR],
                $app[RepositoryServiceProvider::REPOSITORY_MEMBER]
            );
        });
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }
}

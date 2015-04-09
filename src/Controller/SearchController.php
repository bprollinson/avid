<?php

namespace Avid\CandidateChallenge\Controller;

use Avid\CandidateChallenge\DependencyInjection\TwigTrait;
use Avid\CandidateChallenge\DependencyInjection\UrlGeneratorTrait;
use Avid\CandidateChallenge\Repository\MemberRepository;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class SearchController extends Controller
{
    use TwigTrait;
    use UrlGeneratorTrait;

    const ACTION_SEARCH = 'search/search';

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * @param \Twig_Environment $twig
     * @param UrlGenerator $urlGenerator
     * @param MemberRepository $memberRepository
     */
    public function __construct(\Twig_Environment $twig, UrlGenerator $urlGenerator, MemberRepository $memberRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->urlGenerator = $urlGenerator;
        $this->twig = $twig;
    }

    /**
     * @param Request $request
     * @param int $page
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return Response
     */
    public function searchAction(Request $request, $page)
    {
        $keyword = $request->query->get('search');
        if ($request->query->has('search') && empty($keyword)) {
            return $this->redirect($this->path('search'));
        }

        $response = new Response();
        $resultsPerPage = 10;
        $first = ($page - 1) * $resultsPerPage;

        if (empty($keyword)) {
            $members = $this->memberRepository->findAll($first, $resultsPerPage);
            $membersCount = count($this->memberRepository);

            $response->headers->clearCookie('keyword');
        } else {
            $membersCount = $this->memberRepository->getSearchCount($keyword);
            $members = $this->memberRepository->search($keyword, $first, $resultsPerPage);

            $response->headers->setCookie(new Cookie('keyword', $keyword));
        }

        $pages = ceil($membersCount / $resultsPerPage);

        if ($page > $pages && $page != 1) {
            $this->abort(404, 'Page does not exist.');
        }

        return $this->render(
            'search.html.twig',
            [
                'members' => $members,
                'results' => $membersCount,
                'results_per_page' => $resultsPerPage,
                'pages' => $pages,
                'current_page' => $page,
                'keyword' => $keyword,
            ],
            $response
        );
    }
}

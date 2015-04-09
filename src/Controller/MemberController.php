<?php

namespace Avid\CandidateChallenge\Controller;

use Avid\CandidateChallenge\DependencyInjection\FormTrait;
use Avid\CandidateChallenge\DependencyInjection\SessionTrait;
use Avid\CandidateChallenge\DependencyInjection\TwigTrait;
use Avid\CandidateChallenge\DependencyInjection\UrlGeneratorTrait;
use Avid\CandidateChallenge\Form\Data\MemberData;
use Avid\CandidateChallenge\Form\MemberType;
use Avid\CandidateChallenge\Model\Address;
use Avid\CandidateChallenge\Model\BodyType;
use Avid\CandidateChallenge\Model\Email;
use Avid\CandidateChallenge\Model\Ethnicity;
use Avid\CandidateChallenge\Model\Height;
use Avid\CandidateChallenge\Model\Limits;
use Avid\CandidateChallenge\Model\Member;
use Avid\CandidateChallenge\Model\Weight;
use Avid\CandidateChallenge\Provider\CandidateChallengeControllerProvider as Routes;
use Avid\CandidateChallenge\Repository\MemberRepository;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class MemberController extends Controller
{
    use TwigTrait;
    use UrlGeneratorTrait;
    use FormTrait;
    use SessionTrait;

    const ACTION_MANAGE = 'member/manage';
    const ACTION_PROFILE = 'member/profile';
    const ACTION_ADD = 'member/add';
    const ACTION_DELETE = 'member/delete';

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * @param \Twig_Environment $twig
     * @param UrlGenerator $urlGenerator
     * @param FormFactory $formFactory
     * @param Session $session
     * @param MemberRepository $memberRepository
     */
    public function __construct(
        \Twig_Environment $twig,
        UrlGenerator $urlGenerator,
        FormFactory $formFactory,
        Session $session,
        MemberRepository $memberRepository
    ) {
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
        $this->session = $session;
        $this->memberRepository = $memberRepository;
        $this->formFactory = $formFactory;
    }

    /**
     * @return Response
     */
    public function manageAction()
    {
        return $this->render(
            'manage\members.html.twig',
            ['members' => $this->memberRepository->findAll()]
        );
    }

    /**
     * @param Request $request
     * @param Member $member
     *
     * @return Response
     */
    public function profileAction(Request $request, Member $member)
    {
        return $this->render(
            'profile.html.twig',
            ['member' => $member, 'keyword' => $request->cookies->get('keyword')]
        );
    }

    /**
     * @param Member $member
     *
     * @return RedirectResponse
     */
    public function deleteAction(Member $member)
    {
        $affectedMembers = $this->memberRepository->remove($member);

        if ($affectedMembers > 0) {
            $this->flashSuccess(sprintf('Member %s has been removed', $member->getUsername()));
        } else {
            $this->flashAlert(sprintf('Unable to remove member %s', $member->getUsername()));
        }

        return $this->redirect($this->path(Routes::ROUTE_MANAGE_MEMBERS));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(new MemberType());

        if ($request->isMethod('post')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $member = $this->convertDataToMember($form->getData());

                if ($this->memberRepository->add($member)) {
                    $this->flashSuccess(sprintf('Member "%s" was successfully added.', $member->getUsername()));
                } else {
                    $this->flashAlert(sprintf('Member "%s" could not be added.', $member->getUsername()));
                }

                return $this->redirect($this->path(Routes::ROUTE_MANAGE_MEMBERS));
            }
        }

        return $this->render('manage/members.add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param MemberData $data
     *
     * @return Member
     */
    private function convertDataToMember(MemberData $data)
    {
        return new Member(
            $data->username,
            $data->password,
            new Address('Canada', 'Ontario', $data->city, $data->postalCode),
            $data->dateOfBirth,
            Limits::all()[$data->limits],
            new Height($data->height),
            new Weight($data->weight),
            BodyType::all()[$data->bodyType],
            Ethnicity::all()[$data->ethnicity],
            new Email($data->email)
        );
    }
}

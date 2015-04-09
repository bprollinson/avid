<?php

namespace Avid\CandidateChallenge\Command;

use Avid\CandidateChallenge\Model\Member;
use Avid\CandidateChallenge\Model\Address;
use Avid\CandidateChallenge\Model\Email;
use Avid\CandidateChallenge\Model\Height;
use Avid\CandidateChallenge\Model\Weight;
use Avid\CandidateChallenge\Repository\MemberRepository;
use Faker\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class GenerateMembers extends Command
{
    const NAME = 'generate:members';

    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @param MemberRepository $memberRepository
     * @param \Faker\Generator $faker
     */
    public function __construct(MemberRepository $memberRepository, Generator $faker)
    {
        $this->memberRepository = $memberRepository;
        $this->faker = $faker;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Inserts fake members into the database')
            ->addArgument('count', InputArgument::REQUIRED, 'Amount of members to insert');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $count = $input->getArgument('count');
        $output->writeln(sprintf('<info>Generating %d members</info>', $count));

        for ($i = 1; $i <= $count; $i++) {
            $member = new Member(
                $this->faker->userName,
                $this->faker->word,
                new Address('Canada', 'Ontario', $this->faker->gtaCity, $this->faker->postCode),
                new \DateTime($this->faker->dateTimeBetween('-65 years', 'now - 18 years')->format('Y-m-d')),
                $this->faker->limits,
                new Height($this->faker->height),
                new Weight($this->faker->weight),
                $this->faker->bodyType,
                $this->faker->ethnicity,
                new Email($this->faker->freeEmail)
            );

            $this->memberRepository->add($member);

            $output->writeln(sprintf('%s', $member->getUsername()));
        }

        $output->writeln('<info>...done</info>');
    }
}

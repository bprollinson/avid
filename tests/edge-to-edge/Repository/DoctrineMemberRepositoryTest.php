<?php

namespace Avid\CandidateChallenge\Repository;

use Avid\CandidateChallenge\EdgeToEdgeTestCase;
use Avid\CandidateChallenge\Model\Address;
use Avid\CandidateChallenge\Model\Email;
use Avid\CandidateChallenge\Model\Height;
use Avid\CandidateChallenge\Model\Member;
use Avid\CandidateChallenge\Model\Weight;

/**
 * @covers \Avid\CandidateChallenge\Repository\DoctrineMemberRepository
 * @covers \Avid\CandidateChallenge\Repository\DoctrineRepository
 *
 * @uses \Avid\CandidateChallenge\Model\Address
 * @uses \Avid\CandidateChallenge\Model\Email
 * @uses \Avid\CandidateChallenge\Model\Height
 * @uses \Avid\CandidateChallenge\Model\Member
 * @uses \Avid\CandidateChallenge\Model\Weight
 *
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class DoctrineMemberRepositoryTest extends EdgeToEdgeTestCase
{
    const FIXTURES_COUNT = 3;

    /**
     * @var DoctrineMemberRepository
     */
    private $SUT;

    protected function setUp()
    {
        parent::setUp();

        $this->SUT = new DoctrineMemberRepository(self::getDatabase());
    }

    /**
     * @return array
     */
    protected function getSql()
    {
        return ['members.sql'];
    }

    /**
     * @test
     */
    public function it_should_find_all_the_members()
    {
        $members = $this->SUT->findAll();

        $this->assertCount(self::FIXTURES_COUNT, $members);
        $this->assertContainsOnlyInstancesOf(Member::CLASS_NAME, $members);
    }

    /**
     * @test
     */
    public function it_should_properly_hydrate_the_members_values()
    {
        $expectedMember = new Member(
            'kovacek.keara',
            'et',
            new Address('Canada', 'Ontario', 'Hamilton', 'H4Y 5E1'),
            new \DateTime('1993-04-11'),
            'Undecided',
            new Height('4\' 5"'),
            new Weight('150 lbs'),
            'Slim',
            'Other',
            new Email('kovacek.keara@email.com')
        );

        $this->assertEquals($expectedMember, $this->SUT->findByUsername('kovacek.keara'));
    }

    /**
     * @test
     */
    public function it_should_find_all_the_members_for_a_given_position()
    {
        $members = $this->SUT->findAll(1, 1);

        $this->assertCount(1, $members);
        $this->assertSame('nettie.nicolas', $members[0]->getUsername());
    }

    /**
     * @test
     */
    public function it_should_find_a_member_by_username()
    {
        $username = 'alexandria.legros';

        $member = $this->SUT->findByUsername($username);

        $this->assertInstanceOf(Member::CLASS_NAME, $member);
        $this->assertSame($username, $member->getUsername());
    }

    /**
     * @test
     */
    public function it_should_add_a_new_member()
    {
        $username = 'username';
        $password = 'password';
        $address = new Address('country', 'province', 'city', 'postal');
        $dateOfBirth = new \DateTime('1934-05-20');
        $limits = 'limits';
        $height = new Height('5\' 6"');
        $weight = new Weight('180 lbs');
        $bodyType = 'body type';
        $ethnicity = 'ethnicity';
        $email = new Email('some@email.com');

        $member = new Member(
            $username,
            $password,
            $address,
            $dateOfBirth,
            $limits,
            $height,
            $weight,
            $bodyType,
            $ethnicity,
            $email
        );

        $this->assertSame(1, $this->SUT->add($member));
        $this->assertTableRowCount(DoctrineMemberRepository::TABLE_NAME, self::FIXTURES_COUNT + 1);
        $this->assertEquals($member, $this->SUT->findByUsername($username));
    }

    /**
     * @test
     */
    public function it_should_update_a_member_by_username()
    {
        $username = 'kovacek.keara';
        $password = 'new password';
        $address = new Address('new country', 'new province', 'new city', 'new postal');
        $dateOfBirth = new \DateTime('1980-05-20');
        $limits = 'new limits';
        $height = new Height('5\' 4"');
        $weight = new Weight('200 lbs');
        $bodyType = 'new body type';
        $ethnicity = 'new ethnicity';
        $email = new Email('some@new_email.com');

        $member = new Member(
            $username,
            $password,
            $address,
            $dateOfBirth,
            $limits,
            $height,
            $weight,
            $bodyType,
            $ethnicity,
            $email
        );

        $this->assertSame(1, $this->SUT->update($member));
        $this->assertTableRowCount(DoctrineMemberRepository::TABLE_NAME, self::FIXTURES_COUNT);
        $this->assertEquals($member, $this->SUT->findByUsername($username));
    }

    /**
     * @test
     */
    public function it_should_remove_a_member()
    {
        $member = $this->SUT->findByUsername('kovacek.keara');

        $affectedRows = $this->SUT->remove($member);

        $this->assertSame($affectedRows, 1);
        $this->assertTableRowCount(
            DoctrineMemberRepository::TABLE_NAME,
            self::FIXTURES_COUNT - 1,
            $affectedRows
        );
        $this->assertNull($this->SUT->findByUsername('kovacek.keara'));
    }

    /**
     * @test
     */
    public function it_should_return_the_member_count()
    {
        $this->assertCount(self::FIXTURES_COUNT, $this->SUT);
    }

    /**
     * @test
     */
    public function it_should_search_for_members_by_matching_username()
    {
        $members = $this->SUT->search('legros');

        $this->assertCount(1, $members);
        $this->assertSame('alexandria.legros', $members[0]->getUsername());
    }

    /**
     * @test
     */
    public function it_should_count_the_members_found_by_search()
    {
        $this->assertSame(0, $this->SUT->getSearchCount('test'));
        $this->assertSame(1, $this->SUT->getSearchCount('nic'));
    }
}

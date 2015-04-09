<?php

namespace Avid\CandidateChallenge\Repository;

use Avid\CandidateChallenge\Model\Member;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
interface MemberRepository extends Repository
{
    /**
     * @param string $username
     *
     * @return Member|null
     */
    public function findByUsername($username);
}

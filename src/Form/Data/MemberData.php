<?php

namespace Avid\CandidateChallenge\Form\Data;

use Avid\CandidateChallenge\Model\BodyType;
use Avid\CandidateChallenge\Model\Ethnicity;
use Avid\CandidateChallenge\Model\Limits;
use Avid\CandidateChallenge\Model\Member;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class MemberData
{
    const CLASS_NAME = __CLASS__;

    public $username;
    public $password;
    public $city;
    public $postalCode;
    public $dateOfBirth;
    public $limits;
    public $height;
    public $weight;
    public $bodyType;
    public $ethnicity;
    public $email;

    /**
     * @param Member $member
     */
    public function __construct(Member $member = null)
    {
        if (!$member) {
            return;
        }

        $this->username = $member->getUsername();
        $this->password = $member->getPassword();
        $this->city = $member->getAddress()->getCity();
        $this->postalCode = $member->getAddress()->getPostalCode();
        $this->dateOfBirth = $member->getDateOfBirth();
        $this->limits = (int)array_search($member->getLimits(), Limits::all());
        $this->height = (string)$member->getHeight();
        $this->weight = (string)$member->getWeight();
        $this->bodyType = (int)array_search($member->getBodyType(), BodyType::all());
        $this->ethnicity = (int)array_search($member->getEthnicity(), Ethnicity::all());
        $this->email = (string)$member->getEmail();
    }
}

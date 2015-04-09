<?php

namespace Avid\CandidateChallenge\Model;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class Member
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var Address
     */
    private $address;
    /**
     * @var \DateTime
     */
    private $dateOfBirth;

    /**
     * @var string
     */
    private $limits;

    /**
     * @var Height
     */
    private $height;

    /**
     * @var Weight
     */
    private $weight;

    /**
     * @var string
     */
    private $bodyType;

    /**
     * @var string
     */
    private $ethnicity;

    /**
     * @var Email
     */
    private $email;

    /**
     * @param $username
     * @param $password
     * @param Address $address
     * @param \DateTime $dateOfBirth
     * @param $limits
     * @param Height $height
     * @param Weight $weight
     * @param $bodyType
     * @param $ethnicity
     * @param Email $email
     */
    public function __construct(
        $username,
        $password,
        Address $address,
        \DateTime $dateOfBirth,
        $limits,
        Height $height,
        Weight $weight,
        $bodyType,
        $ethnicity,
        Email $email
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->address = $address;
        $this->dateOfBirth = $dateOfBirth;
        $this->limits = $limits;
        $this->height = $height;
        $this->weight = $weight;
        $this->bodyType = $bodyType;
        $this->ethnicity = $ethnicity;
        $this->email = $email;
    }

    /**
     * @return \Avid\CandidateChallenge\Model\Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getBodyType()
    {
        return $this->bodyType;
    }

    /**
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @return \Avid\CandidateChallenge\Model\Email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getEthnicity()
    {
        return $this->ethnicity;
    }

    /**
     * @return \Avid\CandidateChallenge\Model\Height
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return string
     */
    public function getLimits()
    {
        return $this->limits;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return \Avid\CandidateChallenge\Model\Weight
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        $now = new \DateTime();

        return $now->diff($this->dateOfBirth)->y;
    }
}

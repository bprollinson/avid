<?php

namespace Avid\CandidateChallenge\Model;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class Address
{
    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $province;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $postalCode;

    /**
     * @param string $country
     * @param string $province
     * @param string $city
     * @param string $postalCode
     */
    public function __construct($country, $province, $city, $postalCode)
    {
        $this->province = $province;
        $this->city = $city;
        $this->country = $country;
        $this->postalCode = $postalCode;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }
}

<?php

namespace Avid\CandidateChallenge\Model;

/**
 * @covers \Avid\CandidateChallenge\Model\Member
 *
 * @uses \Avid\CandidateChallenge\Model\Address
 * @uses \Avid\CandidateChallenge\Model\Height
 * @uses \Avid\CandidateChallenge\Model\Weight
 * @uses \Avid\CandidateChallenge\Model\Email
 *
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class MemberTest extends \PHPUnit_Framework_TestCase
{
    private static $values = NULL;
    private $member = NULL;

    /**
     * Initialize static values used in simple value tests
     */
    private static function initializeValues() {
        // Needed because we can't instanitate statically and setUpBeforeClass called after data provider

        if (self::$values !== NULL) {
            return;
        }

        self::$values = array(
            'Username' => 'usernamevalue',
            'Password' => 'passwordvalue',
            'Address' => new Address("countryvalue", "provincevalue", "cityvalue", "postalcodevalue"),
            // Year - Month - Day
            'DateOfBirth' => new \DateTime('2000-01-10'),
            'Limits' => 'limitsvalue',
            'Height' => new Height('5\' 11"'),
            'Weight' => new Weight('180lb'),
            'BodyType' => 'bodytypevalue',
            'Ethnicity' => 'ethnicityvalue',
            'Email' => new Email('user@domain.com') 
        ); 
    }

    /**
     * Clean up static values after all tests are run
     */
    public static function tearDownAfterClass() {
        self::$values = NULL;
    }

    /**
     * Initialize the member we are using for testing
     */
    protected function setUp()
    {
        self::initializeValues();

        $this->member = new Member(
            self::$values['Username'],
            self::$values['Password'],
            self::$values['Address'],
            self::$values['DateOfBirth'],
            self::$values['Limits'],
            self::$values['Height'],
            self::$values['Weight'],
            self::$values['BodyType'],
            self::$values['Ethnicity'],
            self::$values['Email']
        );
    }

    /**
     * Clean up the member after each test
     */
    protected function tearDown() {
        $this->member = NULL;
    }

    public function simple_value_provider() {
        self::initializeValues();

        // Convert our mapping to 
        $result = array();
        foreach (self::$values as $key => $value) {
            $result[] = array($key, $value);
        }

        return $result;
    }

    /**
     * @test
     * @dataProvider simple_value_provider
     */
    public function it_should_store_and_retrieve_simple_value($attribute, $expectedValue) {
        $getter = 'get'.$attribute;
        $value = $this->member->$getter();

        $this->assertEquals($expectedValue, $value);
    }

    /**
     * Helper uses to construct a simple member with the provided date as date of birth
     * @param \DateTime $dateOfBirth
     */
    private static function get_simple_member_with_date($dateOfBirth) {
        return new Member(
            self::$values['Username'],
            self::$values['Password'],
            self::$values['Address'],
            $dateOfBirth,
            self::$values['Limits'],
            self::$values['Height'],
            self::$values['Weight'],
            self::$values['BodyType'],
            self::$values['Ethnicity'],
            self::$values['Email']
        );
    }

    /**
     * @test
     */
    public function it_should_use_current_time_by_default() {
        $now = new \DateTime();
        $eleven_months_ago = $now->sub(new \DateInterval('P11M'));
        $member = self::get_simple_member_with_date($eleven_months_ago);
        $age = $member->getAge();
        $this->assertEquals(0, $age);

        $now = new \DateTime();
        $thirteen_months_ago = $now->sub(new \DateInterval('P13M'));
        $member = self::get_simple_member_with_date($thirteen_months_ago);
        $age = $member->getAge();
        $this->assertEquals(1, $age);
    }

    /**
     * @test
     */
    public function it_should_return_an_exact_age() {
        $member = self::get_simple_member_with_date(new \DateTime('2000-01-01'));

        $now = new \DateTime('2001-01-01');
        $age = $member->getAge($now);

        $this->assertEquals(1, $age);
    }

    /**
     * @test
     */
    public function it_should_return_zero_for_age_less_than_one_year() {
        $member = self::get_simple_member_with_date(new \DateTime('2000-01-01'));

        $now = new \DateTime('2000-12-31');
        $age = $member->getAge($now);

        $this->assertEquals(0, $age);
    }

    /**
     * @test
     */
    public function it_should_round_down_for_positive_ages() {
        $member = self::get_simple_member_with_date(new \DateTime('2000-01-01'));

        $now = new \DateTime('2001-12-31');
        $age = $member->getAge($now);

        $this->assertEquals(1, $age);
    }

    /**
     * @test
     */
     public function it_should_not_count_insufficient_time_on_same_day_of_year() {
         $member = self::get_simple_member_with_date(new \DateTime('2000-01-01 12:00:00'));

         $now = new \DateTime('2001-01-01 11:59:59');
         $age = $member->getAge($now);

         $this->assertEquals(0, $age);
    }

    /**
     * @test
     */
     public function it_should_count_leap_day_as_after_feb_28() {
         $member = self::get_simple_member_with_date(new \DateTime('2015-02-28'));

         $now = new \DateTime('2016-02-29');
         $age = $member->getAge($now);

         $this->assertEquals(1, $age);
    }

    /**
     * @test
     */
     public function it_should_count_leap_day_as_before_march_1() {
         $member = self::get_simple_member_with_date(new \DateTime('2016-02-29'));

         $now = new \DateTime('2017-03-01');
         $age = $member->getAge($now);

         $this->assertEquals(1, $age);
    }

    /**
     * @test
     */
     public function it_should_perform_a_timezone_conversion() {
        $member = self::get_simple_member_with_date(new \DateTime('2000-01-01 12:00:00', new \DateTimeZone('America/New_York')));

        $now = new \DateTime('2001-01-01 10:00:00', new \DateTimeZone('America/Edmonton'));
        $age = $member->getAge($now);

        $this->assertEquals(1, $age);
     }

    /**
     * @test
     */
     public function it_should_work_in_viscinity_of_daylight_savings() {
         $member = self::get_simple_member_with_date(new \DateTime('2015-03-08 02:00:00', new \DateTimeZone('America/New_York')));

         $now = new \DateTime('2016-03-08 03:00:00', new \DateTimeZone('America/New_York'));
         $age = $member->getAge($now);

         $this->assertEquals(1, $age);
     }
}

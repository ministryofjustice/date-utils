<?php

namespace DateUtilsTest;


use DateUtils\WorkingDays;

class WorkingDaysTest extends \PHPUnit_Framework_TestCase
{

    protected $bankHolidays;

    /**
     * @var WorkingDays
     */
    protected $workingDays;

    public function setUp()
    {
        $this->bankHolidays = include('config/module.config.php');
        $this->workingDays = new WorkingDays($this->bankHolidays);
    }

    public function testWorkingDaysFromToday()
    {
        $result = $this->workingDays->workingDaysFromToday(1);
        $diff   = $result->diff(\DateTime::createFromFormat('d/m/Y h:i:s', date('d/m/Y 00:00:00')));
        $this->assertTrue($diff->days >= 1);
    }

    public function testWorkingDaysNoArguments()
    {
        $result = $this->workingDays->workingDaysFrom();
        $diff   = $result->diff(\DateTime::createFromFormat('d/m/Y h:i:s', date('d/m/Y 00:00:00')));
        $this->assertTrue($diff->days >= 1);
    }

    public function testWorkingDays()
    {
        $expected = \DateTime::createFromFormat('d/m/Y', '02/05/2014');

        $result = $this->workingDays->workingDaysFrom(
            \DateTime::createFromFormat('d/m/Y', '01/05/2014'),
            1
        );

        $this->assertEquals($expected->format('d/m/Y'), $result->format('d/m/Y'));
    }

    /**
     * know offset, friday, bank holiday monday, we expect tuesday the 6th
     */
    public function testWorkingDaysFromWithBankHoliday()
    {
        $expected = \DateTime::createFromFormat('d/m/Y', '06/05/2014');

        $result = $this->workingDays->workingDaysFrom(
            \DateTime::createFromFormat('d/m/Y', '02/05/2014'),
            1
        );

        $this->assertEquals($expected->format('d/m/Y'), $result->format('d/m/Y'));
    }

    /**
     * know offset, we expect it to be a working month of 20 days
     */
    public function testWorkingDaysForMay2014()
    {
        $expected = \DateTime::createFromFormat('d/m/Y', '30/05/2014');

        $result = $this->workingDays->workingDaysFrom(
            \DateTime::createFromFormat('d/m/Y', '30/04/2014'),
            20
        );

        $this->assertEquals($expected->format('d/m/Y'), $result->format('d/m/Y'));
    }

    /**
     * know offset, we expect it to be a working month of 20 days
     */
    public function testWorkingDaysStartingOnNewYears()
    {
        $expected = \DateTime::createFromFormat('d/m/Y', '02/01/2014');

        $result = $this->workingDays->workingDaysFrom(
            \DateTime::createFromFormat('d/m/Y', '01/01/2014'),
            1
        );

        $this->assertEquals($expected->format('d/m/Y'), $result->format('d/m/Y'));
    }

    public function testWorkingDaysWrapYears()
    {
        $expected = \DateTime::createFromFormat('d/m/Y', '07/01/2015');

        $result = $this->workingDays->workingDaysFrom(
            \DateTime::createFromFormat('d/m/Y', '30/12/2014'),
            5
        );

        $this->assertEquals($expected->format('d/m/Y'), $result->format('d/m/Y'));

    }

    public function testWorkingDaysBetween()
    {
        $expected = 5;

        $this->assertEquals(
            $expected,
            $this->workingDays->workingDaysBetween(
                \DateTime::createFromFormat('d/m/Y', '30/12/2014'),
                \DateTime::createFromFormat('d/m/Y', '07/01/2015')
            )
        );
    }

    /**
     * Will return 0 as we work on from the next working day on all our calculations, tomorrow is < 24 hours
     */
    public function testWorkingDaysTillTomorrow()
    {
        $expected = 0;

        $today = new \DateTime();
        $tomorrow = $today->modify('+1 day');
        $this->assertEquals(
            $expected,
            $this->workingDays->workingDaysBetween(
                $today,
                $tomorrow
            )
        );
    }

    public function testIsWorkingDayReturnsFalse()
    {
        $expected = date('Y-m-d', strtotime('1 January'));
        $expected = \DateTime::createFromFormat('Y-m-d', $expected);

        $this->assertFalse($this->workingDays->isWorkingDay($expected));
    }

    public function testIsWorkingDayReturnsTrue()
    {
        $expected = date('Y-m-d', strtotime('first monday of February'));
        $expected = \DateTime::createFromFormat('Y-m-d', $expected);

        $this->assertTrue($this->workingDays->isWorkingDay($expected));
    }

    public function testWorkingDayOffset()
    {
        $expectedFromOffset = $this->workingDays->workingDaysFromToday('P10D');
        $expected           = $this->workingDays->workingDaysFromToday(10);
        $this->assertEquals($expected, $expectedFromOffset);
    }

    public function testWorkingDaysOffsetNoDate()
    {
        $interval = 'PT30M';
        $expected = (new \DateTime())->add(new \DateInterval($interval));
        $result = $this->workingDays->workingDaysFromToday($interval);

        $this->assertEquals($expected, $result);

    }

    public function testNegativeDays()
    {
        $offset = -1;
        $expectedMessage = 'Cannot calculate working days on a negative offset.';

        try {
            $this->workingDays->workingDaysFromToday($offset);
        }
        catch(\Exception $e) {
            $this->assertTrue($e instanceof \LogicException);
            $this->assertEquals($expectedMessage, $e->getMessage());
        }
    }

    public function testWorkingDaysWithOffsetWraps()
    {
        $interval = 'PT1H';
        $dateStamp = \DateTime::createFromFormat('d/m/Y H:i:s', '04/07/2014 23:00:59');

        $expected = \DateTime::createFromFormat('d/m/Y H:i:s', '07/07/2014 00:00:00');
        $this->assertEquals($expected, $this->workingDays->workingDaysFrom($dateStamp, $interval));
    }
}

<?php

namespace DateUtilsTest;


use DateUtils\WorkingDays;

class WorkingDaysTest extends \PHPUnit_Framework_TestCase
{

    protected $bankHolidays;

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
}

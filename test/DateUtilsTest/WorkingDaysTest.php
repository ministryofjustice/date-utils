<?php

namespace DateUtilsTest;

use DateUtils\WorkingDays;

class WorkingDaysTest extends \PHPUnit_Framework_TestCase
{
    protected $workingDays;

    public function setUp()
    {
        $config = include('config/module.config.php');
        $this->workingDays = new WorkingDays($config);
    }

    public function testWorkingDaysBetween()
    {
        $start = new \DateTime('2015-06-02');
        $finish = new \DateTime('2015-06-04');

        $output = $this->workingDays->workingDaysBetween($start, $finish);

        $this->assertEquals(2, $output);
    }

    public function testWorkingDaysFromToday()
    {
        $expected = new \DateTime();
        $output = $this->workingDays->workingDaysFromToday();

        $this->assertTrue($output->diff($expected)->days >= 1);
    }

    public function testWorkingDaysFromWithDateAndInterval()
    {
        $expected = new \DateTime('2015-06-04');
        $output = $this->workingDays
            ->workingDaysFrom(new \DateTime('2015-06-02'), 'P2D');

        $this->assertEquals(
            $expected->format('Y-m-d'),
            $output->format('Y-m-d')
        );
    }

    public function testWorkingDaysFromWithDateAndIntervalWraps()
    {
        $expected = new \DateTime('2015-06-04 00:00:59');
        $output = $this->workingDays
            ->workingDaysFrom(new \DateTime('2015-06-03 23:00:59'), 'PT1H');

        $this->assertEquals(
            $expected->format('Y-m-d H:i:s'),
            $output->format('Y-m-d H:i:s')
        );
    }

    public function testWorkingDaysFromWithDateAndNegOffset()
    {
        $expected = new \DateTime('2015-06-02');
        $output = $this->workingDays
            ->workingDaysFrom(new \DateTime('2015-06-04'), -2);

        $this->assertEquals(
            $expected->format('Y-m-d'),
            $output->format('Y-m-d')
        );
    }

    public function testWorkingDaysFromWithDateAndNegOffsetOverBankHolWeekend()
    {
        $expected = new \DateTime('2015-05-21');
        $output = $this->workingDays
            ->workingDaysFrom(new \DateTime('2015-05-27'), -3);

        $this->assertEquals(
            $expected->format('Y-m-d'),
            $output->format('Y-m-d')
        );
    }

    public function testWorkingDaysFromWithDateAndOffset()
    {
        $expected = new \DateTime('2015-06-04');
        $output = $this->workingDays
            ->workingDaysFrom(new \DateTime('2015-06-02'), 2);

        $this->assertEquals(
            $expected->format('Y-m-d'),
            $output->format('Y-m-d')
        );
    }

    public function testWorkingDaysFromWithDateAndOffsetOverBankHolWeekend()
    {
        $expected = new \DateTime('2015-05-27');
        $output = $this->workingDays
            ->workingDaysFrom(new \DateTime('2015-05-21'), 3);

        $this->assertEquals(
            $expected->format('Y-m-d'),
            $output->format('Y-m-d')
        );
    }

    public function testWorkingDaysUntil()
    {
        $finish = new \DateTime();
        $finish->modify('+7 days');

        $output = $this->workingDays->workingDaysUntil($finish);

        $this->assertTrue($output >= 3);
    }

    public function testCalculateWorkingDayInThePast()
    {
        $dateToday = new \DateTime();

        $date11workingDaysAgo = $this->workingDays->calculateWorkingDaysInThePastFromDate($dateToday, 11);
        $date = $this->workingDays->workingDaysFrom($date11workingDaysAgo, 11);
        $this->assertEquals($date, $dateToday);
    }
}

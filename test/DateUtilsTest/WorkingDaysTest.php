<?php

namespace DateUtilsTest;

use DateInterval;
use DateTime;
use DateUtils\WorkingDays;
use PHPUnit_Framework_TestCase;

class WorkingDaysTest extends PHPUnit_Framework_TestCase
{
    protected $workingDays;

    public function setUp()
    {
        $config = include('config/module.config.php');
        $this->workingDays = new WorkingDays($config);
    }

    public function testWorkingDaysBetween()
    {
        $start = new DateTime('2015-06-02');
        $finish = new DateTime('2015-06-04');

        $output = $this->workingDays->workingDaysBetween($start, $finish);

        self::assertEquals(2, $output);
    }

    public function testWorkingDaysFromToday()
    {
        $expected = new DateTime();
        $output = $this->workingDays->workingDaysFromToday();

        self::assertTrue($output->diff($expected)->days >= 1);
    }

    public function testWorkingDaysFromWithDateAndInterval()
    {
        $expected = new DateTime('2015-06-04');
        $output = $this->workingDays
            ->workingDaysFrom(new DateTime('2015-06-02'), 'P2D');

        self::assertEquals(
            $expected->format('Y-m-d'),
            $output->format('Y-m-d')
        );
    }

    public function testWorkingDaysFromWithDateAndIntervalWraps()
    {
        $expected = new DateTime('2015-06-04 00:00:59');
        $output = $this->workingDays
            ->workingDaysFrom(new DateTime('2015-06-03 23:00:59'), 'PT1H');

        self::assertEquals(
            $expected->format('Y-m-d H:i:s'),
            $output->format('Y-m-d H:i:s')
        );
    }

    public function testWorkingDaysFromWithDateAndNegOffset()
    {
        $expected = new DateTime('2015-06-02');
        $output = $this->workingDays
            ->workingDaysFrom(new DateTime('2015-06-04'), -2);

        self::assertEquals(
            $expected->format('Y-m-d'),
            $output->format('Y-m-d')
        );
    }

    public function testWorkingDaysFromWithDateAndNegOffsetOverBankHolWeekend()
    {
        $expected = new DateTime('2015-05-21');
        $output = $this->workingDays
            ->workingDaysFrom(new DateTime('2015-05-27'), -3);

        self::assertEquals(
            $expected->format('Y-m-d'),
            $output->format('Y-m-d')
        );
    }

    public function testWorkingDaysFromWithDateAndOffset()
    {
        $expected = new DateTime('2015-06-04');
        $output = $this->workingDays
            ->workingDaysFrom(new DateTime('2015-06-02'), 2);

        self::assertEquals(
            $expected->format('Y-m-d'),
            $output->format('Y-m-d')
        );
    }

    public function testWorkingDaysFromWithDateAndOffsetOverBankHolWeekend()
    {
        $expected = new DateTime('2015-05-27');
        $output = $this->workingDays
            ->workingDaysFrom(new DateTime('2015-05-21'), 3);

        self::assertEquals(
            $expected->format('Y-m-d'),
            $output->format('Y-m-d')
        );
    }

    public function testWorkingDaysUntil()
    {
        $finish = new DateTime();
        $finish->modify('+7 days');

        $output = $this->workingDays->workingDaysUntil($finish);

        self::assertTrue($output >= 3);
    }

    public function testWorkingDaysIncludingToday()
    {
        $expected = new DateTime('2015-06-03');
        $output = $this->workingDays
            ->getWorkingDaysIncludingToday(new DateTime('2015-06-02'), 2);

        self::assertEquals(
            $expected->format('Y-m-d'),
            $output->format('Y-m-d')
        );
    }

    public function testAddIntervalsCanAdd()
    {
        $interval1 = new DateInterval('P1D');
        $interval2 = new DateInterval('P2D');

        $actualDiff = $this->workingDays->addIntervals($interval1, $interval2);

        $actualDateString = $actualDiff->format('%R%D');
        $expectedDateString = '+03';

        self::assertSame($expectedDateString, $actualDateString);
    }

    public function testAddIntervalsCanSubtract()
    {
        $interval1 = new DateInterval('P1D');
        $interval2 = DateInterval::createFromDateString('-2 days');

        $actualDiff = $this->workingDays->addIntervals($interval1, $interval2);

        $actualDateString = $actualDiff->format('%R%D');
        $expectedDateString = '-01';

        self::assertSame($expectedDateString, $actualDateString);
    }
}

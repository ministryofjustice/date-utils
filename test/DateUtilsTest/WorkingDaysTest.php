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

    public function test_workingDaysBetween_tuesday_and_thursday_is_two()
    {
        $start = new DateTime('2015-06-02');
        $finish = new DateTime('2015-06-04');

        $output = $this->workingDays->workingDaysBetween($start, $finish);

        self::assertEquals(2, $output);
    }

    public function test_workingDaysFrom_today_is_always_at_least_one_day()
    {
        $today = new DateTime();
        $output = $this->workingDays->workingDaysFromToday();

        self::assertTrue($output->diff($today)->days >= 1);
    }

    public function test_workingDaysFrom_tuesday_plus_two_day_interval_is_thursday()
    {
        $expected = new DateTime('2015-06-04');
        $output = $this->workingDays
            ->workingDaysFrom(new DateTime('2015-06-02'), 'P2D');

        self::assertEquals(
            $expected->format('Y-m-d'),
            $output->format('Y-m-d')
        );
    }

    public function test_workingDaysFrom_wednesday_230059_hours_plus_one_hour_is_thursday_000059()
    {
        $expected = new DateTime('2015-06-04 00:00:59');
        $output = $this->workingDays
            ->workingDaysFrom(new DateTime('2015-06-03 23:00:59'), 'PT1H');

        self::assertEquals(
            $expected->format('Y-m-d H:i:s'),
            $output->format('Y-m-d H:i:s')
        );
    }

    public function test_workingDaysFrom_thursday_minus_two_days_offset_is_tuesday()
    {
        $expected = new DateTime('2015-06-02');
        $output = $this->workingDays
            ->workingDaysFrom(new DateTime('2015-06-04'), -2);

        self::assertEquals(
            $expected->format('Y-m-d'),
            $output->format('Y-m-d')
        );
    }

    public function test_workingDaysFrom_wednesday_minus_three_days_offset_over_bank_holiday_weekend_is_thursday()
    {
        $expected = new DateTime('2015-05-21');
        $output = $this->workingDays
            ->workingDaysFrom(new DateTime('2015-05-27'), -3);

        self::assertEquals(
            $expected->format('Y-m-d'),
            $output->format('Y-m-d')
        );
    }

    public function test_workingDaysFrom_tuesday_plus_two_days_offset_is_thursday()
    {
        $expected = new DateTime('2015-06-04');
        $output = $this->workingDays
            ->workingDaysFrom(new DateTime('2015-06-02'), 2);

        self::assertEquals(
            $expected->format('Y-m-d'),
            $output->format('Y-m-d')
        );
    }

    public function test_workingDaysFrom_thursday_plus_three_days_offset_is_wednesday()
    {
        $expected = new DateTime('2015-05-27');
        $output = $this->workingDays
            ->workingDaysFrom(new DateTime('2015-05-21'), 3);

        self::assertEquals(
            $expected->format('Y-m-d'),
            $output->format('Y-m-d')
        );
    }

    public function test_workingDaysUntil_week_from_today_is_at_least_three()
    {
        $finish = new DateTime();
        $finish->modify('+7 days');

        $output = $this->workingDays->workingDaysUntil($finish);

        self::assertTrue($output >= 3);
    }

    public function test_workingDaysIncludingToday_from_tuesday_with_two_days_offset_is_wednesday()
    {
        $expected = new DateTime('2015-06-03');
        $output = $this->workingDays
            ->getWorkingDaysIncludingToday(new DateTime('2015-06-02'), 2);

        self::assertEquals(
            $expected->format('Y-m-d'),
            $output->format('Y-m-d')
        );
    }

    public function test_addIntervals_1_day_plus_two_days_is_three()
    {
        $interval1 = new DateInterval('P1D');
        $interval2 = new DateInterval('P2D');

        $actualDiff = $this->workingDays->addIntervals($interval1, $interval2);

        $actualDateString = $actualDiff->format('%R%D');
        $expectedDateString = '+03';

        self::assertSame($expectedDateString, $actualDateString);
    }

    public function test_addIntervals_one_day_minus_two_days_is_minus_one()
    {
        $interval1 = new DateInterval('P1D');
        $interval2 = DateInterval::createFromDateString('-2 days');

        $actualDiff = $this->workingDays->addIntervals($interval1, $interval2);

        $actualDateString = $actualDiff->format('%R%D');
        $expectedDateString = '-01';

        self::assertSame($expectedDateString, $actualDateString);
    }
}

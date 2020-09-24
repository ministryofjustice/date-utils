<?php

namespace DateUtilsTest;

use DateTime;
use DateUtils\BankHolidays;
use PHPUnit_Framework_TestCase;

class BankHolidaysTest extends PHPUnit_Framework_TestCase
{
    public function testGetBankHolidaysForDateTime()
    {
        $holidays = BankHolidays::getBankHolidaysForDateTime(
            new DateTime('2014-01-01')
        );
        self::assertTrue(is_array($holidays));

        self::assertTrue(array_key_exists('newYearsDay', $holidays));
        self::assertTrue(array_key_exists('goodFriday', $holidays));
        self::assertTrue(array_key_exists('easterMonday', $holidays));
        self::assertTrue(array_key_exists('earlyMay', $holidays));
        self::assertTrue(array_key_exists('spring', $holidays));
        self::assertTrue(array_key_exists('summer', $holidays));
        self::assertTrue(array_key_exists('xmasDay', $holidays));
        self::assertTrue(array_key_exists('boxingDay', $holidays));
    }

    public function testGetBankHolidaysForDateTimeIn2005()
    {
        $holidays = BankHolidays::getBankHolidaysForDateTime(
            new DateTime('2005-01-01')
        );
        self::assertEquals(
            '2005-01-03',
            $holidays['newYearsDay']->format('Y-m-d')
        );
        self::assertEquals(
            '2005-03-25',
            $holidays['goodFriday']->format('Y-m-d')
        );
        self::assertEquals(
            '2005-03-28',
            $holidays['easterMonday']->format('Y-m-d')
        );
        self::assertEquals(
            '2005-05-02',
            $holidays['earlyMay']->format('Y-m-d')
        );
        self::assertEquals(
            '2005-05-30',
            $holidays['spring']->format('Y-m-d')
        );
        self::assertEquals(
            '2005-08-29',
            $holidays['summer']->format('Y-m-d')
        );
        self::assertEquals(
            '2005-12-27',
            $holidays['xmasDay']->format('Y-m-d')
        );
        self::assertEquals(
            '2005-12-26',
            $holidays['boxingDay']->format('Y-m-d')
        );
    }

    public function testGetBankHolidaysForDateTimeIn2010()
    {
        $holidays = BankHolidays::getBankHolidaysForDateTime(
            new DateTime('2010-01-01')
        );
        self::assertEquals(
            '2010-01-01',
            $holidays['newYearsDay']->format('Y-m-d')
        );
        self::assertEquals(
            '2010-04-02',
            $holidays['goodFriday']->format('Y-m-d')
        );
        self::assertEquals(
            '2010-04-05',
            $holidays['easterMonday']->format('Y-m-d')
        );
        self::assertEquals(
            '2010-05-03',
            $holidays['earlyMay']->format('Y-m-d')
        );
        self::assertEquals(
            '2010-05-31',
            $holidays['spring']->format('Y-m-d')
        );
        self::assertEquals(
            '2010-08-30',
            $holidays['summer']->format('Y-m-d')
        );
        self::assertEquals(
            '2010-12-27',
            $holidays['xmasDay']->format('Y-m-d')
        );
        self::assertEquals(
            '2010-12-28',
            $holidays['boxingDay']->format('Y-m-d')
        );
    }

    public function testGetBankHolidaysForDateTimeIn2015()
    {
        $holidays = BankHolidays::getBankHolidaysForDateTime(
            new DateTime('2015-01-01')
        );
        self::assertEquals(
            '2015-01-01',
            $holidays['newYearsDay']->format('Y-m-d')
        );
        self::assertEquals(
            '2015-04-03',
            $holidays['goodFriday']->format('Y-m-d')
        );
        self::assertEquals(
            '2015-04-06',
            $holidays['easterMonday']->format('Y-m-d')
        );
        self::assertEquals(
            '2015-05-04',
            $holidays['earlyMay']->format('Y-m-d')
        );
        self::assertEquals(
            '2015-05-25',
            $holidays['spring']->format('Y-m-d')
        );
        self::assertEquals(
            '2015-08-31',
            $holidays['summer']->format('Y-m-d')
        );
        self::assertEquals(
            '2015-12-25',
            $holidays['xmasDay']->format('Y-m-d')
        );
        self::assertEquals(
            '2015-12-28',
            $holidays['boxingDay']->format('Y-m-d')
        );
    }

    public function testGetBankHolidaysWithConfig()
    {
        $config = include('config/module.config.php');
        $bankHolidays = new BankHolidays($config, '2012');
        $holidays = $bankHolidays->getBankHolidays();

        self::assertTrue(array_key_exists('diamondJubilee', $holidays));
        self::assertEquals('2012-06-04', $holidays['spring']);
        self::assertEquals('2012-06-05', $holidays['diamondJubilee']);
    }

    public function testGetBankHolidaysWithEmptyConfig()
    {
        $config = include('config/module.config.php');
        $bankHolidays = new BankHolidays($config, '2000');
        $holidays = $bankHolidays->getBankHolidays();

        self::assertEquals('2000-01-03', $holidays['newYearsDay']);
        self::assertEquals('2000-04-21', $holidays['goodFriday']);
        self::assertEquals('2000-04-24', $holidays['easterMonday']);
        self::assertEquals('2000-05-01', $holidays['earlyMay']);
        self::assertEquals('2000-05-29', $holidays['spring']);
        self::assertEquals('2000-08-28', $holidays['summer']);
        self::assertEquals('2000-12-25', $holidays['xmasDay']);
        self::assertEquals('2000-12-26', $holidays['boxingDay']);
    }

    public function testGetEasterForDateTime()
    {
        // Easter 2000
        $list = BankHolidays::getEasterForDateTime(new DateTime('2000-01-01'));
        self::assertEquals(
            '2000-04-23',
            $list['easterSunday']->format('Y-m-d')
        );

        // Easter 2005
        $list = BankHolidays::getEasterForDateTime(new DateTime('2005-01-01'));
        self::assertEquals(
            '2005-03-27',
            $list['easterSunday']->format('Y-m-d')
        );

        // Easter 2010
        $list = BankHolidays::getEasterForDateTime(new DateTime('2010-01-01'));
        self::assertEquals(
            '2010-04-04',
            $list['easterSunday']->format('Y-m-d')
        );

        // Easter 2015
        $list = BankHolidays::getEasterForDateTime(new DateTime('2015-01-01'));
        self::assertEquals(
            '2015-04-05',
            $list['easterSunday']->format('Y-m-d')
        );

        // Easter 2020
        $list = BankHolidays::getEasterForDateTime(new DateTime('2020-01-01'));
        self::assertEquals(
            '2020-04-12',
            $list['easterSunday']->format('Y-m-d')
        );

        // Easter 2025
        $list = BankHolidays::getEasterForDateTime(new DateTime('2025-01-01'));
        self::assertEquals(
            '2025-04-20',
            $list['easterSunday']->format('Y-m-d')
        );

        // Easter 2030
        $list = BankHolidays::getEasterForDateTime(new DateTime('2030-01-01'));
        self::assertEquals(
            '2030-04-21',
            $list['easterSunday']->format('Y-m-d')
        );
    }
}

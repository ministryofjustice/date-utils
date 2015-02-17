<?php

namespace DateUtilsTest;

use DateUtils\BankHolidays;

class BankHolidaysTest extends \PHPUnit_Framework_TestCase
{
    protected $bankHolidays;

    public function setUp()
    {
        $configs        = include('config/module.config.php');

        $this->bankHolidays = new BankHolidays($configs, 2012);
    }

    public function testSetUpEmptyConfig()
    {
        $configs        = include('config/module.config.php');
        $bankHolidays = new BankHolidays($configs, 2000);
        $bankHolidays = $bankHolidays->getBankHolidays();

        $this->assertEquals('2000-01-01', $bankHolidays['newYearsDay']);
        $this->assertEquals('2000-12-25', $bankHolidays['xmasDay']);
    }

    public function testCalculateFixedHolidays()
    {
        $holidays = BankHolidays::calculateFixedHolidays(2014);
        $this->assertTrue(is_array($holidays));

        $this->assertTrue(array_key_exists('newYearsDay', $holidays));
        $this->assertTrue(array_key_exists('goodFriday', $holidays));
        $this->assertTrue(array_key_exists('easterMonday', $holidays));
        $this->assertTrue(array_key_exists('earlyMay', $holidays));
    }

    public function testGetBankHolidays()
    {
        $holidays = $this->bankHolidays->getBankHolidays();
        $this->assertTrue(array_key_exists('queensDiamondJubilee', $holidays));
        $this->assertEquals("2012-02-01", $holidays['newYearsDay']);
    }

    public function testEasterDate()
    {
        $this->assertEquals(BankHolidays::easterDate(2000), mktime(0, 0, 0, 4, 23, 2000));
        $this->assertEquals(BankHolidays::easterDate(2005), mktime(0, 0, 0, 3, 27, 2005));
        $this->assertEquals(BankHolidays::easterDate(2010), mktime(0, 0, 0, 4, 4, 2010));
        $this->assertEquals(BankHolidays::easterDate(2015), mktime(0, 0, 0, 4, 5, 2015));
        $this->assertEquals(BankHolidays::easterDate(2020), mktime(0, 0, 0, 4, 12, 2020));
    }

    public function testEasterDateRaisesWarningWhenANonNumberIsPassed()
    {
        $currentClass = $this;
        $handler = set_error_handler(
            function($errno, $errstring) use ($currentClass) {
                $currentClass->assertEquals($errno, E_USER_WARNING);
                $currentClass->assertEquals(
                    $errstring,
                    'easterDate expects parameter 1 to be long, string given on line 71'
                );
            }
        );

        $this->assertNull(BankHolidays::easterDate('invalidNumber'));

        set_error_handler($handler);
    }
}

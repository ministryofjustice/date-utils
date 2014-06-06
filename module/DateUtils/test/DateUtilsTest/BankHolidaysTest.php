<?php

namespace DateUtilsTest;

use DateUtils\BankHolidays;
use Zend\ServiceManager\ServiceManager;

class BankHolidaysTest extends \PHPUnit_Framework_TestCase
{
    protected $bankHolidays;

    public function setUp()
    {
        $serviceManager = new ServiceManager();
        $configs        = include('config/autoload/bankholidays.global.php');

        $serviceManager->setService('config', $configs);

        $this->bankHolidays = new BankHolidays($serviceManager, 2012);
    }

    public function testSetUpEmptyConfig()
    {
        $serviceManager = new ServiceManager();
        $configs        = include('config/autoload/bankholidays.global.php');

        $serviceManager->setService('config', $configs);

        $bankHolidays = new BankHolidays($serviceManager, 2000);
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
}

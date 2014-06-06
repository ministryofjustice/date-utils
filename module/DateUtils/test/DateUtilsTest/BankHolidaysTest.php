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
        $configs = include('config/autoload/bankholidays.global.php');

        $serviceManager->setService('config', $configs);

        $this->bankHolidays = new BankHolidays($serviceManager, 2012);
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

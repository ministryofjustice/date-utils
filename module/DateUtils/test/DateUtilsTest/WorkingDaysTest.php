<?php

namespace DateUtilsTest;


use DateUtils\WorkingDays;

class WorkingDaysTest extends \PHPUnit_Framework_TestCase
{

    public function testWorkingDays()
    {
        $expected = \DateTime::createFromFormat('d/m/Y', '02/05/2014');

        $result = WorkingDays::workingDaysFrom(\DateTime::createFromFormat('d/m/Y','01/05/2014'), 1);

        $this->assertEquals($expected->format('d/m/Y'), $result->format('d/m/Y'));
    }
    /**
     * know offset, friday, bank holiday monday, we expect tuesday the 6th
     */
    public function testWorkingDaysFromWithBankHoliday()
    {
        $expected = \DateTime::createFromFormat('d/m/Y', '06/05/2014');

        $result = WorkingDays::workingDaysFrom(\DateTime::createFromFormat('d/m/Y','02/05/2014'), 1);

        $this->assertEquals($expected->format('d/m/Y'), $result->format('d/m/Y'));
    }
}

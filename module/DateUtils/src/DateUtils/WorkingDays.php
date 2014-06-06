<?php

namespace DateUtils;

/**
 * Class WorkingDays
 * @package DateUtils
 */
class WorkingDays
{
    /**
     * @param array     $config
     * @param \DateTime $initialDate
     * @param int       $workingDayOffset
     * @return \DateTime
     */
    public static function workingDaysFrom(array $config, \DateTime $initialDate = null, $workingDayOffset = 1)
    {
        if (null === $initialDate) {
            $initialDate = new \DateTime();
        }

        $dayCounter = 1;
        $currentDay = $initialDate->getTimestamp();
        $holidays   = self::getBankHolidays($config, $initialDate->format('Y'));

        while ($dayCounter <= $workingDayOffset) {

            $date       = date('Y-m-d', $currentDay);
            $currentDay = strtotime($date . ' +1 day');
            $date       = date('Y-m-d', $currentDay);
            $weekday    = date('N', $currentDay);

            if ($weekday < 6 && !in_array($date, $holidays)) {
                $dayCounter++;
            }
        }

        return $initialDate->setTimestamp($currentDay);
    }

    /**
     * @param array $config
     * @param       $year
     * @return array
     */
    protected static function getBankHolidays(array $config, $year)
    {
        $bankHolidays = new BankHolidays($config, $year);
        return $bankHolidays->getBankHolidays();
    }
}

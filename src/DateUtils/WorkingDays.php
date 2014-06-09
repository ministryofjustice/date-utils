<?php

namespace DateUtils;

/**
 * Class WorkingDays
 * @package DateUtils
 */
class WorkingDays
{
    /**
     * @var array
     */
    protected $config;

    public function __construct(array $config)
    {
        $this->config =  $config;
    }

    /**
     * @param \DateTime $initialDate
     * @param int       $workingDayOffset
     * @return \DateTime
     */
    public function workingDaysFrom(\DateTime $initialDate = null, $workingDayOffset = 1)
    {
        if (null === $initialDate) {
            $initialDate = new \DateTime();
        }

        $dayCounter = 1;
        $currentDay = $initialDate->getTimestamp();
        $holidays   = $this->getBankHolidays($initialDate->format('Y'));

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
     * @param       $year
     * @return array
     */
    protected function getBankHolidays($year)
    {
        $bankHolidays = new BankHolidays($this->config, $year);
        return $bankHolidays->getBankHolidays();
    }
}

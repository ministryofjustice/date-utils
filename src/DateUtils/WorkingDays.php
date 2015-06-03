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
        $this->config = $config;
    }

    /**
     * @param \DateTime $initialDate
     * @param int $workingDayOffset
     * @return \DateTime
     * @throws \LogicException
     */
    public function workingDaysFrom(\DateTime $date = null, $offset = 1)
    {
        if ($date === null) {
            $date = new \DateTime();
        }

        if (!is_int($offset)) {
            return $this->workingDaysFromOffset(
                $date,
                new \DateInterval($offset)
            );
        }

        $counter = 0;
        $current = clone $date;

        while ($counter !== $offset) {
            if ($offset < 0) {
                $current->modify('-1 day');
            } else {
                $current->modify('+1 day');
            }

            if ($this->isWorkingDay($current)) {
                if ($offset < 0) {
                    $counter--;
                } else {
                    $counter++;
                }
            }
        }

        return $current;
    }

    /**
     * If the offset is greater than 1 day or it forces us to rollover convert
     * it back to an int and call the other function
     *
     * @param \DateTime     $date
     * @param \DateInterval $offset
     *
     * @return \DateTime
     */
    protected function workingDaysFromOffset(
        \DateTime $date,
        \DateInterval $offset
    ) {
        $start = clone $date;
        $start->setTime(0, 0, 0);

        $finish = clone $date;
        $finish->add($offset);

        $hours = intval($finish->format('H'));
        $minutes = intval($finish->format('i'));
        $seconds = intval($finish->format('s'));

        $date->setTime($hours, $minutes, $seconds);

        $finish->setTime(0, 0, 0);

        $difference = $start->diff($finish);

        $days = intval($difference->format('%r%d'));

        return $this->workingDaysFrom($date, $days);
    }

    /**
     * @param \DateTime $date
     *
     * @return bool
     */
    public function isWorkingDay(\DateTime $date)
    {
        $holidays = $this->getBankHolidays($date->format('Y'));

        $day = intval($date->format('N'));
        $string = $date->format('Y-m-d');

        if ($day < 6 && !in_array($string, $holidays)) {
            return true;
        }

        return false;
    }

    /**
     * Alias function to calculate days from today, which is the most common usage
     *
     * @param int $offset
     *
     * @return \DateTime
     */
    public function workingDaysFromToday($offset = 1)
    {
        return $this->workingDaysFrom(null, $offset);
    }

    /**
     * @param \DateTime $startDay
     * @param \DateTime $endDay
     * @return int
     */
    public function workingDaysBetween(\DateTime $startDay, \DateTime $endDay)
    {
        $holidays = $this->getBankHolidays($startDay->format('Y'));
        $weekDays = range(1, 5);

        $interval = new \DateInterval('P1D');
        $periods = new \DatePeriod($startDay, $interval, $endDay);

        $days = 0;

        foreach ($periods as $period) {

            if ($period->format('Y') != $startDay->format('Y')) {
                $holidays = $this->getBankHolidays($period->format('Y'));
            }

            if (!in_array($period->format('N'), $weekDays)) {
                continue;
            }
            if (in_array($period->format('Y-m-d'), $holidays)) {
                continue;
            }
            $days++;
        }

        return $days;
    }

    /**
     * @param  int $year
     * @return array
     */
    protected function getBankHolidays($year)
    {
        $bankHolidays = new BankHolidays($this->config, $year);

        return $bankHolidays->getBankHolidays();
    }

    /**
     * @param \DateTime $endDay
     * @return int
     */
    public function workingDaysUntil(\DateTime $endDay)
    {
        return $this->workingDaysBetween(new \DateTime(), $endDay);
    }
}

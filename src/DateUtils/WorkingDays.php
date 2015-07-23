<?php
/**
 * Date Utils
 *
 * PHP version 5
 *
 * @package DateUtils
 * @author  Brett Minnie
 * @author  Eddie Abou-Jaoude
 * @author  Dave Nash <dave.nash@teaandcode.com>
 * @license http://opensource.org/licenses/MIT The MIT License
 * @version GIT: $Id$
 * @link    https://github.com/ministryofjustice/date-utils Date Utils
 */

namespace DateUtils;

/**
 * Working days for England and Wales
 *
 * @package DateUtils\WorkingDays
 * @author  Brett Minnie
 * @author  Eddie Abou-Jaoude
 * @author  Dave Nash <dave.nash@teaandcode.com>
 * @license http://opensource.org/licenses/MIT The MIT License
 * @version Release: @package_version@
 * @link    https://github.com/ministryofjustice/date-utils Date Utils
 */
class WorkingDays
{
    /**
     * @var array
     *
     * @access protected
     */
    protected $config;

    /**
     * Sets-up configuration array
     *
     * @param array $config Configuration array
     *
     * @access public
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Returns whether date is a working day in England and Wales
     *
     * @param \DateTime $date Date to check
     *
     * @access public
     * @return boolean
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
     * Returns number of working days in England and Wales between two dates
     *
     * @param \DateTime $start  Start date
     * @param \DateTime $finish Finish date
     *
     * @access public
     * @return integer
     */
    public function workingDaysBetween(\DateTime $start, \DateTime $finish)
    {
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($start, $interval, $finish);

        $counter = 0;

        foreach ($period as $current) {
            if ($this->isWorkingDay($current)) {
                $counter++;
            }
        }

        return $counter;
    }

    /**
     * Returns the offset working day in England and Wales from date specified
     *
     * @param \DateTime $date   Start date
     * @param integer   $offset Working days to count
     *
     * @access public
     * @return \DateTime
     */
    public function workingDaysFrom(\DateTime $date = null, $offset = 1)
    {
        if ($date === null) {
            $date = new \DateTime();
        }

        if (!is_int($offset)) {
            return $this->workingDaysFromWithInterval(
                $date,
                new \DateInterval($offset)
            );
        }

        return $this->getWorkingDayFromDateWithOffset($date, $offset);
    }

    /**
     * @param integer    $daysInPast
     * @param \DateTime  $date
     * @return \DateTime
     *
     * Alias method so that someone can easily find a day in the past
     */
    public function workingDaysInThePast(\DateTime $date = null, $daysInPast = 1)
    {
        if (null === $date) {
            $date = new \DateTime();
        }

        // Ensure our offset is negative
        $daysInPast = abs($daysInPast)*-1;
        return $this->workingDaysFrom($date, $daysInPast);
    }

    /**
     * Returns the offset working day in England and Wales from today
     *
     * @param integer   $offset Working days to count
     *
     * @access public
     * @return \DateTime
     */
    public function workingDaysFromToday($offset = 1)
    {
        return $this->workingDaysFrom(null, $offset);
    }

    /**
     * Returns the next working day in England and Wales from date specified
     * using date interval to calculate days ahead
     *
     * @param \DateTime     $date     Start date
     * @param \DateInterval $interval Interval to add to date
     *
     * @access public
     * @return \DateTime
     */
    public function workingDaysFromWithInterval(
        \DateTime $date,
        \DateInterval $interval
    ) {
        $start = clone $date;
        $start->setTime(0, 0, 0);

        $finish = clone $date;
        $finish->add($interval);

        $hours = intval($finish->format('H'));
        $minutes = intval($finish->format('i'));
        $seconds = intval($finish->format('s'));

        $date->setTime($hours, $minutes, $seconds);

        $finish->setTime(0, 0, 0);

        $difference = $start->diff($finish);

        $offset = intval($difference->format('%r%d'));

        return $this->getWorkingDayFromDateWithOffset($date, $offset);
    }

    /**
     * Returns number of working days in England and Wales until date specified
     *
     * @param \DateTime $finish Finish date
     *
     * @access public
     * @return integer
     */
    public function workingDaysUntil(\DateTime $finish)
    {
        return $this->workingDaysBetween(new \DateTime(), $finish);
    }

    /**
     * Returns array of bank holidays in format specified for the year
     *
     * @param string $year
     *
     * @access public
     * @return array
     */
    protected function getBankHolidays($year)
    {
        $bankHolidays = new BankHolidays($this->config, $year);

        return $bankHolidays->getBankHolidays();
    }

    /**
     * Returns the offset working day in England and Wales from date specified
     *
     * @param \DateTime $date   Start date
     * @param integer   $offset Working days to count
     *
     * @access public
     * @return \DateTime
     */
    protected function getWorkingDayFromDateWithOffset(\DateTime $date, $offset)
    {
        $counter = 0;
        $current = clone $date;

        while ($counter !== $offset) {
            $this->shiftDate($current, $offset);

            if ($this->isWorkingDay($current)) {
                $counter = $this->shiftCounter($counter, $offset);
            }
        }

        return $current;
    }

    /**
     * Increments or decrements the counter depending on offset
     *
     * @param integer $counter Counter
     * @param integer $offset  Working days to count
     *
     * @access protected
     * @return integer
     */
    protected function shiftCounter($counter, $offset)
    {
        if ($offset < 0) {
            $counter--;
        } else {
            $counter++;
        }

        return $counter;
    }

    /**
     * Moves the date up or down by a day depending on offset
     *
     * @param \DateTime $date   Current date
     * @param integer   $offset Working days to count
     *
     * @access protected
     * @return null
     */
    protected function shiftDate(\DateTime $date, $offset)
    {
        if ($offset < 0) {
            $date->modify('-1 day');
        } else {
            $date->modify('+1 day');
        }
    }
}

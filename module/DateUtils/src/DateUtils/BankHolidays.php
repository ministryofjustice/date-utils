<?php

namespace DateUtils;

use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class BankHolidays
 * @package DateUtils
 */
final class BankHolidays
{
    /**
     * @var string
     */
    protected $year;

    /**
     * @var array
     */
    protected $bankHolidays;

    public function __construct(ServiceLocatorInterface $sm, $year)
    {
        $this->bankHolidays = array_merge(
            self::calculateFixedHolidays($year),
            (is_array($sm->get('config')['bankHolidays'][$year]))?
                $sm->get('config')['bankHolidays'][$year] :
                array()
        );
    }

    /**
     * @param $year
     * @return array
     */
    public static function calculateFixedHolidays($year)
    {
        $bankHolidays['newYearsDay']   = date('Y-m-d', strtotime('first day of january ' .$year));
        $bankHolidays['goodFriday']    = date('Y-m-d', strtotime('previous friday', easter_date($year)));
        $bankHolidays['easterMonday']  = date('Y-m-d', strtotime('next monday', easter_date($year)));
        $bankHolidays['earlyMay']      = date('Y-m-d', strtotime('first monday of may ' . $year));
        $bankHolidays['lastMay']       = date('Y-m-d', strtotime('last monday of may ' . $year));
        $bankHolidays['lateAugust']    = date('Y-m-d', strtotime('last monday of august ' . $year));
        $bankHolidays['xmasDay']       = date('Y-m-d', strtotime('25 december ' . $year));
        $bankHolidays['boxingDay']     = date('Y-m-d', strtotime('26 december ' . $year));

        return $bankHolidays;
    }

    /**
     * @return array
     */
    public function getBankHolidays()
    {
        return $this->bankHolidays;
    }
}

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
        $bankHolidays['newYearsDay']   = date('d-m-Y', strtotime('first day of january ' .$year));
        $bankHolidays['goodFriday']    = date('d-m-Y', strtotime('previous friday', easter_date($year)));
        $bankHolidays['easterMonday']  = date('d-m-Y', strtotime('next monday', easter_date($year)));
        $bankHolidays['earlyMay']      = date('d-m-Y', strtotime('first monday of may ' . $year));
        $bankHolidays['lastMay']       = date('d-m-Y', strtotime('last monday of may ' . $year));
        $bankHolidays['lateAugust']    = date('d-m-Y', strtotime('last monday of august ' . $year));
        $bankHolidays['xmasDay']       = date('d-m-Y', strtotime('25 december ' . $year));
        $bankHolidays['boxingDay']     = date('d-m-Y', strtotime('26 december ' . $year));

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

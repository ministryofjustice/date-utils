<?php

namespace DateUtils;

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

    public function __construct(ServiceManagerAwareInterface $sm, $year)
    {
        $this->bankHolidays = array_merge(
            $this->calculateFixedHolidays($year),
            (is_array($sm->get('config')['override'][$year]))?
                $sm->get('config')['override'][$year] :
                array()
        );
    }

    /**
     * @param $year
     * @return array
     */
    protected function calculateFixedHolidays($year)
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

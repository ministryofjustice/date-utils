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

    public function __construct($configArray, $year)
    {
        $this->bankHolidays = array_merge(
            self::calculateFixedHolidays($year),
            (!empty($configArray['bankHolidays'][$year])) ?
                $configArray['bankHolidays'][$year] :
                array()
        );
    }

    /**
     * @param $year
     * @return array
     */
    public static function calculateFixedHolidays($year)
    {
        $bankHolidays['newYearsDay']  = date('Y-m-d', strtotime('first day of january ' . $year));
        $bankHolidays['goodFriday']   = date('Y-m-d', strtotime('previous friday', easter_date($year)));
        $bankHolidays['easterMonday'] = date('Y-m-d', strtotime('next monday', easter_date($year)));
        $bankHolidays['earlyMay']     = date('Y-m-d', strtotime('first monday of may ' . $year));
        $bankHolidays['lastMay']      = date('Y-m-d', strtotime('last monday of may ' . $year));
        $bankHolidays['lateAugust']   = date('Y-m-d', strtotime('last monday of august ' . $year));
        $bankHolidays['xmasDay']      = date('Y-m-d', strtotime('25 december ' . $year));
        $bankHolidays['boxingDay']    = date('Y-m-d', strtotime('26 december ' . $year));

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

if (false === function_exists('easter_dates')) {
    function easter_date ($Year) {

        /*
           G is the Golden Number-1
          H is 23-Epact (modulo 30)
         I is the number of days from 21 March to the Paschal full moon
                    J is the weekday for the Paschal full moon (0=Sunday,
                         1=Monday, etc.)
                            L is the number of days from 21 March to the Sunday on or before
                                 the Paschal full moon (a number between -6 and 28)
                                    */


        $G = $Year % 19;
        $C = (int)($Year / 100);
        $H = (int)($C - (int)($C / 4) - (int)((8*$C+13) / 25) + 19*$G + 15) % 30;
        $I = (int)$H - (int)($H / 28)*(1 - (int)($H / 28)*(int)(29 / ($H + 1))*((int)(21 - $G) / 11));
        $J = ($Year + (int)($Year/4) + $I + 2 - $C + (int)($C/4)) % 7;
        $L = $I - $J;
        $m = 3 + (int)(($L + 40) / 44);
        $d = $L + 28 - 31 * ((int)($m / 4));
        $y = $Year;
        $E = mktime(0,0,0, $m, $d, $y);

        return $E;

    }
}
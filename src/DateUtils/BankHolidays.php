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
        $bankHolidays['newYearsDay'] = date('Y-m-d', strtotime('first day of january ' . $year));
        $bankHolidays['goodFriday'] = date('Y-m-d', strtotime('previous friday', easter_date($year)));
        $bankHolidays['easterMonday'] = date('Y-m-d', strtotime('next monday', easter_date($year)));
        $bankHolidays['earlyMay'] = date('Y-m-d', strtotime('first monday of may ' . $year));
        $bankHolidays['lastMay'] = date('Y-m-d', strtotime('last monday of may ' . $year));
        $bankHolidays['lateAugust'] = date('Y-m-d', strtotime('last monday of august ' . $year));
        $bankHolidays['xmasDay'] = date('Y-m-d', strtotime('25 december ' . $year));
        $bankHolidays['boxingDay'] = date('Y-m-d', strtotime('26 december ' . $year));

        return $bankHolidays;
    }

    /**
     * @return array
     */
    public function getBankHolidays()
    {
        return $this->bankHolidays;
    }

    /**
     * @param int $year
     * @return int
     *
     * @note see http://en.wikipedia.org/wiki/Computus
     */
    public static function easterDate($year)
    {
        //BC Behaviour
        if (false === is_int($year)) {
            $errorMessage = sprintf(
                '%s %s %d',
                __FUNCTION__,
                'expects parameter 1 to be long, string given on line',
                __LINE__
            );

            trigger_error($errorMessage, E_USER_WARNING);

            return null;
        }

        $goldenNumber = $year % 19;
        $century = (int)($year / 100);

        $lunarAge =
            (int)($century - (int)($century / 4) -
                (int)((8 * $century + 13) / 25) + 19 * $goldenNumber + 15) % 30;

        $fullMoonOffset =
            (int)$lunarAge -
            (int)($lunarAge / 28) *
            (1 - (int)($lunarAge / 28) * (int)(29 / ($lunarAge + 1)) * ((int)(21 - $goldenNumber) / 11));

        $weekday = ($year + (int)($year / 4) + $fullMoonOffset + 2 - $century + (int)($century / 4)) % 7;

        $sundayOffset = $fullMoonOffset - $weekday;
        $month = 3 + (int)(($sundayOffset + 40) / 44);
        $day = $sundayOffset + 28 - 31 * ((int)($month / 4));

        $easterTimeStamp = mktime(0, 0, 0, $month, $day, $year);

        return $easterTimeStamp;
    }
}

/**
 * If we do not have the easter_date function defined, we can create our own, it is less efficient than raw C
 */
if (false === function_exists('easter_dates')) {
    function easter_date($year)
    {
        return BankHolidays::easterDate($year);
    }
}
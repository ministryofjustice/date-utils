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
 * Bank holidays for England and Wales
 *
 * @package DateUtils\BankHolidays
 * @author  Brett Minnie
 * @author  Eddie Abou-Jaoude
 * @author  Dave Nash <dave.nash@teaandcode.com>
 * @license http://opensource.org/licenses/MIT The MIT License
 * @version Release: @package_version@
 * @link    https://github.com/ministryofjustice/date-utils Date Utils
 */
final class BankHolidays
{
    /**
     * @var array
     *
     * @access protected
     */
    protected $holidays;

    /**
     * Sets-up bank holidays including others specified in the configuration
     * array for the year specified
     *
     * @param array  $config Configuration array
     * @param string $year   Year for obtaining bank holidays
     *
     * @access public
     */
    public function __construct($config, $year)
    {
        $other = null;

        if (isset($config['bankHolidays'])) {
            $other = $config['bankHolidays'];
        }

        $this->holidays = self::getBankHolidaysForDateTime(
            new \DateTime($year . '-01-01'),
            $other
        );
    }

    /**
     * Returns array of bank holidays in format specified for the year
     * configured by the constructor
     *
     * @param string $format
     *
     * @access public
     * @return array
     */
    public function getBankHolidays($format = 'Y-m-d')
    {
        $holidays = $this->holidays;

        foreach ($holidays as $name => $date) {
            $holidays[$name] = $date->format($format);
        }

        return $holidays;
    }

    /**
     * Returns array of bank holidays including others specified in the array as
     * DateTime objects for the year and specified as a DateTime object
     *
     * @param \DateTime $date  Any date in the year to get bank holidays
     * @param array     $other Array of years containing other special holidays
     *
     * @access public
     * @return array<\DateTime>
     */
    public static function getBankHolidaysForDateTime(
        \DateTime $date,
        array $other = null
    ) {
        $specials = array();
        $holidays = self::getStandardBankHolidaysForDateTime($date);
        $year = $date->format('Y');

        if ($other !== null && is_array($other)) {
            if (isset($other[$year]) && is_array($other[$year])) {
                foreach ($other[$year] as $name => $value) {
                    $special = new \DateTime($value);
                    if ($special->format('Y') == $year) {
                        $specials[$name] = $special;
                    }
                }
                $holidays = array_merge($holidays, $specials);
            }
        }

        return $holidays;
    }

    /**
     * Returns array of easter dates as DateTime objects for the year
     * specified as a DateTime object
     *
     * @param \DateTime $date
     *
     * @access public
     * @return array<\DateTime>
     */
    public static function getEasterForDateTime(\DateTime $date)
    {
        $date->modify('21 march')->modify(
            '+' . easter_days($date->format('Y')) . ' days'
        );

        $goodFriday = clone $date;
        $easterMonday = clone $date;

        return array(
            'goodFriday' => $goodFriday->modify('previous friday'),
            'easterSunday' => $date,
            'easterMonday' => $easterMonday->modify('next monday')
        );
    }

    /**
     * Returns array of standard bank holidays as DateTime objects for the year
     * specified as a DateTime object
     *
     * @param \DateTime $date
     *
     * @access public
     * @return array<\DateTime>
     */
    public static function getStandardBankHolidaysForDateTime(\DateTime $date)
    {
        $newYearsDay = clone $date;
        $newYearsDay->modify('1 january');

        switch ($newYearsDay->format('w')) {
            case '0':
                $newYearsDay->modify('2 january');
                break;

            case '6':
                $newYearsDay->modify('3 january');
                break;
        }

        $easter = self::getEasterForDateTime(clone $date);

        $earlyMay = clone $date;
        $spring = clone $date;
        $summer = clone $date;

        $xmasDay = clone $date;
        $xmasDay->modify('25 december');

        $boxingDay = clone $date;
        $boxingDay->modify('26 december');

        switch ($xmasDay->format('w')) {
            case '0':
                $xmasDay->modify('27 december');
                break;

            case '5':
                $boxingDay->modify('28 december');
                break;

            case '6':
                $xmasDay->modify('27 december');
                $boxingDay->modify('28 december');
                break;
        }

        return array(
            'newYearsDay' => $newYearsDay,
            'goodFriday' => $easter['goodFriday'],
            'easterMonday' => $easter['easterMonday'],
            'earlyMay' => $earlyMay->modify('first monday of may'),
            'spring' => $spring->modify('last monday of may'),
            'summer' => $summer->modify('last monday of august'),
            'xmasDay' => $xmasDay,
            'boxingDay' => $boxingDay
        );
    }
}

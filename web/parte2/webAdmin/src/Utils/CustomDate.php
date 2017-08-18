<?php
/**
 * Util to Get Datetime using or not a format
 * considering the UTC and America/Lima timezones
 */

namespace App\Utils;


class CustomDate
{
    const DATE_FORMAT = "Y-m-d";
    const DATETIME_FORMAT = "Y-m-d H:i:s";

    /**
     * Get the date time according to the time zone<br>
     * The cronJob run in the midnight but generate the Summary of the day before<br>
     * so it returns the previous date
     * @param string|null $date Date provided by format Y-m-d
     * @return string Return tha date/datetime according to the format
     */
    static function getDateForTheCronJob(string $date = null)
    {
        if ($date) {
            return self::getDateProvided($date);
        }

        $date = new \DateTime();

        //convert from UTC to America/Lima timezone
        //$date->modify('-5 hour');
        //Get the day previous day's date
        $date->modify('-1 day');
        return $date->format(self::DATE_FORMAT);
    }

    /**
     * Check if the date provided is correct
     * @param string $date Date provided by format Y-m-d
     * @param string|null $format Date Format such as Y-m-d
     * @return bool|string Return Date else false
     */
    static function getDateProvided(string $date, string $format = null)
    {
        $format = $format ? $format : self::DATE_FORMAT;
        $date = \DateTime::createFromFormat($format, $date);
        //Get the day before
        //$date->modify('-1 day');

        return $date ? $date->format(self::DATE_FORMAT) : null;
    }

    /**
     * Get the date time according to the time zone<br>
     *
     * @param string|null $format Date Format such as Y-m-d
     * @param bool $timezone Timezone
     * @return string Return tha date/datetime according to the format
     */
    static function getDateTime(string $format = null, $timezone = false)
    {
        $format = $format ? $format : self::DATETIME_FORMAT;
        $date = new \DateTime();

        //convert from UTC to America/Lima timezone
//        if ($timezone) {
//            $date->modify('-5 hour');
//        }
        return $date->format($format);
    }

    /**
     * Get the date time according to the time zone<br>
     *
     * @param string|null $date Date such as 2017-02-15
     * @param string|null $format Date Format such as Y-m-d
     * @param bool $timezone Timezone
     * @return string Return tha date/datetime according to the format
     */
    static function getDateTimeObject(string $date = null, string $format = 'Y-m-d', $timezone = false)
    {
        $format = $format ? $format : self::DATETIME_FORMAT;

        if (!empty($date)) {
            $date = \DateTime::createFromFormat($format, $date);
        } else {
            $date = new \DateTime();
        }
        //convert from UTC to America/Lima timezone
//        if (is_object($date)  && $timezone) {
//            $date->modify('-5 hour');
//        }

        return $date ? $date : null;
    }
}
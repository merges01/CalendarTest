<?php

namespace Calendar;

use DateTimeInterface;

class Calendar implements CalendarInterface {

	private $date;

	public function __construct( DateTimeInterface $datetime ) {
        $this->date = $datetime;
	}

    /**
     * Get the day
     *
     * @return int
     */
	public function getDay() {
		return (int) $this->date->format('d');
	}
    
    /**
     * Get the weekday (1-7, 1 = Monday)
     *
     * @return int
     */
	public function getWeekDay() {
        return (int) $this->date->format('w');
    }
    
    /**
     * Get the first weekday of this month (1-7, 1 = Monday)
     *
     * @return int
     */
    public function getFirstWeekDay() {
        $year = $this->date->format('Y');
        $month = $this->date->format('m');
        $date = getdate(mktime( null, null, null, $month, 1, $year));

        return (int) $date['wday'];
    }
    
    /**
     * Get the first week of this month (18th March => 9 because March starts on week 9)
     *
     * @return int
     */
    public function getFirstWeek() {
        $date = clone $this->date;
        $currentDay = $this->getDay();

        if ($currentDay > 1) {
            $date->modify('-' . ($currentDay-1) . ' day');
        }

        return (int) $date->format('W');
    }

    /**
     * Get the number of days in this month
     *
     * @return int
     */
    public function getNumberOfDaysInThisMonth() {
        return (int) $this->date->format('t');
    }

    /**
     * Get the number of days in the previous month
     *
     * @return int
     */
    public function getNumberOfDaysInPreviousMonth() {
        $date = clone $this->date;
        $lastMonthDate = $date->modify('-1 month');
        $month = (int) $lastMonthDate->format('m');
        $year = (int) $lastMonthDate->format('Y');

        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    /**
     * Get the calendar array
     *
     * @return array
     */
    public function getCalendar() {
        $date = clone $this->date;
        $firstWeekDay = $this->getFirstWeekDay();
        $firstWeek = $this->getFirstWeek();
        $prevMonthDaysNum = $this->getNumberOfDaysInPreviousMonth();
        $currentMonthDaysNum = $this->getNumberOfDaysInThisMonth();
        $weekday = $this->getWeekDay();
        $currentDay = $this->getDay();
        $calendar = array();
        $startNum = 0;
        $index = 1;

        if ($firstWeekDay > 1) {
            $date->modify('-' . $currentDay . ' day');
            $startNum = 0-$firstWeekDay;
        } else {
            $date->modify('-' . ($currentDay-1) . ' day');
        }

        for ( $i = $startNum; $i < 35; $i++ ) {
            echo $date->format('m-d') . ' | ';

            $calendar[$firstWeek][(int) $date->format('d')] = false;

            if ( $index == 7 ) {
                $index = 0;
                $firstWeek += 1;
                echo '<br>';
            }
            
            $date->modify('+1 day');
            $index += 1;
        }

        return $calendar;
    }
}

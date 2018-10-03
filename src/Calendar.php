<?php

namespace Calendar;

use DateTime;
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
        return (int) $this->date->format('N');
    }
    
    /**
     * Get the first weekday of this month (1-7, 1 = Monday)
     *
     * @return int
     */
    public function getFirstWeekDay() {
        return (int) (new DateTime($this->date->format('Y-m-01')))->format('N');
    }
    
    /**
     * Get the first week of this month (18th March => 9 because March starts on week 9)
     *
     * @return int
     */
    public function getFirstWeek() {
        return (int) (new DateTime($this->date->format('Y-m-01')))->format('W');
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
       return (int) (new DateTime($this->date->format('Y-m-d')))->modify('last day of previous month')->format('j');
    }

    /**
     * Get the calendar array
     *
     * @return array
     */
    public function getCalendar() {
        $startNum = 0;
        $weekIndex = 1;
        $prevYearLastWeekReset = false;
        $calendar = array();

        $date = clone $this->date;
        $firstWeekDay = $this->getFirstWeekDay();
        $firstWeek = $this->getFirstWeek();
        $currentWeek = (int) $date->format('W');
        $currentMonthDaysNum = $this->getNumberOfDaysInThisMonth();
        $currentMonth = (int) $date->format('m');
        $currentDay = $this->getDay();
        $firstWeekFromLastYear = in_array($firstWeek, array(52, 53));
        $allDays = ceil(($currentMonthDaysNum + ($firstWeekDay-1)) / 7) * 7;


        /**
         * Set the current calendar start date with monday
         */
        if ($firstWeekDay > 1 && $currentDay > 1) {
            $date->modify('-' . ($currentDay+$firstWeekDay-2) . ' day');
            $startNum -= $firstWeekDay;
        }

        if ($firstWeekDay == 1 && $currentDay > 1) {
            $date->modify('-' . ($currentDay-1) . ' day');
        }

        if ($firstWeekDay > 1 && $currentDay == 1) {
            $date->modify('-' . ($firstWeekDay-1) . ' day');
        }

        for ($i = 0; $i < $allDays; $i++) {
            $highlight = false;
            $firstWeekFromLastYear = in_array($firstWeek, array(52, 53));

            if (($currentWeek-1) == $firstWeek || (($currentWeek-1) == 0 && $firstWeekFromLastYear)) {
                $highlight = true;
            }
            
            $calendar[$firstWeek][(int) $date->format('d')] = $highlight;

            if ($weekIndex % 7 == 0) {
                if ($currentMonth == 1 && !$prevYearLastWeekReset) {
                    $firstWeek = 0;
                    $prevYearLastWeekReset = true;
                }

                $firstWeek += 1;
            }

            $weekIndex += 1;
            $date->modify('+1 day');
        }

        return $calendar;
    }
}

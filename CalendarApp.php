<?php

namespace CalendarApp;

require __DIR__ . '/vendor/autoload.php';

use \DateTime;
use Calendar;

$date = new DateTime('2010-03-01');
$calendar = new Calendar\Calendar( $date );

//print_r($calendar->getWeekDay());
print_r($calendar->getCalendar());
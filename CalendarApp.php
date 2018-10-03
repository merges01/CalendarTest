<?php

namespace CalendarApp;

require __DIR__ . '/vendor/autoload.php';

use DateTime;
use Calendar;

$date = new DateTime('2016-01-13');
$calendar = new Calendar\Calendar( $date );

//print_r($calendar->getWeekDay());
print_r($calendar->getCalendar());
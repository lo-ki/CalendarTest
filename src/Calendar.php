<?php
namespace Calendar;

use \DateTime;

class Calendar implements CalendarInterface
{
	private $datetime = false;

	/**
	 * Functions required by test
	 */
		public function __construct($date) {
			$this->datetime = $date;
		}

		public function getDay() {
			return (int) $this->datetime->format('j');
		}
	
		public function getWeekDay() {
			return (int) $this->datetime->format('N');
		}

		public function getFirstWeekDay() {
			$year = $this->getCurrentYear();
			$month = $this->getCurrentMonth();

			$date = (new DateTime())->setDate($year, $month, 1);
			$current = $date->format('N'); 
			return (int) $current;
		}

		public function getNumberOfDaysInThisMonth() {
			return (int) $this->datetime->format('t');
		}

		public function getNumberOfDaysInPreviousMonth() {
			$year = $this->getCurrentYear();
			$month = $this->getCurrentMonth();
			
			$date = (new DateTime())->setDate($year, $month, 1);
			$date->modify('-1 month');

			return (int) $date->format('t');
		}

		public function getFirstWeek() {
			$year = $this->getCurrentYear();
			$month = $this->getCurrentMonth();

			return $this->getDaysInWeek($year, $month, 1);
		}

		public function getCalendar() {
			$month_array = array();
			
			$year = $this->getCurrentYear();
			$month = $this->getCurrentMonth();
			$day = $this->getDay();

			$date = (new DateTime())->setDate($year, $month, 1);

			$previous_week = $this->getPreviousWeek();

			$c_month = $month;
			while ($c_month === $month) {
				$week = (int) $date->format('W');
				$day = $date->format('j');

				$week_array = $this->getDaysInWeek($year, $month, $day);

				if ($week === $previous_week) {
					foreach($week_array as $key => $value) {
						$week_array[$key] = true;
					}
				}

				$month_array[$week] = $week_array;

				$date->modify('+7 days');
				$c_month = (int) $date->format('n');
			}

			$last_keys = array_keys($week_array);
			if (end($last_keys) < $this->getNumberOfDaysInThisMonth() && !in_array(1, $last_keys)) {
				$week = (int) $date->format('W');
				$day = $date->format('j');

				$week_array = $this->getDaysInWeek($year, $c_month, $day);
				$month_array[$week] = $week_array;
			}

			return $month_array;
		}

	/**
	 * Helper Functions
	 */
		public function getCurrentYear() {
			return (int) $this->datetime->format('Y');
		}

		public function getCurrentMonth() {
			return (int) $this->datetime->format('n');
		}

		public function getCurrentWeek() {
			return (int) $this->datetime->format('W');
		}

		public function getPreviousMonth() {
			$year = $this->getCurrentYear();
			$month = $this->getCurrentMonth();
	
			$date = (new DateTime())->setDate($year, $month, 1);
			$date->modify('-1 month');

			return (int) $date->format('n');
		}

		public function getPreviousWeek() {
			$year = $this->getCurrentYear();
			$month = $this->getCurrentMonth();
			$day = $this->getDay();
	
			$date = (new DateTime())->setDate($year, $month, $day);
			$date->modify('-1 week');

			return (int) $date->format('W');
		}

		public function getDaysInWeek($year, $month, $day) {
			$date = (new DateTime())->setDate($year, $month, $day);
			$week = $date->format('W');

			$c_week = $week;
			while($c_week === $week) {
				$date->modify('-1 day');
				$c_week = $date->format('W');
			}

			$week_array = array();
			while(count($week_array) < 7) {
				$date->modify('+1 day');
				$c_day = $date->format('j');
				$week_array[$c_day] = false;
			}

			return $week_array;
		}
}

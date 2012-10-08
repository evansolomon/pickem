<?php

/**
 * Represents a participant in a result
 *
 * all arguments are integers
 */
class Team {
	public $name;
	public $points;
	public $yards;
	public $turnovers;

	function __construct( $name, $points, $yards, $turnovers ) {
		$this->name      = $name;
		$this->points    = (int) $points;
		$this->yards     = (int) $yards;
		$this->turnovers = (int) $turnovers;
	}
}

/**
 * Representes the result of a game
 *
 * $winner and $loser and instances of Team objects
 * $away is boolean
 */
class Result {
	public $winner;
	public $loser;
	public $away;

	function __construct( $winner, $loser, $away ) {
		$this->winner = $winner;
		$this->loser  = $loser;
		$this->away   = (bool) $away;
	}
}

/**
 * Represents a particular scheduled game
 *
 * $season and $week are integers
 * $day is a string, e.g. 'Fri'
 * $result is an instance of a Result object
 */
class Game {
	public $season;
	public $week;
	public $day;
	public $result;

	function __construct( $season, $week, $day, $result ) {
		$this->season = (int) $season;
		$this->week   = (int) $week;
		$this->day    = (int) $day;
		$this->result = $result;
	}
}

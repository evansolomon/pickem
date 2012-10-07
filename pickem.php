<?php

/**
 * Represents a participant in a result
 *
 * all arguments are integers
 */
class Team {
	public $points;
	public $yards;
	public $turnovers;

	function __construct( $points, $yards, $turnovers ) {
		$this->points    = $points;
		$this->yards     = $yards;
		$this->turnovers = $turnovers;
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
		$this->away   = $away;
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
		$this->season = $season;
		$this->week   = $week;
		$this->day    = $day;
		$this->result = $result;
	}
}
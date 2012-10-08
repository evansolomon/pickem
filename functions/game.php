<?php

// Abstract data about particular games

class Game_Data {
	static function get_winner( $game ) {
		return $game->result->winner;
	}

	static function get_loser( $game ) {
		return $game->result->loser;
	}

	static function is_winner( $game, $team ) {
		return $team == get_winner( $game )->name;
	}

	static function is_winner_away( $game ) {
		return $game->result->away;
	}

	static function get_stat( $game, $stat, $winner = true ) {
		$team = ( $winner ) ? 'winner' : 'loser';

		return $game->result->$team->$stat;
	}

	static function get_points( $game, $winner = true ) {
		return self::get_stat( $game, 'points', $winner );
	}

	static function get_yards( $game, $winner = true ) {
		return self::get_stat( $game, 'yards', $winner );
	}

	static function get_turnovers( $game, $winner = true ) {
		return self::get_stat( $game, 'turnovers', $winner );
	}
}

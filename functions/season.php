<?php

// Abstract data about a team's season-to-data
class Season_Data {
	static function get_wins( $season, $week, $team ) {
		$wins = 0;
		for ( $i = 0; $i < $week; $i++ ) {
			$game = get_game( $season, $i, $team );
			if ( $team == Game_Data::get_winner( $game )->name )
				$wins++;
		}

		return $wins;
	}

	static function get_losses( $season, $week, $team ) {
		$wins = 0;
		for ( $i = 0; $i < $week; $i++ ) {
			$game = get_game( $season, $i, $team );
			if ( $team == Game_Data::get_winner( $game )->name )
				$wins++;
		}

		return $wins;
	}

	static function get_record( $season, $week, $team ) {
		$wins   = self::get_wins( $season, $week, $team );
		$losses = self::get_losses( $season, $week, $team );

		return array( 'wins' => $wins, 'losses' => $losses );
	}

	static function get_stat( $season, $week, $team, $stat ) {
		$total = 0;
		for ( $i = 0; $i < $week; $i++ ) {
			$game   =  get_game( $season, $week, $team );
			$winner =  Game_Data::is_winner( $game, $team );
			$total  += Game_Data::get_stat( $game, $winner );
		}

		return $total;
	}

	static function get_points( $season, $week, $team ) {
		return self::get_stat( $season, $week, $team, 'points' );
	}

	static function get_yards( $season, $week, $team ) {
		return self::get_stat( $season, $week, $team, 'yards' );
	}

	static function get_turnovers( $season, $week, $team ) {
		return self::get_stat( $season, $week, $team, 'turnovers' );
	}
}

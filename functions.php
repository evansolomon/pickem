<?php

/**
 * Get a Result object for a game
 */
function get_game( $season, $week, $team ) {
	$season = (int) $season;
	$week   = (int) $week;
	$team   = mysql_real_escape_string( $team );

	$sql_query = "SELECT *
		FROM games
		WHERE season = $season AND week = $week AND ( winner = '$team' OR loser = '$team' )";

	$sql_result = mysql_query( $sql_query );
	$game       = mysql_fetch_assoc( $sql_result );

	// Make sure the game exists
	if ( ! $game )
		return false;

	// Create team objects
	$winner = new Team( $game['winner'], $game['points_winner'], $game['yards_winner'], $game['turnovers_winner'] );
	$loser = 	new Team( $game['loser'],  $game['points_loser'],  $game['yards_loser'],  $game['turnovers_loser']  );

	// Create Result object
	$result = new Result( $winner, $loser, $game['away_win'] );

	// Create new Game object
	return new Game( $season, $week, $game['day'], $result );
}

/**
 * Get an array of result objects for a week of games
 */
function get_week( $season, $week ) {
	$season = (int) $season;
	$week   = (int) $week;

	$sql_query = "SELECT winner
		FROM games
		WHERE season = $season AND week = $week";

	$sql_result = mysql_query( $sql_query );
	$games = array();
	while ( $game = mysql_fetch_assoc( $sql_result ) )
		$games[] = get_game( $season, $week, $game['winner'] );

	return $games;
}

/**
 * Get an array of weeks for a season
 * Each week is an array of Result objects
 */
function get_season( $season ) {
	$season = (int) $season;

	$sql_query = "SELECT DISTINCT week
		FROM games
		WHERE season = $season";

	$sql_result = mysql_query( $sql_query );
	$weeks = array();
	while( $week = mysql_fetch_row( $sql_result ) )
		$weeks[] = get_week( $season, $week[0] );

	return $weeks;
}


// Abstract data about particular games

function get_winner( $game ) {
	return $game->result->winner;
}

function get_loser( $game ) {
	return $game->result->loser;
}

function is_winner_away( $game ) {
	return $game->result->away;
}

function get_points( $game, $winner = true ) {
	return get_stat( $game, 'points', $winner );
}

function get_yards( $game, $winner = true ) {
	return get_stat( $game, 'yards', $winner );
}

function get_turnovers( $game, $winner = true ) {
	return get_stat( $game, 'turnovers', $winner );
}

function get_stat( $game, $stat, $winner = true ) {
	$team = ( $winner ) ? 'winner' : 'loser';

	return $game->result->$team->$stat;
}

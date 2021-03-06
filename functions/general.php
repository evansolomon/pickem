<?php

/**
 * Get a Game object for a season/week/team combination
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

	// Create Participant objects
	$winner = new Participant( $game['winner'], $game['points_winner'], $game['yards_winner'], $game['turnovers_winner'] );
	$loser  = new Participant( $game['loser'],  $game['points_loser'],  $game['yards_loser'],  $game['turnovers_loser']  );

	// Create Result object
	$result = new Result( $winner, $loser, $game['away_win'] );

	// Create new Game object
	return new Game( $season, $week, $game['day'], $result );
}

/**
 * Get an array of Game objects for a week of games
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
 * Each week is an array of Game objects
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

/**
 * Get an array of years with season data
 *
 * Returns an array of integers
 * Does not return Game objects
 */
function get_seasons() {
	$sql = "SELECT DISTINCT season FROM games";
	$result = mysql_query( $sql );

	$years = array();
	while( $year = mysql_fetch_array( $result ) )
		$years[] = $year[0];

	return $years;
}

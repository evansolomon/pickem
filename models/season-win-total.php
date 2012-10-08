<?php

function pickem_model( $season, $week, $teams ) {
	$season_wins = array();

	foreach ( $teams as $key => $team )
		$season_wins[$team] = Season_Data::get_wins( $season, $week, $team );

	$prediction = array_keys( $season_wins, max( $season_wins ) );
	if ( count( $prediction ) > 1 )
		return false;
	else
		return $prediction[0];
}
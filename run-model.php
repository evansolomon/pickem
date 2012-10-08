<?php

// Pass a model slug as $argv[1]
if ( ! isset( $argv[1] ) )
	die( 'You must pass a model as $argv[1]' );

// Load init and the model
include( __DIR__ . '/init.php' );
include( __DIR__ . "/models/{$argv[1]}.php" );

// Make sure the model defines the 'callback'
if ( ! function_exists( 'pickem_model' ) )
	die( 'Model file must define pickem_model()' );

// Just a place to save results
$results = array(
	'correct'   => 0,
	'incorrect' => 0,
);

// All years
foreach( get_seasons() as $season ) {
	echo "$season\n";

	// Individual season
	foreach ( get_season( $season ) as $week ) {

		// Week of games
		foreach ( $week as $game ) {

			// Team names
			$teams = array(
				Game_Data::get_winner( $game )->team,
				Game_Data::get_loser( $game )->team,
			);

			// Pass the previous week's value so the prediction doesn't try to use the current week's game
			$prediction = pickem_model( $game->season, $game->week - 1, $teams );

			// If no prediciton was made, bail
			if ( ! $prediction )
				continue;

			// Success?
			if ( Game_Data::get_winner( $game ) == $prediction )
				$results['correct']++;
			else
				$results['incorrect']++;
		}

	}

}

var_dump( $results );

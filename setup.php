<?php

/**
 * Setup database if needed
 */

// Check for database config
if ( ! file_exists( __DIR__ . '/db.php' ) )
	die( 'Needs database config' );

// Load database config
include( __DIR__ . '/db.php' );

// Create the database if it doesn't exist yet
function create_database() {
	$schema = '
	CREATE TABLE IF NOT EXISTS `games` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `week` int(11) NOT NULL,
	  `day` varchar(3) NOT NULL,
	  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  `season` int(11) NOT NULL,
	  `winner` varchar(255) NOT NULL,
	  `loser` varchar(255) NOT NULL,
	  `away_win` int(11) NOT NULL,
	  `points_winner` int(11) NOT NULL,
	  `points_loser` int(11) NOT NULL,
	  `yards_winner` int(11) NOT NULL,
	  `yards_loser` int(11) NOT NULL,
	  `turnovers_winner` int(11) NOT NULL,
	  `turnovers_loser` int(11) NOT NULL,
	  PRIMARY KEY (`id`),
	  UNIQUE KEY `timestamp_winner` (`timestamp`,`winner`)
	)';

	return mysql_query( $schema );
}

// Try to actually create the database
if ( ! create_database() )
	die( 'Database creation failed' );
else
	echo "Database created (or existed already)\n";

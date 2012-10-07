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


// Import historical data
function backfill_games() {
	for ( $season = 1970; $season <= date( 'Y' ); $season++ ) {
		echo "$season\n";
		$data = file_get_contents( "http://www.pro-football-reference.com/years/{$season}/games.htm" );

		// Do some ghetto HTML parsing
		$dom = new DomDocument();
		$dom->loadHTML( $data );
		$dom->preserveWhitespace = false;

		$tables = $dom->getElementsByTagName( 'table' );
		$rows   = $tables->item( 0 )->getElementsByTagName( 'tr' );

		foreach ( $rows as $row ) {
			$cols = $row->getElementsByTagName( 'td' );
			if ( is_null( $cols->item( 0 ) ) )
				continue;

			$week = $cols->item( 0 )->nodeValue;

			// Turn playoff week names into consistent integers
			if ( ! $week )
				continue;
			elseif ( 'WildCard' == $week )
				$week = 18;
			elseif ( 'Division' == $week )
				$week = 19;
			elseif ( 'ConfChamp' == $week )
				$week = 20;
			elseif ( 'SuperBowl' == $week )
				$week = 21;

			$day = $cols->item( 1 )->nodeValue;

			// Maintain seasons across calendar years
			if ( date( "n", strtotime( $cols->item( 2 )->nodeValue ) ) < 5 )
				$year = $season + 1;
			else
				$year = $season;

			// Get and sanitize game data
			$timestamp        = date( "Y-m-d h:i:s", strtotime( $cols->item(2)->nodeValue . ", " . $year ) );
			$winner           = mysql_real_escape_string( $cols->item(4)->nodeValue );
			$loser            = mysql_real_escape_string( $cols->item(6)->nodeValue );
			$points_winner    = (int) $cols->item(7)->nodeValue;
			$points_loser     = (int) $cols->item(8)->nodeValue;
			$yards_winner     = (int) $cols->item(9)->nodeValue;
			$yards_loser      = (int) $cols->item(11)->nodeValue;
			$turnovers_winner = (int) $cols->item(10)->nodeValue;
			$turnovers_loser  = (int) $cols->item(12)->nodeValue;
			$away             = ( $cols->item( 5 )->nodeValue == "@" ) ? 1 : 0;

			// Save it
			$sql = "INSERT INTO games VALUES (
				null,
				'$week',
				'$day',
				'$timestamp',
				'$season',
				'$winner',
				'$loser',
				'$away',
				'$points_winner',
				'$points_loser',
				'$yards_winner',
				'$yards_loser',
				'$turnovers_winner',
				'$turnovers_loser'
			)";

			mysql_query( $sql );
		}
	}
}

// Run the importer
backfill_games();

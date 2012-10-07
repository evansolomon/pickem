<?php

// Connect to MySQL, but only once
call_user_func( function() {
	static $connected;

	if ( ! $connected ) {
		if ( ! mysql_connect( 'localhost', 'root', 'root' ) )
			die( 'Could not connect to MySQL' );

		if ( ! mysql_select_db( 'pickem' ) )
			die( 'Could not select MySQL table' );

		$connected = true;
	}
} );
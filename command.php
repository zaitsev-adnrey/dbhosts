<?php

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

/**
 * Says "Hello World" to new users
 *
 * @when before_wp_load
 */
$dbhosts_command = function($args) {
					$catalog = $args[0];
	                $find_options = array(
                      'return'     => true,   // Return 'STDOUT'; use 'all' for full object.
                      'parse'      => 'json', // Parse captured STDOUT to JSON array.
                      'launch'     => false,  // Reuse the current process.
                      'exit_error' => true,   // Halt script execution on error.
                    );
	$paths = WP_CLI::runcommand('find '. $catalog .'  --field=wp_path --format=json', $find_options);
	$options = array('return'     => true,'exit_error' => false, );
	foreach ($paths as $key => $path) {
			//$multisite = WP_CLI::runcommand('config get MULTISITE --path=' . $path .' ',$options);
			$hostdb = WP_CLI::runcommand('config get DB_HOST --path=' . $path .' ',$options);
			$namedb = WP_CLI::runcommand('config get DB_NAME --path=' . $path .' ',$options);
			$sitelist = WP_CLI::runcommand('site list --field=url --path=' . $path .' ',$options);
			if(empty($sitelist)){
					$list = "";
				}
				else{
						$list = PHP_EOL . WP_CLI::colorize("%W%9$sitelist%n");
					}
			

			//WP_CLI::runcommand('config get DB_NAME --path=' . $path .' ');
			WP_CLI::line(
				WP_CLI::colorize("%c$hostdb%n") . ' ' . 
				WP_CLI::colorize("%y$namedb%n") .' '. 
				WP_CLI::colorize("%p$path%n"). 
				$list );
	}

};
WP_CLI::add_command( 'dbhosts', $dbhosts_command  );

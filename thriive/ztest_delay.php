<?php require '/var/www/html/wp-config.php';


/*$read = "SELECT data FROM zodiac_data ORDER BY SID DESC LIMIT 1";
	$read_query = $wpdb->get_results($read, ARRAY_A);
	$json_contents = str_replace('#', "'", $read_query[0]['data']);
	$json_horo = json_decode($json_contents, TRUE);
	echo ($json_contents);
*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo get_field('availability', 15680);




?>
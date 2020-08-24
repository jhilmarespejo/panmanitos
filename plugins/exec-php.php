<?php
/*
Plugin Name: Exec-PHP
Description: Evaluates PHP code inside of page contents
Version: 1.1
Author: Chris Cagle
Author URI: http://www.cagintranet.com/
*/

# get correct id for plugin
$thisfile=basename(__FILE__, ".php");

# register plugin
register_plugin(
	$thisfile, 													# ID of plugin, should be filename minus php
	'Exec-PHP', 									# Title of plugin
	'1.1', 															# Version of plugin
	'Chris Cagle',											# Author of plugin
	'http://www.cagintranet.com/', 			# Author URL
	'Evaluates PHP code inside of page contents', 	# Plugin Description
	'', 													# Page type of plugin
	''  										# Function that displays content
);

# activate filter
add_filter('content','eval_php_in_content'); 

# functions
function eval_php_in_content($c) {
	$c = eval("?>" . stripslashes(htmlspecialchars_decode($c, ENT_QUOTES)) . "<?php ");
	return $c;
}
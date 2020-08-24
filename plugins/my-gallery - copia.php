<?php

if(!defined('IN_GS')){ die('You cannot load this page directly.'); }

Error_Reporting(E_ALL & ~E_NOTICE);

/*
Plugin Name: My Gallery (my-gallery.php)
Description: Gallery plugin with the ability to upload images through the admin panel
Version: 1.0.3 / may 2020
Author: NetExplorer
Email: netexplorer@yandex.ru
Author URI: http://netexplorer.h1n.ru

This plugin will help you create a gallery.
How to install a gallery?

Unzip and place file in the plugin folder of GetSimple. Then activate the plugin My Gallery (my-gallery.php).

Create a gallery, select it and upload files. You can create multiple galleries. 
For output, use a short-code or PHP-code that is automatically generated for each new gallery.

The plugin can be translated into other languages. See /lang/en.php, ru.php ... 
Make your translation and upload the "xx.php" file to the directory - /lang/ - then select your language file in the plugin settings.
When uploading large files сheck the values upload_max_filesize and post_max_size in php.ini.

Also see LICENSE.txt, README.txt
*/

// Get correct id for plugin
$my_gallery_thisfile = basename(__FILE__, '.php');

// Load configuration
require(GSROOTPATH.'plugins/'.$my_gallery_thisfile.'/cfg.php');

// Load language
require(GSROOTPATH.'plugins/'.$my_gallery_thisfile.'/lang/'.$my_gallery_language.'.php');

// Register plugin
register_plugin(
    $my_gallery_thisfile,    // ID of plugin, should be filename minus php
    $my_gallery_plugin_name,    // Title of plugin
    '1.0.3',    // Version of plugin
    'NetExplorer',    // Author of plugin
    'http://netexplorer.h1n.ru',    // Author URL
    $my_gallery_small_description,    // Plugin Description
    'plugins',    // Page type of plugin
    'my_gallery_options'    // Function that displays content
);

register_style('my_gallery_style_baguettebox', $SITEURL.'plugins/'.$my_gallery_thisfile.'/baguettebox/baguettebox.css', false, 'all');
queue_style('my_gallery_style_baguettebox', GSFRONT);

register_script('my_gallery_js_baguettebox', $SITEURL.'plugins/'.$my_gallery_thisfile.'/baguettebox/baguettebox.js', false, false);
queue_script('my_gallery_js_baguettebox', GSFRONT);

// Creates a menu option on the Admin/Theme sidebar
add_action('plugins-sidebar', 'createSideMenu', array($my_gallery_thisfile, $my_gallery_plugin_name));

// activate filter
add_filter('content', 'ShortCodeGetMyGallery');

// Show options in plugin page
function my_gallery_options() {
	global $my_gallery_thisfile, $my_gallery_language;
	
	// Load language
	require(GSROOTPATH.'plugins/'.$my_gallery_thisfile.'/lang/'.$my_gallery_language.'.php');
	
	// Please do not remove the donation links. If you want remove - please donate $2 for every domain.
	echo $my_gallery_description . '
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="display: inline;">
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="business" value="netexplorer@yandex.ru">
<input type="hidden" name="currency_code" value="USD">
<input type="submit" border="0" name="submit" class="button" value="'.$my_gallery_admin_donate.'" title="Donate with PayPal button" alt="Donate with PayPal button">
</form> &nbsp; <a href="https://money.yandex.ru/to/410012986152433" target="_blank">Yandex.Money</a>
';

	// Load plugin settings
	require(GSROOTPATH.'plugins/'.$my_gallery_thisfile.'/admin.php');
}



//*** Get My Gallery ***//
function GetMyGallery($my_gallery_dir = 'no-galleries') {
   global $my_gallery_thisfile, $my_gallery_msg_no_files, $SITEURL; 
   require(GSROOTPATH.'plugins/'.$my_gallery_thisfile.'/cfg.php');
   
$return = '';
$return .= '
<span class="baguetteBoxOne baguetteBox_gallery baguetteBox_'.$my_gallery_thisfile.'_'.$my_gallery_dir.'">';
        if (file_exists(GSROOTPATH.'plugins/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_dir.'/cache_gallery.dat')) {
			$arr = file(GSROOTPATH.'plugins/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_dir.'/cache_gallery.dat', FILE_IGNORE_NEW_LINES);
			$nom = count($arr);
			if ($nom) {
				for($i = 0; $i < $nom; ++$i) {				
					$title = file_get_contents(GSROOTPATH.'plugins/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_dir.'/title_'.$arr[$i].'_dat');
					$return .= '<a href="'.$SITEURL.'plugins/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_dir.'/'.$arr[$i].'" title="'.$title.'"><img src="'.$SITEURL.'plugins/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_dir.'/s_'.$arr[$i].'" alt="'.$title.'"></a>';
				}
			}
			else {
			  $return .= $my_gallery_msg_no_files;
			}
		} else {
			$return .= $my_gallery_msg_no_files;
		}	
$return .= '
</span>
<script>
	baguetteBox.run(".baguetteBox_'.$my_gallery_thisfile.'_'.$my_gallery_dir.'");
</script>';

return $return;
}

// Search and replace
function ShortCodeGetMyGallery($content) {
 preg_match_all("|\[\#(.*)\#\]|U", $content, $matches);
 //print_r($matches);
 for($i = 0, $count = count($matches[1]); $i < $count; $i++) {
  $arr_tmp = explode(':', $matches[1][$i]);
  $my_gallery_dir = $arr_tmp[1]; 
  if ($matches[1][$i] == "GetMyGallery:$my_gallery_dir") {
	 $content = str_ireplace("[#GetMyGallery:$my_gallery_dir#]", GetMyGallery($my_gallery_dir), $content);
  }
 }

return $content;
}

?>
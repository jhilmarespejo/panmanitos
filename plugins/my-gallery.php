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
	echo $my_gallery_description;

	// Load plugin settings
	require(GSROOTPATH.'plugins/'.$my_gallery_thisfile.'/admin.php');
}



//*** Get My Gallery ***//
function GetMyGallery($my_gallery_dir = 'no-galleries') {
   global $my_gallery_thisfile, $my_gallery_msg_no_files, $SITEURL; 
   require(GSROOTPATH.'plugins/'.$my_gallery_thisfile.'/cfg.php');
   
$return = '';
$return .= '
<div id="imageGalleryCarousel" data-ride="gallery-carousel" class="card carousel allery-carousel slide carousel-fade baguetteBoxOne baguetteBox_gallery baguetteBox_'.$my_gallery_thisfile.'_'.$my_gallery_dir.'"><div class="carousel-inner">';
        if (file_exists(GSROOTPATH.'plugins/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_dir.'/cache_gallery.dat')) {
			$arr = file(GSROOTPATH.'plugins/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_dir.'/cache_gallery.dat', FILE_IGNORE_NEW_LINES);
			$nom = count($arr);
			if ($nom) {
				for($i = 0; $i < $nom; ++$i) {	
				if($i == 0) {
					$title = file_get_contents(GSROOTPATH.'plugins/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_dir.'/title_'.$arr[$i].'_dat');
					$return .= '
					<div class="card-body d-flex justify-content-center carousel-item active">
						<a class="img" href="'.$SITEURL.'plugins/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_dir.'/'.$arr[$i].'" title="'.$title.'">
							<img class="img-fluid" src="'.$SITEURL.'plugins/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_dir.'/s_'.$arr[$i].'" alt="'.$title.'">
						</a>
					</div>';

				}	else{
					$title = file_get_contents(GSROOTPATH.'plugins/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_dir.'/title_'.$arr[$i].'_dat');
					$return .= '
					<div class="card-body d-flex justify-content-center carousel-item">
						<a class="img" href="'.$SITEURL.'plugins/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_dir.'/'.$arr[$i].'" title="'.$title.'">
							<img class="img-fluid" src="'.$SITEURL.'plugins/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_dir.'/s_'.$arr[$i].'" alt="'.$title.'">
						</a>
					</div>';

				}		
					
				}
			}
			else {
			  $return .= $my_gallery_msg_no_files;
			}
		} else {
			$return .= $my_gallery_msg_no_files;
		}	
$return .= '
	</div>
	  <a class="carousel-control-prev" href="#imageGalleryCarousel" role="button" data-slide="prev">
	    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
	    <span class="sr-only">Previous</span>
	  </a>
	  <a class="carousel-control-next" href="#imageGalleryCarousel" role="button" data-slide="next">
	    <span class="carousel-control-next-icon" aria-hidden="true"></span>
	    <span class="sr-only">Next</span>
	  </a>
</div>
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
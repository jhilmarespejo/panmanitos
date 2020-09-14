<?php


/****************************************************
*
* @File:  groupdocs_viewer.php
* @Action:  Provides groupdocs viewer shortcode  
*
*****************************************************/
$viewer_tags   = array();  // Shortcode tags array
$viewer_info   = array();  // Shortcode tags info array

# get correct id for plugin
$thisfile=basename(__FILE__, ".php");

# register plugin
register_plugin(
	$thisfile, 				# ID of plugin, should be filename minus php
	'GroupDocs Documents Viewer',			 	# Title of plugin
	'1.0', 				# Version of plugin
	'GroupDocs Marketplace Team',				# Author of plugin
	'http://www.groupdocs.com/', 		# Author URL
	'GroupDocs Documents Viewer plugin to embed GroupDocs Viewer', 			# Plugin Description
	'plugins' 				# Page type of plugin
);

# activate hooks
//add_action('plugins-sidebar','createSideMenu',array($thisfile,'Shortcode Info')); 
if (get_filename_id()=="edit"){
	add_action('edit-extras','insertViewerButton',array()); 
}

function insertViewerButton(){
	echo "<script type=\"text/javascript\">";
	echo "jQuery(document).ready(function() { ";
	
    echo "$(\"<input class=submit id=gdviewer type=button value='GroupDocs Viewer' />\").insertAfter($('#metadata_window'));";
	?>
	
	$("#gdviewer").on("click", function(){
		var selectedText = CKEDITOR.instances["post-content"].getSelection().getSelectedText();
		var tag= "[GroupDocsViewer id='guid' width='500' height='600' /]";
		if (selectedText!='') {
			tag = tag.replace('guid',selectedText);
		}
		CKEDITOR.instances["post-content"].insertText(tag);
	})
	
	<?php 
	echo " })";
	echo "</script>";

}


/* 
 * @uses $viewer_tags,$viewer_info
 *
 * @param string $tag Shortcode tag to be searched in post content.
 * @param callable $func Hook to run when shortcode is found.
 * @param string $desc , the desription of the shortcode
 */
function gdv_shortcode($tag, $func, $desc="Default Description") {
        global $viewer_tags;
	global $viewer_info; 
        if ( is_callable($func) )
                $viewer_tags[(string)$tag] = $func;
		$viewer_info[(string)$tag] = $desc;
}


/**
 * Search content for groupdocs shortcode and alter shortcodes through it's hook.
 *
 * If there are no shortcode tags defined, then the content will be returned
 * without any filtering. 
 * 
 * @uses $viewer_tags
 * @uses gdv_get_shortcode_regex() Gets the search pattern for searching shortcodes.
 *
 * @param string $content Content to search for shortcodes
 * @return string Content with shortcodes filtered out.
 */
function gdv_do_shortcode($content) {
        global $viewer_tags;

        if (empty($viewer_tags) || !is_array($viewer_tags))
                return $content;

        $tagnames = array_keys($viewer_tags);
        $tagregexp = join( '|', array_map('preg_quote', $tagnames) );
        
		$removeTagsPattern = '/(\s*)(<p>\s*)(\[('.$tagregexp.')\b(.*?)(?:(\/))?\])(?:(.+?)\[\/\2\])?(.?)(\s*<\/p>)/';

		$content2 = preg_replace($removeTagsPattern, '$3 ', $content) ;
		
        $pattern = gdv_get_shortcode_regex();
        return preg_replace_callback('/'.$pattern.'/s', 'gdv_do_shortcode_tag', htmlspecialchars_decode($content2));
}

/**
 * Retrieve the shortcode regular expression for searching.
 *
 * The regular expression combines the shortcode tags in the regular expression
 * in a regex class.
 *
 * The regular expresion contains 6 different sub matches to help with parsing.
 *
 * 1/6 - An extra [ or ] to allow for escaping shortcodes with double [[]]
 * 2 - The shortcode name
 * 3 - The shortcode argument list
 * 4 - The self closing /
 * 5 - The content of a shortcode when it wraps some content.
 *
 * @uses $viewer_tags
 *
 * @return string The shortcode search regular expression
 */
function gdv_get_shortcode_regex() {
        global $viewer_tags;
        $tagnames = array_keys($viewer_tags);
        $tagregexp = join( '|', array_map('preg_quote', $tagnames) );

        // WARNING! Do not change this regex without changing gdv_do_shortcode_tag() and gdv_strip_shortcodes()
        return '(.?)\[('.$tagregexp.')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)';
}

/**
 * Regular Expression callable for gdv_do_shortcode() for calling shortcode hook.
 * @see gdv_get_shortcode_regex for details of the match array contents.
 *
 * @access private
 * @uses $viewer_tags
 *
 * @param array $m Regular expression match array
 * @return mixed False on failure.
 */
function gdv_do_shortcode_tag( $m ) {
        global $viewer_tags;

        // allow [[foo]] syntax for escaping a tag
        if ( $m[1] == '[' && $m[6] == ']' ) {
                return substr($m[0], 1, -1);
        }

        $tag = $m[2];
        $attr = gdv_shortcode_parse_atts( $m[3] );

        if ( isset( $m[5] ) ) {
                // enclosing tag - extra parameter
                return $m[1] . call_user_func( $viewer_tags[$tag], $attr, $m[5], $tag ) . $m[6];
        } else {
                // self-closing tag
                return $m[1] . call_user_func( $viewer_tags[$tag], $attr, NULL,  $tag ) . $m[6];
        }
}

/**
 * Retrieve all attributes from the shortcodes tag.
 *
 * The attributes list has the attribute name as the key and the value of the
 * attribute as the value in the key/value pair. This allows for easier
 * retrieval of the attributes, since all attributes have to be known.
 *
 *
 * @param string $text
 * @return array List of attributes and their value.
 */
function gdv_shortcode_parse_atts($text) {
        $atts = array();
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
        if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
                foreach ($match as $m) {
                        if (!empty($m[1]))
                                $atts[strtolower($m[1])] = stripcslashes($m[2]);
                        elseif (!empty($m[3]))
                                $atts[strtolower($m[3])] = stripcslashes($m[4]);
                        elseif (!empty($m[5]))
                                $atts[strtolower($m[5])] = stripcslashes($m[6]);
                        elseif (isset($m[7]) and strlen($m[7]))
                                $atts[] = stripcslashes($m[7]);
                        elseif (isset($m[8]))
                                $atts[] = stripcslashes($m[8]);
                }
        } else {
                $atts = ltrim($text);
        }
        return $atts;
}

/**
 * Combine user attributes with known attributes and fill in defaults when needed.
 *
 * The pairs should be considered to be all of the attributes which are
 * supported by the caller and given as a list. The returned attributes will
 * only contain the attributes in the $pairs list.
 *
 * If the $atts list has unsupported attributes, then they will be ignored and
 * removed from the final returned list.
 *
 *
 * @param array $pairs Entire list of supported attributes and their defaults.
 * @param array $atts User defined attributes in shortcode tag.
 * @return array Combined and filtered attribute list.
 */
function gdv_shortcode_atts($pairs, $atts) {
        $atts = (array)$atts;
        $out = array();
        foreach($pairs as $name => $default) {
                if ( array_key_exists($name, $atts) )
                        $out[$name] = $atts[$name];
                else
                        $out[$name] = $default;
        }
        return $out;
}

/**
 * Remove all shortcode tags from the given content.
 *
 * @uses $viewer_tags
 *
 * @param string $content Content to remove shortcode tags.
 * @return string Content without shortcode tags.
 */
function gdv_strip_shortcodes( $content ) {
        global $viewer_tags;

        if (empty($viewer_tags) || !is_array($viewer_tags))
                return $content;

        $pattern = gdv_get_shortcode_regex();

        return preg_replace('/'.$pattern.'/s', '$1$6', $content);
}



function gdv_output_shortcode($data){
	$ret=gdv_do_shortcode($data);
	echo $ret;
}

include GSPLUGINPATH.'groupdocs_viewer/shortcode.php';

if (file_exists(GSTHEMESPATH .$TEMPLATE.'/shortcode.php')){
	include GSTHEMESPATH.$TEMPLATE.'/shortcode.php';
	
}

add_filter('content','gdv_do_shortcode');
?>

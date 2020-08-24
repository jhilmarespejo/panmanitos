<?php
/*
Plugin Name: Microdata Breadcrumbs
Description: Provides breadcrumbs with microdata for your site navigation and SEO
Version: 1.0
Author: Renaud Mariage-Gaudron
Author URI: http://www.responsive-mind.fr
*/

// Global plugin settings
$thisfile = basename(__FILE__, ".php");
$breadcrumbs_folder_name = 'microdata_breadcrumbs';
$breadcrumbs_folder_name_folder_path = GSPLUGINPATH.$breadcrumbs_folder_name.'/';
$microdata_breadcrumbs_settings_file = $breadcrumbs_folder_name_folder_path.'settings.xml';

// Plugin registration
register_plugin(
	$thisfile, 
	'Microdata Breadcrumbs', 	
	'1.0',
	'Renaud Mariage-Gaudron',
	'http://www.responsive-mind.fr/', 
	'Provides breadcrumbs with microdata for your site navigation and SEO',
	'plugins',
        'microdata_breadcrumbs_setup'
	
);

// Admin menu creation
add_action('plugins-sidebar', 'createSideMenu', array($thisfile, 'Microdata Breadcrumbs'));


/**
 * Plugin settings administration form
 */
function microdata_breadcrumbs_setup()
{
    global $microdata_breadcrumbs_settings_file;
 
    if($_POST && $_POST['separator']){
        $xml = @new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><item></item>');
        $note = $xml->addChild('separator');
        $note->addCData($_POST['separator']);
        $xml->asXML($microdata_breadcrumbs_settings_file);
	 microdata_breadcrumbs_alert('Microdata Breadcrumbs settings Saved Successfully!');
    }
    elseif($_POST && !$_POST['separator'])
    {
        microdata_breadcrumbs_alert('<strong>Error:</strong> You cannot save an empty separator.',true);
    }
    
    $setting_value = get_microdata_breadcrumbs_settings();
   
    ?>
    <label>Microdata Breadcrumbs settings</label> <br/><br/>
    <p>Choose which separator you want between your breadcrumbs</p>
    <form method="post" action="<?php echo $_SERVER ['REQUEST_URI']?>">
            <table class="highlight">
                <tr>
                    <td>Separator</td>
                    <td><input type="text" name="separator" value="<?=$setting_value['separator']?>" /> </td>
                </tr>
            </table>
            <input type="submit" name="submit" class="submit" value="Save Settings" />
    </form>
    <?php
}



/**
 * Returns an array with the plugin settings : only the separator in this version
 *
 * @return array
 */
function get_microdata_breadcrumbs_settings() {
    global $microdata_breadcrumbs_settings_file;
    $_settings = array();
    if (file_exists($microdata_breadcrumbs_settings_file)) {;
        $_xml = getXML($microdata_breadcrumbs_settings_file);
        $_settings['separator'] = $_xml ->separator;
    }
    return $_settings;
}


/**
 *  Generates a message div
 */
if (!function_exists('microdata_breadcrumbs_alert'))
{
	function microdata_breadcrumbs_alert($msg, $error = false)
	{
          	if ($error == true)
		{
              	echo '<div class="error">'.$msg.'</div>';
		}
	       else
		{
			echo '<div class="updated">'.$msg.'</div>';
		}
	}
}


/**
 * Displays the breacrumbs with their microdatas
 */
function get_microdata_breadcrumbs()
{
    $_settings = get_microdata_breadcrumbs_settings();
    $_separator = $_settings['separator'];

    $_slugs = array();
    $i = 1;
    $_lastSlug = return_page_slug();
    $_slugs[0] = array('slug' => $_lastSlug, 'title' => returnPageField($_lastSlug, 'title'), 'url' => returnPageField($_lastSlug, 'url')); ;
    $_parent = trim(returnPageField($_lastSlug, 'parent'));
    while($_parent != '') 
    {
        $_lastSlug = $_parent;
        $_slugs[$i] = array('slug' => $_parent, 'title' => returnPageField($_parent, 'title'), 'url' => returnPageField($_parent, 'url'));
        $i++;
        if ($_parent != '')
        {
            try 
            {
                $_parent = returnPageField($_parent, 'parent');
            }
            catch (Exception $e)
            {
                $_parent = '';
            }
        }
    } 
    ?>

<nav aria-label="breadcrumb" class="xbreadcrumb">
    <!-- <h3><?php get_page_title(); ?></h3><br> -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item" >
            <a href="<?php get_site_url(); ?>" itemprop="url">
                <span itemprop="title"><?php get_site_name(); ?></span>
            </a>
        </li>
        <!-- <li class="breadcrumb-item"><?php echo $_separator; ?></li> -->
        <?php 
        for ($j = $i-1 ; $j > 0 ; $j--) { ?>
            <li class="breadcrumb-item" >
                <a href="<?php echo $_slugs[$j]['url']; ?>" itemprop="url">
                    <span itemprop="title"><?php echo $_slugs[$j]['title']; ?></span>
                </a>
            </li>
            <li class="breadcrumb-item"><?php echo $_separator; ?> </li>
        <?php } ?>
        <li class="breadcrumb-item" >
            <span itemprop="title"><?php echo $_slugs[0]['title']; ?></span>
        </li>    
    </ol>
</nav>
<?php } ?>

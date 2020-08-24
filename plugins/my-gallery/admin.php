<?php
 
if(!defined('IN_GS')){ die('You cannot load this page directly.'); }

// 
// Plugin settings
//

// dirname with levels as in php7 for php5
function my_gallery_Dirname($path, $levels = 1) 
 {
	$arr = explode(DIRECTORY_SEPARATOR, dirname($path));
	array_splice($arr, count($arr) + 1 - $levels);
	return implode(DIRECTORY_SEPARATOR, $arr);
 }
	$MGDIR = my_gallery_Dirname(__FILE__, 2); // Similar to dirname in php7 (can try constant GSROOTPATH)
 
$thisfilew = GSDATAOTHERPATH .'website.xml';
if (file_exists($thisfilew)) 
 {
	$dataw = getXML($thisfilew);
	$MGURL = $dataw->SITEURL;
	//$MGURL = rtrim($dataw->SITEURL, '/') . '/';
 } else {
	$MGURL = '/';
 } 

require($MGDIR.'/'.$my_gallery_thisfile.'/cfg.php');

$my_galleries_arr = file($MGDIR.'/'.$my_gallery_thisfile.'/galleries/ids-names.dat');
$my_galleries_ids = array();
$my_galleries_names = array();

if (!file_exists($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir)) { $my_gallery_selected_dir = 'no-galleries'; $my_gallery_selected_name = $my_gallery_admin_no_аctive_gallery; $my_galleries_arr[] = 'no-galleries|'.$my_gallery_admin_no_аctive_gallery.''; }

for ($i = 0, $mg_count_arr = count($my_galleries_arr); $i < $mg_count_arr; $i++) {
	$my_galleries_tmp = explode('|', $my_galleries_arr[$i]);	 
	$my_galleries_ids[] = trim($my_galleries_tmp[0]);
	$my_galleries_names[] = trim($my_galleries_tmp[1]);
}

if (@$_POST['act']) { $act = @$_POST['act']; } else { $act = @$_GET['act']; }

if ($act) { echo '<div class="updated" style="display: block; margin-top: 20px;"><p>'.$my_gallery_admin_updating_settings.'</p></div>'; }

// Directory deletion function
function my_gallery_rmRec($path) {
  if (is_file($path)) return unlink($path);
  if (is_dir($path)) {
    foreach(scandir($path) as $p) if (($p!='.') && ($p!='..'))
      my_gallery_rmRec($path.DIRECTORY_SEPARATOR.$p);
    return rmdir($path); 
    }
  return false;
}

function my_gallery_Resize($file_input, $file_output, $w_o, $h_o, $percent = false, $quality) // Function to resize the image
 { 
	list($w_i, $h_i, $type) = getimagesize($file_input); 
	if (!$w_i || !$h_i) 
	{ 
	$url_name = explode("/", $file_input); echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.end($url_name).' '.$my_gallery_admin_error11.'</p></div>\'; } </script>';
	return;
	} 
	$types = array('','gif','jpeg','png','jpg','GIF','JPEG','PNG','JPG'); 
	$ext = $types[$type]; 
	if ($ext) 
	{ 
	$func = 'imagecreatefrom'.$ext; 
	$img = $func($file_input); 
	} 
	else 
	{ 
	$url_name = explode("/", $file_input); echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.end($url_name).' '.$my_gallery_admin_error12.'</p></div>\'; } </script>';
	return;
	} 
	if ($percent) 
	{ 
	$w_o *= $w_i / 100; 
	$h_o *= $h_i / 100; 
	} 
	if (!$h_o) $h_o = $w_o/($w_i/$h_i); 
	if (!$w_o) $w_o = $h_o/($h_i/$w_i); 
	$img_o = imagecreatetruecolor($w_o, $h_o); 
	imagecopyresampled($img_o, $img, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i); 
	if ($type == 2) 
	{ 
	return imagejpeg($img_o,$file_output, $quality); 
	} 
	else 
	{ 
	$func = 'image'.$ext; 
	return $func($img_o,$file_output); 
	}
 }
 
function my_gallery_reArrayFiles($file) // Function for multi-loading
 {
       $file_ary = array();
       $file_count = count($file['name']);
       $file_key = array_keys($file);
    
      for($i=0;$i<$file_count;$i++)
       {
          foreach($file_key as $val)
          {
              $file_ary[$i][$val] = $file[$val][$i];
          }
       }
       return $file_ary;
 }	

function my_gallery_Array_move(&$a, $oldpos, $newpos) // Move function
 { 
	if ($oldpos==$newpos) {return;} 
	array_splice($a,max($newpos,0),0,array_splice($a,max($oldpos,0),1)); 
 }
 
function my_gallery_ClearDir($dir) // Delete files in the directory function
 { 
    if (file_exists($dir))
     foreach (glob($dir.'/*') as $file)
      if ($file != '.htaccess') unlink($file);
 }
  
echo '
<script>
function my_gallery_CSSLoad(file){
	var link = document.createElement("link");
	link.setAttribute("rel", "stylesheet");
	link.setAttribute("type", "text/css");
	link.setAttribute("href", file);
	document.getElementsByTagName("head")[0].appendChild(link);
}

function my_gallery_JSLoad(file){
	var link = document.createElement("script");
	link.setAttribute("type", "text/javascript");
	link.setAttribute("src", file);
	document.getElementsByTagName("head")[0].appendChild(link);
}

function my_gallery_toAnchor(anchor){
    window.location.hash = "#" + anchor;
}
</script>';


print <<< my_gallery_JS_code
<script>
var my_gallery_thisfile = "$my_gallery_thisfile";
var MGURL = "$MGURL";

var my_gallery_New = '<form name="my_gallery_New" action="load.php?id='+my_gallery_thisfile+'" method="post" enctype="multipart/form-data">'+
    '<input type="hidden" name="act" value="my_gallery_New">'+
	'<div class="a">$my_gallery_admin_name'+
	'<input class="text" style="width: 288px;" type="text" name="my_gallery_new_name" value="" required></div>'+	
	'<div class="b"><button type="submit">$my_gallery_btn_create</button>&nbsp;<button type="button" onclick="closewindow(\'window\');\">$my_gallery_btn_cancel</button>'+	
	'</div></form>';
	
var my_gallery_Delete = '<form name="my_gallery_Delete" action="load.php?id='+my_gallery_thisfile+'" method="post" enctype="multipart/form-data">'+
    '<input type="hidden" name="act" value="my_gallery_Delete">'+
	'<div class="a">$my_gallery_admin_delete_gallery_confirm</div>'+
	'<div class="b"><button type="submit">$my_gallery_btn_delete</button>&nbsp;<button type="button" onclick="closewindow(\'window\');\">$my_gallery_btn_cancel</button>'+	
	'</div></form>';
	
var my_gallery_Rename = '<form name="my_gallery_Rename" action="load.php?id='+my_gallery_thisfile+'" method="post" enctype="multipart/form-data">'+
    '<input type="hidden" name="act" value="my_gallery_Rename">'+
	'<div class="a">$my_gallery_admin_name'+
	'<input class="text" type="hidden" name="my_gallery_old_id" value="">'+
	'<input class="text" type="hidden" name="my_gallery_old_name" value="">'+
	'<input class="text" style="width: 288px;" type="text" name="my_gallery_new_name" value="" required></div>'+
	'<div class="b"><button type="submit">$my_gallery_btn_rename</button>&nbsp;<button type="button" onclick="closewindow(\'window\');\">$my_gallery_btn_cancel</button>'+
	'</div></form>';

var my_gallery_Uploadform = '<form name="my_gallery_Uploadform" action="load.php?id='+my_gallery_thisfile+'" method="post" enctype="multipart/form-data"><div class="a">'+
    '<input type="hidden" name="act" value="my_gallery_Up">'+		
	'<style>#imagePreview {padding-left:80px; width:60px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale);}</style>'+
	'<div id="imagePreview"></div><br>'+
	'<input id="imageInput" type="file" name="userfile" accept="image/*,image/jpeg,image/png,image/gif" onchange="my_gallery_LoadImageFile();" required>'+
	'<br><br>'+

	'<span>$my_gallery_admin_default_max_file_size:<td><input type="hidden" name="MAX_FILE_SIZE" value="$my_gallery_default_max_file_size"><input style="width:40px; height:20px" value="$my_gallery_default_max_file_size" min="0" type="number" name="max_f_size" required> MB</td></span>'+
	'<br><br>'+
	
	'<h3 style="color:blue;" >[PAN MANITOS Thumbnails REQUIRED Width:480px; Height: 270px;]</h3><div>$my_gallery_txt_thumbnails_size</div>'+
	'<span>$my_gallery_txt_images_width<td><input style="width:47px; height:20px;" value="$my_gallery_default_thumbnails_size" min="0" type="number" name="m_width" required>'+
	'</span><span> $my_gallery_txt_images_height<input style="width:47px; height:20px;" value="" min="0" type="number" name="m_height"></span>'+
	'<span> $my_gallery_txt_images_quality<input style="width:40px; height:20px;" value="95" min="0" maxlength="3" min="0" max="100" type="number" name="m_quality" required></span></td>'+
	'<br><br>'+
	
	'<div>$my_gallery_txt_images_size</div>'+
	'<span>$my_gallery_txt_images_width<td><input style="width:47px; height:20px;" value="$my_gallery_default_images_size" min="0" type="number" name="img_width" required>'+
	'</span><span> $my_gallery_txt_images_height<input style="width:47px; height:20px;" value="" min="0" type="number" name="img_height"></span>'+
	'<span> $my_gallery_txt_images_quality<input style="width:40px; height:20px;" value="90" min="0" maxlength="3" min="0" max="100" type="number" name="img_quality" required></span></td>'+
	'<br><br>'+
	
	'<div>$my_gallery_txt_title</div>'+
	'<textarea name="TextBox" id="inputTextToSave" style="width:288px; height:40px;"></textarea></div>'+	
	
	'<div class="b"><button type="submit">$my_gallery_btn_upload</button>&nbsp;<button type="button" onclick="window.location.href = \'load.php?id='+my_gallery_thisfile+'\';">$my_gallery_btn_cancel</button>'+	
	'</div></form>';
	
var my_gallery_Multiuploadform = '<form name="my_gallery_Multiuploadform" action="load.php?id='+my_gallery_thisfile+'" method="post" multipart="" enctype="multipart/form-data"> <div class="a">'+ 
	'<input type="hidden" name="act" value="my_gallery_Multiup">'+
	'<input accept="image/*,image/jpeg,image/png,image/gif" type="file" name="img[]" multiple required>'+
	'<br><br>'+
	
	'<span>$my_gallery_admin_default_max_file_size:<td><input type="hidden" name="MAX_FILE_SIZE" value="$my_gallery_default_max_file_size"><input style="width:40px; height:20px" value="$my_gallery_default_max_file_size" min="0" type="number" name="max_f_size" required> MB</td></span>'+
	'<br><br>'+
	
	'<h3 style="color:blue;" >[PAN MANITOS Thumbnails REQUIRED Width:480px; Height: 270px;]</h3> <div>$my_gallery_txt_thumbnails_size</div>'+
	'<span>$my_gallery_txt_images_width<td><input style="width:47px; height:20px;" value="$my_gallery_default_thumbnails_size" min="0" type="number" name="m_width" required>'+
	'</span><span> $my_gallery_txt_images_height<input style="width:47px; height:20px;" value="" min="0" type="number" name="m_height"></span>'+
	'<span> $my_gallery_txt_images_quality<input style="width:40px; height:20px;" value="95" maxlength="3" min="0" max="100" type="number" name="m_quality" required></span></td>'+
	'<br><br>'+
	
	'<div>$my_gallery_txt_images_size</div>'+
	'<span>$my_gallery_txt_images_width<td><input style="width:47px; height:20px;" value="$my_gallery_default_images_size" min="0" type="number" name="img_width" required>'+
	'</span><span> $my_gallery_txt_images_height<input style="width:47px; height:20px;" value="" min="0" type="number" name="img_height"></span>'+
	'<span> $my_gallery_txt_images_quality<input style="width:40px; height:20px;" value="90" min="0" maxlength="3" min="0" max="100" type="number" name="img_quality" required></span></td>'+
	'</div>'+  
					  
	'<div class="b"><button type="submit">$my_gallery_btn_upload</button>&nbsp;<button type="button" onclick="closewindow(\'window\');">$my_gallery_btn_cancel</button>'+	 
	'</div></form>';

var my_gallery_LoadImageFile = (function () { // Preview
    if (window.FileReader) {
        var oPreviewImg = null, oFReader = new window.FileReader(),
            rFilter = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;
        oFReader.onload = function (oFREvent) {
            if (!oPreviewImg) {
                var newPreview = document.getElementById("imagePreview");
                oPreviewImg = new Image();
                oPreviewImg.style.width = (newPreview.offsetWidth).toString() + "px";				
                //oPreviewImg.style.height = (newPreview.offsetHeight).toString() + "px";
                newPreview.appendChild(oPreviewImg);
            }
            oPreviewImg.src = oFREvent.target.result;
        };
        return function () {
            var aFiles = document.getElementById("imageInput").files;
            if (aFiles.length === 0) { return; }
            if (!rFilter.test(aFiles[0].type)) { alert("$my_gallery_admin_img_error"); return; }
            oFReader.readAsDataURL(aFiles[0]);
        }
    }
    if (navigator.appName === "Microsoft Internet Explorer") {
        return function () {
            document.getElementById("imagePreview").filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = document.getElementById("imageInput").value;
        }
    }
})();

var my_gallery_Cleangallery = '<div class="a">$my_gallery_admin_delete_all_image_confirm</div>' +
	'<div class="b">'+
	'<button type="button" onclick="window.location.href = \'load.php?id='+my_gallery_thisfile+'&amp;act=my_gallery_Clean_gallery\';">$my_gallery_btn_delete</button> '+
	'<button type="button" onclick="closewindow(\'window\');">$my_gallery_btn_cancel</button>'+
	'</div>';
	

function my_gallery_Array_move(url){
return '<div class="a">$my_gallery_txt_confirm_moving</div>' +
	   '<div class="b">' +
	   '<button type="button" onclick="window.location.href = \''+url+'\';">$my_gallery_btn_change_move</button> '+
	   '<button type="button" onclick="closewindow(\'window\');">$my_gallery_btn_cancel</button>'+
	   '</div>';
}	

function my_gallery_Is_img(file)
{
var a = file.split('.');  
var ext = a[a.length-1]; 
var r = (ext == 'gif' ||
	ext == 'GIF' ||
	ext == 'jpg' ||
	ext == 'JPG' ||
	ext == 'jpeg'||
	ext == 'JPEG'||
	ext == 'png' ||
	ext == 'PNG')? true: false;
return r;
}

function my_gallery_OnRequestStateChange()
{
if (httpRequest.readyState != 4)
  return;
if (httpRequest.status != 200)
  return;
document.getElementById("textPlace").innerHTML = httpRequest.responseText;
}

function my_gallery_Infofile(file){	
var numReserve = []
while (numReserve.length < 12) {
  var randomNumber = Math.ceil(Math.random() * 1000);
  var found = false;
  for (var i = 0; i < numReserve.length; i++) {
  if (numReserve[i] === randomNumber){ 
   found = true;
   break;
  }
  }
  if (!found) { numReserve[numReserve.length]=randomNumber; }
}
httpRequest = new XMLHttpRequest();
httpRequest.open("GET", MGURL+"plugins/"+my_gallery_thisfile+"/galleries/$my_gallery_selected_dir/title_"+file+"_dat?"+randomNumber+"", true); // Getting the file content
httpRequest.onreadystatechange = my_gallery_OnRequestStateChange;
httpRequest.send(null);
var imge = my_gallery_Is_img(file)?'<div style="border: 1px solid #e5e5e5; background-color:#fafafa; padding: 10px;text-align:center;"><img style="max-height: 100px; max-width: 120px;"  src="'+MGURL+'plugins/'+my_gallery_thisfile+'/galleries/$my_gallery_selected_dir/s_'+file+'" alt=""></div>': ''; 
return '<div class="a">'+ imge +
	'$my_gallery_txt_file_name ' +
	'<a href="'+MGURL+'plugins/'+my_gallery_thisfile+'/galleries/$my_gallery_selected_dir/'+file+'">'+file+'</a><br> '+
	'$my_gallery_txt_title <span id="textPlace"></span>' +	
	'</div>' +
	'<div class="b">'+					
	'<button type="button" onclick="openwindow(\'window\', 400, \'auto\', my_gallery_Chtitle(\''+file+'\'));">$my_gallery_btn_change_title</button> '+ // Changing the signature. Passing the file name
	'<button type="button" onclick="window.location.href = MGURL+\'plugins/'+my_gallery_thisfile+'/galleries/$my_gallery_selected_dir/'+file+'\';">$my_gallery_btn_download</button> '+
	'<button style="margin-top:4px;" type="button" onclick="openwindow(\'window\', 400, \'auto\', my_gallery_Dell(\''+file+'\'));">$my_gallery_btn_delete</button> '+
	'<button style="margin-top:4px;" type="button" onclick="closewindow(\'window\');">$my_gallery_btn_cancel</button>'+
	'</div>';	
}

function my_gallery_Chtitle(file){ // Changing the signature
closewindow('window');
var numReserve = []
while (numReserve.length < 12) {
  var randomNumber = Math.ceil(Math.random() * 1000);
  var found = false;
  for (var i = 0; i < numReserve.length; i++) {
  if (numReserve[i] === randomNumber){ 
   found = true;
   break;
  }
  }
  if (!found) { numReserve[numReserve.length]=randomNumber; }
}
httpRequest = new XMLHttpRequest();
httpRequest.open("GET", MGURL+"plugins/"+my_gallery_thisfile+"/galleries/$my_gallery_selected_dir/title_"+file+"_dat?"+randomNumber+"", true);
httpRequest.onreadystatechange = my_gallery_OnRequestStateChange;
httpRequest.send(null);
return '<form name="my_gallery_Uploadform" enctype="multipart/form-data" action="load.php?id='+my_gallery_thisfile+'&amp;act=my_gallery_Ch_title&amp;file='+file+'\" method="post">'+ // Changing the signature. The name transfer file and the content of the form
    '<div class="a">$my_gallery_txt_change_title <a href="'+MGURL+'plugins/'+my_gallery_thisfile+'/galleries/$my_gallery_selected_dir/'+file+'">'+file+'</a>' +
	'<textarea name="TextBox" id="textPlace" style="width:340px; height:40px;"></textarea></div>'+   
	'<div class="b">' +		
	'<button type="submit" style="margin-top: 10px;">$my_gallery_btn_save</button> '+
	'<button type="button" onclick="closewindow(\'window\');">$my_gallery_btn_cancel</button>'+
	'</div></form>';
}

function my_gallery_Dell(file){ // File deletion
closewindow('window');
return '<div class="a">$my_gallery_admin_delete_one_image_confirm <a href="'+MGURL+'plugins/'+my_gallery_thisfile+'/galleries/$my_gallery_selected_dir/'+file+'">'+file+'</a></div>' +
	'<div class="b">' +
	'<button type="button" onclick="window.location.href = \'load.php?id='+my_gallery_thisfile+'&amp;act=my_gallery_Dell&amp;file='+file+'\';">$my_gallery_btn_delete</button> '+
	'<button type="button" onclick="closewindow(\'window\');">$my_gallery_btn_cancel</button>'+
	'</div>';
}
</script>


my_gallery_JS_code;


echo '
<a id="my_gallery_error" href="#"></a>
<div class="error_place"></div>
<p><hr style="height: 1px; border: none; color: #dddddd; background: #dddddd; margin: 0 0 20px 0;"></p>
<h3>'.$my_gallery_admin_plugin_settings.'</h3>
<h3 style="color:blue;" >[PAN MANITOS Thumbnails REQUIRED Width:480px; Height: 270px;]</h3>

	<form class="largeform" id="my_gallery_settingsform" name="my_gallery_settingsform" action="load.php?id='.$my_gallery_thisfile.'" method="post" accept-charset="utf-8">
		
		<INPUT TYPE="hidden" NAME="act" VALUE="my_gallery_Addsettings">
		
		<div class="leftsec">';
		
			echo '
		    <p>
			<label>'.$my_gallery_admin_language_file.'</label>';
			$my_gallery_array_languages = scandir($MGDIR.'/'.$my_gallery_thisfile.'/lang/'); // scan the directory, get an array
			for ( $i = 0; $i < count($my_gallery_array_languages); $i++ )
			{
			  if ($my_gallery_array_languages[$i] == $my_gallery_language.'.php') // if matches
			  {
			    $my_gallery_tmp_language = $my_gallery_array_languages[$i]; // write to variable
                unset($my_gallery_array_languages[$i]); // remove from array
			  }
			}
			array_unshift($my_gallery_array_languages, $my_gallery_tmp_language); // add the first element to the array
			
		    echo '
		    <select class="text" name="my_gallery_language">';
            foreach ($my_gallery_array_languages as $key => $value) 
			{
			  if (strripos($my_gallery_array_languages[$key], '.php') !== false)
			  { 
                echo '<option value="'.$my_gallery_array_languages[$key].'">'.$value.'</option>'; // output (the first element)
			  }
            }
		    echo '
            </select>
			</p>';
			
			echo '
			<p id="submit_line">
			<span><input class="submit" type="submit" name="" value="'.$my_gallery_admin_submit.'"></span> &nbsp;&nbsp;'.$my_gallery_admin_or.'&nbsp;&nbsp; <a class="cancel" href="plugins.php">'.$my_gallery_admin_backward.'</a>
			</p>';
			
		echo '
		</div>';
		
		echo '
		<div class="rightsec">';
		
		echo '
		<div class="rightsec">
		<p>
		<label>'.$my_gallery_admin_default_thumbnails_size.'</label>
		<input class="text short" name="my_gallery_default_thumbnails_size" type="text" value="'.$my_gallery_default_thumbnails_size.'">
		</p>
		</div>
		
		<div class="rightsec">
		<p>
		<label>'.$my_gallery_admin_default_images_size.'</label>
		<input class="text short" name="my_gallery_default_images_size" type="text" value="'.$my_gallery_default_images_size.'">
		</p>
		</div>
		
		<div class="rightsec" style="margin-top: -18px;">
		<p>
		<label>'.$my_gallery_admin_default_max_file_size.'</label>
		<input class="text short" style="width: 79%;" name="my_gallery_default_max_file_size" type="text" value="'.$my_gallery_default_max_file_size.'"> MB
		</p>
		</div>
		';

		echo '
		</div>';
		
		echo '
		<div class="clear"></div>';
		
		echo '
		<h3>'.$my_gallery_admin_actions_galleries.'</h3>
		<div class="leftsec">
		    <p>
			<label>'.$my_gallery_admin_аctive_gallery.'</label>';
			for ( $i = 0; $i < count($my_galleries_ids); $i++ )
			{
			  if ($my_galleries_ids[$i] == $my_gallery_selected_dir) // if matches
			  {
			    $my_gallery_tmp_selected_dir = $my_galleries_ids[$i]; // write to variable
				$my_gallery_tmp_selected_name = $my_galleries_names[$i]; 
                unset($my_galleries_ids[$i]); // remove from array
				unset($my_galleries_names[$i]); 
			  }
			}
			array_unshift($my_galleries_ids, $my_gallery_tmp_selected_dir); // add the first element to the array
			array_unshift($my_galleries_names, $my_gallery_tmp_selected_name); 
			
		    echo '
		    <select class="text" name="my_gallery_selected_dir" onchange="document.querySelector(\'input[name=my_gallery_selected_name]\').value = this.options[selectedIndex].text; this.form.submit();">';
		
            for ( $i = 0; $i < count($my_galleries_ids); $i++ )
			{   
                echo '<option value="'.$my_galleries_ids[$i].'">'.$my_galleries_names[$i].'</option>'; // output (the first element)
            }
		    echo '
            </select>
			<input type="hidden" name="my_gallery_selected_name" value="'.$my_galleries_names[0].'">
			</p>
		</div>';

		echo '
		<div class="rightsec" style="padding-top: 19px;">
		<p class="edit-nav">
		<a href="javascript:void(0);" onclick="openwindow(\'window\', 360, \'auto\', my_gallery_Delete);">'.$my_gallery_btn_delete.'</a>
		<a href="javascript:void(0);" onclick="openwindow(\'window\', 360, \'auto\', my_gallery_Rename);
				document.querySelector(\'input[name=my_gallery_old_id]\').value = document.querySelector(\'select[name=my_gallery_selected_dir]\').options[0].value;
				document.querySelector(\'input[name=my_gallery_old_name]\').value = document.querySelector(\'select[name=my_gallery_selected_dir]\').options[0].text;
				document.querySelector(\'input[name=my_gallery_new_name]\').value = document.querySelector(\'select[name=my_gallery_selected_dir]\').options[0].text;
				">'.$my_gallery_btn_rename.'</a>
		<a href="javascript:void(0);" onclick="openwindow(\'window\', 360, \'auto\', my_gallery_New);">'.$my_gallery_btn_create.'</a>
		</p>
		</div>
		
		<div class="clear"></div>
		<pre id="my_gallery_short_code"><p style="margin:0;font-size:12px;color:#000;font-weight:bold;">'.($my_gallery_selected_dir != 'no-galleries' ? '<span style="font-weight:normal;">'.$my_gallery_admin_short_code.' </span>[#GetMyGallery:'.$my_gallery_selected_dir.'#]<span style="font-weight:normal;"><br>'.$my_gallery_admin_php_code.' </span>&lt;?php if (function_exists(\'GetMyGallery\')) { print GetMyGallery(\''.$my_gallery_selected_dir.'\'); } ?&gt;' : '').'</p></pre>
		<hr style="height: 1px; border: none; color: #dddddd; background: #dddddd; margin: 0 0 20px 0;">
		
		<h3 class="floated">'.$my_gallery_admin_images.'</h3>
		<p class="edit-nav">
		<a href="javascript:void(0);" id="metadata_toggle" accesskey="">'.$my_gallery_admin_properties.'</a>
		<a href="javascript:void(0);" onclick="openwindow(\'window\', 360, \'auto\', my_gallery_Cleangallery);">'.$my_gallery_admin_delete_all_image.'</a>
		<a href="javascript:void(0);" onclick="openwindow(\'window\', 360, \'auto\', my_gallery_Multiuploadform);">'.$my_gallery_admin_multiupload_image.'</a>
		<a href="javascript:void(0);" onclick="openwindow(\'window\', 360, \'auto\', my_gallery_Uploadform);">'.$my_gallery_admin_upload_image.'</a>
		</p>';
			
		echo '
		<div class="edit-nav">
			<div class="clear"></div>
		</div>	
		<span style="margin:0;font-size:12px;color:#999;">'.$my_gallery_admin_limit_uploading.' '.((toBytes(ini_get('upload_max_filesize'))/1024)/1024).' MB / '.((toBytes(ini_get('post_max_size'))/1024)/1024).' MB</span>
		<hr style="height: 1px; border: none; color: #dddddd; background: #dddddd; margin: 0 0 20px 0;">
		
			<!-- metadata toggle screen -->
			<div style="display: none; padding: 4px;" id="metadata_window">
			  <div>
				<div class="inline clearfix">';
				
				 if (file_exists($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/cache_gallery.dat')) {
				 $arr = file($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/cache_gallery.dat', FILE_IGNORE_NEW_LINES);
				 $nom = count($arr);
				  if ($nom) {
				   for($i = 0; $i < $nom; ++$i) {													
					echo '<div style="float: left; padding: 2px; margin: 2px; border: 1px solid #ddd;">
					      <p style="text-align: center;">
						   <a href="javascript:void(0);" onclick="openwindow(\'window\', 412, \'auto\', my_gallery_Infofile(\''.$arr[$i].'\'));">
						   <img style="width: 112px; padding: 2px;" src="'.$MGURL.'plugins/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/s_'.$arr[$i].'" alt=""></a> 
						   <br>
						   <small>'.$arr[$i].'</small>
						   <br>
						   <span class="edit-nav">
						   <a href="javascript:void(0);" onclick="openwindow(\'window\', 360, \'auto\', my_gallery_Array_move(\'load.php?id='.$my_gallery_thisfile.'&amp;act=my_gallery_Array_move_down&amp;catalog='.$arr[$i].'\'));" style="margin-right: 4px; padding-left: 4px; padding-right: 4px;">'.$my_gallery_btn_right.'</a>
						   <a href="javascript:void(0);" onclick="openwindow(\'window\', 412, \'auto\', my_gallery_Infofile(\''.$arr[$i].'\'));" style="padding-left: 4px; padding-right: 4px;">'.$my_gallery_btn_edit.'</a>
						   <a href="javascript:void(0);" onclick="openwindow(\'window\', 360, \'auto\', my_gallery_Array_move(\'load.php?id='.$my_gallery_thisfile.'&amp;act=my_gallery_Array_move_up&amp;catalog='.$arr[$i].'\'));" style="padding-left: 4px; padding-right: 4px;">'.$my_gallery_btn_left.'</a>
						   </span>
						   <span class="clear"></span>
						  </p>
						  </div>';
				   }
				  } else {
					echo $my_gallery_msg_no_files;
					}
				} else {
				  echo $my_gallery_msg_no_files;
				  }
				
				echo '
				</div>
			  </div>
			</div>';
		
		echo '
		<div class="clear"></div>

	</form>
';

if ($act == 'my_gallery_Addsettings') {
	$my_gallery_selected_dir = htmlspecialchars($_POST['my_gallery_selected_dir']);
	$my_gallery_selected_name = htmlspecialchars($_POST['my_gallery_selected_name']);
	if ($my_gallery_selected_dir == 'no-galleries') { $my_gallery_selected_name = ''; }
file_put_contents($MGDIR.'/'.$my_gallery_thisfile.'/cfg.php', 
'<?php
$my_gallery_language="'.str_replace('.php', '', htmlspecialchars($_POST['my_gallery_language'])).'";
$my_gallery_selected_dir="'.$my_gallery_selected_dir.'";
$my_gallery_selected_name="'.$my_gallery_selected_name.'";
$my_gallery_default_thumbnails_size="'.intval($_POST['my_gallery_default_thumbnails_size']).'";
$my_gallery_default_images_size="'.intval($_POST['my_gallery_default_images_size']).'";
$my_gallery_default_max_file_size="'.intval($_POST['my_gallery_default_max_file_size']).'";
?>'); 
require($MGDIR.'/'.$my_gallery_thisfile.'/cfg.php');
require($MGDIR.'/'.$my_gallery_thisfile.'/lang/'.$my_gallery_language.'.php');
echo '<script> if (document.querySelector(\'.main\')) { document.querySelector(\'.main\').innerHTML = \'\'; } </script> <div class="updated" style="display: block;"><p>'.$my_gallery_admin_updating_settings.'</p></div>';
?>
<script type="text/javascript">
setTimeout('window.location.href = \'load.php?id=<?php echo $my_gallery_thisfile; ?>\';', 3000);
</script>
<?php
}

if ($act == 'my_gallery_New') {
	$my_gallery_new_name = htmlspecialchars($_POST['my_gallery_new_name']);
	$my_gallery_new_id = uniqid(rand(10, 99));
	if (mkdir($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_new_id)) {
	 file_put_contents($MGDIR.'/'.$my_gallery_thisfile.'/galleries/ids-names.dat', $my_gallery_new_id . '|' . $my_gallery_new_name . "\r\n", FILE_APPEND);
	 echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML = \'<div class="updated" style="display: block;"><p>'.$my_gallery_admin_msg_created.'</p></div>\'; } </script>';
	}
	else {
	 echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML = \'<div class="error" style="display: block;"><p>'.$my_gallery_admin_error13.'</p></div>\'; } </script>';
	}
	 echo '<script> document.querySelector(\'select[name=my_gallery_selected_dir]\').options[0].text = \'\'; document.querySelector(\'#my_gallery_short_code\').style.display = \'none\'; </script>';
?>
<script type="text/javascript">
setTimeout('window.location.href = \'load.php?id=<?php echo $my_gallery_thisfile; ?>\';', 3000);
</script>
<?php
}

if ($act == 'my_gallery_Delete') {
		$my_gallery_dir_and_name = $my_gallery_selected_dir .'|'. $my_gallery_selected_name; // dir|name
		
		foreach ( $my_galleries_arr as $key => $val ) 
		 {
			if ( strpos($val, $my_gallery_dir_and_name, 0) !== false ) // look for a match
			{   
				$index = $key; 
			}
		 }

        unset($my_galleries_arr[$index]); // delete
	
		$str = '';
		foreach ( $my_galleries_arr as $key => $val )
		 {
		  $str .= $val;
		 }
		
		if ($my_gallery_selected_dir != 'no-galleries') {
		 file_put_contents($MGDIR.'/'.$my_gallery_thisfile.'/galleries/ids-names.dat', $str); // write to file
		 my_gallery_rmRec($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'');
		 echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML = \'<div class="updated" style="display: block;"><p>'.$my_gallery_admin_msg_deleted.'</p></div>\'; } </script>';
		}
		else { 
		 echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML = \'<div class="error" style="display: block;"><p>'.$my_gallery_admin_error14.'</p></div>\'; } </script>';
		}
		 echo '<script> document.querySelector(\'select[name=my_gallery_selected_dir]\').options[0].text = \'\'; document.querySelector(\'#my_gallery_short_code\').style.display = \'none\'; </script>';
?>
<script type="text/javascript">
setTimeout('window.location.href = \'load.php?id=<?php echo $my_gallery_thisfile; ?>\';', 3000);
</script>
<?php
}

if ($act == 'my_gallery_Rename') {
	$arr_file = file($MGDIR.'/'.$my_gallery_thisfile.'/galleries/ids-names.dat');
    $my_gallery_old_id = htmlspecialchars($_POST['my_gallery_old_id']);
	$my_gallery_old_name = htmlspecialchars($_POST['my_gallery_old_name']);
	$my_gallery_new_name = htmlspecialchars($_POST['my_gallery_new_name']);
	$my_gallery_dir_and_name = $my_gallery_old_id .'|'. $my_gallery_old_name; // dir|name
	
		$str = '';
		foreach($arr_file as $key => $val)
		 {
		  if ($my_gallery_dir_and_name == trim($val)) {
		   $val = str_replace($my_gallery_old_name, $my_gallery_new_name, $val);
		  }
		  $str .= $val;
		 }

	if (file_exists($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_old_id)) {
	 file_put_contents($MGDIR.'/'.$my_gallery_thisfile.'/galleries/ids-names.dat', $str); // write to file
	 echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML = \'<div class="updated" style="display: block;"><p>'.$my_gallery_admin_msg_renamed.'</p></div>\'; } </script>';
	}
	else {
	 echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML = \'<div class="error" style="display: block;"><p>'.$my_gallery_admin_error15.'</p></div>\'; } </script>';
	}
	 echo '<script> document.querySelector(\'select[name=my_gallery_selected_dir]\').options[0].text = \'\'; document.querySelector(\'#my_gallery_short_code\').style.display = \'none\'; </script>';
?>
<script type="text/javascript">
setTimeout('window.location.href = \'load.php?id=<?php echo $my_gallery_thisfile; ?>\';', 3000);
</script>
<?php
}

if ($act == 'my_gallery_Array_move_up') {
		$catalog = $_GET['catalog']; // name of the selected file
		$arr_file = file($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/cache_gallery.dat');
			
		foreach ( $arr_file as $key => $val ) 
		 {
			if ( strpos($val, $catalog, 0) !==false ) // looking for a match
			{   
				$index = $key; 
			}
		 }
	
        my_gallery_Array_move($arr_file, $index, $index - 1); // in this case, left
	
		$str = '';
		foreach($arr_file as $key => $val)
		 {
		  $str .= $val;
		 }
		
		file_put_contents($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/cache_gallery.dat', $str); // write to file
		echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML = \'<div class="updated" style="display: block;"><p>'.$my_gallery_txt_moving_left.'</p></div>\'; } </script>';
?>
<script>
setTimeout('window.location.href = \'load.php?id=<?php echo $my_gallery_thisfile; ?>\';', 3000);
</script>
<?php
	}

if ($act == 'my_gallery_Array_move_down')
	{
		$catalog = $_GET['catalog']; // name of the selected file
		$arr_file = file($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/cache_gallery.dat');
		
		foreach ( $arr_file as $key => $val ) 
		 {
			if ( strpos($val, $catalog, 0) !==false ) // looking for a match
			{   
				$index = $key; 
			}
		 }
	
        my_gallery_Array_move($arr_file, $index, $index + 1); // in this case, right
	
		$str = '';
		foreach ( $arr_file as $key => $val )
		 {
		  $str .= $val;
		 }
		
		file_put_contents($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/cache_gallery.dat', $str); // write to file
		echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML = \'<div class="updated" style="display: block;"><p>'.$my_gallery_txt_moving_right.'</p></div>\'; } </script>';
?>
<script>
setTimeout('window.location.href = \'load.php?id=<?php echo $my_gallery_thisfile; ?>\';', 3000);
</script>
<?php
	}
	
if ($act == 'my_gallery_Ch_title') // Changing the signature
	{ 
        $file = $_GET['file']; // selected file
		
		file_put_contents($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/title_'.$file.'_dat', $_POST['TextBox']);
		echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML = \'<div class="updated" style="display: block;"><p>'.$my_gallery_admin_title_images_changed_successfully.'</p></div>\'; } </script>';		  				  
?>
<script>
setTimeout('window.location.href = \'load.php?id=<?php echo $my_gallery_thisfile; ?>\';', 3000);
</script>
<?php
    }
	
if ($act == 'my_gallery_Dell'){
        $file = $_GET['file']; // selected file
		
		// removing the selected image from the list of images (removing an element from the array)
        $arr_file = file($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/cache_gallery.dat', FILE_IGNORE_NEW_LINES);
        $key = array_search($file, $arr_file);
        unset($arr_file[$key]);
   
        // writing a new list of images
        $f = fopen($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/cache_gallery.dat', 'w');
        foreach($arr_file as $k => $v){ fwrite($f, $arr_file[$k] . "\r\n"); } 
        fclose($f);
		
		unlink($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/'.$file);				
		unlink($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/s_'.$file);
		unlink($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/title_'.$file.'_dat');		  
		echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML = \'<div class="updated" style="display: block;"><p>'.$my_gallery_admin_delete_one_image.'</p></div>\'; } </script>';
		
?>
<script>
setTimeout('window.location.href = \'load.php?id=<?php echo $my_gallery_thisfile; ?>\';', 3000);
</script>
<?php
	}
	
if ($act == 'my_gallery_Clean_gallery'){ // Delete all images
		if ($my_gallery_selected_dir != 'no-galleries') {
		$dir = $MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir; 
		my_gallery_ClearDir($dir);
		echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML = \'<div class="updated" style="display: block;"><p>'.$my_gallery_admin_delete_all_images.'</p></div>\'; } </script>';
		}
		else {
		echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML = \'<div class="error" style="display: block;"><p>'.$my_gallery_admin_error16.'</p></div>\'; } </script>';
		}
?>
<script>
setTimeout('window.location.href = \'load.php?id=<?php echo $my_gallery_thisfile; ?>\';', 3000);
</script>
<?php
	}

if ($act == 'my_gallery_Up'){
		if ($my_gallery_selected_dir == 'no-galleries') {
		 echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.$my_gallery_admin_error17.'</p></div>\'; } </script>';
		 goto _end_my_gallery_Up;
		}
	    $max_f_size = ($_POST['max_f_size'] * 1048576);
		if(isset($_FILES['userfile'])) {
			$error = $_FILES['userfile']['error'];
			
			$ext = explode('.', $_FILES['userfile']['name']); $ext = strtolower(end($ext));
			$newname = uniqid(rand(10, 99)) . '.' . $ext;
			
		    $type = $_FILES['userfile']['type'];
			$size = $_FILES['userfile']['size'];
			if($error === 0) {	
			  if(($type == 'image/gif' || $type == 'image/jpeg' || $type == 'image/png') 
			  && ($size != 0 and  $size <= $max_f_size)) {  
				if (move_uploaded_file($_FILES['userfile']['tmp_name'], $MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/'.$newname)) {											
					$img_width = $_POST['img_width'];
					$m_width = $_POST['m_width'];
					$img_height = $_POST['img_height'];
					$m_height = $_POST['m_height'];					
															
					$quality = $_POST['img_quality'];
                    my_gallery_Resize($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/'.$newname.'', $MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/'.$newname.'',$img_width, $img_height,'',$quality);
					$quality = $_POST['m_quality'];
					my_gallery_Resize($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/'.$newname.'', $MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/s_'.$newname.'',$m_width, $m_height,'',$quality);
					
					file_put_contents($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/title_'.$newname.'_dat', $_POST['TextBox']);	

				    file_put_contents($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/cache_gallery.dat', $newname . "\r\n", FILE_APPEND);
					echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="updated" style="display: block; margin: 1px;"><p>'.$_FILES['userfile']['name'].' => '.$newname.' '.$my_gallery_admin_success.'</p></div>\'; } </script>';
				}
				else {
				 echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.$_FILES['userfile']['name'].' '.$my_gallery_admin_error1.'</p></div>\'; } </script>';
				}
			  }
			  else {
			   echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.$_FILES['userfile']['name'].' '.$my_gallery_admin_error2.'</p></div>\'; } </script>';
	          }	
			}
			else {
				      if ($error == 1) { 
					   echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.ini_get('upload_max_filesize').' - upload_max_filesize. '.$_FILES['userfile']['name'].' '.$my_gallery_admin_error3.'</p></div>\'; } </script>';
					  } elseif($error == 3) { 
						 echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.$_FILES['userfile']['name'].' '.$my_gallery_admin_error4.'</p></div>\'; } </script>';
						} elseif($error == 4) { 
						   echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.$_FILES['userfile']['name'].' '.$my_gallery_admin_error5.'</p></div>\'; } </script>';
						  } elseif($error == 6) { 
							 echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.$_FILES['userfile']['name'].' '.$my_gallery_admin_error6.'</p></div>\'; } </script>';
							} elseif($error == 7) { 
							   echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.$_FILES['userfile']['name'].' '.$my_gallery_admin_error7.'</p></div>\'; } </script>';
							  } else { 
							     echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.$_FILES['userfile']['name'].' '.$my_gallery_admin_error8.'</p></div>\'; } </script>';
								}
										   
			}
		}
		else {
		 echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.$_FILES['userfile']['name'].' '.$my_gallery_admin_error9.'</p></div>\'; } </script>';
		}	
_end_my_gallery_Up:
?>
<script>
setTimeout('window.location.href = \'load.php?id=<?php echo $my_gallery_thisfile; ?>\';', 3000);
</script>
<?php
	}
	
	if ($act == 'my_gallery_Multiup') { // Multiloading
		if ($my_gallery_selected_dir == 'no-galleries') {
		 echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.$my_gallery_admin_error17.'</p></div>\'; } </script>';
		 goto _end_my_gallery_Multiup;
		}
	    $i = 0; $j = 0; $k = 0; $l = 0;
		$max_f_size = ($_POST['max_f_size'] * 1048576);
		$img = $_FILES['img'];
		if(!empty($img)) {
		   $img_desc = my_gallery_reArrayFiles($img);
		   
			foreach($img_desc as $val) {	
              $type = $img_desc[$i++]['type'];
			  $size = $img_desc[$j++]['size'];
			  $name = $img_desc[$k++]['name'];
			   
			  $ext = explode('.', $name); $ext = strtolower(end($ext));
			  $newname = uniqid(rand(10, 99)) . '.' . $ext;
			  
			  $error = $img_desc[$l++]['error'];
			   
			  if ($error === 0) {
			   if(($type == 'image/gif' || $type == 'image/jpeg' || $type == 'image/png') 
			   && ($size != 0 and $size <= $max_f_size)) {  	 
			      if (move_uploaded_file($val['tmp_name'],$MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/'.$newname)) {
				   
			  	   $img_width = $_POST['img_width'];
				   $m_width = $_POST['m_width'];
				   $img_height = $_POST['img_height'];
				   $m_height = $_POST['m_height'];					
															
				   $quality = $_POST['img_quality'];
                   my_gallery_Resize($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/'.$newname,$MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/'.$newname, $img_width, $img_height,'',$quality);
				   $quality = $_POST['m_quality'];
				   my_gallery_Resize($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/'.$newname,$MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/s_'.$newname, $m_width, $m_height,'',$quality);  
				  
				   file_put_contents($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/title_'.$newname.'_dat', '');
				   
				   file_put_contents($MGDIR.'/'.$my_gallery_thisfile.'/galleries/'.$my_gallery_selected_dir.'/cache_gallery.dat', $newname . "\r\n", FILE_APPEND); 
				   
				   echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="updated" style="display: block; margin: 1px;"><p>'.$name.' => '.$newname.' '.$my_gallery_admin_success.'</p></div>\'; } </script>';
				 
				  }
				  else {				 	 
					 echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.$name.' '.$my_gallery_admin_error1.'</p></div>\'; } </script>';
				 }
                }
				else {
					  echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.$name.' '.$my_gallery_admin_error2.'</p></div>\'; } </script>';
				}
			   }
			   else {
				     if ($error == 1) { 
					  echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.ini_get('upload_max_filesize').' - upload_max_filesize. '.$name.' '.$my_gallery_admin_error3.'</p></div>\'; } </script>';
					 } elseif($error == 3) { 
						echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.$name.' '.$my_gallery_admin_error4.'</p></div>\'; } </script>';
					   } elseif($error == 4) { 
						  echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.$name.' '.$my_gallery_admin_error5.'</p></div>\'; } </script>';
						 } elseif($error == 6) { 
							echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.$name.' '.$my_gallery_admin_error6.'</p></div>\'; } </script>';
						   } elseif($error == 7) { 
							  echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.$name.' '.$my_gallery_admin_error7.'</p></div>\'; } </script>';
							 } else { 
								echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.$name.' '.$my_gallery_admin_error8.'</p></div>\'; } </script>';
							   }
			   }                      
			 } 
		 }
		 else {
		  echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="error" style="display: block; margin: 1px;"><p>'.$name.' '.$my_gallery_admin_error9.'</p></div>\'; } </script>';
		 }
_end_my_gallery_Multiup:
?>
<script>
setTimeout('window.location.href = \'load.php?id=<?php echo $my_gallery_thisfile; ?>\';', 3000);
</script>
<?php
	}

if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
	echo '<script> if (document.querySelector(\'.error_place\')) { document.querySelector(\'.error_place\').innerHTML += \'<div class="updated" style="display: block; margin-top: 20px;"><p>'.$my_gallery_admin_updating_settings.'</p></div> <div id="my_gallery_error" class="error" style="display: block; margin: 1px;"><p>'.ini_get('post_max_size').' - post_max_size. '.$my_gallery_admin_error10.'</p></div>\'; document.addEventListener("DOMContentLoaded", () => { my_gallery_toAnchor("my_gallery_error"); }); } </script>';
?>
<script>
setTimeout('window.location.href = \'load.php?id=<?php echo $my_gallery_thisfile; ?>\';', 3000);
</script>
<?php
	}
	
if (!$act) { echo '<script> window.onload = function() { document.querySelector("#metadata_toggle").click(); } </script>'; }

echo '
<script>
	my_gallery_CSSLoad("'.$MGURL.'plugins/'.$my_gallery_thisfile.'/windows.css");
	my_gallery_JSLoad("'.$MGURL.'plugins/'.$my_gallery_thisfile.'/windows.js");
</script>';

?>
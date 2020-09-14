<?php

function addGroupDocsViewer($atts, $content = null) {
 extract(gdv_shortcode_atts(array(
    "id" => null,
    "width" => '500',
    "height" => '600'    
  ), $atts));
  return '<div><iframe src="https://apps.groupdocs.com/document-viewer/Embed/'.$id.'?quality=50&use_pdf=False&download=False&referer=getsimple/1.0" frameborder="0" width="'.$width.'" height="'.$height.'"></iframe></div>';
}
gdv_shortcode('GroupDocsViewer','addGroupDocsViewer', '[groupdocs id="" width="" height="" /]');

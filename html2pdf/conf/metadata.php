<?php
$meta['restrict']        = array('onoff');      // restrict access
$meta['templatepath']    = array('string','_pattern' => '/^(|[a-zA-Z\-]+)$/'); // the location of the template file
$meta['users_namespace'] = array('string','_pattern' => '/^(|[a-zA-Z\-:]+)$/'); // the namespace containing user directories 
?>

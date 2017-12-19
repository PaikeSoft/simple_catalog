<?php
header('Content-type: application/json');

require_once '../../../../wp-load.php';
require_once 'frontend.php';

$myJSON = json_encode( frontend::quick_view($_POST['id']) );
echo $myJSON;
?>
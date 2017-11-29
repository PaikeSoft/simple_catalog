<?php
header('Content-type: application/json');

require_once '../../../../wp-load.php';
require_once 'frontend.php';

//$arr['description'] = 'description';

//print_r(frontend::quick_view($_POST['id']));
//$arr_val = frontend::get_product_param(0);
//$myObj = frontend::quick_view($_POST['id']); 



//$ss = frontend::quick_view();


//print_r($ss);

$myJSON = json_encode( frontend::quick_view($_POST['id']) );
echo $myJSON;

?>
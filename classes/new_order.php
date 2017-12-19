<?php
$to = $_POST['send_email'];
$subject = "New order";
 
$message  = 'The buyer made an order<br />'.$_POST['prname'].'<br /><br />';
$message .= '<table style="font-size:12px;font-family:Arial,Helvetica,sans-serif" cellpadding="0" cellspacing="0" border="1">';
$message .= '<tr><td style="padding:5px"><b>Name</b>:</td><td style="padding:5px">'.$_POST['name'].'</td></tr>';
$message .= '<tr><td style="padding:5px"><b>Phone</b>:</td><td style="padding:5px">'.$_POST['phone'].'</td></tr>';
$message .= '<tr><td style="padding:5px"><b>Email</b>:</td><td style="padding:5px">'.$_POST['email'].'</td></tr>';
$message .= '</table>';

$header = "From:scatalog \r\n";
$header .= "Cc:scatalog \r\n"; 
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-type: text/html\r\n";

mail ($to,$subject,$message,$header);
?>
<?php
date_default_timezone_set('Asia/Dhaka');

include 'include/db.php';

$date = date("d/m/y", time());	
$query="SELECT * from net_diag where text LIKE '%LICEN%' AND cancel_time=''";
$result=mysql_query($query);


//==================================================================================================================================================================================					
$Subject = $date."_Licence Related Alarm for Nokia Network from Net Diagnoser";

//Defining the E-mail body:			
$EmailBody = '<html>';
$EmailBody .= '<body>';
$EmailBody .= 'Dear Concern,'."<br/><br/>".'Please take necessary actions for the below alarms:'."<br/><br/>";			
$EmailBody .= '<table cellpadding="3">';


//Licence related alarms.	
$EmailBody .= "<table><tr><td valign='top'>";							
$EmailBody .= "<tr style='font-family:verdana; background: #123456;'><th colspan='4'><font color='ffffff'> Licence Related Alarms</font></th></tr>";			
$EmailBody .= "<tr style='font-family:verdana; background: #E89609;'><td><strong>Alarm Text:</strong></td><td><strong>Object Name:</strong></td><td><strong>DN:</strong></td><td><strong>Alarm Time:</strong></td><tr>";			
while($rowsql=mysql_fetch_assoc($result)) {
    $EmailBody .="<tr style='font-family:verdana; background: #FDEEF4;'><td>".$rowsql['text']."</td><td>".$rowsql['object_name']."</td><td>".$rowsql['dn']."</td><td>".$rowsql['alarm_time']."</td></tr>";
}
; 					
$EmailBody .= "</table>";
//end of licence related alarms.			

$EmailBody .= "</td></tr></table>";			

$EmailBody .= "<br/><br/>"."Best Regards,"."<br/>"."OMC BSS, Banglalink"."<br/>"."+88 019 15000739"."<br/>"."Powered By: <b><a href=''>ASKA-NetDiag</a></b>";			
$EmailBody .= "</body></html>";

//$EmailBody= 'asdfasd';
//==================================================================================================================================================================================		

include "include/class.phpmailer.php";

$mail = new PHPMailer();
$mail->SetLanguage("en", "/mail/language/");

$mail->IsSMTP();                                      // set mailer to use SMTP
$mail->Host = "";  // specify main and backup server
$mail->SMTPAuth = false; // turn on SMTP authentication
$mail->Username = "";   // SMTP username
$mail->Password = "";  // SMTP password

$mail->From = "";

$mail->FromName = "OMC BSS";

if ( $email_to != '') { $mail->AddAddress( $email_to, "");}

$email_to="";	
$email_to1="";	
$email_to2="";	
//$email_to3="";	
$email_to4="";	
$email_to5="";	
$email_to6="";	

$CC1 = "";
$BCC="";

if ( $email_to != '') { $mail->AddAddress( $email_to, "");}
if ( $email_to1 != '') { $mail->AddAddress( $email_to1, "");}
if ( $email_to2 != '') { $mail->AddAddress( $email_to2, "");}						
//if ( $email_to3 != '') { $mail->AddAddress( $email_to3, "");}
if ( $email_to4 != '') { $mail->AddAddress( $email_to4, "");}
if ( $email_to5 != '') { $mail->AddAddress( $email_to5, "");}
if ( $email_to6 != '') { $mail->AddAddress( $email_to6, "");}

if ( $BCC != '') { $mail->AddBCC( $BCC, "");}



if ( $CC1 != '') { $mail->AddCC( $CC1, "");}
$mail->WordWrap = 50;                                 // set word wrap to 50 characters
$mail->AddAttachment($path);    // optional name
$mail->IsHTML(true);                                  // set email format to HTML
$mail->Subject = $Subject;
$mail->Body    = $EmailBody."<br>";
if(!$mail->Send())
{
    echo "E-mail could not be sent. <p>";
    echo "Mailer Error: " . $mail->ErrorInfo;
    exit;
}

else
{
    echo "<h1>Please fill up the EmailBody field !!!</h1>";
}
//==================================================================================================================================================================================		
//E-mailing ends here....	

//session timeout command test end code	

?>

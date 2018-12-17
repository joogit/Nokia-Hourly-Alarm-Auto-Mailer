<?php
date_default_timezone_set('Asia/Dhaka');
include 'include/db.php';


$date = date("d/m/y", time());	
$query4="SELECT object_name, alarm_time, cancel_time FROM net_diag_hr WHERE (object_name!='' AND cancel_time='' AND text LIKE 'FAILURE IN OMC%' AND object_name!='BSFR01_C' AND object_name!='BSTA03_C') ORDER BY object_name";	
$result4=mysql_query($query4);	

$query5="SELECT object_name, dn, text, alarm_time, cancel_time, sup_info FROM net_diag_hr WHERE (cancel_time='' AND ( text LIKE 'INCORRECT WORKING STATE%' OR text LIKE 'FAN UNIT%')  AND dn NOT LIKE '%TCSM%' AND dn NOT LIKE '%PCM%' AND object_name NOT LIKE 'PLMN%') ORDER BY object_name";	
$result5=mysql_query($query5);		
//==================================================================================================================================================================================					
$Subject = $date."_Hourly Alarm Notification for Nokia Network from Net Diagnoser";

//Defining the E-mail body:			
$EmailBody = '<html style="font-family:verdana; font-size=0.6em; border-color: #666;">';
$EmailBody .= '<body>';
$EmailBody .= 'Dear Concern,'."<br/><br/>".'Please take necessary actions for the below alarms:'."<br/><br/>";						
$EmailBody .= "<table><tr>";


if(mysql_num_rows($result4)!=0){
    $EmailBody .= '<table style="font-family:verdana; font-size=0.6em; border-color: 1px solid #666;" cellpadding="2">';
    $EmailBody .= "<tr style='font-family:verdana; font-size=0.6em; background: #123456; '><th colspan='3'><font color='ffffff'> OMC Connection Failure Alarms for Nokia</font></th></tr>";
    $EmailBody .= "<tr style='font-family:verdana; font-size=0.6em; background: #E89609;'><td><strong>Object Name</strong></td><td><strong>Alarm Time</strong></td><td><strong>Cancel Time</strong></td><tr>";
    while($rowsql4=mysql_fetch_assoc($result4)) {
        $EmailBody .="<tr style='font-family:verdana; font-size=0.6em; background: #FDEEF4;'><td>".$rowsql4['object_name']."</td><td>".$rowsql4['alarm_time']."</td><td>".$rowsql4['cancel_time']."</td></tr>";
    }
    ;
    $EmailBody .= "</table>";
}

else{
    $EmailBody .= "<p style='font-family:verdana; font-size=0.6em; background: #1BE042; '><font color='black'> No OMC Connection Failure Alarms for Nokia</font></p>";
}

if(mysql_num_rows($result5)!=0){
    $EmailBody .= '<table style="font-family:verdana; font-size=0.6em; border-color: #666;" cellpadding="2">';
    $EmailBody .= "<tr style='font-family:verdana; font-size=0.6em; background: #123456;'><th colspan='6'><font color='ffffff'> BSC Hardware Faulty Alarms</font></th></tr>";
    $EmailBody .= "<tr style='font-family:verdana; font-size=0.6em; background: #E89609;'><td><strong>Object Name</strong></td><td><strong>DN</strong></td><td><strong>Text</strong></td><td><strong>Alarm Time</strong></td><td><strong>Cancel Time</strong></td><td><strong>Supply Info</strong></td><tr>";
    while($rowsql5=mysql_fetch_assoc($result5)) {
        $EmailBody .="<tr style='font-family:verdana; font-size=0.6em; background: #FDEEF4;'><td>".$rowsql5['object_name']."</td><td>".$rowsql5['dn']."</td><td>".$rowsql5['text']."</td><td>".$rowsql5['alarm_time']."</td><td>".$rowsql5['cancel_time']."</td><td>".$rowsql5['sup_info']."</td></tr>";
    }
    ;
    $EmailBody .= "</table>";
}

else{
    $EmailBody .= "<p style='font-family:verdana; font-size=0.6em; background: #1BE042; '><font color='black'> No Hardware Failure Alarms for Nokia BSCs</font></p>";
}				

$EmailBody .= "</tr></table>";			


$EmailBody .= "<br/><br/>"."Best Regards,"."<br/>"."OMC BSS, Banglalink"."<br/>"."+88 019 15000739"."<br/>"."Powered By: <b><a href=''>ASKA-NetDiag</a></b>";			
$EmailBody .= "</body></html>";

//$EmailBody= 'asdfasd';
//==================================================================================================================================================================================		

if(mysql_num_rows($result4)!=0 OR mysql_num_rows($result5)!=0){

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

    $email_to="";
    $email_to1="";
    $email_to2="";

    $CC2 = "";
    $CC3 = "";
    $CC11 = "";

    $CC12 = "";

    if ( $email_to != '') { $mail->AddAddress( $email_to, "");}
    if ( $email_to1 != '') { $mail->AddAddress( $email_to1, "");}
    if ( $email_to2 != '') { $mail->AddAddress( $email_to2, "");}
    if ( $CC1 != '') { $mail->AddCC( $CC1, "");}
    if ( $CC2 != '') { $mail->AddCC( $CC2, "");}
    if ( $CC3 != '') { $mail->AddCC( $CC3, "");}if ( $CC11 != '') { $mail->AddCC( $CC11, "");}
    if ( $CC12 != '') { $mail->AddCC( $CC12, "");}

    $mail->WordWrap = 50;                                 // set word wrap to 50 characters
    $mail->AddAttachment($path);    // optional name
    $mail->IsHTML(true);                                  // set email format to HTML
    $mail->Subject = $Subject;
    $mail->Body    = $EmailBody."<br>";
    //$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
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
}						
//==================================================================================================================================================================================		
//E-mailing ends here....	

//session timeout command test end code	

?>

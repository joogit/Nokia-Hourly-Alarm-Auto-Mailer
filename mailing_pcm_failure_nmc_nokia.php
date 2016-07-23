<?php
date_default_timezone_set('Asia/Dhaka');

include 'include/db.php';

$date = date("d/m/y", time());	

$query5="SELECT Object_Name, dn, alarm_time, cancel_time, ack_status from net_diag_hr where text='PCM Failure' AND (Object_Name like 'DHK%' or Object_Name like 'KHU%') and (cancel_time='' and ack_status='1') AND Object_Name not in (select Object_Name FROM net_diag_hr where text='BTS O&M Link Failure' AND cancel_time='') AND Object_Name NOT IN (SELECT name from bcf where adm='3') ORDER BY alarm_time";		
$result5=mysql_query($query5);		
//==================================================================================================================================================================================					
$Subject = $date."_PCM Failure Alarm Notification for NSN Network from Net Diagnoser";

//Defining the E-mail body:			
$EmailBody = '<html style="font-family:verdana; font-size=0.6em; border-color: #666;">';
$EmailBody .= '<body>';
$EmailBody .= 'Dear Concern,'."<br/><br/>".'Please Issue TT to BSS Field for below alarms:'."<br/><br/>";						
$EmailBody .= 'N.B: If PCM is Not on Air/Site Swapped to other vendor/BSC, Please ask BSSFO to request for Acknowledging the Alarm from NMC. '."<br/><br/>";						
$EmailBody .= "<table><tr>";


if(mysql_num_rows($result5)!=0){
    $EmailBody .= '<table style="font-family:verdana; font-size=0.3em; border-color: #666;" cellpadding="0">';
    $EmailBody .= "<tr style='font-family:verdana; font-size=0.6em; background: #123456;'><th colspan='3'><font color='ffffff'> PCM Failure Alarms for NSN</font></th></tr>";
    $EmailBody .= "<tr style='font-family:verdana; font-size=0.6em; background: #E89609; text-align:center;'><td><strong>Object Name</strong></td><td><strong>DN</strong></td><td><strong>Alarm Time</strong></td><tr>";
    while($rowsql5=mysql_fetch_assoc($result5)) {
        $EmailBody .="<tr style='font-family:verdana; font-size=0.6em; background: #FDEEF4;'><td>".$rowsql5['Object_Name']."</td><td>".$rowsql5['dn']."</td><td>".$rowsql5['alarm_time']."</td></tr>";
        $c++;
    }
    ;
    $EmailBody .= "</table>";
}

else{
    $EmailBody .= "<p style='font-family:verdana; font-size=0.6em; background: #1BE042; '><font color='black'> No PCM Failure Alarms for NSN Network </font></p>";
}				

$EmailBody .= "</tr></table>";			
$EmailBody .= "<font color='blue'>Total No. of Alarms:".$c."</font>";			


$EmailBody .= "<br/><br/>"."Best Regards,"."<br/>"."OMC BSS, Banglalink"."<br/>"."+88 019 15000739"."<br/>"."Powered By: <b><a href=''>ASKA-NetDiag</a></b>";			
$EmailBody .= "</body></html>";

//$EmailBody= 'asdfasd';
//==================================================================================================================================================================================		

if(mysql_num_rows($result4)!=0 OR mysql_num_rows($result5)!=0){
    include "include/class.phpmailer.php";
    $mail = new PHPMailer();
    $mail->SetLanguage("en", "/mail/language/");

    $mail->IsSMTP();                                      // set mailer to use SMTP
    $mail->Host = "172.16.10.170";  // specify main and backup server
    $mail->SMTPAuth = false; // turn on SMTP authentication
    $mail->Username = "";   // SMTP username
    $mail->Password = "";  // SMTP password

    $mail->From = "omc_bss@banglalinkgsm.com";
    $mail->FromName = "OMC BSS";

    $email_to="supervision@banglalinkgsm.com";
    $CC2 = "pkumar@banglalinkgsm.com";
    $CC3 = "ijamil@banglalinkgsm.com";

    $CC4 = "assarker@banglalinkgsm.com";
    $CC5 = "ahossain@banglalinkgsm.com";
    $CC6 = "FAhmed@banglalinkgsm.com";
    $CC12 = "OMC_BSS@banglalinkgsm.com";


    if ( $email_to != '') { $mail->AddAddress( $email_to, "");}
    if ( $CC1 != '') { $mail->AddCC( $CC1, "");}
    if ( $CC2 != '') { $mail->AddCC( $CC2, "");}
    if ( $CC3 != '') { $mail->AddCC( $CC3, "");}


    if ( $CC4 != '') { $mail->AddCC( $CC4, "");}
    if ( $CC5 != '') { $mail->AddCC( $CC5, "");}
    if ( $CC6 != '') { $mail->AddCC( $CC6, "");}
                        /*
                        if ( $CC7 != '') { $mail->AddCC( $CC7, "");}
                        if ( $CC8 != '') { $mail->AddCC( $CC8, "");}
                        if ( $CC9 != '') { $mail->AddCC( $CC9, "");}
                        if ( $CC10 != '') { $mail->AddCC( $CC10, "");}
                        if ( $CC11 != '') { $mail->AddCC( $CC11, "");}
                        */
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

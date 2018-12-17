<?php
date_default_timezone_set('Asia/Dhaka');

include 'include/db.php';

$date = date("d/m/y", time());	
$query="SELECT * FROM net_diag_hr where text LIKE '%LICEN%' AND cancel_time=''";
$result=mysql_query($query);

$query2="SELECT object_name, COUNT(*) FROM net_diag_hr WHERE (alarm_time LIKE '%$date%' AND text LIKE 'BCCH MISSING' AND vip_site='Y' AND (object_name LIKE 'DHK%' OR object_name LIKE 'KHU%' OR object_name LIKE 'RAJ%' OR object_name LIKE 'SYL%') ) GROUP BY object_name ORDER BY COUNT(*) DESC";	
$result2=mysql_query($query2);

$query3="SELECT object_name, COUNT(*) FROM net_diag_hr WHERE (alarm_time LIKE '%$date%' AND text LIKE 'PCM FAILURE' AND vip_site='Y' AND (object_name LIKE 'DHK%' OR object_name LIKE 'KHU%' OR object_name LIKE 'RAJ%' OR object_name LIKE 'SYL%') ) GROUP BY object_name ORDER BY COUNT(*) DESC";	
$result3=mysql_query($query3);	

$query4="SELECT object_name, alarm_time, cancel_time FROM net_diag_hr WHERE (cancel_time='' AND text LIKE 'FAILURE IN OMC%' AND object_name!='BSFR01_C' AND object_name!='BSTA03_C') ORDER BY object_name";	
$result4=mysql_query($query4);	

$query5="SELECT object_name, dn, text, alarm_time, cancel_time, sup_info FROM net_diag_hr WHERE (cancel_time='' AND ( text LIKE 'INCORRECT WORKING STATE%' OR text LIKE 'FAN UNIT%')  AND dn NOT LIKE '%TCSM%' AND dn NOT LIKE '%PCM%' AND object_name NOT LIKE 'PLMN%') ORDER BY object_name";	$result5=mysql_query($query5);	
$result5=mysql_query($query5);

$query91="SELECT object_name, COUNT(object_name) FROM net_diag_hr WHERE (alarm_time LIKE '%$date%' AND text LIKE 'BCCH MISSING' AND vip_site='N' AND (object_name LIKE 'DHK%' OR object_name LIKE 'KHU%' OR object_name LIKE 'RAJ%' OR object_name LIKE 'SYL%')) GROUP BY object_name HAVING COUNT(object_name) > '10' ORDER BY COUNT(object_name) DESC ";	
$result91=mysql_query($query91);

$query92="SELECT object_name, COUNT(object_name) FROM net_diag_hr WHERE (alarm_time LIKE '%$date%' AND text LIKE 'PCM FAILURE' AND vip_site='N' AND (object_name LIKE 'DHK%' OR object_name LIKE 'KHU%' OR object_name LIKE 'RAJ%' OR object_name LIKE 'SYL%')) GROUP BY object_name HAVING COUNT(object_name) > '5' ORDER BY COUNT(object_name) DESC";	
$result92=mysql_query($query92);	

$query200="SELECT object_name, dn, count(*) from net_diag_hr where (alarm_time LIKE '%$date%' AND text LIKE 'TRX RESTARTED' AND vip_site='N') GROUP BY dn HAVING count(*) > '20' ORDER BY count(*) DESC";              

$result200=mysql_query($query200);

$query201="SELECT object_name, dn, count(*) from net_diag_hr where (alarm_time LIKE '%$date%' AND text LIKE 'TRX RESTARTED' AND vip_site='Y') GROUP BY dn HAVING count(*) > '10' ORDER BY count(*) DESC";	
$result201=mysql_query($query201);

//==================================================================================================================================================================================					
$Subject = $date."_Daily Alarm Notification for Nokia Network from Net Diagnoser";

//Defining the E-mail body:			
$EmailBody = '<html>';
$EmailBody .= '<body>';
$EmailBody .= 'Dear Concern,'."<br/><br/>".'Please take necessary actions for the below alarms:'."<br/><br/><font color=Blue>"."N.B: For Alarm Deatils, Please Click the Site Code."."</font>";			
$EmailBody .= '<table cellpadding="3">';

//Code for TRX RESTARTED ALARMS
$EmailBody .= "<table><tr><td valign='top'>";				
$EmailBody .= '<table style="font-family:verdana; border-color: #666;" cellpadding="3">';
$EmailBody .= "<tr style='font-family:verdana; background: #123456;'><th colspan='3'><font color='ffffff'> TRX RESTARTED Alarms for NON-VVIP Sites</font></th></tr>";			
$EmailBody .= "<tr style='font-family:verdana; background: #E89609;'><td><strong>Site ID</strong></td><td><strong>TRX ID</strong></td><td><strong>No of Occurrence</strong></td></tr>";						

while($rowsql200=mysql_fetch_assoc($result200)) {
    $EmailBody .="<tr style='font-family:verdana; background: #FDEEF4;'><td>".$rowsql200['object_name']."</td><td align='center'>".$rowsql200['dn']."</td><td align='center'>".$rowsql200['count(*)']."</td></tr>";
}
; 
$EmailBody .= "</table>";

$EmailBody .= "</td></tr><tr><td valign='top'>";			
$EmailBody .= '<table style="font-family:verdana; border-color: #666;" cellpadding="3">';
$EmailBody .= "<tr style='font-family:verdana; background: #123456;'><th colspan='3'><font color='ffffff'> TRX RESTARTED Alarms (VVIP Sites)</font></th></tr>";			
$EmailBody .= "<tr style='font-family:verdana; background: #E89609;'><td><strong>Site ID</strong></td><td><strong>TRX ID</strong></td><td><strong>No of Occurrence</strong></td></tr>";								
while($rowsql201=mysql_fetch_assoc($result201)) {
    $EmailBody .="<tr style='font-family:verdana; background: #FDEEF4;'><td>".$rowsql201['object_name']."</td><td align='center'>".$rowsql201['dn']."</td><td align='center'>".$rowsql201['count(*)']."</td></tr>";
}
; 
$EmailBody .= "</table>";							
$EmailBody .= "</td></tr></table>";
//Code end for TRX RESTARTED ALARMS


$EmailBody .= "<table><tr><td valign='top'><br/><br/>";
$EmailBody .= '<table style="font-family:verdana; border-color: #666;" cellpadding="3">';
$EmailBody .= "<tr style='font-family:verdana; background: #123456;'><th colspan='2'><font color='ffffff'> BCCH MISSING Alarms (VVIP Sites)</font></th></tr>";			
$EmailBody .= "<tr style='font-family:verdana; background: #E89609;'><td><strong>Site ID</strong></td><td><strong>No of Occurrence</strong></td><tr>";						
while($rowsql2=mysql_fetch_assoc($result2)) {
    $EmailBody .="<tr style='font-family:verdana; background: #FDEEF4;'><td><a href='/notify_alarms/alarm_details_bts.php?object_type=bts&object_name=".$rowsql2['object_name']."'>".$rowsql2['object_name']."</a></td><td align='center'>".$rowsql2['COUNT(*)']."</td></tr>";
}
; 
$EmailBody .= "</table>";

$EmailBody .= "</td><td valign='top'><br/><br/>";			
$EmailBody .= '<table style="font-family:verdana; border-color: #666;" cellpadding="3">';
$EmailBody .= "<tr style='font-family:verdana; background: #123456;'><th colspan='2'><font color='ffffff'> PCM FAILURE Alarms(VVIP Sites)</font></th></tr>";			
$EmailBody .= "<tr style='font-family:verdana; background: #E89609;'><td><strong>Site ID</strong></td><td><strong>No of Occurrence</strong></td><tr>";								

while($rowsql3=mysql_fetch_assoc($result3)) {
    $EmailBody .="<tr style='font-family:verdana; background: #FDEEF4;'><td><a href='/notify_alarms/alarm_details_bts.php?object_type=bts&object_name=".$rowsql3['object_name']."'>".$rowsql3['object_name']."</a></td><td align='center'>".$rowsql3['COUNT(*)']."</td></tr>";
}
; 
$EmailBody .= "</table>";			
$EmailBody .= "</td></tr></table>";				
//Code for E-mailing Non-VVIP sites Alarms
$EmailBody .= "<table><tr><td valign='top'>";				
$EmailBody .= '<table style="font-family:verdana; border-color: #666;" cellpadding="3">';
$EmailBody .= "<tr style='font-family:verdana; background: #123456;'><th colspan='2'><font color='ffffff'> BCCH MISSING Alarms(NON-VVIP)</font></th></tr>";			
$EmailBody .= "<tr style='font-family:verdana; background: #E89609;'><td><strong>Site ID</strong></td><td><strong>No of Occurrence</strong></td><tr>";						

while($rowsql91=mysql_fetch_assoc($result91)) {
    $EmailBody .="<tr style='font-family:verdana; background: #FDEEF4;'><td><a href='/notify_alarms/alarm_details_bts.php?object_type=bts&object_name=".$rowsql91['object_name']."'>".$rowsql91['object_name']."</a></td><td align='center'>".$rowsql91['COUNT(object_name)']."</td></tr>";
}
; 
$EmailBody .= "</table>";

$EmailBody .= "</td><td valign='top'>";			
$EmailBody .= '<table style="font-family:verdana; border-color: #666;" cellpadding="3">';
$EmailBody .= "<tr style='font-family:verdana; background: #123456;'><th colspan='2'><font color='ffffff'> PCM FAILURE Alarms(NON-VVIP)</font></th></tr>";			
$EmailBody .= "<tr style='font-family:verdana; background: #E89609;'><td><strong>Site ID</strong></td><td><strong>No of Occurrence</strong></td><tr>";								
while($rowsql92=mysql_fetch_assoc($result92)) {
    $EmailBody .="<tr style='font-family:verdana; background: #FDEEF4;'><td><a href='/notify_alarms/alarm_details_bts.php?object_type=bts&object_name=".$rowsql92['object_name']."'>".$rowsql92['object_name']."</a></td><td align='center'>".$rowsql92['COUNT(object_name)']."</td></tr>";
}
; 
$EmailBody .= "</table>";							
$EmailBody .= "</td></tr></table>";
//Code end for Non-VVIP sites Alarms				


$EmailBody .= "<table><tr><td valign='top'><br/>";	

if(mysql_num_rows($result5)!=0){		
    $EmailBody .= '<table style="font-family:verdana; border-color: #666;" cellpadding="3">';
    $EmailBody .= "<tr style='font-family:verdana; background: #123456;'><th colspan='5'><font color='ffffff'> BSC Hardware Fault </font></th></tr>";
    $EmailBody .= "<tr style='font-family:verdana; background: #E89609;'><td><strong>Object Name</strong></td><td><strong>TRX ID</strong></td><td><strong>Alarm Time</strong></td><td><strong>Cancel Time</strong></td><td><strong>Supply Info</strong></td></tr>";
    while($rowsql5=mysql_fetch_assoc($result5)) {
        $EmailBody .="<tr style='font-family:verdana; background: #FDEEF4;'><td>".$rowsql5['object_name']."</td><td>".$rowsql5['dn']."</td><td>".$rowsql5['alarm_time']."</td><td>".$rowsql5['cancel_time']."</td><td>".$rowsql5['sup_info']."</td></tr>";
    }
    ;
    $EmailBody .= "</table>";
}
else{
    $EmailBody .= "<p style='font-family:verdana; font-size=0.6em; background: #1BE042; '><font color='black'> No BSC Hardware Failure Alarms for Nokia</font></p>";
}

$EmailBody .= "</td></tr></table>";

$EmailBody .= "<table><tr><td valign='top'>";	
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

$EmailBody .= "</td></tr></table>";

$EmailBody .= "<br/>"."For more details please visit "."<b><a href=''> Net Diagnoser</a></b>"."<br/>";			

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
$mail->FromName = "";
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
if ( $CC3 != '') { $mail->AddCC( $CC3, "");}	
if ( $CC11 != '') { $mail->AddCC( $CC11, "");}																		
if ( $CC12 != '') { $mail->AddCC( $CC12, "");}											


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
                        /*
                        else
                        {
                            echo "<h1>Please fill up the EmailBody field !!!</h1>";
                        }
                        */
//==================================================================================================================================================================================		
//E-mailing ends here....	

?>

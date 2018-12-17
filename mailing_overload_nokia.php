<?php
date_default_timezone_set('Asia/Dhaka');

include 'include/db.php';

$date = date("d/m/y", time());	
$query="SELECT * FROM net_diag where text LIKE '%LICEN%' AND cancel_time=''";
$result=mysql_query($query);

$query2="SELECT object_name, COUNT(*) FROM net_diag WHERE (text LIKE '%TELECOM LINK OVERLOAD%' AND object_name LIKE 'BS%') GROUP BY object_name ORDER BY COUNT(*) DESC";
$result2=mysql_query($query2);

$query3="SELECT object_name, COUNT(*) FROM net_diag WHERE (text LIKE '%SIGNALING LINK LOAD%') GROUP BY object_name ORDER BY COUNT(*) DESC";
$result3=mysql_query($query3);	

$query4="SELECT object_name, alarm_time, cancel_time FROM net_diag WHERE (text LIKE '%PAGING OVERLOAD%') GROUP BY object_name ORDER BY COUNT(*) DESC";
$result4=mysql_query($query4);	

$query5="SELECT * FROM net_diag where (text LIKE '%PROCESSOR LOAD RATE ALARM LIMIT EX%') GROUP BY object_name ORDER BY COUNT(*) DESC";
$result5=mysql_query($query5);

//==================================================================================================================================================================================					
$Subject = $date."_Overload Alarm Notification for Nokia Network from Net Diagnoser";

//Defining the E-mail body:			
$EmailBody = '<html>';
$EmailBody .= '<body>';
$EmailBody .= 'Dear Concern,'."<br/><br/>".'This is an auto generated mail from Server in addition to our regular BSS Overload mail FYNA.'."<br/><br/>"."For Alarm Deatils, Please click on the link below:";			
$EmailBody .= "<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1. <a href='/alarm_details.php?object_type=bsc&text=TELECOM%20LINK%20OVERLOAD'>"."Telecom Link Overload (BTS Overload)". '</a>';			
$EmailBody .= "<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2. <a href='/alarm_details.php?object_type=bsc&text=SIGNALING%20LINK%20LOAD%20OVER%20THRESHOLD'>"."Signaling Link Load Over Threshold (Signaling Overload)". '</a>';			
$EmailBody .= "<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 3. <a href='/alarm_details.php?object_type=bsc&text=PAGING%20OVERLOAD'>"."Paging Overload". '</a>';			
$EmailBody .= "<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 4. <a href='/alarm_details.php?object_type=bsc&text=PROCESSOR%20LAOD%20RATE%20ALARM%20LIMIT%20EXCEEDED'>"."Processor Load Rate Limit Exceeded (Processor Overload)". '</a>';			
$EmailBody .= "<br/><br/><table style='border-color: #666; border: 2px solid #000000; '><tr><th colspan='4' style=' background: #E89609; border: 1px solid #123456; '>Overload Related Alarm Summary for Nokia Network</th></tr><tr><td valign='top'>";    //main table starts here
$EmailBody .= '<table><tr><td><table style=" border-color: #666;  " >';
$EmailBody .= "<tr style=' background: #E89609;  '><th colspan='2' style=' background: #52C5F4; border: 1px solid #123456;'>Telecom Link Overload</th></tr>";			
$EmailBody .= "<tr><td style=' background: #52C5F4; border: 1px solid #123456;'>BSC Name</td><td style=' background: #52C5F4; border: 1px solid #123456;'>No of Occurrence</td><tr>";						
while($rowsql2=mysql_fetch_assoc($result2)) {
    $EmailBody .="<tr><td style=' background: #FDEEF4; border: 1px solid #123456;'>".$rowsql2['object_name']."</td><td align='center' style=' background: #FDEEF4; border: 1px solid #123456;'>".$rowsql2['COUNT(*)']."</td></tr>";
}
; 
$EmailBody .= "</table>";

$EmailBody .= "</td></tr></table> </td><td valign='top'><table><tr><td valign='top'>";	//starting of new cell

if(mysql_num_rows($result3)!=0){		
    $EmailBody .= '<table style=" border-color: #666; " >';
    $EmailBody .= "<tr style=' background: ##123456;'><th colspan='5'><font color='#123456'> SIGNALING LINK OVERLOAD ALARM </font></th></tr>";
    $EmailBody .= "<tr style=' background: #52C5F4;'><td><strong>Object Name</strong></td><td><strong>DN</strong></td><td><strong>Alarm Time</strong></td><td><strong>Cancel Time</strong></td><td><strong>Supply Info</strong></td><tr>";
    while($rowsql3=mysql_fetch_assoc($result3)) {
        $EmailBody .="<tr style=' background: #FDEEF4;'><td>".$rowsql3['object_name']."</td><td>".$rowsql3['dn']."</td><td>".$rowsql3['alarm_time']."</td><td>".$rowsql3['cancel_time']."</td><td>".$rowsql3['sup_info']."</td></tr>";
    }
    ;
    $EmailBody .= "</table>";
}
else{
    $EmailBody .= "<p  style=' background: #1BE042; border: 1px solid #123456; padding: 2px;'> No 'SIGNALING OVERLOAD' alarm @ Nokia </p>";
}		

$EmailBody .= "</td></tr><tr><td>";	//end of cell and starts new cell
if(mysql_num_rows($rowsql4)!=0){		
    $EmailBody .= '<table style=" border-color: #666; " >';
    $EmailBody .= "<tr style=' background: ##123456;'><th colspan='5'><font color='#123456'> PAGING OVERLOAD ALARM </font></th></tr>";
    $EmailBody .= "<tr style=' background: #52C5F4;'><td><strong>Object Name</strong></td><td><strong>DN</strong></td><td><strong>Alarm Time</strong></td><td><strong>Cancel Time</strong></td><td><strong>Supply Info</strong></td><tr>";
    while($rowsql4=mysql_fetch_assoc($rowsql4)) {
        $EmailBody .="<tr style=' background: #FDEEF4;'><td>".$rowsql4['object_name']."</td><td>".$rowsql4['dn']."</td><td>".$rowsql4['alarm_time']."</td><td>".$rowsql4['cancel_time']."</td><td>".$rowsql4['sup_info']."</td></tr>";
    }
    ;
    $EmailBody .= "</table>";
}
else{
    $EmailBody .= "<p  style=' background: #1BE042; border: 1px solid #123456; padding: 2px;'> No 'PAGING OVERLOAD' alarm @ Nokia </p>";
}


$EmailBody .= "</td></tr><tr><td>";	//end of cell and starts new cell
if(mysql_num_rows($rowsql5)!=0){		
    $EmailBody .= '<table style=" border-color: #666; " >';
    $EmailBody .= "<tr style=' background: ##123456;'><th colspan='5'><font color='#123456'> PROCESSOR LOAD RATE LIMIT OVER ALARM </font></th></tr>";
    $EmailBody .= "<tr style=' background: #52C5F4;'><td><strong>Object Name</strong></td><td><strong>DN</strong></td><td><strong>Alarm Time</strong></td><td><strong>Cancel Time</strong></td><td><strong>Supply Info</strong></td><tr>";
    while($rowsql5=mysql_fetch_assoc($rowsql5)) {
        $EmailBody .="<tr style=' background: #FDEEF4;'><td>".$rowsql5['object_name']."</td><td>".$rowsql5['dn']."</td><td>".$rowsql5['alarm_time']."</td><td>".$rowsql5['cancel_time']."</td><td>".$rowsql5['sup_info']."</td></tr>";
    }
    ;
    $EmailBody .= "</table>";
}
else{
    $EmailBody .= "<p  style=' background: #1BE042; border: 1px solid #123456; padding: 2px;'> No 'PROCESSOR OVERLAOD' alarm @ Nokia </p>";
}


$EmailBody .= "</td></tr></table>  </td></tr></table>";	//end of cell and end of main table with row		

$EmailBody .= "<br/>"."For more details please visit "."<b><a href='/index.php'> Net Diagnoser</a></b>"."<br/>";			

$EmailBody .= "<br/><br/>"."Best Regards,"."<br/>"."OMC BSS, Banglalink"."<br/>"."+88 019 15000739"."<br/>"."Powered By: <b><a href='/index.php'>ASKA-NetDiag</a></b>";			
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
$email_to="";
$CC12 = "";

if ( $email_to != '') { $mail->AddAddress( $email_to, "");}
if ( $email_to1 != '') { $mail->AddAddress( $email_to1, "");}
if ( $email_to2 != '') { $mail->AddAddress( $email_to2, "");}						
if ( $CC1 != '') { $mail->AddCC( $CC1, "");}
if ( $CC2 != '') { $mail->AddCC( $CC2, "");}
if ( $CC3 != '') { $mail->AddCC( $CC3, "");}						
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
//==================================================================================================================================================================================		
//E-mailing ends here....	

?>

<?php	
ini_set("memory_limit", "1000M");
set_time_limit(0);	
date_default_timezone_set('Asia/Dhaka');

include 'include/db.php';

$del_table = mysql_query("TRUNCATE TABLE net_diag_hr");

$input="raw_files/alarm_tdy.csv";
$lines = file("$input");
//Updating the date of the latest alarm update.... must maintain as this will ensure the user that the database is updated.
if($input){
    $cs_date=  date("m/d/y G.i:s<br>", time());
    $update_date=mysql_query("UPDATE update_date SET alarm_update_hr='$cs_date' ");

    foreach ($lines as $line_num => $line) {
        if($line_num > 0) {
        $arr = explode(",", $line);
        $dn = trim((string) $arr[1]);
        $original_severity = trim((string) $arr[4]);
        $alarm_time = trim((string) $arr[5]);
        $cancel_time = trim((string) $arr[6]);
        $ack_status = trim((string) $arr[8]);
        $ack_time = trim((string) $arr[9]);
        $acked_by = trim((string) $arr[10]);
        $alarm_number = trim((string) $arr[11]);
        $text = trim((string) $arr[15]);
        $sup_info = trim((string) $arr[22]);
        $object_name = trim((string) $arr[46]);
        $vip_site = 'N';
        $sql2 = sprintf("INSERT INTO net_diag  VALUES ('$dn', '$original_severity', '$alarm_time', '$cancel_time', '$ack_status', '$ack_time', '$acked_by', '$alarm_number', '$text', '$sup_info', '$object_name', '$vip_site')");
        $result2 = mysql_query($sql2);
        }
    }
    $query_vip="UPDATE net_diag_hr SET vip_site='Y' where object_name in (select object_name from sp_case);";
    $result_vip=mysql_query($query_vip);

}
else{
    echo "Database not updated";
}	

?>
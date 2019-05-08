<?php
session_start();
include('includes/connect.php');
include('includes/check_user.php');

session_start();    
//$expire_date = time() + 21600;
///setcookie("userid", "", $expire_date);
session_destroy();
session_regenerate_id(true);	
		
$login_Domain=strtoupper($_SERVER[SERVER_NAME]);
$userIPAddress = $_SERVER['REMOTE_ADDR']."_$login_Domain";
		
    $loginIdQuery = "SELECT max(loginhistory_id) AS loginhistory_id from loginhistory where ClerkID='$login_userid' and 
        user_ip='$userIPAddress' and logout_time='0000-00-00 00:00:00'";
		
    $result = mysql_query($loginIdQuery);
    $row = mysql_fetch_assoc($result);
    
   // print $query;
        
    $query = "Update loginhistory set logout_time =NOW(), status='Signed off' where loginhistory_id = $row[loginhistory_id]";
    //if (!$backdoor)
        mysql_query($query);
            
//	print $login_Domain;exit;		
        header("Location: /invoiceirrigating/index.php");
  
  
  
  
  
?>
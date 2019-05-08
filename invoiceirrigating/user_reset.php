<?php 
/*
user_reset.php

??? ???? ?? ??? ???? ???? ???? ???????? ?? ???

*/

include('includes/connect.php'); ?>
<?php include('includes/check_user.php'); 
      include('includes/elements.php'); ?>
	  
	  <?php
if ($login_Permission_granted==0) header("Location: login.php");
$uid = is_numeric($_GET["uid"]) ? intval($_GET["uid"]) : 0;
$dis = is_numeric($_GET["dis"]) ? intval($_GET["dis"]) : 0;
$dison = $_GET["dison"];
		$linearray = explode('_',$dison);
        $dison=$linearray[0];
        if ($linearray[1]==4) $disnum=3; else $disnum=4;
    	
		
if ($dison>0)
{
    //clerk ???? ???????
			$query = "
    		UPDATE clerk SET
    		Disable = '".$disnum."'
    		WHERE ClerkID = " . $dison . ";";
    		$result = mysql_query($query);

}

else if ($dis>0)
{
			$query = "
    		UPDATE clerk SET
    		Disable = NULL
    		WHERE ClerkID = " . $dis . ";";
    		$result = mysql_query($query);

}
else
{
		   $password="1234";
			$query = "
    		UPDATE clerk SET
    		WN = '" . encrypt($password) . "'
    		WHERE ClerkID = " . $uid . ";";
    		$result = mysql_query($query);
}			
//           print $query;
  //          exit;
            
header("Location: user_list.php");
	
?>

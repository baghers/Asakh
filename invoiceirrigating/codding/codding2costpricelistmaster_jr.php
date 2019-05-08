<?php 

/*
codding/codding2costpricelistmaster_jr.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
codding/codding2costpricelistmaster.php

*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


	$year=$_POST['in1'];
	$MonthID=$_POST['in2'];
	//costpricelistmaster جدول فهرست بها
    $sql = "
SELECT * FROM costpricelistmaster
where YearID='$year' and MonthID='$MonthID'";


$result = mysql_query($sql);
if (! mysql_fetch_assoc($result))    
    {
    $query = "INSERT INTO costpricelistmaster(YearID,MonthID,SaveTime,SaveDate,ClerkID) VALUES('" .

            $year . "', '" . $MonthID . "', '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$_POST['in3']."');";


    //  print $query;
     
		 try 
			  {		
				 $result = mysql_query($query);
			  }
			  //catch exception
			  catch(Exception $e) 
			  {
				echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
			  }

    }
    	exit();
    
    
    			
	
   
   
   
			
			
		
	

?>




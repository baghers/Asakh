<?php 
/*
pricesaving/pricesavingpipe_jr.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
pricesaving/pricesavingpipe.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


	$Date=$_POST['in1'];
	$PE32=$_POST['in2'];
	$PE40=$_POST['in3'];
	$PE80=$_POST['in4'];
	$PE100=$_POST['in5'];
	$ClerkID=$_POST['in6'];
	
    $Permissionvals=supervisorcoderrquirement_sql($login_ostanId);
    $maxpe32=$Permissionvals['maxpe32pipeprice'];             
    $maxpe40=$Permissionvals['maxpe40pipeprice'];    
    $maxpe80=$Permissionvals['maxpe80pipeprice'];    
    $maxpe100=$Permissionvals['maxpe100pipeprice'];    
    
     /*
pipeprice جدول قیمت لوله
maxpe100pipeprice  سقف قیمت لوله 100
maxpe80pipeprice سقف قیمت لوله 80
maxpe32pipeprice سقف قیمت لوله 32
maxpe40pipeprice سقف قیمت لوله 40
*/
        
     
     $query = "select count(*) cnt from pipeprice where ProducersID='$login_ProducersID' and date='$Date'";
                              
        
								try 
								  {		
									  $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

     $row = mysql_fetch_assoc($result);           
 		
    	   
            


    if ($PE32<($maxpe32-$Permissionvals['lowrange']))
    {
        $temp_array = array('error' => '7');
    }
    if ($PE40<($maxpe40-$Permissionvals['lowrange']))
    {
        $temp_array = array('error' => '8');
    }
    if ($PE80<($maxpe80-$Permissionvals['lowrange']))
    {
        $temp_array = array('error' => '9');
    }
    if ($PE100<($maxpe100-$Permissionvals['lowrange']))
    {
        $temp_array = array('error' => '10');
    }
    if ($PE32>$maxpe32)
    {
        $temp_array = array('error' => '5');
    }
    else if ($PE40>$maxpe40)
    {
        $temp_array = array('error' => '6');
    }
    else if ($PE80>$maxpe80)
    {
        $temp_array = array('error' => '3');
    }
    else if ($PE100>$maxpe100)
    {
        $temp_array = array('error' => '2');
    }
    else if ($row['cnt']>0) 
        $temp_array = array('error' => '1');
    else  if ($login_ProducersID>0)
    {
        
        $temp_array = array('error' => '0');
        
        $query = "INSERT INTO pipeprice(ProducersID,Date, PE32, PE40, PE80, PE100,SaveTime,SaveDate,ClerkID) VALUES('$login_ProducersID','$Date','$PE32','$PE40','$PE80','$PE100','" .date('Y-m-d H:i:s'). "','".date('Y-m-d')."','$ClerkID');";

         
								try 
								  {		
									  $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

  
     }   
     else 
    {
        $temp_array = array('error' => '4');
    }
        
        
        
        
		
        echo json_encode($temp_array);
        
    	exit();
    

?>




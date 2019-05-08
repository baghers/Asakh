<?php
/*
tools/tools_request_delete.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools_request.php
*/
include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');



if ($login_Permission_granted==0) header("Location: ../login.php");
    /*
       toolsrequest جدول ابزارهای درخواستی
       toolsrequestID شناسه ابزار درخواستی
    */
    $toolsrequestid = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $query = " DELETE FROM toolsrequest WHERE toolsrequestid=$toolsrequestid ;";
            try 
								  {		
									    mysql_query($query);  
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  } 
    
    header("Location: tools_request.php");
                                            
                            
?>

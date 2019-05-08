<?php
/*
tools/tools_listrequest_done.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools_listrequest.php
*/
include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');




if ($login_Permission_granted==0) header("Location: ../login.php");


$linearray = explode('_',substr($_GET["uid"],40,strlen($_GET["uid"])-45));
$toolsrequestid=$linearray[0];//شناسه ابزار درخواستی
$state=$linearray[1];//وضعیت


/*
    toolsrequest جدول ابزارهای درخواستی
    toolsrequestID شناسه ابزار درخواستی
*/
    $query = " update toolsrequest set state='".($state%2+1)."' WHERE toolsrequestid=$toolsrequestid ";
    try 
								  {		
									    mysql_query($query); 
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
    
    header("Location: tools_listrequest.php");
                                            
                            
?>

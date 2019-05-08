<?php

/*
message/msgsending7_delete.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
message/msgsending7.php
*/
include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');

    /*
messages جدول پیغام ها
*/
 

if ($login_Permission_granted==0) header("Location: ../login.php");

$MessagesID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
          
    
    $query = " DELETE from messages where messagesID='$MessagesID';";
    
    
			  		           	try 
								  {		
									  	  	  mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    header("Location: msgsending7.php");
                                            
                            
?>

<?php
/*
instruction/instruction1_delete.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
instruction/instruction1.php
*/


include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');


 

if ($login_Permission_granted==0) header("Location: ../login.php");

    $id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
          
            /*
        instruction جدول دستور العمل ها
        instructionID شناسه دستورالعمل
        */
    $query = " DELETE from instruction where instructionID='$id';";
                    
		  						try 
								  {		
									  	    mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

 
    header("Location: instruction1.php");
                                            
                            
?>

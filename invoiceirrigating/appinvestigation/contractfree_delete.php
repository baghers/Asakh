<?php

/*

//appinvestigation/contractfree_delete.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/contractfree_list.php
 -
*/

include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');




if ($login_Permission_granted==0) header("Location: ../login.php");

$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
$linearray = explode('_',$ids);
$contractfreeID=$linearray[0];//قرارداد آزادسازی
$designercocontractID=$linearray[1];//قرارداد
$designercoID=$linearray[2];//طراح
/*
contractfree قرارداد آزادسازی
contractfreeID شناسه
*/
    $query = " DELETE FROM contractfree WHERE contractfreeID='$contractfreeID';";
    $result = mysql_query($query);
								try 
							  {		
								mysql_query($query);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

    header("Location: contractfree_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$designercocontractID.'_1_0'.$DesignerCoID.rand(10000,99999));
                                            
                            
?>

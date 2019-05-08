<?php

/*

//appinvestigation/invoicemasterfree_delete.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/invoicemasterfree_list.php
 -
*/

include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');




if ($login_Permission_granted==0) header("Location: ../login.php");

$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
$linearray = explode('_',$ids);
$applicantfreedetailID=$linearray[0];//شناسه جدول آزادسازی
$ApplicantMasterID=$linearray[1];//شناسه طرح
$OperatorCoID=$linearray[2];//شناسه پیمانکار
    //applicantfreedetail  جدول آزادسازی
    $query = " DELETE FROM applicantfreedetail WHERE applicantfreedetailID='$applicantfreedetailID';";

							try 
							  {		
								    $result = mysql_query($query);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

    header("Location: invoicemasterfree_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.'_1_0'.$OperatorCoID.rand(10000,99999));
                                            
                            
?>

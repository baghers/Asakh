<?php
/*

//appinvestigation/allapplicantstates_return.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/applicant_manageredit.php
/appinvestigation/allapplicantstates.php
-
*/

include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');


 if (!($login_userid>0)) header("Location: ../login.php");

   
    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
	
$linearray = explode('_',$ids);
$ApplicantMasterID=$linearray[0];//شناسه طرح
$type=$linearray[1];//نوع 3 انصراف 4 برگشت به کارتابل 5 حذف آخرین وضعیت
$ApplicantMasterIDtemp=$linearray[5];//شناسه طرح
$login_RolesID=$linearray[6];//نقش

if ($type==5){
$applicantstatesID=$linearray[4];//شناسه وضعیت
$ApplicantMasterIDmaster=$linearray[0];//شناسه طرح اجرایی
}

if ($type==3)//انصراف متقاضی
{
    /*
    proposestatep=-1 انصراف
    applicantmaster جدول مشخصات طرح
    ApplicantMasterID شناسه طرح
    */
    mysql_query("update applicantmaster set 
    SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
    proposestatep=-1  where ApplicantMasterID='$ApplicantMasterID'");
	    header("Location: "."allapplicantstates.php");
}
//print_r($linearray);exit;
//if ($login_Permission_granted==0) header("Location: ../login.php");



    if ($login_RolesID==16) //صندوق
      $ApplicantstatesID=12; 
    else if ($login_RolesID==7) //بانک
    $ApplicantstatesID=36; 
     else  if ($login_RolesID==1 || $login_RolesID==5) //مدیریت آب و خاک و مدیر پیگیری
     $ApplicantstatesID=33; 
	  else  if ($login_RolesID==13 || $login_RolesID==18) //مدیر آبیاری یا مدیر آب و خاک
	        {
             if ($applicantstatesID==45) {$ApplicantstatesID=43; }
			 else   {$ApplicantstatesID=26;$ApplicantstatesIDdelete=40;}
	    	}
    else header("Location: ../login.php");
      //appchangestate جدول تغییر وضعیت ها
    $query = "SELECT max(stateno)+1 stateno
            FROM appchangestate 
            where ApplicantMasterID='$ApplicantMasterID' 
    ";
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



    $row = mysql_fetch_assoc($result);
    $maxstateno=$row['stateno'];            
    
    if ($ApplicantMasterID>0 && $maxstateno>0 && $ApplicantstatesID>0)
    {
        //درج وضعیت برگشت به کارتابل 
        $query = "INSERT INTO appchangestate(ApplicantMasterID, stateno, applicantstatesID,Description,SaveTime,SaveDate,ClerkID) VALUES('" .
        $ApplicantMasterID . "',$maxstateno,'$ApplicantstatesID','برگشت به کارتابل', '" . 
        date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."');";
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
      
		if ($type==5 && $ApplicantstatesIDdelete==40 && $ApplicantMasterIDtemp>0)
		{  //بروز رسانی وضعیت
			$querys =" UPDATE `appchangestate` SET `Description` = '$ApplicantMasterID' WHERE `appchangestate`.`applicantstatesID` = '$ApplicantstatesIDdelete' and `appchangestate`.`ApplicantMasterID` = '$ApplicantMasterIDmaster' ";
			$results = mysql_query($querys);
							try 
							  {		
								mysql_query($querys);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }
			//حذف وضعیت				  
			$querys ="DELETE FROM `applicantmaster` WHERE `applicantmaster`.`ApplicantMasterID` = '$ApplicantMasterIDmaster' and
			`applicantmaster`.`ApplicantMasterIDmaster` = '$ApplicantMasterID' ";
			$results = mysql_query($querys);
							try 
							  {		
								mysql_query($querys);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

		}
        //حذف طرح
        if ($login_RolesID==7 || $login_RolesID==16)
        	mysql_query("update operatorapprequest set ApplicantMasterID=0,applicantmasteridret='$ApplicantMasterID'  where ApplicantMasterID='$ApplicantMasterID'");
    }  
               
                   // print $query;
                   // exit;
   if ($type==4) header("Location: "."allapplicantstates.php");
   if ($type==5 && $ApplicantstatesID==43) header("Location: "."allapplicantstatesoplist.php");
   if ($type==5 && $ApplicantstatesID==26) header("Location: "."allapplicantstatesop.php");
    
    header("Location: "."allapplicantstates.php");
                            
?>

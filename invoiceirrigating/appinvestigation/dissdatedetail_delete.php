<?php

/*

//appinvestigation/dissdatedetail_delete.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/applicant_manageredit.php
 -
*/

include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');



if ($login_Permission_granted==0) header("Location: ../login.php");



    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
	//print $ids;exit;
    $linearray = explode('_',$ids);
    $ApplicantMasterID=$linearray[0];//شاسه طرح
    $type=$linearray[1];//نوع
    $DesignerCoID=$linearray[2];//طراح
    $operatorcoID=$linearray[3];//مجری
	$DesignArea=$linearray[4];//مساحت
	$login_ostanId=$linearray[5];//شناسه استان
	freeproject($DesignArea,$operatorcoID,4,$login_ostanId);//تابع آزادسازی پروژه
	
if ($type==1) {$Description='حذف آزادسازی ظرفیت';$str="and  stateno='1000'";} 
	else if ($type==2) {$Description='حذف انصراف از اجرا';$str="and  stateno='1100'";}
                	/*
                    appchangestate جدول تغییر وضعیت طرح ها
                    Description شرح
                    ApplicantMasterID شناسه طرح
                    stateno ترتیب تغییر
                    */
					$query = "SELECT Description
					FROM appchangestate 
					where ApplicantMasterID='$ApplicantMasterID' and 
					stateno=(SELECT max(stateno) maxstate FROM appchangestate where ApplicantMasterID='$ApplicantMasterID' $str)";
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
					$Description=$Description.'-'.$row['Description'];
        			/*
                    appchangestate جدول تغییر وضعیت طرح ها
                    Description شرح
                    ApplicantMasterID شناسه طرح
                    stateno ترتیب تغییر
                    */
					$query = "SELECT appchangestate.applicantstatesID,stateno
					FROM appchangestate 
					where ApplicantMasterID='$ApplicantMasterID' and 
					stateno=(SELECT max(stateno) maxstate FROM appchangestate where ApplicantMasterID='$ApplicantMasterID' and stateno<1000)";
					
//print $query;exit;
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
					$applicantstatesID=$row['applicantstatesID'];
					$maxstateunder1000=$row['stateno']+1;
        			/*
                    appchangestate جدول تغییر وضعیت طرح ها
                    Description شرح
                    ApplicantMasterID شناسه طرح
                    stateno ترتیب تغییر
                    */
				    $query = "update appchangestate set stateno='$maxstateunder1000',applicantstatesID='$applicantstatesID'
					,Description='$Description'
					 where ApplicantMasterID='$ApplicantMasterID' $str ";
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
        			/*
                    applicantmaster مشخصات طرح
                    applicantstatesID شناسه وضعیت
                    ApplicantMasterID شناسه طرح
                    */
                    $query = "update applicantmaster set applicantstatesID='$applicantstatesID'
	               where applicantmaster.ApplicantMasterID='$ApplicantMasterID' ";
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
     

					
				
					
					
					
					
					
					
					
	 $id="applicant_manageredit.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ApplicantMasterID.'_3_'.$DesignerCoID.'_'.$operatorcoid.rand(10000,99999);
							
	
    header("Location: ".$id);
    
                            
?>

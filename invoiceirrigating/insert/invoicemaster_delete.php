<?php

/*

insert/invoicemaster_delete.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/invoicemaster_list.php
*/

include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');

if ($login_RolesID==17 || $login_RolesID==10)//ناظی مقیم یا مشاور ناظر
    $login_DesignerCoID='67';


if ($login_Permission_granted==0) header("Location: ../login.php");
    $invoicemasterID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);

    if ($login_DesignerCoID>0) $condition1=" and applicantmaster.DesignerCoID='$login_DesignerCoID'";
    else $condition1=" and applicantmaster.operatorcoid='$login_OperatorCoID' and ifnull(operatorcoid,0)<>0 ";
/*
invoicemaster لیست لوازم
InvoiceMasterID شناسه لیست
applicantmaster جدول مشخصات طرح
*/

if ($login_RolesID==18 || $login_RolesID==13 || $login_RolesID==14 || $login_RolesID==1) $condition1="";
    $query = "SELECT invoicemaster.InvoiceMasterID,invoicemaster.ApplicantMasterID FROM invoicemaster 
    inner join applicantmaster on applicantmaster.ApplicantMasterID=invoicemaster.ApplicantMasterID
    WHERE InvoiceMasterID ='$invoicemasterID'  $condition1 ;";
    
 //   print $query;exit;
    				  	  	try 
								  {		
									  $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

	
    $resquery = mysql_fetch_assoc($result);
    if (!$resquery["InvoiceMasterID"]) header("Location: ../logout.php");
    
    
    
    
    ///////////////بررسی گردش در سایر جداول
    $deletefromtable="invoicemaster";
    $deletefromtablefield="InvoiceMasterID";
    $deletefromtablefieldvalue=$resquery['InvoiceMasterID'];
    $hascirculation="";
    $query = " SELECT DISTINCT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE upper(COLUMN_NAME) like '%".strtoupper($deletefromtablefield)."%' AND TABLE_SCHEMA = '$_server_db';";
    $result = mysql_query($query);
    while($row = mysql_fetch_assoc($result))
    {
        if($row['TABLE_NAME']<>$deletefromtable && $row['TABLE_NAME']<>"invoicetiming" && $row['TABLE_NAME']<>"invoicedetail"  && $row['TABLE_NAME']<>"invoicedetailviewed")
        {
            $queryin = " SELECT count( * ) cnt FROM $row[TABLE_NAME] WHERE $deletefromtablefield =$deletefromtablefieldvalue";
            //print $queryin;
            
							   	try 
								  {		
									  $resultin = mysql_query($queryin);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

            $rowin = mysql_fetch_assoc($resultin);
            if ($rowin['cnt']>0)
            $hascirculation.=" ".$row['TABLE_NAME'];
        }
        
    }
    if (strlen($hascirculation)>0) 
    {
        print " این مقدار در جداول زیر گردش دارد ".$hascirculation;
        exit;
    }
    
    //invoicedetail ریز لوازم
    $query = " DELETE FROM invoicedetail WHERE InvoiceMasterID = '$resquery[InvoiceMasterID]';";
    				  	  	try 
								  {		
									  $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    //invoicemaster جدول لیست لوازم
    $query = " DELETE FROM invoicemaster WHERE InvoiceMasterID = '$resquery[InvoiceMasterID]';";
   				  	  	try 
								  {		
									  $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    
    //invoicetiming جدول زمانبندی
    $query = " DELETE FROM invoicetiming WHERE InvoiceMasterID = '$resquery[InvoiceMasterID]';";
    				  	  	try 
								  {		
									  $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    
    
    
    header("Location: "."invoicemaster_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
    rand(10000,99999).rand(10000,99999).rand(10000,99999).$resquery["ApplicantMasterID"].rand(10000,99999));
                        
                            
?>

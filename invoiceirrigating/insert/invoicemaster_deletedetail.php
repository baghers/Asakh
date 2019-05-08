<?php

/*

insert/invoicemaster_deletedetail.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/invoicemaster_list.php
*/

include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');

if ($login_RolesID==17 || $login_RolesID==10)//ناظی مقیم یا مشاور ناظر
    $login_DesignerCoID='67';


if ($login_Permission_granted==0) header("Location: ../login.php");

    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ids);
    $type=$linearray[0];

//echo $type."sa";exit;
if ($type=="im")    
{
    $mID=$linearray[1];
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
    WHERE InvoiceMasterID ='$mID'  $condition1 ;";
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
    //invoicedetail ریز لوازم
    $query = " DELETE FROM invoicedetail WHERE InvoiceMasterID = '$resquery[InvoiceMasterID]';";
    try 
    {		
        mysql_query($query);
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }    
    header("Location: "."invoicemaster_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
    rand(10000,99999).rand(10000,99999).rand(10000,99999).$resquery["ApplicantMasterID"].rand(10000,99999));
    
}
else if ($type=="man")  
{

    $mID=$linearray[1];
    $ApplicantMasterID=$mID;
    $fehrestsmasterID=$linearray[2];
    $fehrestsfaslsID=$linearray[3];
    $appfoundationID=$linearray[4];
    $T=$linearray[5];
    
    if ($appfoundationID<>0)    
    $sql = "delete FROM manuallistprice
     where manuallistprice.ApplicantMasterID = '".$ApplicantMasterID."' 
     and manuallistprice.appfoundationID='$appfoundationID'
     and fehrestsfaslsID='".$fehrestsfaslsID."'
     " ;
    else 
    $sql = "delete FROM manuallistprice 
    where ApplicantMasterID = '".$ApplicantMasterID."' 
    and fehrestsfaslsID='".$fehrestsfaslsID."'
    and ifnull(manuallistprice.appfoundationID,0)=0 " ;
    
    echo $sql;
    try 
    {		
        mysql_query($sql);
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }    
    header("Location: "."manualcostlist_pluscostlist_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID
                    ."_".$fehrestsmasterID."_".$T."_".$appfoundationID.rand(10000,99999));
    
}
else if ($type=="mana")  
{
    $mID=$linearray[1];
    $ApplicantMasterID=$mID;
    $fehrestsmasterID=$linearray[2];
    $fehrestsfaslsID=$linearray[3];
    $appfoundationID=$linearray[4];
    $T=$linearray[5];
 
    
    if ($appfoundationID<>0)    
    $sql = "delete FROM manuallistpriceall
     where manuallistpriceall.ApplicantMasterID = '".$ApplicantMasterID."' 
     and manuallistpriceall.appfoundationID='$appfoundationID'
     and fehrestsID in (select fehrestsID from fehrests 
     inner join fehrestsfasls on substring(fehrests.Code,1,2)=fehrestsfasls.fasl and fehrestsfasls.fehrestsfaslsID = '$fehrestsfaslsID'
     where fehrests.fehrestsmasterID='$fehrestsmasterID')
     
     " ;
    else 
    $sql = "delete FROM manuallistpriceall 
    where ApplicantMasterID = '".$ApplicantMasterID."' 
    and ifnull(manuallistpriceall.appfoundationID,0)=0 
    and fehrestsID in (select fehrestsID from fehrests 
     inner join fehrestsfasls on substring(fehrests.Code,1,2)=fehrestsfasls.fasl and fehrestsfasls.fehrestsfaslsID = '$fehrestsfaslsID'
     where fehrests.fehrestsmasterID='$fehrestsmasterID')" ;
    
    //echo $sql;exit;
    try 
    {		
        mysql_query($sql);
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }    
    header("Location: "."manualcostlist_pluscostlist_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID
                    ."_".$fehrestsmasterID."_".$T."_".$appfoundationID.rand(10000,99999));
    
}
                        
                            
?>

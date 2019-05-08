<?php

/*

insert/invoicemaster_deletedetail.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/invoicemaster_list.php
*/

include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');

if ($login_Permission_granted==0) header("Location: ../login.php");

    $ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ids);
    $ApplicantMasterID=$linearray[0];

    $query = " DELETE FROM invoicedetail WHERE InvoiceMasterID in 
    (select InvoiceMasterID from invoicemaster where ifnull(proposable,0)<>1 and ApplicantMasterID='$ApplicantMasterID');";
    try 
    {		
        mysql_query($query);
    }
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
    }      
    $query = " DELETE FROM invoicemaster where ifnull(proposable,0)<>1 and ApplicantMasterID='$ApplicantMasterID';";
    try 
    {		
        mysql_query($query);
    }
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
    }     
    $query = " DELETE FROM manuallistprice where ApplicantMasterID='$ApplicantMasterID';";
    try 
    {		
        mysql_query($query);
    }
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
    }     
    $query = " DELETE FROM manuallistpriceall where ApplicantMasterID='$ApplicantMasterID';";
    try 
    {		
        mysql_query($query);
    }
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
    }     
    header("Location: applicant_list.php");
    

                        
                            
?>

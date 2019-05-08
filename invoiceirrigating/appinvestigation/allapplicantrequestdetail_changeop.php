<?php
    /*
    appinvestigation/allapplicantrequestdetail_changeop.php
    
    فرم هایی که این صفحه داخل آنها فراخوانی می شود
    
    appinvestigation/allapplicantrequestp.php
    */
    include('../includes/connect.php');
    include('../includes/check_user.php');
    include('../includes/elements.php');

    if ($login_Permission_granted==0) header("Location: ../login.php");
    $ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
	$linearray = explode('_',$ID);
    $ApplicantMasterID1=$linearray[0];
    $ApplicantMasterID2=$linearray[1];
    /*
    producerapprequest جدول پیشنهاد قیمت اجرا
    ApplicantMasterID شناسه جدول پیشنهاد قیمت
    SaveTime زمان
    ClerkID کاربر
    */
    $query = " update producerapprequest set ApplicantMasterID='$ApplicantMasterID2',SaveTime = '" . date('Y-m-d H:i:s') . "', 
                ClerkID = '" . $login_userid . "' WHERE ApplicantMasterID='$ApplicantMasterID1' ;";
    
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
    applicantmaster جدول مشخصات طرح
    SaveTime زمان
    SaveDate تاریخ
    ClerkID کاربر
    proposestatep وضعیت پیشنهاد قیمت لوله
    ApplicantMasterID شناسه جدول پیشنهاد قیمت
    */        
    $query = " update applicantmaster set 
    SaveTime = '" . date('Y-m-d H:i:s') . "', 
    SaveDate = '" . date('Y-m-d') . "', 
    ClerkID = '" . $login_userid . "',
    proposestatep=(select max(proposestatep) proposestatep from (select proposestatep from applicantmaster where ApplicantMasterID='$ApplicantMasterID1')
    view1) WHERE ApplicantMasterID='$ApplicantMasterID2' ;";  
    
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
    invoicemaster جدول عناوین پیش فاکتورها
    ApplicantMasterID شناسه جدول پیشنهاد قیمت
    proposable پیش فاکتور در پیشنهاد قیمت رفته یا خیر
    SaveTime زمان
    SaveDate تاریخ
    ClerkID کاربر
    */     
    $query = " update invoicemaster set ApplicantMasterID='$ApplicantMasterID2',SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "' WHERE ApplicantMasterID='$ApplicantMasterID1' and proposable=1 ;";
    try 
        {		
            mysql_query($query);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
        } 
    header("Location: ".$_SERVER['HTTP_REFERER']); 
    
    
                            
?>

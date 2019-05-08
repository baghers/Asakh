<?php
/*
appinvestigation/allapplicantrequestdetail_changeop.php
    
فرم هایی که این صفحه داخل آنها فراخوانی می شود
    
appinvestigation/allapplicantrequestp.php
*/
include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');
    /*
    $_SERVER['HTTP_REFERER'] آدرس یو آر الی که به این صفحه ارجاع شده است
    */
    if (strlen(strstr($_SERVER['HTTP_REFERER'],'allapplicantrequestdetail.php'))>0)// در صورتی که از این صفحه وارد شده باشد allapplicantrequestdetail.php 
    {
        if ($login_Permission_granted==0) header("Location: ../login.php");
        $operatorapprequestID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
        /*
        applicantmaster جدول مشخصات طرح    
        SaveTime زمان
        SaveDate تاریخ
        ClerkID کاربر
        proposestate وضعیت پیشنهاد قیمت اجرا
        applicantmasterid شناسه طرح
        operatorapprequest جدول پیشنهاد قیمت
        
        */
        $query = " update applicantmaster set 
        SaveTime = '" . date('Y-m-d H:i:s') . "', 
        SaveDate = '" . date('Y-m-d') . "', 
        ClerkID = '" . $login_userid . "',
        proposestate=0  WHERE applicantmasterid in (
        select applicantmasterid from operatorapprequest WHERE operatorapprequestID='$operatorapprequestID')
        and (select count(*) cnt from operatorapprequest WHERE applicantmasterid in 
        (select applicantmasterid from operatorapprequest WHERE operatorapprequestID='$operatorapprequestID') )=1  ;";
             
        try 
        {		
            mysql_query($query);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
        }         
        //tbl_log جدول لاگ ها
        //در این جدول تغییرات مقادیر اطلاعات جداول مرتبط با طرح ذخیره می شود که دارای ستون های زیر می باشد
        //applicantmaster_logID شاسه جدول لاگ
        //tName نام جدولی که تغییر در آن رخ داده است
        //tID شناسه رکورد از جدولی که تغییر در آن رخ داده است
        //colname ستونی که تغییر در آن رخ داده است
        //oldval مقدار قبل از تغییر
        //newval مقدار بعد از تغییر
        //SaveDate تاریخ ثبت این تغییر
        //SaveTime ساعت ثبت این تغییر
        //ClerkID شناسه کاربری که این تغییر را داده است

        $query = " insert into tbl_log (tName,tID,colname,oldval,newval,SaveDate,SaveTime,ClerkID) 
        values('operatorapprequest','$operatorapprequestID','operatorapprequestID','$_SERVER[REMOTE_ADDR]',0,'" . 
        date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "','$login_userid';";
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
        operatorapprequest جدول پیشنهاد قیمت
        operatorapprequestID شناسه جدول پیشنهاد قیمت
        */
        $query = " delete from operatorapprequest WHERE operatorapprequestID='$operatorapprequestID' ;";
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
    }
    else if (strlen(strstr($_SERVER['HTTP_REFERER'],'allapplicantrequestdetail2.php'))>0)// در صورتی که از این صفحه وارد شده باشد allapplicantrequestdetail2.php
    {
        $producerapprequestID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
        array_map('unlink', glob("../../upfolder/proposep/$producerapprequestID"."_*.*"));//حذف فایل مربوطه
        
        /*
        producerapprequest جدول پیشنهاد قیمت اجرا
        ApplicantMasterID شناسه جدول پیشنهاد قیمت
        applicantmaster جدول مشخصات طرح 
        producerapprequestID شناسه جدول پیشنهاد قیمت
        proposestatep وضعیت پیشنهاد قیمت لوله
        SaveTime زمان
        SaveDate تاریخ
        ClerkID کاربر
        */
        $query = " update applicantmaster set 
        SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
        proposestatep=0  WHERE applicantmasterid in (
        select applicantmasterid from producerapprequest WHERE producerapprequestID='$producerapprequestID')
        and (select count(*) cnt from producerapprequest WHERE applicantmasterid in 
        (select applicantmasterid from producerapprequest WHERE producerapprequestID='$producerapprequestID') )=1 ;";
        try 
        {		
            mysql_query($query);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
        }   
         
        //tbl_log جدول لاگ ها
        //در این جدول تغییرات مقادیر اطلاعات جداول مرتبط با طرح ذخیره می شود که دارای ستون های زیر می باشد
        //applicantmaster_logID شاسه جدول لاگ
        //tName نام جدولی که تغییر در آن رخ داده است
        //tID شناسه رکورد از جدولی که تغییر در آن رخ داده است
        //colname ستونی که تغییر در آن رخ داده است
        //oldval مقدار قبل از تغییر
        //newval مقدار بعد از تغییر
        //SaveDate تاریخ ثبت این تغییر
        //SaveTime ساعت ثبت این تغییر
        //ClerkID شناسه کاربری که این تغییر را داده است        
        $query = " insert into tbl_log (tName,tID,colname,oldval,newval,SaveDate,SaveTime,ClerkID)
        values('producerapprequest','$producerapprequestID','producerapprequestID','$_SERVER[REMOTE_ADDR]',0,'" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "','$login_userid';";
        
        try 
        {		
            mysql_query($query);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
        }           
        $query = " delete from producerapprequest WHERE producerapprequestID='$producerapprequestID' ;";
         
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
    }
    else if (strlen(strstr($_SERVER['HTTP_REFERER'],'apprequestp.php'))>0)// در صورتی که از این صفحه وارد شده باشد apprequestp.php
    {
        $producerapprequestID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
        array_map('unlink', glob("../../upfolder/proposep/$producerapprequestID"."_*.*"));//حذف فایل پیشنهادی
        /*
        producerapprequest جدول پیشنهاد قیمت اجرا
        ApplicantMasterID شناسه جدول پیشنهاد قیمت
        applicantmaster جدول مشخصات طرح 
        producerapprequestID شناسه جدول پیشنهاد قیمت
        proposestatep وضعیت پیشنهاد قیمت لوله
        SaveTime زمان
        SaveDate تاریخ
        ClerkID کاربر
        */        
        $query = " update applicantmaster set 
        SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
        proposestatep=0  WHERE applicantmasterid in (
        select applicantmasterid from producerapprequest WHERE producerapprequestID='$producerapprequestID')
        and (select count(*) cnt from producerapprequest WHERE applicantmasterid in 
        (select applicantmasterid from producerapprequest WHERE producerapprequestID='$producerapprequestID') )=1 ;";
        try 
        {		
            mysql_query($query);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
        } 
         
        //tbl_log جدول لاگ ها
        //در این جدول تغییرات مقادیر اطلاعات جداول مرتبط با طرح ذخیره می شود که دارای ستون های زیر می باشد
        //applicantmaster_logID شاسه جدول لاگ
        //tName نام جدولی که تغییر در آن رخ داده است
        //tID شناسه رکورد از جدولی که تغییر در آن رخ داده است
        //colname ستونی که تغییر در آن رخ داده است
        //oldval مقدار قبل از تغییر
        //newval مقدار بعد از تغییر
        //SaveDate تاریخ ثبت این تغییر
        //SaveTime ساعت ثبت این تغییر
        //ClerkID شناسه کاربری که این تغییر را داده است         
        $query = " insert into tbl_log (tName,tID,colname,oldval,newval,SaveDate,SaveTime,ClerkID)
        values('producerapprequest','$producerapprequestID','producerapprequestID','$_SERVER[REMOTE_ADDR]',0,'" . date('Y-m-d') . "','" . date('Y-m-d H:i:s') . "','$login_userid';";
        
        try 
        {		
            mysql_query($query);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
        }     
        $query = " delete from producerapprequest WHERE producerapprequestID='$producerapprequestID' ;";
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
    }
    
    
                            
?>

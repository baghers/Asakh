<?php
/*
appinvestigation/allapplicantrequestdetail_extend.php
    
فرم هایی که این صفحه داخل آنها فراخوانی می شود

appinvestigation/allapplicantrequestp.php
*/
include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');

    if (strlen(strstr($_SERVER['HTTP_REFERER'],'apprequestp.php'))>0)// در صورتی که از این صفحه وارد شده باشد apprequestp.php
    {
        $producerapprequestID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);//شناسه پیشنهاد قیمت
        /*
        producerapprequest جدول پیشنهاد قیمت اجرا
        validday مدت اعتبار پیشنهاد قیمت
        producerapprequestID شناسه جدول پیشنهاد قیمت
        */ 
        $query = "update producerapprequest set validday=validday+3 WHERE producerapprequestID='$producerapprequestID' ;";
        $result = mysql_query($query); 
        header("Location: ".$_SERVER['HTTP_REFERER']);
    }
    if (strlen(strstr($_SERVER['HTTP_REFERER'],'allapplicantrequestp.php'))>0)// در صورتی که از این صفحه وارد شده باشد allapplicantrequestp.php
    {
        $ApplicantMasterID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);//شناسه طرح
        /*
        applicantmaster جدول مشخصات طرح    
        SaveTime زمان
        SaveDate تاریخ
        ClerkID کاربر
        applicantmasterid شناسه طرح
        surveyDate مساحت نقشه برداری
        */
        $query = "update applicantmaster set 
        SaveTime = '" . date('Y-m-d H:i:s') . "', 
        SaveDate = '" . date('Y-m-d') . "', 
        ClerkID = '" . $login_userid . "',
        surveyDate='' where ApplicantMasterID='$ApplicantMasterID' ;";
        $result = mysql_query($query); 
        header("Location: ".$_SERVER['HTTP_REFERER']);
    }    
    
                            
?>

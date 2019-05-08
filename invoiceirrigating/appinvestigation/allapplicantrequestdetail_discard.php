<?php
/*
appinvestigation/allapplicantrequestdetail_discard.php
    
فرم هایی که این صفحه داخل آنها فراخوانی می شود
    
appinvestigation/allapplicantrequestdetail.php
*/
include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');

if (strlen(strstr($_SERVER['HTTP_REFERER'],'allapplicantrequestdetail.php'))>0)// در صورتی که از این صفحه وارد شده باشد allapplicantrequestdetail.php
{
    if ($login_Permission_granted==0) header("Location: ../login.php");
    $ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
	$linearray = explode('_',$ID);
    $ApplicantMasterID=$linearray[0];//شناسه طرح
    $DesignArea=$linearray[1];//مساحت طرح
	$smallapplicantsize=$linearray[2];//اندازه مساحت های کوچک
	$operatorcoID=$linearray[3];//شناسه پیمانکار
	$login_ostanId=$linearray[4];//شناسه استان
    /*
    operatorapprequest جدول پیشنهاد قیمت
    state وضعیت انتخاب شدن
    ordering ترتیب
    errors ترتیب در پیشنهاد قیمت
    Windate تاریخ انتخاب مجری
    ApplicantMasterID شناسه طرح
    applicantmaster جدول مشخصات طرح
    SaveTime زمان
    SaveDate تاریخ
    ClerkID کاربر
    proposestate وضعیت پیشنهاد قیمت
    */	
    $query = " update operatorapprequest set state=0,ordering=0,errors='',Windate='' WHERE ApplicantMasterID='$ApplicantMasterID' ;";
    $result = mysql_query($query);    
    $query = " update applicantmaster set 
    SaveTime = '" . date('Y-m-d H:i:s') . "', 
    SaveDate = '" . date('Y-m-d') . "', 
    ClerkID = '" . $login_userid . "',
    proposestate=proposestate-1 WHERE ApplicantMasterID='$ApplicantMasterID' ;";  
    $result = mysql_query($query);
    freeproject($DesignArea,$operatorcoID,2,$login_ostanId);//تابع آزادسازی پروژه
    header("Location: ".$_SERVER['HTTP_REFERER']);    
}
else if (strlen(strstr($_SERVER['HTTP_REFERER'],'allapplicantrequestdetail2.php'))>0)// در صورتی که از این صفحه وارد شده باشد allapplicantrequestdetail2.php
{
    if ($login_Permission_granted==0) header("Location: ../login.php");
    $ApplicantMasterID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);//شناسه طرح
    /*
        producerapprequest جدول پیشنهاد قیمت اجرا
        ApplicantMasterID شناسه جدول پیشنهاد قیمت
        applicantmaster جدول مشخصات طرح 
        producerapprequestID شناسه جدول پیشنهاد قیمت
        proposestatep وضعیت پیشنهاد قیمت لوله
        state وضعیت انتخاب شدن
        ordering ترتیب
        errors ترتیب در پیشنهاد قیمت
        Windate تاریخ انتخاب مجری
        SaveTime زمان
        SaveDate تاریخ
        ClerkID کاربر
    */   
    $query = " update producerapprequest set state=0,ordering=0,errors='',Windate='' WHERE ApplicantMasterID='$ApplicantMasterID' ;";
    $result = mysql_query($query);    
    $query = " update applicantmaster set 
    SaveTime = '" . date('Y-m-d H:i:s') . "', 
    SaveDate = '" . date('Y-m-d') . "', 
    ClerkID = '" . $login_userid . "',
    proposestatep=proposestatep-1 WHERE ApplicantMasterID='$ApplicantMasterID' ;";        
    $result = mysql_query($query);
    header("Location: ".$_SERVER['HTTP_REFERER']);    
}

    
                            
?>

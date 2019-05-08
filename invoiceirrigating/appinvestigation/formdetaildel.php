<?php
/*
//insert/applicant_delete.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
insert/applicant_list.php
*/
//اتصال به دیتا بیس
require_once('../includes/connect.php'); 
// بررسی لاگین شده یا نه 
//از روی سیشن به متغیرها انتقال می دهد
//مثل 
//$login_RolesID
require_once('../includes/check_user.php'); 
// توابع مرتبط با المنت های اچ تی امال صفحات 
require_once('../includes/elements.php');



if ($login_Permission_granted==0) header("Location: ../login.php");//بررسی مجوز ورود به صفحه

    $id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);//استخراج شناسه  از متغیر گت

$query = " DELETE FROM form3detail WHERE form3detailid = '$id';";
    mysql_query($query);
    

    header("Location: $_SERVER[HTTP_REFERER]");
    
                            
?>

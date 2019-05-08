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

    $id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);//استخراج شناسه طرح از متغیر گت

/*
$login_RolesID 19 شناسه مدیریت پرونده ها
$login_RolesID 24 نقشه بردار
$login_RolesID=17 ناظر مقیم
$login_RolesID=1 مدیر پیگیری
در صورتی که نقش های مدیریتی فوق نبودند محدودیت زیر که فیلتر کردن نام شرکت می باشد اعمال شود
*/
if ($login_RolesID!=19 && $login_RolesID!=24  && $login_RolesID!=17 && $login_RolesID!=1)
{ 
    if ($login_DesignerCoID>0) $condition1=" and DesignerCoID='$login_DesignerCoID'";//محدود کردن شناسه شرکت طراح DesignerCoID
        else $condition1=" and operatorcoid='$login_OperatorCoID' and ifnull(operatorcoid,0)<>0 ";//محدود کردن شناسه شرکت پیمانکار operatorcoid
   
}

    
//پرس و جوی اینکه آیا چنین شناسه طرحی وجود دارد و اینکه آیا شرکت های طراح یا پیمانکار مالک آن طرح هستند که طرح را حذف نمایند یا خیر
$query = "SELECT ApplicantMasterID FROM applicantmaster WHERE ApplicantMasterID ='$id'  $condition1 ;";
try 
{		
    $result = mysql_query($query);
    $resquery = mysql_fetch_assoc($result);
}
//catch exception
catch(Exception $e) 
{
    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    exit;
}

if (!$resquery["ApplicantMasterID"]) header("Location: ../logout.php");//درصورتی که کاربر مالک طرح نبوده و یا چنین شناسه ای یافت نشود
    
    
    ///////////////بررسی گردش در سایر جداول
    $deletefromtable="applicantmaster";//نام جدول
    $deletefromtablefield="ApplicantMasterID";//نام ستون کلید اصلی
    $deletefromtablefieldvalue=$id;//شناسه طرح
    $hascirculation="";
    //پرس و جوی استخراج نام جداولی که شناسه طرح کلید خارجی آن جداول می باشد
    $query = " SELECT DISTINCT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE upper(COLUMN_NAME) like '%".
    strtoupper($deletefromtablefield)."%' AND TABLE_SCHEMA = '$_server_db';";
    try 
    {		
        $result = mysql_query($query);
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        exit;
    }

    
    
    while($row = mysql_fetch_assoc($result))//پیمایش جداولی که کلید خارجی آنها شناسه طرح می باشد
    {
        //در صورتی که در جداول زیر گردش داشته باشد گردش آنها قابل حذف است
        //appchangestate جدول تغییر وضعیت ها
        //designsystemgroupsdetail جدول سیستم های آبیاری
        //applicantsystemtype جدول الگوی کشت
        //applicantmasterdetail جدول ارتباطی طرح ها
        if ($row['TABLE_NAME']=='designsystemgroupsdetail' || $row['TABLE_NAME']=='applicantsystemtype' || $row['TABLE_NAME']=='appchangestate'
         || $row['TABLE_NAME']=='applicantmasterdetail')
            continue;
        if($row['TABLE_NAME']<>$deletefromtable)//در صورتی که جدول همان جدول مشخصات طرح نباشد میزان گردش آن از طریق پرس و جوی زیر بررسی می شود
        {
            $queryin = " SELECT count( * ) cnt FROM $row[TABLE_NAME] WHERE $deletefromtablefield =$deletefromtablefieldvalue";
            try 
            {		
                $resultin = mysql_query($queryin);
                $rowin = mysql_fetch_assoc($resultin);
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                exit;
            }       
            if ($rowin['cnt']>0)//وجود گردش
            $hascirculation.=$row['TABLE_NAME'].' ';//افزودن به نام جداول گردش دار
        }
        
    }
    if (strlen($hascirculation)>0) 
    {
	 	print " این مقدار در جداول زیر گردش دارد ".$hascirculation;
	 	exit();
    }
    
    $querytr=" update applicantmasterdetail set ApplicantMasterIDmaster=0  WHERE ApplicantMasterIDmaster ='$resquery[ApplicantMasterID]';";
    $querytr.=" update applicantmasterdetail set ApplicantMasterIDsurat=0  WHERE ApplicantMasterIDsurat ='$resquery[ApplicantMasterID]';";
    $querytr.=" delete from applicantmasterdetail WHERE ApplicantMasterID ='$resquery[ApplicantMasterID]';";
    $querytr.= " delete from appchangestate WHERE ApplicantMasterID ='$resquery[ApplicantMasterID]' ;";
    $querytr.= " delete from applicantsystemtype WHERE ApplicantMasterID ='$resquery[ApplicantMasterID]' ;";
    $querytr.= " delete from designsystemgroupsdetail WHERE ApplicantMasterID ='$resquery[ApplicantMasterID]' ;";
    $querytr.= " delete from applicantmaster WHERE ApplicantMasterID ='$resquery[ApplicantMasterID]' ;";
    
    
   try 
        {		
           
           $coni=mysqli_connect($_server, $_server_user, $_server_pass,$_server_db);
            // Check connection
            if (mysqli_connect_errno())
            {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }
            if (!mysqli_multi_query($coni,$querytr))
            {
                print "START TRANSACTION;".$querytr.";COMMIT;";
                exit;                   
            }
            mysqli_close($coni);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            exit;
        }
        
    //print $query;
    //exit;
    header("Location: "."applicant_list.php");
    
                            
?>

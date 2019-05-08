<?php
/*

//insert/applicant_timing.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود

appinvestigation/allapplicantstatesop.php

*/

 include('../includes/connect.php'); 
 include('../includes/check_user.php'); 
 include('../includes/elements.php'); 
 

if ($login_Permission_granted==0) header("Location: ../login.php");

if ($_POST) //درصورتی که دکمه ثبت کلیک شده باشد
{
    $ApplicantTimingID = $_POST['ApplicantTimingID'];//شناسه جدول زمانبندی
    $ApplicantMasterID = $_POST['ApplicantMasterID'];//شناسه جدول طرح
    $RoleID = $_POST['RoleID'];//شناسه نقش کاربر
    if ($_POST['pathstart'] && $_POST['pathend']) 
    { 
        $pathstart = jalali_to_gregorian($_POST['pathstart']);//تاریخ شروع پياده كردن مسير  
        $pathend = jalali_to_gregorian($_POST['pathend']);//تاریخ پایان پياده كردن مسير
    } 
    else 
    { 
        $pathstart=""; 
        $pathend=""; 
    }
    //توضیحات پياده كردن مسير
    $pathdescription = $_POST['pathdescription'];
    //transportstart تاریخ شروع تهيه و حمل لوازم طرح
    //transportend تاریخ پایان تهيه و حمل لوازم طرح
    //transportdescription شرح تهيه و حمل لوازم طرح 
    if ($_POST['transportstart'] && $_POST['transportend']) { $transportstart = jalali_to_gregorian($_POST['transportstart']); $transportend = jalali_to_gregorian($_POST['transportend']); } else { $transportstart=""; $transportend=""; }
    $transportdescription = $_POST['transportdescription'];
    //drillingstart تاریخ شروع حفر تراشه لوله گذاري
    //drillingend تاریخ پایان حفر تراشه لوله گذاري
    //drillingdescription  شرح حفر تراشه لوله گذاري  
    if ($_POST['drillingstart'] && $_POST['drillingend']) { $drillingstart = jalali_to_gregorian($_POST['drillingstart']); $drillingend = jalali_to_gregorian($_POST['drillingend']); } else  { $drillingstart=""; $drillingend = ""; }
    $drillingdescription = $_POST['drillingdescription'];
    //rglazhstart تاریخ شروع رگلاژ و ريختن خاك نرم يا سرندي كف تراشه
    //rglazhend تاریخ پایان رگلاژ و ريختن خاك نرم يا سرندي كف تراشه
    //rglazhdescription شرح رگلاژ و ريختن خاك نرم يا سرندي كف تراشه
    if ($_POST['rglazhstart'] && $_POST['rglazhend']) { $rglazhstart = jalali_to_gregorian($_POST['rglazhstart']);  $rglazhend = jalali_to_gregorian($_POST['rglazhend']); } else  { $rglazhstart = ""; $rglazhend =""; }
    $rglazhdescription = $_POST['rglazhdescription'];
    //intubationstart تاریخ شروع لوله گذاري خط اصلي و فرعي و نصب اتصالات
    //intubationend تاریخ پایان لوله گذاري خط اصلي و فرعي و نصب اتصالات
    //intubationdescription  شرح لوله گذاري خط اصلي و فرعي و نصب اتصالات
    if ($_POST['intubationstart'] && $_POST['intubationend']) { $intubationstart = jalali_to_gregorian($_POST['intubationstart']);  $intubationend = jalali_to_gregorian($_POST['intubationend']); } else  { $intubationstart = ""; $intubationend =""; }
    $intubationdescription = $_POST['intubationdescription'];
    //pondstart تاریخ شروع ساختن حوضچه پمپاژ و فونداسيون
    //pondend تاریخ پایان ساختن حوضچه پمپاژ و فونداسيون
    //ponddescription  شرح ساختن حوضچه پمپاژ و فونداسيون
    if ($_POST['pondstart'] && $_POST['pondend']) { $pondstart = jalali_to_gregorian($_POST['pondstart']);  $pondend = jalali_to_gregorian($_POST['pondend']); } else  { $pondstart = ""; $pondend =""; }
    $ponddescription = $_POST['ponddescription'];
    //pumpingstationstart تاریخ شروع نصب و راه اندازي ايستگاه پمپاژ و كنترل مركزي
    //pumpingstationend تاریخ پایان نصب و راه اندازي ايستگاه پمپاژ و كنترل مركزي
    //pumpingstationdescription شرح  نصب و راه اندازي ايستگاه پمپاژ و كنترل مركزي 
    if ($_POST['pumpingstationstart'] && $_POST['pumpingstationend']) { $pumpingstationstart = jalali_to_gregorian($_POST['pumpingstationstart']);  $pumpingstationend = jalali_to_gregorian($_POST['pumpingstationend']); } else  { $pumpingstationstart = ""; $pumpingstationend =""; }
    $pumpingstationdescription = $_POST['pumpingstationdescription'];
    //soilpipestart تاریخ شروع ريختن خاك نرم يا سرندي روي لوله
    //soilpipeend تاریخ پایان ريختن خاك نرم يا سرندي روي لوله
    //soilpipedescription شرح ريختن خاك نرم يا سرندي روي لوله
    if ($_POST['soilpipestart'] && $_POST['soilpipeend']) { $soilpipestart = jalali_to_gregorian($_POST['soilpipestart']);  $soilpipeend = jalali_to_gregorian($_POST['soilpipeend']); } else  { $soilpipestart = ""; $soilpipeend =""; }
    $soilpipedescription = $_POST['soilpipedescription'];
    //networkteststart تاریخ شروع تست شبكه
    //networktestend تاریخ پایان تست شبكه
    //networktestdescription شرح تست شبكه
    if ($_POST['networkteststart'] && $_POST['networktestend']) { $networkteststart = jalali_to_gregorian($_POST['networkteststart']);  $networktestend = jalali_to_gregorian($_POST['networktestend']); } else  { $networkteststart = ""; $networktestend =""; }
    $networktestdescription = $_POST['networktestdescription'];
    //soilintrenchstart تاریخ شروع برگرداندن خاك درون تراشه
    //soilintrenchend تاریخ پایان برگرداندن خاك درون تراشه
    //soilintrenchdescription شرح برگرداندن خاك درون تراشه
    if ($_POST['soilintrenchstart'] && $_POST['soilintrenchend']) { $soilintrenchstart = jalali_to_gregorian($_POST['soilintrenchstart']);  $soilintrenchend = jalali_to_gregorian($_POST['soilintrenchend']); } else  { $soilintrenchstart = ""; $soilintrenchend =""; }
    $soilintrenchdescription = $_POST['soilintrenchdescription'];
    //dispersivestart تاریخ شروع نصب وراه اندازي و مونتاژ بالهاي آبياري و پاشنده ها و گسيلنده ها
    //dispersiveend تاریخ پایان نصب وراه اندازي و مونتاژ بالهاي آبياري و پاشنده ها و گسيلنده ها
    //dispersivedescription شرح نصب وراه اندازي و مونتاژ بالهاي آبياري و پاشنده ها و گسيلنده ها
    if ($_POST['dispersivestart'] && $_POST['dispersiveend']) { $dispersivestart = jalali_to_gregorian($_POST['dispersivestart']);  $dispersiveend = jalali_to_gregorian($_POST['dispersiveend']); } else  { $dispersivestart = ""; $dispersiveend =""; }
    $dispersivedescription = $_POST['dispersivedescription'];
    //commissionstart تاریخ شروع راه اندازي طرح
    //commissionend تاریخ پایان راه اندازي طرح
    //commissiondescription شرح راه اندازي طرح
    if ($_POST['commissionstart'] && $_POST['commissionend']) { $commissionstart = jalali_to_gregorian($_POST['commissionstart']);  $commissionend = jalali_to_gregorian($_POST['commissionend']); } else  { $commissionstart = ""; $commissionend =""; }
    $commissiondescription = $_POST['commissiondescription'];
    //statementstart تاریخ شروع تحويل صورت وضعيت
    //statementend تاریخ پایان تحويل صورت وضعيت
    //statementdescription شرح تحويل صورت وضعيت
    if ($_POST['statementstart'] && $_POST['statementend']) { $statementstart = jalali_to_gregorian($_POST['statementstart']);  $statementend = jalali_to_gregorian($_POST['statementend']); } else  { $statementstart = ""; $statementend =""; }
    $statementdescription = $_POST['statementdescription'];
    //workdeliverystart تاریخ شروع تحويل كار
    //workdeliveryend تاریخ پایان تحويل كار
    //workdeliverydescription شرح تحويل كار
    if ($_POST['workdeliverystart'] && $_POST['workdeliveryend']) { $workdeliverystart = jalali_to_gregorian($_POST['workdeliverystart']);  $workdeliveryend = jalali_to_gregorian($_POST['workdeliveryend']); } else  { $workdeliverystart = ""; $workdeliveryend =""; }
    $workdeliverydescription = $_POST['workdeliverydescription'];
    $tahvildate='';//تاریخ تویل زمین
    if($_POST['tahvildate']!='')
     $tahvildate = jalali_to_gregorian($_POST['tahvildate']);
    
    //---------------------------------------------------
    $err='';//عنوان ردیف هایی که خالی است
    $errnum=0;//تعداد ردیف هایی که خالی است
    //مدت اعلامی ناظر برای فاز 1
    $lenn1 = (strtotime($pathend) - strtotime($pathstart))/86400;//مدت دوره
    $minn1 = (strtotime($pathstart) - strtotime($pathstart))/86400;
    if ($lenn1<0 || $minn1<0) $err.='*پياده كردن مسير';$errnum=$errnum+1;
    
    if ($transportend && $transportstart){
    //مدت اعلامی ناظر برای فاز 2
    $lenn2 = ((strtotime($transportend) - strtotime($transportstart))/86400);
    $minn2 = ((strtotime($transportstart) - strtotime($pathstart))/86400);
    if ($lenn2<0 || $minn2<0) $err.='*تهيه و حمل لوازم طرح';$errnum=$errnum+1;
    }
    
    if ($drillingend && $drillingstart){
    //مدت اعلامی ناظر برای فاز 3
    $lenn3 = ((strtotime($drillingend) - strtotime($drillingstart))/86400);
    $minn3 = ((strtotime($drillingstart) - strtotime($pathstart))/86400);
    if ($lenn3<0 || $minn3<0) $err.='*حفر تراشه لوله گذاري';$errnum=$errnum+1;
    }
    
    if ($rglazhend && $rglazhstart){
    //مدت اعلامی ناظر برای فاز 4
    $lenn4 = ((strtotime($rglazhend) - strtotime($rglazhstart))/86400);
    $minn4 = ((strtotime($rglazhstart) - strtotime($pathstart))/86400);
    if ($lenn4<0 || $minn4<0) $err.='*رگلاژ و ريختن خاك نرم يا سرندي كف تراشه';$errnum=$errnum+1;
    }
    
    if ($intubationend && $intubationstart){
    //مدت اعلامی ناظر برای فاز 5
    $lenn5 = ((strtotime($intubationend) - strtotime($intubationstart))/86400);
    $minn5 = ((strtotime($intubationstart) - strtotime($pathstart))/86400);
    if ($lenn5<0 || $minn5<0) $err.='*لوله گذاري خط اصلي و فرعي و نصب اتصالات';$errnum=$errnum+1;
    }
    
    if ($pondend && $pondstart){
    //مدت اعلامی ناظر برای فاز 6
    $lenn6 = ((strtotime($pondend) - strtotime($pondstart))/86400);
    $minn6 = ((strtotime($pondstart) - strtotime($pathstart))/86400);
    if ($lenn6<0 || $minn6<0) $err.='*ساختن حوضچه پمپاژ و فونداسيون';$errnum=$errnum+1;
    }
    
    if ($pumpingstationend && $pumpingstationstart){
    //مدت اعلامی ناظر برای فاز 7
    $lenn7 = ((strtotime($pumpingstationend) - strtotime($pumpingstationstart))/86400);
    $minn7 = ((strtotime($pumpingstationstart) - strtotime($pathstart))/86400);
    if ($lenn7<0 || $minn7<0) $err.='*نصب و راه اندازي ايستگاه پمپاژ و كنترل مركزي';$errnum=$errnum+1;
    }
    
    if ($soilpipeend && $soilpipestart){
    //مدت اعلامی ناظر برای فاز 8
    $lenn8 = ((strtotime($soilpipeend) - strtotime($soilpipestart))/86400);
    $minn8 = ((strtotime($soilpipestart) - strtotime($pathstart))/86400);
    if ($lenn8<0 || $minn8<0) $err.='*ريختن خاك نرم يا سرندي روي لوله';$errnum=$errnum+1;
    }
    
    if ($networktestend && $networkteststart){
    //مدت اعلامی ناظر برای فاز 9
    $lenn9 = ((strtotime($networktestend) - strtotime($networkteststart))/86400);
    $minn9 = ((strtotime($networkteststart) - strtotime($pathstart))/86400);
    if ($lenn9<0 || $minn9<0) $err.='*تست شبكه';$errnum=$errnum+1;
    }
    
    if ($soilintrenchend && $soilintrenchstart){
    //مدت اعلامی ناظر برای فاز 10
    $lenn10 = ((strtotime($soilintrenchend) - strtotime($soilintrenchstart))/86400);
    $minn10 = ((strtotime($soilintrenchstart) - strtotime($pathstart))/86400);
    if ($lenn10<0 || $minn10<0) $err.='*برگرداندن خاك درون تراشه';$errnum=$errnum+1;
    }
    
    if ($dispersiveend && $dispersivestart){
    //مدت اعلامی ناظر برای فاز 11
    $lenn11 = ((strtotime($dispersiveend) - strtotime($dispersivestart))/86400);
    $minn11 = ((strtotime($dispersivestart) - strtotime($pathstart))/86400);
    if ($lenn11<0 || $minn11<0) $err.='*نصب وراه اندازي و مونتاژ بالهاي آبياري و پاشنده ها و گسيلنده ها';$errnum=$errnum+1;
    }
    
    if ($commissionend && $commissionstart){
    //مدت اعلامی ناظر برای فاز 12
    $lenn12 = ((strtotime($commissionend) - strtotime($commissionstart))/86400);
    $minn12 = ((strtotime($commissionstart) - strtotime($pathstart))/86400);
    if ($lenn12<0 || $minn12<0) $err.='*راه اندازي طرح';$errnum=$errnum+1;
    }
    
    if ($statementend && $statementstart){
    //مدت اعلامی ناظر برای فاز 13
    $lenn13 = ((strtotime($statementend) - strtotime($statementstart))/86400);
    $minn13 = ((strtotime($statementstart) - strtotime($pathstart))/86400);
    if ($lenn13<0 || $minn13<0) $err.='*تحويل صورت وضعيت';$errnum=$errnum+1;
    }
    
    if ($workdeliveryend && $workdeliverystart){
    //مدت اعلامی ناظر برای فاز 14
    $lenn14 = ((strtotime($workdeliveryend) - strtotime($workdeliverystart))/86400);
    $minn14 = ((strtotime($workdeliverystart) - strtotime($pathstart))/86400);
    if ($lenn14<0 || $minn14<0) $err.='*تحويل كار';$errnum=$errnum+1;
    }
    
    if ($err) {$err=$errnum.' اخطار!! تاریخ '.$err.' صحیح وارد نشده است!';}
    
    
    //---------------------------------------------------
    $percentsum=0;$mpercentsum=0;$num=0;
    $percent1 =$_POST['percent1'];if (($percent1)>0) {$percentsum=$percentsum+$percent1;$num++;}//percent1 درصد پیشرفت فاز 1
    $percent2 =$_POST['percent2'];if (($percent2)>0) {$percentsum=$percentsum+$percent2;$num++;}//percent2 درصد پیشرفت فاز 2
    $percent3 =$_POST['percent3'];if (($percent3)>0) {$percentsum=$percentsum+$percent3;$num++;}//percent3 درصد پیشرفت فاز 3
    $percent4 =$_POST['percent4'];if (($percent4)>0) {$percentsum=$percentsum+$percent4;$num++;}//percent4 درصد پیشرفت فاز 4
    $percent5 =$_POST['percent5'];if (($percent5)>0) {$percentsum=$percentsum+$percent5;$num++;}//percent5 درصد پیشرفت فاز 5
    $percent6 =$_POST['percent6'];if (($percent6)>0) {$percentsum=$percentsum+$percent6;$num++;}//percent6 درصد پیشرفت فاز 6
    $percent7 =$_POST['percent7'];if (($percent7)>0) {$percentsum=$percentsum+$percent7;$num++;}//percent7 درصد پیشرفت فاز 7
    $percent8 =$_POST['percent8'];if (($percent8)>0) {$percentsum=$percentsum+$percent8;$num++;}//percent8 درصد پیشرفت فاز  8
    $percent9 =$_POST['percent9'];if (($percent9)>0) {$percentsum=$percentsum+$percent9;$num++;}//percent9 درصد پیشرفت فاز 9
    $percent10 =$_POST['percent10'];if (($percent10)>0) {$percentsum=$percentsum+$percent10;$num++;}//percent10 درصد پیشرفت فاز 10
    $percent11 =$_POST['percent11'];if (($percent11)>0) {$percentsum=$percentsum+$percent11;$num++;}//percent11 درصد پیشرفت فاز 11
    $percent12 =$_POST['percent12'];if (($percent12)>0) {$percentsum=$percentsum+$percent12;$num++;}//percent12 درصد پیشرفت فاز 12
    $percent13 =$_POST['percent13'];if (($percent13)>0) {$percentsum=$percentsum+$percent13;$num++;}//percent13 درصد پیشرفت فاز 13
    $percent14 =$_POST['percent14'];if (($percent14)>0) {$percentsum=$percentsum+$percent14;$num++;}//percent14 درصد پیشرفت فاز 14
    if ($num>0) $percentsum =$percentsum/$num;//پیشرفت کار
    //---------------------------------------------------
    
    //دورقمی نمودن پیشرفت مراحل
    if($_POST['percent1']=='')$percent1 ='0'; else $percent1 =$_POST['percent1'];
    if($_POST['percent2']=='')$percent2 ='0'; else $percent2 =$_POST['percent2'];
    if($_POST['percent3']=='')$percent3 ='0'; else $percent3 =$_POST['percent3'];
    if($_POST['percent4']=='')$percent4 ='0'; else $percent4 =$_POST['percent4'];
    if($_POST['percent5']=='')$percent5 ='0'; else $percent5 =$_POST['percent5'];
    if($_POST['percent6']=='')$percent6 ='0'; else $percent6 =$_POST['percent6'];
    if($_POST['percent7']=='')$percent7 ='0'; else $percent7 =$_POST['percent7'];
    if($_POST['percent8']=='')$percent8 ='0'; else $percent8 =$_POST['percent8'];
    if($_POST['percent9']=='')$percent9 ='0'; else $percent9 =$_POST['percent9'];
    if($_POST['percent10']=='')$percent10 ='0'; else $percent10 =$_POST['percent10'];
    if($_POST['percent11']=='')$percent11 ='0'; else $percent11 =$_POST['percent11'];
    if($_POST['percent12']=='')$percent12 ='0'; else $percent12 =$_POST['percent12'];
    if($_POST['percent13']=='')$percent13 ='0'; else $percent13 =$_POST['percent13'];
    if($_POST['percent14']=='')$percent14 ='0'; else $percent14 =$_POST['percent14'];
    
    
    if ($login_RolesID==1) //نقش مدیر پیگیری
        $RoleID=10;//نقش مشاور ناظر 
            else $RoleID=$login_RolesID;//شناسه نقش کاربر لاگین شده
    if ($ApplicantTimingID>0) //شناسه جدول زمانبندی
    /*
    applicanttiming جدول ثبت زمانبندی
    ApplicantMasterID شناسه جدول طرح
    pathstart تاریخ شروع پياده كردن مسير  
    pathend تاریخ پایان پياده كردن مسير
    pathdescription توضیحات پياده كردن مسير
    transportstart تاریخ شروع تهيه و حمل لوازم طرح
    transportend تاریخ پایان تهيه و حمل لوازم طرح
    transportdescription شرح تهيه و حمل لوازم طرح 
    drillingstart تاریخ شروع حفر تراشه لوله گذاري
    drillingend تاریخ پایان حفر تراشه لوله گذاري
    drillingdescription  شرح حفر تراشه لوله گذاري  
    rglazhstart تاریخ شروع رگلاژ و ريختن خاك نرم يا سرندي كف تراشه
    rglazhend تاریخ پایان رگلاژ و ريختن خاك نرم يا سرندي كف تراشه
    rglazhdescription شرح رگلاژ و ريختن خاك نرم يا سرندي كف تراشه
    intubationstart تاریخ شروع لوله گذاري خط اصلي و فرعي و نصب اتصالات
    intubationend تاریخ پایان لوله گذاري خط اصلي و فرعي و نصب اتصالات
    intubationdescription  شرح لوله گذاري خط اصلي و فرعي و نصب اتصالات
    pondstart تاریخ شروع ساختن حوضچه پمپاژ و فونداسيون
    pondend تاریخ پایان ساختن حوضچه پمپاژ و فونداسيون
    ponddescription  شرح ساختن حوضچه پمپاژ و فونداسيون
    pumpingstationstart تاریخ شروع نصب و راه اندازي ايستگاه پمپاژ و كنترل مركزي
    pumpingstationend تاریخ پایان نصب و راه اندازي ايستگاه پمپاژ و كنترل مركزي
    pumpingstationdescription شرح  نصب و راه اندازي ايستگاه پمپاژ و كنترل مركزي 
    soilpipestart تاریخ شروع ريختن خاك نرم يا سرندي روي لوله
    soilpipeend تاریخ پایان ريختن خاك نرم يا سرندي روي لوله
    soilpipedescription شرح ريختن خاك نرم يا سرندي روي لوله
    networkteststart تاریخ شروع تست شبكه
    networktestend تاریخ پایان تست شبكه
    networktestdescription شرح تست شبكه
    soilintrenchstart تاریخ شروع برگرداندن خاك درون تراشه
    soilintrenchend تاریخ پایان برگرداندن خاك درون تراشه
    soilintrenchdescription شرح برگرداندن خاك درون تراشه
    dispersivestart تاریخ شروع نصب وراه اندازي و مونتاژ بالهاي آبياري و پاشنده ها و گسيلنده ها
    dispersiveend تاریخ پایان نصب وراه اندازي و مونتاژ بالهاي آبياري و پاشنده ها و گسيلنده ها
    dispersivedescription شرح نصب وراه اندازي و مونتاژ بالهاي آبياري و پاشنده ها و گسيلنده ها
    commissionstart تاریخ شروع راه اندازي طرح
    commissionend تاریخ پایان راه اندازي طرح
    commissiondescription شرح راه اندازي طرح
    statementstart تاریخ شروع تحويل صورت وضعيت
    statementend تاریخ پایان تحويل صورت وضعيت
    statementdescription شرح تحويل صورت وضعيت
    workdeliverystart تاریخ شروع تحويل كار
    workdeliveryend تاریخ پایان تحويل كار
    workdeliverydescription شرح تحويل كار
    tahvildate تاریخ تویل زمین
    RoleID شناسه نقش کاربر ثبت کننده
    */
    	$query = "UPDATE applicanttiming SET pathstart = '$pathstart', pathend = '$pathend', pathdescription = '$pathdescription', transportstart = '$transportstart', transportend = '$transportend', transportdescription = '$transportdescription', drillingstart = '$drillingstart', drillingend = '$drillingend', drillingdescription = '$drillingdescription', rglazhstart = '$rglazhstart', rglazhend = '$rglazhend', rglazhdescription = '$rglazhdescription', intubationstart = '$intubationstart', intubationend = '$intubationend', intubationdescription = '$intubationdescription', pondstart = '$pondstart', pondend = '$pondend', ponddescription = '$ponddescription', pumpingstationstart = '$pumpingstationstart', pumpingstationend = '$pumpingstationend', pumpingstationdescription = '$pumpingstationdescription', soilpipestart = '$soilpipestart', soilpipeend = '$soilpipeend', soilpipedescription = '$soilpipedescription', networkteststart = '$networkteststart', networktestend = '$networktestend', networktestdescription = '$networktestdescription', soilintrenchstart = '$soilintrenchstart', soilintrenchend = '$soilintrenchend', soilintrenchdescription = '$soilintrenchdescription', dispersivestart = '$dispersivestart', dispersiveend = '$dispersiveend', dispersivedescription = '$dispersivedescription', commissionstart = '$commissionstart', commissionend = '$commissionend', commissiondescription = '$commissiondescription', statementstart = '$statementstart', statementend = '$statementend', statementdescription = '$statementdescription' , workdeliverystart = '$workdeliverystart' , workdeliveryend ='$workdeliveryend', workdeliverydescription = '$workdeliverydescription'
    			,tahvildate='$tahvildate',errnum='$errnum',err='$err'
    			,percent1='$percent1',percent2='$percent2',percent3='$percent3',percent4='$percent4',percent5='$percent5',percent6='$percent6',percent7='$percent7',percent8='$percent8',percent9='$percent9',percent10='$percent10',percent11='$percent11',percent12='$percent12',percent13='$percent13',percent14='$percent14',percentsum='$percentsum'			
    			,SaveTime='". date('Y-m-d H:i:s')."',SaveDate='".date('Y-m-d')."',ClerkID='".$login_userid."'
    			where ApplicantMasterID = $ApplicantMasterID and applicanttiming.RoleID='$RoleID' ";
    	else 
    	$query = " INSERT INTO applicanttiming (ApplicantTimingID, ApplicantMasterID, RoleID, pathstart, pathend, pathdescription, transportstart, transportend, transportdescription, drillingstart, drillingend, drillingdescription, rglazhstart, rglazhend, rglazhdescription, intubationstart, intubationend, intubationdescription, pondstart, pondend, ponddescription, pumpingstationstart, pumpingstationend, pumpingstationdescription, soilpipestart, soilpipeend, soilpipedescription, networkteststart, networktestend, networktestdescription, soilintrenchstart, soilintrenchend, soilintrenchdescription, dispersivestart, dispersiveend, dispersivedescription, commissionstart, commissionend, commissiondescription, statementstart, statementend, statementdescription, workdeliverystart, workdeliveryend, workdeliverydescription
    	        ,tahvildate,errnum,err,percent1,percent2,percent3,percent4,percent5,percent6,percent7,percent8,percent9,percent10,percent11,percent12,percent13,percent14,percentsum,SaveTime,SaveDate,ClerkID) 
    			VALUES ('$ApplicantTimingID', '$ApplicantMasterID','$RoleID','$pathstart','$pathend', '$pathdescription', '$transportstart', '$transportend', '$transportdescription', '$drillingstart', '$drillingend', '$drillingdescription', '$rglazhstart', '$rglazhend', '$rglazhdescription', '$intubationstart', '$intubationend', '$intubationdescription', '$pondstart', '$pondend', '$ponddescription', '$pumpingstationstart', '$pumpingstationend', '$pumpingstationdescription', '$soilpipestart', '$soilpipeend', '$soilpipedescription', '$networkteststart', '$networktestend', '$networktestdescription', '$soilintrenchstart', '$soilintrenchend', '$soilintrenchdescription', '$dispersivestart', '$dispersiveend', '$dispersivedescription', '$commissionstart', '$commissionend', '$commissiondescription', '$statementstart', '$statementend', '$statementdescription', '$workdeliverystart', '$workdeliveryend', '$workdeliverydescription'
    	        ,'$tahvildate','$errnum','$err','$percent1','$percent2','$percent3','$percent4','$percent5','$percent6','$percent7'
    			,'$percent8','$percent9','$percent10','$percent11','$percent12','$percent13','$percent14','$percentsum', '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."')";
       //echo $query;
       
       try 
       {
            $result1 = mysql_query($query);
       }
       //catch exception
       catch(Exception $e) 
       {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
       }
                      
          

}


//---------------------------------------------------
if ($_GET) 
{
	$uid=$_GET["uid"];
	$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
	$linearray = explode('_',$ids);
	$ApplicantMasterID=$linearray[0];//شناسه طرح
	$numpage=$linearray[1];//تعداد ردیف های صفحه
}
//	print $numpage;
	
 if ($login_RolesID==2)//نقش پیمانکار
 /*
 ApplicantName نام پروژه
 ApplicantFName نام متقاضی
 DesignArea مساحت
 operatorcoTitle شرکت پیمانکار
 ostancityname نام استان
 shahrcityname نام شهر
 bakhshcityname نام بخش
 DesignerCotitle مهندس مشاور
 applicanttiming جدول زمانبندی اجرای طرح
 applicantmaster جدول مشخصات طرح
 applicantmasterdetail جدول ارتباطی طرح ها
 operatorco جدول پیمانکاران
 operatorcoID شناسه پیمانکار
 tax_tbcity7digit جدول شهرها
 designerco جدول مهندسین مشاور
 ApplicantMasterID شناسه جدول طرح
 RoleID شناسه نقش کاربر ثبت کننده
 */
$sql = "SELECT applicantmaster.ApplicantName,applicantmaster.ApplicantFName,applicantmaster.DesignArea,operatorco.title operatorcoTitle,
ostan.cityname ostancityname,shahr.cityname shahrcityname,bakhsh.cityname bakhshcityname,designerco.title DesignerCotitle,applicanttiming.* 
FROM applicantmaster 

inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDmaster=applicantmaster.ApplicantMasterID
left outer join operatorco on applicantmaster.operatorcoid=operatorco.operatorcoID

inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
inner join tax_tbcity7digit bakhsh on bakhsh.id=applicantmaster.cityid

left outer join designerco on designerco.DesignerCoID=case ifnull(applicantmasterdetail.nazerID,0) when 0 then shahr.DesignerCoIDnazer else applicantmasterdetail.nazerID end
left outer join applicanttiming on applicanttiming.ApplicantMasterID = applicantmaster.ApplicantMasterID and applicanttiming.RoleID='$login_RolesID'  
where applicantmaster.ApplicantMasterID='$ApplicantMasterID' 
 ";
else  if ($login_RolesID==17)//نقش ناظر مقیم
 /*
 ApplicantName نام پروژه
 ApplicantFName نام متقاضی
 DesignArea مساحت
 operatorcoTitle شرکت پیمانکار
 ostancityname نام استان
 shahrcityname نام شهر
 bakhshcityname نام بخش
 DesignerCotitle مهندس مشاور
 applicanttiming جدول زمانبندی اجرای طرح
 applicantmaster جدول مشخصات طرح
 applicantmasterdetail جدول ارتباطی طرح ها
 operatorco جدول پیمانکاران
 operatorcoID شناسه پیمانکار
 tax_tbcity7digit جدول شهرها
 designerco جدول مهندسین مشاور
 ApplicantMasterID شناسه جدول طرح
 RoleID شناسه نقش کاربر ثبت کننده
 DesignerCoID شناسه مهندس مشاور
 DesignerCoIDnazer شرکت مشاور ناظر
 */
$sql = "SELECT applicantmaster.ApplicantName,applicantmaster.ApplicantFName,applicantmaster.DesignArea,operatorco.title operatorcoTitle,ostan.cityname ostancityname,shahr.cityname shahrcityname,bakhsh.cityname bakhshcityname,designerco.title DesignerCotitle,applicanttiming.* 
FROM applicantmaster 
inner join applicantmasterdetail on 
case applicantmasterdetail.ApplicantMasterIDmaster>0 when 1 then 
applicantmasterdetail.ApplicantMasterIDmaster 
else applicantmasterdetail.ApplicantMasterID end=applicantmaster.ApplicantMasterID
and ifnull(applicantmasterdetail.prjtypeid,0)=1

left outer join operatorco on applicantmaster.operatorcoid=operatorco.operatorcoID

inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
inner join tax_tbcity7digit bakhsh on bakhsh.id=applicantmaster.cityid

left outer join designerco on designerco.DesignerCoID=case ifnull(applicantmasterdetail.nazerID,0) when 0 then shahr.DesignerCoIDnazer else applicantmasterdetail.nazerID end
left outer join applicanttiming on applicanttiming.ApplicantMasterID = applicantmaster.ApplicantMasterID and applicanttiming.RoleID='$login_RolesID'  
where applicantmaster.ApplicantMasterID='$ApplicantMasterID' 
 ";
 /*
 ApplicantName نام پروژه
 ApplicantFName نام متقاضی
 DesignArea مساحت
 operatorcoTitle شرکت پیمانکار
 ostancityname نام استان
 shahrcityname نام شهر
 bakhshcityname نام بخش
 DesignerCotitle مهندس مشاور
 applicanttiming جدول زمانبندی اجرای طرح
 applicantmaster جدول مشخصات طرح
 applicantmasterdetail جدول ارتباطی طرح ها
 operatorco جدول پیمانکاران
 operatorcoID شناسه پیمانکار
 tax_tbcity7digit جدول شهرها
 designerco جدول مهندسین مشاور
 ApplicantMasterID شناسه جدول طرح
 RoleID شناسه نقش کاربر ثبت کننده
 DesignerCoID شناسه مهندس مشاور
 DesignerCoIDnazer شرکت مشاور ناظر
 nazerID شناسه ناظر پروژه
 RoleID='10' نقش مدیر مهندسین مشاور
 */
 else $sql = "SELECT applicantmaster.ApplicantName,applicantmaster.ApplicantFName,applicantmaster.DesignArea,operatorco.title operatorcoTitle,ostan.cityname ostancityname,shahr.cityname shahrcityname,bakhsh.cityname bakhshcityname,designerco.title DesignerCotitle,applicanttiming.* 
FROM applicantmaster 

inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDmaster=applicantmaster.ApplicantMasterID
left outer join operatorco on applicantmaster.operatorcoid=operatorco.operatorcoID

inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
inner join tax_tbcity7digit bakhsh on bakhsh.id=applicantmaster.cityid

left outer join designerco on designerco.DesignerCoID=case ifnull(applicantmasterdetail.nazerID,0) when 0 then shahr.DesignerCoIDnazer else applicantmasterdetail.nazerID end
left outer join applicanttiming on applicanttiming.ApplicantMasterID = applicantmaster.ApplicantMasterID  
where applicantmaster.ApplicantMasterID='$ApplicantMasterID' and 
case applicanttiming.RoleID when 2 then applicanttiming.RoleID='$login_RolesID' else applicanttiming.RoleID='10' end
 ";
/*
 ApplicantName نام پروژه
 ApplicantFName نام متقاضی
 DesignArea مساحت
 operatorcoTitle شرکت پیمانکار
 ostancityname نام استان
 shahrcityname نام شهر
 bakhshcityname نام بخش
 DesignerCotitle مهندس مشاور
 applicanttiming جدول زمانبندی اجرای طرح
 applicantmaster جدول مشخصات طرح
 applicantmasterdetail جدول ارتباطی طرح ها
 operatorco جدول پیمانکاران
 operatorcoID شناسه پیمانکار
 tax_tbcity7digit جدول شهرها
 designerco جدول مهندسین مشاور
 ApplicantMasterID شناسه جدول طرح
 RoleID شناسه نقش کاربر ثبت کننده
 DesignerCoID شناسه مهندس مشاور
 DesignerCoIDnazer شرکت مشاور ناظر
 nazerID شناسه ناظر پروژه
 RoleID='2' نقش پیمانکار
 */
$sqln = "SELECT applicantmaster.ApplicantName,applicantmaster.ApplicantFName,applicantmaster.DesignArea,operatorco.title operatorcoTitle,ostan.cityname ostancityname,shahr.cityname shahrcityname,bakhsh.cityname bakhshcityname,designerco.title DesignerCotitle,applicanttiming.* 
FROM applicantmaster 

inner join applicantmasterdetail on 
case applicantmasterdetail.ApplicantMasterIDmaster>0 when 1 then 
applicantmasterdetail.ApplicantMasterIDmaster 
else applicantmasterdetail.ApplicantMasterID end=applicantmaster.ApplicantMasterID
and ifnull(applicantmasterdetail.prjtypeid,0)=1
left outer join operatorco on applicantmaster.operatorcoid=operatorco.operatorcoID

inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' and  substring(ostan.id,1,2)=substring('$login_CityId',1,2)
inner join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
inner join tax_tbcity7digit bakhsh on bakhsh.id=applicantmaster.cityid

left outer join designerco on designerco.DesignerCoID=case ifnull(applicantmasterdetail.nazerID,0) when 0 then shahr.DesignerCoIDnazer else applicantmasterdetail.nazerID end
left outer join applicanttiming on applicanttiming.ApplicantMasterID = applicantmaster.ApplicantMasterID  and applicanttiming.RoleID='2'
where applicantmaster.ApplicantMasterID='$ApplicantMasterID' ";
try 
    {
        $resultn = mysql_query($sqln);
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }
       
$rown = mysql_fetch_assoc($resultn);
//print $sqln;
//مدت پیشنهادی ناظر برای فاز 1
$lenn1 = (strtotime($rown['pathend']) - strtotime($rown['pathstart']))/86400;
$minn1 = ((strtotime($rown['pathstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['pathend']=="" || $rown['pathstart']=="" )  $minn1 = 0; 
$maxn1 =  $lenn1 + $minn1;
//مدت پیشنهادی ناظر برای فاز 2
$lenn2 = ((strtotime($rown['transportend']) - strtotime($rown['transportstart']))/86400);
$minn2 = ((strtotime($rown['transportstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['transportend']=="" || $rown['transportstart']=="")  $minn2 = 0; 
$maxn2 =  $lenn2 + $minn2;
//مدت پیشنهادی ناظر برای فاز 3
$lenn3 = ((strtotime($rown['drillingend']) - strtotime($rown['drillingstart']))/86400);
$minn3 = ((strtotime($rown['drillingstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['drillingstart']=="" || $rown['drillingend']=="")  $minn3 = 0; 
$maxn3 =  $lenn3 + $minn3;
//مدت پیشنهادی ناظر برای فاز 4
$lenn4 = ((strtotime($rown['rglazhend']) - strtotime($rown['rglazhstart']))/86400);
$minn4 = ((strtotime($rown['rglazhstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['rglazhstart']=="" || $rown['rglazhend']=="")  $minn4 = 0; 
$maxn4 =  $lenn4 + $minn4;
//مدت پیشنهادی ناظر برای فاز 5
$lenn5 = ((strtotime($rown['intubationend']) - strtotime($rown['intubationstart']))/86400);
$minn5 = ((strtotime($rown['intubationstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['intubationstart']=="" || $rown['intubationend']=="")  $minn5 = 0; 
$maxn5 =  $lenn5 + $minn5;
//مدت پیشنهادی ناظر برای فاز 6
$lenn6 = ((strtotime($rown['pondend']) - strtotime($rown['pondstart']))/86400);
$minn6 = ((strtotime($rown['pondstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['pondstart']=="" || $rown['pondend']=="")  $minn6 = 0; 
$maxn6 =  $lenn6 + $minn6;
//مدت پیشنهادی ناظر برای فاز 7
$lenn7 = ((strtotime($rown['pumpingstationend']) - strtotime($rown['pumpingstationstart']))/86400);
$minn7 = ((strtotime($rown['pumpingstationstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['pumpingstationstart']=="" || $rown['pumpingstationend']=="")  $minn7 = 0; 
$maxn7 =  $lenn7 + $minn7;
//مدت پیشنهادی ناظر برای فاز 8 
$lenn8 = ((strtotime($rown['soilpipeend']) - strtotime($rown['soilpipestart']))/86400);
$minn8 = ((strtotime($rown['soilpipestart']) - strtotime($rown['pathstart']))/86400);
if ($rown['soilpipestart']=="" || $rown['soilpipeend']=="")  $minn8 = 0; 
$maxn8 =  $lenn8 + $minn8;
//مدت پیشنهادی ناظر برای فاز 9
$lenn9 = ((strtotime($rown['networktestend']) - strtotime($rown['networkteststart']))/86400);
$minn9 = ((strtotime($rown['networkteststart']) - strtotime($rown['pathstart']))/86400);
if ($rown['networkteststart']=="" || $rown['networktestend']=="")  $minn9 = 0; 
$maxn9 =  $lenn9 + $minn9;
//مدت پیشنهادی ناظر برای فاز 10
$lenn10 = ((strtotime($rown['soilintrenchend']) - strtotime($rown['soilintrenchstart']))/86400);
$minn10 = ((strtotime($rown['soilintrenchstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['soilintrenchstart']=="" || $rown['soilintrenchend']=="")  $minn10 = 0; 
$maxn10 =  $lenn10 + $minn10;
//مدت پیشنهادی ناظر برای فاز 11
$lenn11 = ((strtotime($rown['dispersiveend']) - strtotime($rown['dispersivestart']))/86400);
$minn11 = ((strtotime($rown['dispersivestart']) - strtotime($rown['pathstart']))/86400);
if ($rown['dispersiveend']=="" || $rown['dispersivestart']=="")  $minn11 = 0; 
$maxn11 =  $lenn11 + $minn11;
//مدت پیشنهادی ناظر برای فاز 12
$lenn12 = ((strtotime($rown['commissionend']) - strtotime($rown['commissionstart']))/86400);
$minn12 = ((strtotime($rown['commissionstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['commissionstart']=="" || $rown['commissionend']=="")  $minn12 = 0; 
$maxn12 =  $lenn12 + $minn12;
//مدت پیشنهادی ناظر برای فاز 13
$lenn13 = ((strtotime($rown['statementend']) - strtotime($rown['statementstart']))/86400);
$minn13 = ((strtotime($rown['statementstart']) - strtotime($rown['pathstart']))/86400);
if ($rown['statementstart']=="" || $rown['statementend']=="")  $minn13 = 0; 
$maxn13 =  $lenn13 + $minn13;
//مدت پیشنهادی ناظر برای فاز 14
$lenn14 = ((strtotime($rown['workdeliveryend']) - strtotime($rown['workdeliverystart']))/86400);
$minn14 = ((strtotime($rown['workdeliverystart']) - strtotime($rown['pathstart']))/86400);
if ($rown['workdeliveryend']=="" || $rown['workdeliverystart']=="")  $minn14 = 0; 
$maxn14 =  $lenn14 + $minn14;

try 
{
    $result = mysql_query($sql);
}
//catch exception
catch(Exception $e) 
{
    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
}
       
$row = mysql_fetch_assoc($result);
//print $sql;
//طول فاز 1
$len1 = (strtotime($row['pathend']) - strtotime($row['pathstart']))/86400;
$min1 = ((strtotime($row['pathstart']) - strtotime($rown['pathstart']))/86400);
if ($row['pathend']=="" || $row['pathstart']=="" )  $min1 = 0; 
$max1 =  $len1 + $min1;
//طول فاز 2
$len2 = ((strtotime($row['transportend']) - strtotime($row['transportstart']))/86400);
$min2 = ((strtotime($row['transportstart']) - strtotime($rown['pathstart']))/86400);
if ($row['transportend']=="" || $row['transportstart']=="")  $min2 = 0; 
$max2 =  $len2 + $min2;
//طول فاز 3
$len3 = ((strtotime($row['drillingend']) - strtotime($row['drillingstart']))/86400);
$min3 = ((strtotime($row['drillingstart']) - strtotime($rown['pathstart']))/86400);
if ($row['drillingstart']=="" || $row['drillingend']=="")  $min3 = 0; 
$max3 =  $len3 + $min3;
//طول فاز 4
$len4 = ((strtotime($row['rglazhend']) - strtotime($row['rglazhstart']))/86400);
$min4 = ((strtotime($row['rglazhstart']) - strtotime($rown['pathstart']))/86400);
if ($row['rglazhstart']=="" || $row['rglazhend']=="")  $min4 = 0; 
$max4 =  $len4 + $min4;
//طول فاز 5
$len5 = ((strtotime($row['intubationend']) - strtotime($row['intubationstart']))/86400);
$min5 = ((strtotime($row['intubationstart']) - strtotime($rown['pathstart']))/86400);
if ($row['intubationstart']=="" || $row['intubationend']=="")  $min5 = 0; 
$max5 =  $len5 + $min5;
//طول فاز 6
$len6 = ((strtotime($row['pondend']) - strtotime($row['pondstart']))/86400);
$min6 = ((strtotime($row['pondstart']) - strtotime($row['pathstart']))/86400);
if ($row['pondstart']=="" || $row['pondend']=="")  $min6 = 0; 
$max6 =  $len6 + $min6;
//طول فاز 7
$len7 = ((strtotime($row['pumpingstationend']) - strtotime($row['pumpingstationstart']))/86400);
$min7 = ((strtotime($row['pumpingstationstart']) - strtotime($rown['pathstart']))/86400);
if ($row['pumpingstationstart']=="" || $row['pumpingstationend']=="")  $min7 = 0; 
$max7 =  $len7 + $min7;
//طول فاز 8
$len8 = ((strtotime($row['soilpipeend']) - strtotime($row['soilpipestart']))/86400);
$min8 = ((strtotime($row['soilpipestart']) - strtotime($rown['pathstart']))/86400);
if ($row['soilpipestart']=="" || $row['soilpipeend']=="")  $min8 = 0; 
$max8 =  $len8 + $min8;
//طول فاز 9
$len9 = ((strtotime($row['networktestend']) - strtotime($row['networkteststart']))/86400);
$min9 = ((strtotime($row['networkteststart']) - strtotime($rown['pathstart']))/86400);
if ($row['networkteststart']=="" || $row['networktestend']=="")  $min9 = 0; 
$max9 =  $len9 + $min9;
//طول فاز 10
$len10 = ((strtotime($row['soilintrenchend']) - strtotime($row['soilintrenchstart']))/86400);
$min10 = ((strtotime($row['soilintrenchstart']) - strtotime($rown['pathstart']))/86400);
if ($row['soilintrenchstart']=="" || $row['soilintrenchend']=="")  $min10 = 0; 
$max10 =  $len10 + $min10;
//طول فاز 11
$len11 = ((strtotime($row['dispersiveend']) - strtotime($row['dispersivestart']))/86400);
$min11 = ((strtotime($row['dispersivestart']) - strtotime($rown['pathstart']))/86400);
if ($row['dispersiveend']=="" || $row['dispersivestart']=="")  $min11 = 0; 
$max11 =  $len11 + $min11;
//طول فاز 12
$len12 = ((strtotime($row['commissionend']) - strtotime($row['commissionstart']))/86400);
$min12 = ((strtotime($row['commissionstart']) - strtotime($rown['pathstart']))/86400);
if ($row['commissionstart']=="" || $row['commissionend']=="")  $min12 = 0; 
$max12 =  $len12 + $min12;
//طول فاز 13
$len13 = ((strtotime($row['statementend']) - strtotime($row['statementstart']))/86400);
$min13 = ((strtotime($row['statementstart']) - strtotime($rown['pathstart']))/86400);
if ($row['statementstart']=="" || $row['statementend']=="")  $min13 = 0; 
$max13 =  $len13 + $min13;
//طول فاز 14
$len14 = ((strtotime($row['workdeliveryend']) - strtotime($row['workdeliverystart']))/86400);
$min14 = ((strtotime($row['workdeliverystart']) - strtotime($rown['pathstart']))/86400);
if ($row['workdeliveryend']=="" || $row['workdeliverystart']=="")  $min14 = 0; 
$max14 =  $len14 + $min14;

//---------------------------------------------------
$mpercentsum=0;$num=0;
//مدت فاز 1
if ($maxn1<$max1 && $maxn1>0 && $max1>0) $mpercent1=round(100*$maxn1/$max1,1); else if ($maxn1>0 && $max1>0) $mpercent1=100; if ($mpercent1>0) {$mpercentsum=$mpercentsum+$mpercent1;$num++;}
//مدت فاز 2
if ($maxn2<$max2 && $maxn2>0 && $max2>0) $mpercent2=round(100*$maxn2/$max2,1); else if ($maxn2>0 && $max2>0) $mpercent2=100; if ($mpercent2>0) {$mpercentsum=$mpercentsum+$mpercent2;$num++;}
//مدت فاز 3
if ($maxn3<$max3 && $maxn3>0 && $max3>0) $mpercent3=round(100*$maxn3/$max3,1); else if ($maxn3>0 && $max3>0) $mpercent3=100; if ($mpercent3>0) {$mpercentsum=$mpercentsum+$mpercent3;$num++;}
//مدت فاز 4
if ($maxn4<$max4 && $maxn4>0 && $max4>0) $mpercent4=round(100*$maxn4/$max4,1); else if ($maxn4>0 && $max4>0) $mpercent4=100; if ($mpercent4>0) {$mpercentsum=$mpercentsum+$mpercent4;$num++;}
//مدت فاز 5
if ($maxn5<$max5 && $maxn5>0 && $max5>0) $mpercent5=round(100*$maxn5/$max5,1); else if ($maxn5>0 && $max5>0) $mpercent5=100; if ($mpercent5>0) {$mpercentsum=$mpercentsum+$mpercent5;$num++;}
//مدت فاز 6
if ($maxn6<$max6 && $maxn6>0 && $max6>0) $mpercent6=round(100*$maxn6/$max6,1); else if ($maxn6>0 && $max6>0) $mpercent6=100; if ($mpercent6>0) {$mpercentsum=$mpercentsum+$mpercent6;$num++;}
//مدت فاز 7
if ($maxn7<$max7 && $maxn7>0 && $max7>0) $mpercent7=round(100*$maxn7/$max7,1); else if ($maxn7>0 && $max7>0) $mpercent7=100; if ($mpercent7>0) {$mpercentsum=$mpercentsum+$mpercent7;$num++;}
//مدت فاز 8
if ($maxn8<$max8 && $maxn8>0 && $max8>0) $mpercent8=round(100*$maxn8/$max8,1); else if ($maxn8>0 && $max8>0) $mpercent8=100; if ($mpercent8>0) {$mpercentsum=$mpercentsum+$mpercent8;$num++;}
//مدت فاز 9
if ($maxn9<$max9 && $maxn9>0 && $max9>0) $mpercent9=round(100*$maxn9/$max9,1); else if ($maxn9>0 && $max9>0) $mpercent9=100; if ($mpercent9>0) {$mpercentsum=$mpercentsum+$mpercent9;$num++;}
//مدت فاز 10
if ($maxn10<$max10 && $maxn10>0 && $max10>0) $mpercent10=round(100*$maxn10/$max10,1); else if ($maxn10>0 && $max10>0) $mpercent10=100; if ($mpercent10>0) {$mpercentsum=$mpercentsum+$mpercent10;$num++;}
//مدت فاز 11
if ($maxn11<$max11 && $maxn11>0 && $max11>0) $mpercent11=round(100*$maxn11/$max11,1); else if ($maxn11>0 && $max11>0) $mpercent11=100; if ($mpercent11>0) {$mpercentsum=$mpercentsum+$mpercent11;$num++;}
//مدت فاز 12
if ($maxn12<$max12 && $maxn12>0 && $max12>0) $mpercent12=round(100*$maxn12/$max12,1); else if ($maxn12>0 && $max12>0) $mpercent12=100; if ($mpercent12>0) {$mpercentsum=$mpercentsum+$mpercent12;$num++;}
//مدت فاز 13
if ($maxn13<$max13 && $maxn13>0 && $max13>0) $mpercent13=round(100*$maxn13/$max13,1); else if ($maxn13>0 && $max13>0) $mpercent13=100; if ($mpercent13>0) {$mpercentsum=$mpercentsum+$mpercent13;$num++;}
//مدت فاز 14
if ($maxn14<$max14 && $maxn14>0 && $max14>0) $mpercent14=round(100*$maxn14/$max14,1); else if ($maxn14>0 && $max14>0) $mpercent14=100; if ($mpercent14>0) {$mpercentsum=$mpercentsum+$mpercent14;$num++;}
//مدت کل 
if ($num>0) $mpercentsum =round($mpercentsum/$num,1);
//print $maxn13.'*'.$max13.'*'.$mpercent13;
//---------------------------------------------------
$date = new DateTime(date('Y-m-d'));
$date->modify('-10 day');
$date10before= gregorian_to_jalali($date->format('Y-m-d'));//از تاریخ
$datecurrent= gregorian_to_jalali(date('Y-m-d'));//تاریخ فعلی
?>
<!DOCTYPE html>
<html>
<head>
  	<title>جدول زمانبندي طرح آبياري تحت فشار </title>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
		
		<link rel="stylesheet" href="../assets/style.css" type="text/css" />
		<link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
	<script>



    function CheckForm()
    {
        var LockError=0;
        var fields = ["pathend", "transportend", "drillingend", "rglazhend", "intubationend", "pondend", "pumpingstationend"
        , "soilpipeend", "networktestend", "soilintrenchend", "dispersiveend", "commissionend", "statementend", "workdeliveryend"];
        
        var fieldsstr = ["پياده كردن مسير", "تهيه و حمل لوازم طرح", "حفر تراشه لوله گذاري", "رگلاژ و ريختن خاك نرم يا سرندي كف تراشه"
        , "لوله گذاري خط اصلي و فرعي و نصب اتصالات", "ساختن حوضچه پمپاژ و فونداسيون", "نصب و راه اندازي ايستگاه پمپاژ و كنترل مركزي"
        , "ريختن خاك نرم يا سرندي روي لوله", "	تست شبكه", "برگرداندن خاك درون تراشه", "نصب وراه اندازي و مونتاژ بالهاي آبياري و پاشنده ها و گسيلنده ها"
        , "راه اندازي طرح", "تحويل صورت وضعيت", "تحويل كار"];
        
        if ('<?php echo $login_RolesID==10; ?>'=='10')
        {
            for (i = 0; i < fields.length; i++) 
            {
                if ((document.getElementById(fields[i]).value) != (document.getElementById(fields[i]+'h').value) )
                {
                    if (document.getElementById(fields[i]).value.length<10)
                    {
                        var str=document.getElementById(fields[i]).value;
                        var res = str.split("/");
                        var outstr=res[0];
                        if (res[1].length<2)
                            outstr+="/0"+res[1];
                        else
                            outstr+="/"+res[1];
                        if (res[2].length<2)
                            outstr+="/0"+res[2];
                        else
                            outstr+="/"+res[2];
                        document.getElementById(fields[i]).value=outstr;
                        //alert (res[1].length);
                        //alert('تاریخ '+fieldsstr[i]+' به صورت کامل ده رقمی وارد نشده است');
                        //if (LockError && '<?php echo $login_RolesID==10; ?>'=='10' ) return false; 
                    }
                    if (document.getElementById(fields[i]).value<'<?php echo $date10before;  ?>')
                    {
                        alert('مهلت ثبت '+fieldsstr[i]+' سپری شده است');
                        if (LockError && '<?php echo $login_RolesID==10; ?>'=='10' ) return false;
                    }
                    else if (document.getElementById(fields[i]).value>'<?php echo $datecurrent;  ?>')
                    {
                        alert('زمان ثبت '+fieldsstr[i]+' فرا نرسیده است');
                        if (LockError && '<?php echo $login_RolesID==10; ?>'=='10') return false;
                    }
        
                }
            }    
        }
        
        return confirm('مطمئن هستید که تغییر  اعمال شود ؟');;
    }
                
    </script>
	
    
    	<script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
		<script type="text/javascript" src="../js/persiandatepicker.js"></script>

    
	 <script type="text/javascript">
            $(function() {
                $("#pathstart, #simpleLabel").persiandatepicker();   
                $("#pathend, #simpleLabel").persiandatepicker();   
				$("#transportstart, #simpleLabel").persiandatepicker();   
                $("#transportend, #simpleLabel").persiandatepicker();   
				$("#drillingstart, #simpleLabel").persiandatepicker();   
                $("#drillingend, #simpleLabel").persiandatepicker();   
				$("#rglazhstart, #simpleLabel").persiandatepicker();   
                $("#rglazhend, #simpleLabel").persiandatepicker();   
				$("#intubationstart, #simpleLabel").persiandatepicker();   
                $("#intubationend, #simpleLabel").persiandatepicker();   
				$("#pondstart, #simpleLabel").persiandatepicker();   
                $("#pondend, #simpleLabel").persiandatepicker();   
				$("#pumpingstationstart, #simpleLabel").persiandatepicker();   
                $("#pumpingstationend, #simpleLabel").persiandatepicker();   
				$("#soilpipestart, #simpleLabel").persiandatepicker();   
                $("#soilpipeend, #simpleLabel").persiandatepicker();   
				$("#networkteststart, #simpleLabel").persiandatepicker();   
                $("#networktestend, #simpleLabel").persiandatepicker();   
				$("#soilintrenchstart, #simpleLabel").persiandatepicker();   
                $("#soilintrenchend, #simpleLabel").persiandatepicker();   
				$("#dispersivestart, #simpleLabel").persiandatepicker();   
                $("#dispersiveend, #simpleLabel").persiandatepicker();   
				$("#commissionstart,#simpleLabel").persiandatepicker();   
                $("#commissionend, #simpleLabel").persiandatepicker();   
				$("#statementstart, #simpleLabel").persiandatepicker();   
                $("#statementend, #simpleLabel").persiandatepicker();   
				$("#workdeliverystart, #simpleLabel").persiandatepicker();   
                $("#workdeliveryend, #simpleLabel").persiandatepicker();   
				$("#tahvildate, #simpleLabel").persiandatepicker();
            });
                

    </script>
	<style>
		td.rowtable {
		text-align:center; height:30px; vertical-align:middle;
		}
	</style>
	
    <!-- /scripts -->
</head>
<body >

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">

			<!-- top -->
        	<?php include('../includes/top.php'); ?>
            <!-- /top -->

            <!-- main navigation -->
            <?php include('../includes/navigation.php'); ?>
            <!-- /main navigation -->
			<!-- main navigation -->
            <?php include('../includes/subnavigation.php'); ?>
            <!-- /main navigation -->

			<!-- header -->
            <?php //include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
            <?php if ($result1==1) print $secerror= '<br/><p class="note">اطلاعات با موفقيت ذخيره شد.</p>'.$err; ?>
            
			<form action="applicant_timing.php" onSubmit="return CheckForm()" method="post"  >
                
                <br/>
                <table id="records" width="95%" align="center">
                     
                   <tbody>           
               <tr>
			   <td colspan="8" style="height:80px; vertical-align:middle; font-weight:bold; text-align:center;">جدول زمان بندي طرح آبياري <?php 
			   if ($numpage==4) $hec='متر'; else $hec='هکتار';
               echo $rown['ApplicantFName'].'&nbsp' .$rown['ApplicantName'] .'&nbsp;'. $rown['DesignArea'] .'&nbsp; '.$hec. 'شهرستان&nbsp;'. $rown['shahrcityname'] .'<br/><br/>';
               
//               if ($login_RolesID<>2)
			          
		   		   print "
                        <a  target=\"_blank\"  href='chart_applicant_timing.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ApplicantMasterID.'_1'.rand(1000,9999).'1'."'>
						<img style = 'width: 25px' src=\"../img/chart.png\" title=' نمودار مشاور ناظر '></a>";
                     	
				
			      print "".'مشاور ناظر : '.$rown['DesignerCotitle'] . '&nbsp;&nbsp;&nbsp;پيمانكار :'. $rown['operatorcoTitle'] ;
                   
                   print "
                        <a  target=\"_blank\"  href='chart_applicant_timing.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ApplicantMasterID.'_2'.rand(1000,9999).'2'."'>
						<img style = 'width: 25px' src=\"../img/chart.png\" title=' نمودار پيمانكار '></a>";
             
                
                   print "
                        <a  target=\"_blank\"  href='chart_applicant_timing.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ApplicantMasterID.'_3'.rand(1000,9999).'3'."'>
						<img style = 'width: 25px' src=\"../img/refresh.png\" title=' مقایسه نمودار پيمانكار و ناظر'></a>";
             
		
				         
				$hideop='style=display:none;';$hideop1='display:none;';
				$hidenazer='style=display:none;';$hidenazer1='display:none;';
				
				if ($login_RolesID==10 || $login_RolesID==1 || $login_RolesID==17 || $login_RolesID==31 || $login_RolesID==32){$hideop='';$hideop1='';}
				if ($login_RolesID==1 || $login_RolesID==13 || $login_RolesID==14 || $login_RolesID==23 || $login_RolesID==31 || $login_RolesID==32) {$hidenazer='';$hidenazer1='';}
				
				
				$Titr0='تاريخ تحويل زمين';
				$Titr1='پياده كردن مسير';
				$Titr2='تهيه و حمل لوازم طرح';
				$Titr3='حفر تراشه لوله گذاري';
				$Titr4='رگلاژ و ريختن خاك نرم يا سرندي كف تراشه';
				$Titr5='لوله گذاري خط اصلي و فرعي و نصب اتصالات';
				$Titr6='ساختن حوضچه پمپاژ و فونداسيون';
				$Titr7='نصب و راه اندازي ايستگاه پمپاژ و كنترل مركزي';
				$Titr8='ريختن خاك نرم يا سرندي روي لوله';
				$Titr9='تست شبكه';
				$Titr10='برگرداندن خاك درون تراشه';
				$Titr11='نصب وراه اندازي و مونتاژ بالهاي آبياري و <br>پاشنده ها و گسيلنده ها';
				$Titr12='راه اندازي طرح';
				$Titr13='تحويل صورت وضعيت';
				$Titr14='تحويل كار';
				
				
				if ($numpage==4)
				{
				$Titr0='تاريخ تحويل زمين';
				$Titr1='تمیزکردن مسیر لوله گذاری و انجام کارهای نقشه برداری';
				$Titr2='تهیه و حمل لوله ولوازم';
				$Titr3='حفر تراشه لوله گذاري';
				$Titr4='رگلاژ و ريختن خاك نرم يا سرندي كف تراشه';
				$Titr5='لوله گذاری خط اصلی';
				$Titr6='ساختن حوضچه های مورد نیاز';
				$Titr7='قراردادن لوله ها و اتصالی ها درون ترانشه و نصب آنها';
				$Titr8='خاکریزی اطراف و روی لوله درون ترانشه، با خاک سرندی';
				$Titr9='آزمایش هیدرولیکی نهایی';
				$Titr10='پخش و کوبیدن خاک سرندی';
				$Titr11='خاکریزی نهایی و عملیات تکمیلی';
				$Titr12='راه اندازي طرح';
				$Titr13='تنظیم صورت وضعیت';
				$Titr14='تحویل موقت پروژه';
				}
				
				
					?>
								<tr style="height:40px; font-weight:bold;">
									<td  style="text-align:center; width:5%;  vertical-align:middle; ">رديف</td>
									<td  style="text-align:center; width:35%;  vertical-align:middle;">شرح عمليات</td>
									<td  style="text-align:center; width:15%;  vertical-align:middle;">شروع</td>
									<td  style="text-align:center; width:10%;  vertical-align:middle;">پايان</td>
									<td></td>
                                    <td  style="text-align:center; width:10%; <?php echo $hideop1;?> vertical-align:middle;">درصد پیشرفت</td>
									<td  style="text-align:center; width:10%; <?php echo $hidenazer1;?>  vertical-align:middle;">پیشرفت  زمانی</td>
									<td  style="text-align:center; width:30%;  vertical-align:middle;">توضيحات</td>
								
								</tr>					  
				      
						<tr>
							<td class='label'></td>
							<td><?php echo $Titr0; ?></td>
							<td  class="rowtable"><input placeholder="انتخاب تاریخ"  name="tahvildate" type="text" class="textbox" id="tahvildate" 
							value="<?php  if (strlen($row['tahvildate'])>0) echo gregorian_to_jalali($row['tahvildate']);?>" size="12" maxlength="10" /></td>
							<td  colspan="4"></td>
						</tr>
					
						<tr>
							<td class='label'>1</td>
							<td ><?php echo $Titr1; ?></td>
							<td  class="rowtable"><input placeholder="انتخاب تاریخ"  name="pathstart" type="text" class="textbox" id="pathstart" value="<?php if (strlen($row['pathstart'])>0) echo gregorian_to_jalali($row['pathstart']);?>" size="12" maxlength="10" /></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="pathend" type="text" class="textbox" id="pathend" value="<?php if (strlen($row['pathend'])>0) echo gregorian_to_jalali($row['pathend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable"><input style='display:none;' placeholder="انتخاب تاریخ"  name="pathendh" type="text" class="textbox" id="pathendh" value="<?php if (strlen($row['pathend'])>0) echo gregorian_to_jalali($row['pathend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable" <?php echo $hideop;?>><input placeholder="0-100"  name="percent1" type="textbox" max="100" class="textbox" id="percent1" value="<?php if ($row['percent1']>0) echo $row['percent1'];?>" size="6" maxlength="6" /></td>
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly  name="mpercent1" type="text" class="textbox" id="mpercent1" value="<?php if ($mpercent1>0) echo $mpercent1;?>" size="6" maxlength="6" /></td>
							<td class="rowtable" ><input  name="pathdescription" type="text" class="textbox" id="pathdescription" value="<?php echo $row['pathdescription']; ?>" size="30" maxlength="50" /></td>
							
						
						</tr>
                     <tr>
							<td class='label'>2</td>
							<td><?php echo $Titr2; ?></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="transportstart" type="text" class="textbox" id="transportstart" value="<?php if (strlen($row['transportstart'])>0) echo gregorian_to_jalali($row['transportstart']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable"><input placeholder="انتخاب تاریخ"  name="transportend" type="text" class="textbox" id="transportend" value="<?php if (strlen($row['transportend'])>0) echo gregorian_to_jalali($row['transportend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable"><input style='display:none;' placeholder="انتخاب تاریخ"  name="transportendh" type="text" class="textbox" id="transportendh" value="<?php if (strlen($row['transportend'])>0) echo gregorian_to_jalali($row['transportend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable" <?php echo $hideop;?>><input placeholder="0-100"  name="percent2" type="textbox" max="100" class="textbox" id="percent2" value="<?php if ($row['percent2']>0) echo $row['percent2'];  ?>" size="6" maxlength="6" /></td>
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly  name="mpercent2" type="text" class="textbox" id="mpercent2" value="<?php if ($mpercent2>0) echo $mpercent2;?>" size="6" maxlength="6" /></td>
							<td class="rowtable"><input  name="transportdescription" type="text" class="textbox" id="transportdescription" value="<?php echo $row['transportdescription']; ?>" size="30" maxlength="50" /></td>
						
						</tr>
						<tr>
							<td class='label'>3</td>
							<td><?php echo $Titr3; ?></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="drillingstart" type="text" class="textbox" id="drillingstart" value="<?php if (strlen($row['drillingstart'])>0) echo gregorian_to_jalali($row['drillingstart']);?>" size="12" maxlength="10" /></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="drillingend" type="text" class="textbox" id="drillingend" value="<?php if (strlen($row['drillingend'])>0) echo gregorian_to_jalali($row['drillingend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable"><input style='display:none;' placeholder="انتخاب تاریخ"  name="drillingendh" type="text" class="textbox" id="drillingendh" value="<?php if (strlen($row['drillingend'])>0) echo gregorian_to_jalali($row['drillingend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable" <?php echo $hideop;?>><input placeholder="0-100"  name="percent3" type="textbox" max="100" class="textbox" id="percent3" value="<?php if ($row['percent3']>0) echo $row['percent3'];?>" size="6" maxlength="6" /></td>
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly  name="mpercent3" type="text" class="textbox" id="mpercent3" value="<?php if ($mpercent3>0) echo $mpercent3;?>" size="6" maxlength="6" /></td>
							<td class="rowtable"><input  name="drillingdescription" type="text" class="textbox" id="drillingdescription" value="<?php echo $row['drillingdescription']; ?>" size="30" maxlength="50" /></td>
						
						</tr>
						<tr>
							<td class='label'>4</td>
							<td><?php echo $Titr4; ?></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="rglazhstart" type="text" class="textbox" id="rglazhstart" value="<?php if (strlen($row['rglazhstart'])>0) echo gregorian_to_jalali($row['rglazhstart']);?>" size="12" maxlength="10" /></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="rglazhend" type="text" class="textbox" id="rglazhend" value="<?php if (strlen($row['rglazhend'])>0) echo gregorian_to_jalali($row['rglazhend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable"><input style='display:none;' placeholder="انتخاب تاریخ"  name="rglazhendh" type="text" class="textbox" id="rglazhendh" value="<?php if (strlen($row['rglazhend'])>0) echo gregorian_to_jalali($row['rglazhend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable" <?php echo $hideop;?>><input placeholder="0-100"  name="percent4" type="textbox" max="100" class="textbox" id="percent4" value="<?php if ($row['percent4']>0) echo $row['percent4'];?>" size="6" maxlength="6" /></td>
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly  name="mpercent4" type="text" class="textbox" id="mpercent4" value="<?php if ($mpercent4>0) echo $mpercent4;?>" size="6" maxlength="6" /></td>
							<td class="rowtable"><input  name="rglazhdescription" type="text" class="textbox" id="rglazhdescription" value="<?php echo $row['rglazhdescription']; ?>" size="30" maxlength="50" /></td>
							
						</tr>
						<tr>
							<td class='label'>5</td>
							<td ><?php echo $Titr5; ?></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="intubationstart" type="text" class="textbox" id="intubationstart" value="<?php if (strlen($row['intubationstart'])>0) echo gregorian_to_jalali($row['intubationstart']);?>" size="12" maxlength="10" /></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="intubationend" type="text" class="textbox" id="intubationend" value="<?php if (strlen($row['intubationend'])>0) echo gregorian_to_jalali($row['intubationend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable"><input style='display:none;' placeholder="انتخاب تاریخ"  name="intubationendh" type="text" class="textbox" id="intubationendh" value="<?php if (strlen($row['intubationend'])>0) echo gregorian_to_jalali($row['intubationend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable" <?php echo $hideop;?>><input placeholder="0-100"  name="percent5" type="textbox" max="100" class="textbox" id="percent5" value="<?php if ($row['percent5']>0) echo $row['percent5'];?>" size="6" maxlength="6" /></td>
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly  name="mpercent5" type="text" class="textbox" id="mpercent5" value="<?php if ($mpercent5>0) echo $mpercent5;?>" size="6" maxlength="6" /></td>
							<td class="rowtable"><input  name="intubationdescription" type="text" class="textbox" id="intubationdescription" value="<?php echo $row['intubationdescription']; ?>" size="30" maxlength="50" /></td>
						</tr>
						<tr>
							<td class='label'>6</td>
							<td><?php echo $Titr6; ?></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="pondstart" type="text" class="textbox" id="pondstart" value="<?php if (strlen($row['pondstart'])>0) echo gregorian_to_jalali($row['pondstart']);?>" size="12" maxlength="10" /></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="pondend" type="text" class="textbox" id="pondend" value="<?php if (strlen($row['pondend'])>0) echo gregorian_to_jalali($row['pondend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable"><input style='display:none;' placeholder="انتخاب تاریخ"  name="pondendh" type="text" class="textbox" id="pondendh" value="<?php if (strlen($row['pondend'])>0) echo gregorian_to_jalali($row['pondend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable" <?php echo $hideop;?>><input placeholder="0-100"  name="percent6" type="textbox" max="100" class="textbox" id="percent6" value="<?php if ($row['percent6']>0) echo $row['percent6'];?>" size="6" maxlength="6" /></td>
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly  name="mpercent6" type="text" class="textbox" id="mpercent6" value="<?php if ($mpercent6>0) echo $mpercent6;?>" size="6" maxlength="6" /></td>
							<td class="rowtable"><input  name="ponddescription" type="text" class="textbox" id="ponddescription" value="<?php echo $row['ponddescription']; ?>" size="30" maxlength="50" /></td>
						</tr>
						<tr>
							<td class='label'>7</td>
							<td><?php echo $Titr7; ?></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="pumpingstationstart" type="text" class="textbox" id="pumpingstationstart" value="<?php if (strlen($row['pumpingstationstart'])>0) echo gregorian_to_jalali($row['pumpingstationstart']);?>" size="12" maxlength="10" /></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="pumpingstationend" type="text" class="textbox" id="pumpingstationend" value="<?php if (strlen($row['pumpingstationend'])>0) echo gregorian_to_jalali($row['pumpingstationend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable"><input style='display:none;' placeholder="انتخاب تاریخ"  name="pumpingstationendh" type="text" class="textbox" id="pumpingstationendh" value="<?php if (strlen($row['pumpingstationend'])>0) echo gregorian_to_jalali($row['pumpingstationend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable" <?php echo $hideop;?>><input placeholder="0-100"  name="percent7" type="textbox" max="100" class="textbox" id="percent7" value="<?php if ($row['percent7']>0) echo $row['percent7'];?>" size="6" maxlength="6" /></td>
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly  name="mpercent7" type="text" class="textbox" id="mpercent7" value="<?php if ($mpercent7>0) echo $mpercent7;?>" size="6" maxlength="6" /></td>
							<td class="rowtable"><input  name="pumpingstationdescription" type="text" class="textbox" id="pumpingstationdescription" value="<?php echo $row['pumpingstationdescription']; ?>" size="30" maxlength="50" /></td>
							
						
						</tr>
						<tr>
							<td class='label'>8</td>
							<td><?php echo $Titr8; ?></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="soilpipestart" type="text" class="textbox" id="soilpipestart" value="<?php if (strlen($row['soilpipestart'])>0) echo gregorian_to_jalali($row['soilpipestart']);?>" size="12" maxlength="10" /></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="soilpipeend" type="text" class="textbox" id="soilpipeend" value="<?php if (strlen($row['soilpipeend'])>0) echo gregorian_to_jalali($row['soilpipeend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable"><input style='display:none;' placeholder="انتخاب تاریخ"  name="soilpipeendh" type="text" class="textbox" id="soilpipeendh" value="<?php if (strlen($row['soilpipeend'])>0) echo gregorian_to_jalali($row['soilpipeend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable" <?php echo $hideop;?>><input placeholder="0-100"  name="percent8" type="textbox" max="100" class="textbox" id="percent8" value="<?php if ($row['percent8']>0) echo $row['percent8'];?>" size="6" maxlength="6" /></td>
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly  name="mpercent8" type="text" class="textbox" id="mpercent8" value="<?php if ($mpercent8>0) echo $mpercent8;?>" size="6" maxlength="6" /></td>
							<td class="rowtable"><input  name="soilpipedescription" type="text" class="textbox" id="soilpipedescription" value="<?php echo $row['soilpipedescription']; ?>" size="30" maxlength="50" /></td>
							
					
						</tr>
						<tr>
							<td class='label'>9</td>
							<td><?php echo $Titr9; ?></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="networkteststart" type="text" class="textbox" id="networkteststart" value="<?php if (strlen($row['networkteststart'])>0) echo gregorian_to_jalali($row['networkteststart']);?>" size="12" maxlength="10" /></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="networktestend" type="text" class="textbox" id="networktestend" value="<?php if (strlen($row['networktestend'])>0) echo gregorian_to_jalali($row['networktestend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable"><input style='display:none;' placeholder="انتخاب تاریخ"  name="networktestendh" type="text" class="textbox" id="networktestendh" value="<?php if (strlen($row['networktestend'])>0) echo gregorian_to_jalali($row['networktestend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable" <?php echo $hideop;?>><input placeholder="0-100"  name="percent9" type="textbox" max="100" class="textbox" id="percent9" value="<?php if ($row['percent9']>0) echo $row['percent9'];?>" size="6" maxlength="6" /></td>
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly  name="mpercent9" type="text" class="textbox" id="mpercent9" value="<?php if ($mpercent9>0) echo $mpercent9;?>" size="6" maxlength="6" /></td>
							<td class="rowtable"><input  name="networktestdescription" type="text" class="textbox" id="networktestdescription" value="<?php echo $row['networktestdescription']; ?>" size="30" maxlength="50" /></td>
						
						</tr>
						<tr>
							<td class='label'>10</td>
							<td><?php echo $Titr10; ?></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="soilintrenchstart" type="text" class="textbox" id="soilintrenchstart" value="<?php if (strlen($row['soilintrenchstart'])>0) echo gregorian_to_jalali($row['soilintrenchstart']);?>" size="12" maxlength="10" /></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="soilintrenchend" type="text" class="textbox" id="soilintrenchend" value="<?php if (strlen($row['soilintrenchend'])>0) echo gregorian_to_jalali($row['soilintrenchend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable"><input style='display:none;' placeholder="انتخاب تاریخ"  name="soilintrenchendh" type="text" class="textbox" id="soilintrenchendh" value="<?php if (strlen($row['soilintrenchend'])>0) echo gregorian_to_jalali($row['soilintrenchend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable" <?php echo $hideop;?>><input placeholder="0-100"  name="percent10" type="textbox" max="100" class="textbox" id="percent10" value="<?php if ($row['percent10']>0) echo $row['percent10'];?>" size="6" maxlength="6" /></td>
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly  name="mpercent10" type="text" class="textbox" id="mpercent10" value="<?php if ($mpercent10>0) echo $mpercent10;?>" size="6" maxlength="6" /></td>
							<td class="rowtable"><input  name="soilintrenchdescription" type="text" class="textbox" id="soilintrenchdescription" value="<?php echo $row['soilintrenchdescription']; ?>" size="30" maxlength="50" /></td>
						
						<tr>
							<td class='label'>11</td>
							<td><?php echo $Titr11; ?></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="dispersivestart" type="text" class="textbox" id="dispersivestart" value="<?php if (strlen($row['dispersivestart'])>0) echo gregorian_to_jalali($row['dispersivestart']);?>" size="12" maxlength="10" /></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="dispersiveend" type="text" class="textbox" id="dispersiveend" value="<?php if (strlen($row['dispersiveend'])>0) echo gregorian_to_jalali($row['dispersiveend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable"><input style='display:none;' placeholder="انتخاب تاریخ"  name="dispersiveendh" type="text" class="textbox" id="dispersiveendh" value="<?php if (strlen($row['dispersiveend'])>0) echo gregorian_to_jalali($row['dispersiveend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable" <?php echo $hideop;?>><input placeholder="0-100"  name="percent11" type="textbox" max="100" class="textbox" id="percent11" value="<?php if ($row['percent11']>0) echo $row['percent11'];?>" size="6" maxlength="6" /></td>
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly  name="mpercent11" type="text" class="textbox" id="mpercent11" value="<?php if ($mpercent11>0) echo $mpercent11;?>" size="6" maxlength="6" /></td>
							<td class="rowtable"><input  name="dispersivedescription" type="text" class="textbox" id="dispersivedescription" value="<?php echo $row['dispersivedescription']; ?>" size="30" maxlength="50" /></td>
						
						</tr>
						<tr>
							<td class='label'>12</td>
							<td><?php echo $Titr12; ?></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="commissionstart" type="text" class="textbox" id="commissionstart" value="<?php if (strlen($row['commissionstart'])>0) echo gregorian_to_jalali($row['commissionstart']);?>" size="12" maxlength="10" /></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="commissionend" type="text" class="textbox" id="commissionend" value="<?php if (strlen($row['commissionend'])>0) echo gregorian_to_jalali($row['commissionend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable"><input style='display:none;' placeholder="انتخاب تاریخ"  name="commissionendh" type="text" class="textbox" id="commissionendh" value="<?php if (strlen($row['commissionend'])>0) echo gregorian_to_jalali($row['commissionend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable" <?php echo $hideop;?>><input placeholder="0-100"  name="percent12" type="textbox" max="100" class="textbox" id="percent12" value="<?php if ($row['percent12']>0) echo $row['percent12'];?>" size="6" maxlength="6" /></td>
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly  name="mpercent12" type="text" class="textbox" id="mpercent12" value="<?php if ($mpercent12>0) echo $mpercent12;?>" size="6" maxlength="6" /></td>
							<td class="rowtable"> <input  name="commissiondescription" type="text" class="textbox" id="commissiondescription" value="<?php echo $row['commissiondescription']; ?>" size="30" maxlength="50" /></td>
						
						</tr>
						<tr>
							<td class='label'>13</td>
							<td><?php echo $Titr13; ?></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="statementstart" type="text" class="textbox" id="statementstart" value="<?php if (strlen($row['statementstart'])>0) echo gregorian_to_jalali($row['statementstart']); else echo "";?>" size="12" maxlength="10" /></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="statementend" type="text" class="textbox" id="statementend" value="<?php if (strlen($row['statementend'])>0) echo gregorian_to_jalali($row['statementend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable"><input style='display:none;' placeholder="انتخاب تاریخ"  name="statementendh" type="text" class="textbox" id="statementendh" value="<?php if (strlen($row['statementend'])>0) echo gregorian_to_jalali($row['statementend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable" <?php echo $hideop;?>><input placeholder="0-100"  name="percent13" type="textbox" max="100" class="textbox" id="percent13" value="<?php if ($row['percent13']>0) echo $row['percent13'];?>" size="6" maxlength="6" /></td>
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly  name="mpercent13" type="text" class="textbox" id="mpercent13" value="<?php if ($mpercent13>0) echo $mpercent13;?>" size="6" maxlength="6" /></td>
							<td class="rowtable"><input  name="statementdescription" type="text" class="textbox" id="statementdescription" value="<?php echo $row['statementdescription']; ?>" size="30" maxlength="50" /></td>
						
						</tr>
						<tr>
							<td class='label'>14</td>
							<td><?php echo $Titr14; ?></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="workdeliverystart" type="text" class="textbox" id="workdeliverystart" value="<?php if (strlen($row['workdeliverystart'])>0) echo gregorian_to_jalali($row['workdeliverystart']);?>" size="12" maxlength="10" /></td>
							<td class="rowtable"><input placeholder="انتخاب تاریخ"  name="workdeliveryend" type="text" class="textbox" id="workdeliveryend" value="<?php if (strlen($row['workdeliveryend'])>0) echo gregorian_to_jalali($row['workdeliveryend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable"><input style='display:none;' placeholder="انتخاب تاریخ"  name="workdeliveryendh" type="text" class="textbox" id="workdeliveryendh" value="<?php if (strlen($row['workdeliveryend'])>0) echo gregorian_to_jalali($row['workdeliveryend']);?>" size="12" maxlength="10" /></td>
							
                            <td class="rowtable" <?php echo $hideop;?>><input placeholder="0-100"  name="percent14" type="textbox" max="100" class="textbox" id="percent14" value="<?php if ($row['percent14']>0) echo $row['percent14'];?>" size="6" maxlength="6" /></td>
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly  name="mpercent14" type="text" class="textbox" id="mpercent14" value="<?php if ($mpercent14>0) echo $mpercent14;?>" size="6" maxlength="6" /></td>
							<td class="rowtable"><input  name="workdeliverydescription" type="text" class="textbox" id="workdeliverydescription" value="<?php echo $row['workdeliverydescription']; ?>" size="30" maxlength="50" /></td>
						
						</tr>
						
						</tr>
						<tr>
							<td class='label'></td>
							<td></td>
							<td></td>
							<td></td>
							<td class="rowtable" <?php echo $hideop;?>><input readonly  name="percentsum" type="textbox" class="textbox" id="percent1" value="<?php if ($row['percentsum']>0) echo $row['percentsum'];?>" size="6" maxlength="6" /></td>
							<td class="rowtable" <?php echo $hidenazer;?>><input readonly name="mpercentsum" type="text" class="textbox" id="mpercentsum" value="<?php if ($mpercentsum>0) echo $mpercentsum;?>" size="6" maxlength="6" /></td>
							<td></td>
							
						</tr>
						
						<?php  $permitrolsid = array("1", "2","10","20","17");
					//	print $numpage.' '.$login_RolesID;
						if (in_array($login_RolesID, $permitrolsid)) {
						?>  <tr> 
                            <td colspan="7">
							<input name="ApplicantTimingID" type="hidden" value="<?php echo $row['ApplicantTimingID'];?>">
							<input name="ApplicantMasterID" type="hidden" value="<?php echo $ApplicantMasterID;?> ">
							<input name="RoleID" type="hidden" value="<?php echo $login_RolesID; ?>">
							<?php if (($numpage!=1  && $login_RolesID==2) || ($login_RolesID==10) || ($login_RolesID==17)) { ?>
							<input  name='submit' type='submit' class='button' id='submit' value='ثبت' />
							<?php } ?>
							</td></tr>
							<?php } ?>
                     
                   
                    </tbody>
                   
                </table>
                      
                 <tr >
                        <span colspan="1" id="fooBar">  &nbsp;</span>
                   </tr>
                </form>   
            </div>
			<!-- /content -->


            <!-- footer -->
			<?php include('../includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>

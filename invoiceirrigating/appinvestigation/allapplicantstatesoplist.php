<?php 

/*

//appinvestigation/allapplicantstatesoplist.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود

reports/report_evaluationop.php

*/

include('../includes/connect.php'); 
include('../includes/check_user.php');
require ('../includes/functions.php');



if ($login_Permission_granted==0) header("Location: ../login.php");
$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
$linearray = explode('_',$ids);

if ($linearray[0]>0)
{
    $_POST['operatorcoid']=$linearray[0];//شناسه شرکت پیمانکار
    $_POST['showa']=$linearray[1];//نمایش همه طر ها از جمله تاييد صورت وضعيت 
    $str=" and applicanttiming.emtiaz>0";//افزودن محدودیت نمایش طرح های ارزشیابی شده
}

        
$showa=0;
$yearid='';//سال سهمیه
if ($_POST)//اگر دکمه ثبت کلیک شده بود
{
    $yearid=$_POST['YearID'];//شناسه سال
    $DesignAreafrom=$_POST['DesignAreafrom'];//فیلتر مساحت از
    $DesignAreato=$_POST['DesignAreato'];//فیلتر مساحت تا
    $sos=$_POST['sos'];//شهر
    $sob=$_POST['sob'];//بخش
    $operatorcoid=$_POST['operatorcoid'];//شناسه پیمانکار
    $applicantstatesID=$_POST['applicantstatesID'];//شناسه وضعیت طرح
    $creditcsourceID=$_POST['creditcsourceID'];//منبع تامین اعتبار
    $DesignerCoIDnazer=$_POST['DesignerCoid'];//شناسه مشاور طراح
    $BankCode=$_POST['BankCode'];//کد رهگیری
    
    $dateID=$_POST['dateID'];//تاریخ 
	$ApplicantFname=$_POST['ApplicantFname'];//نام متقاضی
    $Applicantname=$_POST['ApplicantName'];//عنوان طرح
    $DesignSystemGroupstitle=$_POST['DesignSystemGroupstitle'];//سیستم آبیاری
   
    if ($_POST['showa']=='on')//نمایش تمام طرح ها از جمله  تاييد صورت وضعيت 
    $showa=1;
        if (trim($BankCode)==-2)//فیلتر کد های رهگیری صفر
        $str.=" and ifnull(applicantmasterop.BankCode,0)=0";
    else if (trim($BankCode)==-1)//فیلتر کدهای رهگیری بزرگتر از صفر
        $str.=" and ifnull(applicantmasterop.BankCode,0)>0";
    else if (strlen(trim($BankCode))>0)//فیلتر کد  رهگیری خاص
        $str.=" and applicantmasterop.BankCode='$BankCode'";
        
    if (strlen(trim($_POST['DesignAreafrom']))>0)//فیلتر مساحت از
        $str.=" and applicantmasterop.DesignArea>='$_POST[DesignAreafrom]'";
    if (strlen(trim($_POST['DesignAreato']))>0)//فیلتر مساحت تا
        $str.=" and applicantmasterop.DesignArea<='$_POST[DesignAreato]'";
    if (strlen(trim($_POST['sos']))>0)//فیلتر استان
        $str.=" and shahr.id='$_POST[sos]'";
    if (strlen(trim($_POST['operatorcoid']))>0)//فیلتر پیمانکار
        $str.=" and applicantmasterop.operatorcoid='$_POST[operatorcoid]'";
	//print $_POST['DesignerCoid'];
	if (strlen(trim($_POST['DesignerCoid']))>0)//فیلتر مهندس مشاور
        $str.=" and case ifnull(applicantmasterdetail.nazerID,0) when 0 then tax_tbcity7digitnazer.DesignerCoIDnazer else applicantmasterdetail.nazerID end='$_POST[DesignerCoid]'";
		
		if (strlen(trim($_POST['applicantstatesID']))>0)//فیلتر شناسه وضعیت طرح
        $str.=" and applicantstates.applicantstatesID='$_POST[applicantstatesID]'";   
          
    if (strlen(trim($_POST['dateID']))>0)//فیلتر تاریخ طرح
        $str.=" and applicantmasterop.TMDate='$_POST[dateID]'";  
            
	if (strlen(trim($_POST['DesignSystemGroupstitle']))>0)//فیلتر سیستم آبیاری طرح
        $str.=" and designsystemgroups.designsystemgroupsid='$_POST[DesignSystemGroupstitle]'";		
                if (trim($_POST['creditcsourceID'])==-2)//منبع تامین اعتبار صفر
        $str.=" and ifnull(applicantmasterop.creditsourceID,0)=0";
    else if (trim($_POST['creditcsourceID'])==-1)//منبع تامین اعتبار بزرگتر از صفر
        $str.=" and ifnull(applicantmasterop.creditsourceID,0)>0";
    else if (strlen(trim($_POST['creditcsourceID']))>0)//فیلتر منبع تامین اعتبار
        $str.=" and applicantmasterall.creditsourceID='$_POST[creditcsourceID]'"; 
        
	if (strlen(trim($_POST['ApplicantFname']))>0)//نام متقاضی
        $str.=" and applicantmasterop.ApplicantFname like'%$_POST[ApplicantFname]%'";
	if (strlen(trim($_POST['ApplicantName']))>0)//عنوان طرح
        $str.=" and applicantmasterop.ApplicantName like '%$_POST[ApplicantName]%'";
	
    if (strlen(trim($_POST['IDArea']))>0)//فیلتر مسات در بازه مورد نظر
		if (trim($_POST['IDArea'])==1)
        $str.=" and applicantmasterop.DesignArea>0 and applicantmasterop.DesignArea<=10";
		else if (trim($_POST['IDArea'])==2)
        $str.=" and applicantmasterop.DesignArea>10 and applicantmasterop.DesignArea<=20";
		else if (trim($_POST['IDArea'])==3)
        $str.=" and applicantmasterop.DesignArea>20 and applicantmasterop.DesignArea<=50";
		else if (trim($_POST['IDArea'])==4)
        $str.=" and applicantmasterop.DesignArea>50 and applicantmasterop.DesignArea<=100";
		else if (trim($_POST['IDArea'])==5)
        $str.=" and applicantmasterop.DesignArea>100 and applicantmasterop.DesignArea<=200";
		else if (trim($_POST['IDArea'])==6)
        $str.=" and applicantmasterop.DesignArea>200 and applicantmasterop.DesignArea<=500";
		else if (trim($_POST['IDArea'])==7)
        $str.=" and applicantmasterop.DesignArea>500 and applicantmasterop.DesignArea<=1000";
		else if (trim($_POST['IDArea'])==8)
        $str.=" and applicantmasterop.DesignArea>1000";
	
	 if (strlen(trim($_POST['IDemtiaz']))>0)//فیلتر امتیاز ارزشیابی بر اساس بازه مورد نظر
		if (trim($_POST['IDemtiaz'])==1)
        $str.=" and applicanttiming.emtiaz>0 and applicanttiming.emtiaz<=50";
		else if (trim($_POST['IDemtiaz'])==2)
        $str.=" and applicanttiming.emtiaz>50 and applicanttiming.emtiaz<=60";
		else if (trim($_POST['IDemtiaz'])==3)
        $str.=" and applicanttiming.emtiaz>60 and applicanttiming.emtiaz<=70";
		else if (trim($_POST['IDemtiaz'])==4)
        $str.=" and applicanttiming.emtiaz>70 and applicanttiming.emtiaz<=80";
		else if (trim($_POST['IDemtiaz'])==5)
        $str.=" and applicanttiming.emtiaz>80 and applicanttiming.emtiaz<=90";
		else if (trim($_POST['IDemtiaz'])==6)
        $str.=" and applicanttiming.emtiaz>90 and applicanttiming.emtiaz<=100";
		
	
	
    if (trim($_POST['IDprice'])==-2)//فیلتر مبلغ کل هزینه های طرح صفر
        $str.=" and ifnull(applicantmasterop.LastTotal,0)=0";
    else if (trim($_POST['IDprice'])==-1)//فیلتر مبلغ کل هزینه های طرح بزرگتر از صفر
        $str.=" and ifnull(applicantmasterop.LastTotal,0)>0";
    else if (strlen(trim($_POST['IDprice']))>0)	//فیلتر مبلغ کل هزینه های طرح بر اساس بازه انتخابی
        if (trim($_POST['IDprice'])==1)
		$str.=" and applicantmasterop.LastTotal>0 and applicantmasterop.LastTotal<=1000000000";
		else if (trim($_POST['IDprice'])==2)
		$str.=" and applicantmasterop.LastTotal>1000000000 and applicantmasterop.LastTotal<=1500000000";
		else if (trim($_POST['IDprice'])==3)
		$str.=" and applicantmasterop.LastTotal>1500000000 and applicantmasterop.LastTotal<=2000000000";
		else if (trim($_POST['IDprice'])==4)
		$str.=" and applicantmasterop.LastTotal>2000000000 and applicantmasterop.LastTotal<=3000000000";
		else if (trim($_POST['IDprice'])==5)
		$str.=" and applicantmasterop.LastTotal>3000000000 and applicantmasterop.LastTotal<=5000000000";
		else if (trim($_POST['IDprice'])==6)
		$str.=" and applicantmasterop.LastTotal>5000000000 and applicantmasterop.LastTotal<=8000000000";
		else if (trim($_POST['IDprice'])==7)
		$str.=" and applicantmasterop.LastTotal>8000000000 and applicantmasterop.LastTotal<=10000000000";
		else if (trim($_POST['IDprice'])==8)
		$str.=" and applicantmasterop.LastTotal>10000000000";
        
        if (trim($_POST['IDbela'])==-2)//فیلتر مبلغ کل بلاعوض  طرح صفر
        $str.=" and ifnull(applicantmasterop.belaavaz,0)=0";
    else if (trim($_POST['IDbela'])==-1)//فیلتر مبلغ کل بلاعوض  طرح بزرگتر از صفر
        $str.=" and ifnull(applicantmasterop.belaavaz,0)>0";
    else if (strlen(trim($_POST['IDbela']))>0)//فیلتر مبلغ کل بلاعوض  طرح بر اساس بازه انتخابی	
        if (trim($_POST['IDbela'])==1)
		$str.=" and applicantmasterop.belaavaz>0 and applicantmasterop.belaavaz<=1000";
		else if (trim($_POST['IDbela'])==2)
		$str.=" and applicantmasterop.belaavaz>1000 and applicantmasterop.belaavaz<=1500";
		else if (trim($_POST['IDbela'])==3)
		$str.=" and applicantmasterop.belaavaz>1500 and applicantmasterop.belaavaz<=2000";
		else if (trim($_POST['IDbela'])==4)
		$str.=" and applicantmasterop.belaavaz>2000 and applicantmasterop.belaavaz<=3000";
		else if (trim($_POST['IDbela'])==5) 	
		$str.=" and applicantmasterop.belaavaz>3000 and applicantmasterop.belaavaz<=5000";
		else if (trim($_POST['IDbela'])==6)
		$str.=" and applicantmasterop.belaavaz>5000 and applicantmasterop.belaavaz<=8000";
		else if (trim($_POST['IDbela'])==7)
		$str.=" and applicantmasterop.belaavaz>8000 and applicantmasterop.belaavaz<=10000";
		else if (trim($_POST['IDbela'])==8)
		$str.=" and applicantmasterop.belaavaz>10000";    
         
  if($yearid>0)    $str.=" and applicantmasterall.yearid='$yearid' ";//فیلتر سال طرح
         
}

if($login_RolesID==26) {$showc=1;$showt=1;}
if ($showc==1) $str.=" and ifnull(applicantmasterop.criditType,0)=1 ";//فیلتر نوع اعتبار بانک یا صندوق که مقدار پیش فرض یک یعنی صندوق می باشد
    
    
    $sql = "SELECT value  FROM year where YearID='$yearid' ";//پرس و جوی سالهای مختلف برای کومبوباکس سالها
    try 
        {		
            $result = mysql_query($sql);
            $row = mysql_fetch_assoc($result);
            $yearvalue=$row['value'];
        }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
    }
        
    
    
    
  switch ($_POST['IDorder'])//افزودن مرتب سازی به پرس و جو 
  {
    case 1: $orderby=' order by applicantmasterop.ApplicantName COLLATE utf8_persian_ci'; break;//مرتب سازی بر اساس عنوان پروژه 
    case 2: $orderby=' order by ApplicantFName COLLATE utf8_persian_ci'; break;//مرتب سازی بر اساس نام متقاضی
    case 3: $orderby=' order by DesignArea'; break;//مرتب سازی بر اساس مساحت پروژه
    case 4: $orderby=' order by DesignSystemGroupstitle'; break;//مرتب سازی بر اساس سیستم آبیاری 
    case 5: $orderby=' order by shahrcityname COLLATE utf8_persian_ci'; break;//مرتب سازی بر اساس شهر
    case 6: $orderby=' order by operatorcotitle COLLATE utf8_persian_ci'; break;//مرتب سازی بر اساس پیمانکار
    case 7: $orderby=' order by applicantstatestitle COLLATE utf8_persian_ci'; break;//مرتب سازی بر اساس وضعیت طرح
    case 8: $orderby=' order by applicantmasterop.TMDate'; break;//مرتب سازی بر اساس آخرین تاریخ تغییر وضعیت طرح
    case 9: $orderby=' order by cast(applicantmasterall.sandoghcode as  decimal(10,0))'; break;//مرتب سازی بر اساس  کد صندوق
    case 10: $orderby=' order by DesignerCoIDnazertitle  COLLATE utf8_persian_ci'; break;//مرتب سازی بر اساس 
    case 11: $orderby=' order by emtiaz desc'; break;//مرتب سازی بر اساس 

	default: 
    if ($login_RolesID=='7' || $login_RolesID=='16')//در صورتی که کاربر بانک یا صندوق باشد مرتب سازی پیش فرض بر اساس کد صندوق می باشد
        $orderby=' order by cast(applicantmasterall.sandoghcode as  decimal(10,0))';
    else     
	    $orderby=' order by applicantstates.applicantstatesID,applicantmasterop.TMDate'; break;//به صورت پیش فرض مرتب سازی بر اساس شناسه وضعیت و سپس تاریخ آن 
  }
  $strjoin="";
   
  if ($login_RolesID=='10')//کاربر مدیر مهندس مشاور باشد
  //طرح های تت نظارت شهرستان مرتبط با خود را مشاهده می نماید
            $str.=" and case ifnull(applicantmasterdetail.nazerID,0) 
            when 0 then tax_tbcity7digitnazer.DesignerCoIDnazer 
            else applicantmasterdetail.nazerID end='$login_DesignerCoID'";   
        
if ($login_RolesID=='17')//کاربر ناظرر مقیم شهرستان باشد
//فیلتر مشاهده طرح های شهر خود 
    $str.=" and substring(applicantmasterop.cityid,1,4)=substring('$login_CityId',1,4) ";
else if (($login_RolesID=='14') && ($showa==0))//کاربر ناظر عالی استان باشد 
//فیلتر شهرستان های مرتبط با هر ناظر عالی که در ستون
//ClerkIDExcellentSupervisor
//در جدول شهر ها مشخص شده است
        $str.=" and substring(applicantmasterop.cityid,1,4) in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid') ";
else if (($login_RolesID=='29') )//کاربر بازرس باشد
//هر تولید کننده یک بازرس تعیین شده که هر بازرس طرح های تولید کننده خود را مشاهده می نماید
        $str.=" and 
        case applicantmasterop.ClerkIDsurveyor>0 when 1 then applicantmasterop.ClerkIDsurveyor='$login_userid'
        else 
        invoicemaster.ProducersID in (select ProducersID from producers where ClerkIDexaminer='$login_userid')
        end ";
    
			
if ($showa==0)//مشاهده طرح های تاييد صورت وضعيت نشده
{
     $str.=" and ifnull(applicantmasterop.applicantstatesID,0) not in (45)";
    
}
 
	
$selectedCityId=$login_CityId;//شناسه شهر کاربر لاگین شده
if ($_POST['ostan']>0)//استان کاربر
        $selectedCityId=$_POST['ostan'];
/*
    invoicetiming جدول زمانبندی اجرای طرح ها
    ApproveA تایید ارسال لوله ها توسط بازرس
    BOLNO شماره بارنامه لوله
    ApproveP تاریخ اعلامی تولیدکننده جهت ارسال لوازم به محل پروژه
    creditsourceID منبع تامین اعتبار طرح
    creditsource جدول منابع اعتباری
    criditType تجمیع بودن یا نبودن طرح
    DesignSystemGroupsID نوع سیستم آبیاری
    DesignerCoIDnazer شناسه مشاور ناظر طرح
    ApplicantFName عنوان اول طرح
    SaveTime زمان ثبت طرح
    SaveDate تاریخ ثبت طرح
    ClerkID کاربر ثبت
    CityId شناسه شهر طرح
    CountyName روستای طرح
    numfield شماره پرونده طرح
    ClerkIDsurveyor شناسه کاربر نقشه بردار
    YearID سال طرح
    mobile تلفن همراه
    melicode کد/شناسه ملی
    SurveyArea مساحت نقشه برداری شده
    surveyDate تاریخ نقشه برداری
    coef5 ضریب منطقه ای طرح
    CostPriceListMasterID شناسه فهرست بهای آبیاری تحت فشار
    TransportCostTableMasterID شناسه جدول هزینه حمل طرح
    RainDesignCostTableMasterID شناسه جدول هزینه های طراحی طرح های بارانی
    DropDesignCostTableMasterID شناسه جدول هزینه های طراحی طرح های قطره ای
    DesignerID شناسه طراح طرح
    StationNumber تعداد ایستگاه های طرح
    XUTM1 یو تی ام ایکس
    YUTM1 یو تی ام وای
    SoilLimitation محدودیت بافت خاک دارد یا خیر    
    proposable  پیشنهاد قیمت لوله
    applicantstatesID شناسه وضعیت پروژه
    TMDate تاریخ جلسه کمیته فنی
    applicantstates.title عنوان وضعیت پروژه
    hektar سطح پروژه
    prjtypeid نوع پروژه
    nazerID ناظر پروژه
    creditsourceTitle عنوان منبع تامین اعتبار
    ApplicantMasterIDmaster شناسه طرح اجرایی
    DesignerCoID شناسه مشاور طراح
    applicantmaster جدول مشخصات طرح
    applicantmasterdetail جدول ارتباطی طرح ها
    ApplicantMasterID شناسه طرح
    ApplicantMasterIDmaster شناسه طرح اجرایی
    designsystemgroupsdetail جدول ریز سیستم های آبیاری
    appstatesee لیست وضعیت هایی که هر نقش می بیند
    invoicemaster لیست پیش فاکتورها
    operatorcoid شناسه پیمانکار
    private شخصی بودن طرح
    Debi دبی طرح
    DesignArea مساحت طرح
    Code سریال طرح
    BankCode کد رهگیری طرح
    ApplicantName عنوان طرح

    */    
$sql = "SELECT distinct invoicetiming.ApproveA,invoicetiming.BOLNO,invoicetiming.ApproveP,creditsource.creditsourceid,applicantmasterop.criditType criditType,
designsystemgroups.DesignSystemGroupsid ,
designerco.DesignerCoid DesignerCoIDnazer ,
designerco.DesignerCoid,designerco.Title DesignerCoIDnazertitle,

applicantmasterop.ApplicantFName,
applicantmasterop.belaavaz
,applicantmasterop.LastTotal,applicantmasterop.SaveTime,applicantmasterop.LastChangeDate,applicantmasterall.sandoghcode,
applicantmasterop.operatorcoid,applicantmasterop.ApplicantMasterID,applicantmasterop.BankCode,applicantmasterop.DesignArea,
applicantmasterop.ApplicantName
,applicantmasterop.ApplicantFName,applicantmasterop.ApplicantName
,operatorco.title operatorcotitle 
,applicantstates.title applicantstatestitle,applicantstates.applicantstatesID, 
shahr.cityname shahrcityname,shahr.id shahrid 
,creditsource.title creditsourcetitle,designsystemgroups.title DesignSystemGroupstitle,applicantfree.price fprice
,applicantmasterop.TMDate laststatedate,substring(SUBSTRING_INDEX(applicantmasterop.numfield2, '_', -1),1,10) permdeldate

,case (case applicanttiming.emtiaz>0 when 1 then 1 else 0 end+case applicanttiming.m_emtiaz>0 when 1 then 1 else 0 end+
case applicanttiming2.emtiaz>0 when 1 then 1 else 0 end+case applicanttiming2.m_emtiaz>0 when 1 then 1 else 0 end)>0
when 1 then round((ifnull(applicanttiming.emtiaz,0)+ifnull(applicanttiming.m_emtiaz,0)+
ifnull(applicanttiming2.emtiaz,0)+ifnull(applicanttiming2.m_emtiaz,0))/
(case applicanttiming.emtiaz>0 when 1 then 1 else 0 end+case applicanttiming.m_emtiaz>0 when 1 then 1 else 0 end+
case applicanttiming2.emtiaz>0 when 1 then 1 else 0 end+case applicanttiming2.m_emtiaz>0 when 1 then 1 else 0 end))
else 0 end emtiaz
,applicantmasterdetail.nazerID nazerID

FROM applicantmaster applicantmasterop

inner join applicantstates on applicantstates.applicantstatesID=applicantmasterop.applicantstatesID

left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmasterop.cityid,1,4) 
and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'

left outer join tax_tbcity7digit tax_tbcity7digitnazer on substring(tax_tbcity7digitnazer.id,1,4)=substring(applicantmasterop.cityid,1,4) 
and substring(tax_tbcity7digitnazer.id,5,3)='000'


inner join operatorco on operatorco.operatorcoid=applicantmasterop.operatorcoid

left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.DesignSystemGroupsid=applicantmasterop.DesignSystemGroupsid

inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDsurat=applicantmasterop.applicantmasterid

inner join applicantmaster applicantmasterall on applicantmasterdetail.ApplicantMasterID=applicantmasterall.applicantmasterid

left outer join creditsource on creditsource.creditsourceid=applicantmasterall.creditsourceid


    left outer join (select max(InvoiceMasterID) InvoiceMasterID,max(ProducersID)ProducersID,ApplicantMasterID from invoicemaster
    where invoicemaster.proposable=1 group by ApplicantMasterID) invoicemaster  
    on invoicemaster.ApplicantMasterID=applicantmasterop.ApplicantMasterIDmaster  
    left outer join invoicetiming on invoicetiming.InvoiceMasterID=invoicemaster.InvoiceMasterID  

left outer join designerco on designerco.DesignerCoid=case ifnull(applicantmasterdetail.nazerID,0) when 0 then 
tax_tbcity7digitnazer.DesignerCoIDnazer else applicantmasterdetail.nazerID end

left outer join (select ApplicantMasterID,sum(price) price from applicantfreedetail group by ApplicantMasterID)applicantfree on 
        applicantfree.ApplicantMasterID=applicantmasterop.ApplicantMasterID


left outer join (select applicanttiming.ApplicantMasterID ,applicanttiming.errnum ,applicanttiming.RoleID ,
                applicanttiming.emtiaz ,applicanttiming.m_emtiaz from applicanttiming 
                where applicanttiming.RoleID='2') applicanttiming2 on  
                applicanttiming2.ApplicantMasterID=applicantmasterop.ApplicantMasterIDmaster
                
left outer join (select applicanttiming.ApplicantMasterID ,applicanttiming.errnum ,applicanttiming.RoleID ,
                applicanttiming.emtiaz ,applicanttiming.m_emtiaz from applicanttiming 
                where applicanttiming.RoleID='10') applicanttiming on  
                applicanttiming.ApplicantMasterID=applicantmasterop.ApplicantMasterIDmaster


$strjoin
where substring(applicantmasterop.cityid,1,2)=substring('$selectedCityId',1,2) and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)>0  
$str
$orderby";

    try 
        {		
            $result = mysql_query($sql.$login_limited);
        }
        //catch exception
    catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
        } 
  
    $ID1[' ']=' ';
    $ID2[' ']=' ';
    $ID3[' ']=' ';
    $ID4[' ']=' ';
    $ID5[' ']=' ';
    $ID6[' ']=' ';
    $ID7[' ']=' ';
    $ID8[' ']=' ';
    $ID9[' ']=' ';
    $ID10[' ']=' ';
	$ID11[' ']=' ';
	
$dasrow=0;
//در حلقه زیر آرایه های کلید و مقدار برای کومبو باکس ها جهت فیلتر گزارش مانند فیلتر نرم افزار اکسل ایجاد می شود
while($row = mysql_fetch_assoc($result))
{
    $dasrow=1;
    $ID1[trim($row['creditsourcetitle'])]=trim($row['creditsourceid']);//آیتم های کومبوباکس منابع اعتباری
    $ID2[trim($row['shahrcityname'])]=trim($row['shahrid']);//آیتم های کومبوباکس شهرها
    $ID3[trim($row['ApplicantName'])]=trim($row['ApplicantName']);//آیتم های کومبوباکس عناوین پروژه ها
    $ID4[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);//آیتم های کومبوباکس پیمانکاران
    $ID5[trim($row['applicantstatestitle'])]=trim($row['applicantstatesID']);//آیتم های کومبوباکس وضعیت های پروژه
    $ID6[trim(gregorian_to_jalali($row['laststatedate']))]=trim($row['laststatedate']);//آیتم های کومبوباکس آخرین تاریخ تغییر وضعیت طر ها
    $ID7[trim($row['applicantstategroupsTitle'])]=trim($row['applicantstategroupsID']);//آیتم های کومبوباکس وضعیت در حال طراحی/طراحی شده
    $ID8[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);//آیتم های کومبوباکس متاضیان پروژه ها
    $ID9[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsid']);//آیتم های کومبوباکس سیستم های آبیاری
    $ID10[trim($row['BankCode'])]=trim($row['BankCode']);//آیتم های کومبوباکس کدهای رهگیری
    $ID11[trim($row['DesignerCoIDnazertitle'])]=trim($row['DesignerCoid']);//آیتم های کومبوباکس مشاوران ناظر پروژه ها
}
//مرتب سازی آرایه های کلید مقدار
$ID1=mykeyvalsort($ID1);
$ID2=mykeyvalsort($ID2);
$ID3=mykeyvalsort($ID3);
$ID4=mykeyvalsort($ID4);
$ID5=mykeyvalsort($ID5);
$ID6=mykeyvalsort($ID6);
$ID7=mykeyvalsort($ID7);
$ID8=mykeyvalsort($ID8);
$ID9=mykeyvalsort($ID9);
$ID10=mykeyvalsort($ID10);
$ID11=mykeyvalsort($ID11);

if ($dasrow)
mysql_data_seek( $result, 0 );


//پرس و جوی مورد استفاده در کومبوباکس ترتیب
$query="
select 'نام خانوادگی' _key,1 as _value union all
select 'نام' _key,2 as _value union all 
select 'مساحت' _key,3 as _value union all
select 'نوع سیستم' _key,4 as _value union all
select 'شهرستان' _key,5 as _value union all
select 'شرکت طراح' _key,6 as _value union all
select 'وضعیت' _key,7 as _value union all
select 'تاریخ' _key,8 as _value union all
select 'کد' _key,9 as _value union all
select 'شرکت ناظر' _key,10 as _value union all
select 'ارزشیابی' _key,11 as _value ";
$IDorder = get_key_value_from_query_into_array($query);
if (!$_POST['IDorder'])
    $IDorderval=7;//پیش فرض بر اساس وضعیت مرتب شود
else $IDorderval=$_POST['IDorder'];
//پرس و جوی مورد استفاده در  مبلغ پروژه
$query="
select ' خالی' _key,-2 as _value union all 
select ' غیرخالی' _key,-1 as _value union all 
select '0-100 م تومان' _key,1 as _value union all 
select '100-150 م تومان' _key,2 as _value union all
select '150-200 م تومان' _key,3 as _value union all
select '200-300 م تومان' _key,4 as _value union all
select '300-500 م تومان' _key,5 as _value union all
select '500-800 م تومان' _key,6 as _value union all
select '800-1000 م تومان' _key,7 as _value union all
select '<1000 م تومان' _key,8 as _value ";
$IDprice = get_key_value_from_query_into_array($query);
if ($_POST['IDprice']>0)
    $IDpriceval=$_POST['IDprice'];
//پرس و جوی مورد استفاده در  بلاعوض پروژه    
$query="
select '0-100 م تومان' _key,1 as _value union all 
select '100-150 م تومان' _key,2 as _value union all
select '150-200 م تومان' _key,3 as _value union all
select '200-300 م تومان' _key,4 as _value union all
select '300-500 م تومان' _key,5 as _value union all
select '500-800 م تومان' _key,6 as _value union all
select '800-1000 م تومان' _key,7 as _value union all
select '<1000 م تومان' _key,8 as _value ";
$IDbela= get_key_value_from_query_into_array($query);
if ($_POST['IDbela']>0)
    $IDbelaval=$_POST['IDbela'];
//پرس و جوی مورد استفاده در  امتیاز ارزشیابی پروژه
$query="
select '0-50' _key,1 as _value union all 
select '50-60' _key,2 as _value union all
select '60-70' _key,3 as _value union all
select '70-80' _key,4 as _value union all
select '80-90' _key,5 as _value union all
select '90-100' _key,6 as _value";
$IDemtiaz = get_key_value_from_query_into_array($query);
if ($_POST['IDemtiaz']>0)
    $IDemtiazval=$_POST['IDemtiaz'];
//پرس و جوی مورد استفاده در  مسات های مختلف   پروژه ها
$query="
select '0-10' _key,1 as _value union all 
select '10-20' _key,2 as _value union all
select '20-50' _key,3 as _value union all
select '50-100' _key,4 as _value union all
select '100-200' _key,5 as _value union all
select '200-500' _key,6 as _value union all
select '500-1000' _key,7 as _value union all
select '<1000' _key,8 as _value ";
$IDArea = get_key_value_from_query_into_array($query);
if ($_POST['IDArea']>0)
    $IDAreaval=$_POST['IDArea'];
    
//خواندن قراردادهای بارگذاری شده توسط پیمانکاران
$directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/contract/';
$handler = opendir($directory);
$arraycontract=array();
$i=0;
while ($file = readdir($handler)) 
{
    if ($file != "." && $file != "..") 
    {                        
        //print $file;
        $linearray = explode('_',$file);
        if ($linearray[0]>0)
        {
        $arraycontract[$i]=$linearray[0];
        $i++; 
            
        }          
    }
}
    
?>
<!DOCTYPE html>
<html>
<head>
  	<title>ليست  صورت وضعیت طرح ها</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />


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
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
            
            
            
			<div id="content">
            
            <form action="allapplicantstatesoplist.php" method="post">
                <table width="95%" align="center">
                    <tbody class='no-print' >
                           <tr>
                            <?php 
                           
                     $query="SELECT YearID as _value,Value as _key FROM `year` 
                     where YearID in (select YearID from cityquota)
                     
                     ORDER BY year.Value DESC";
    				 $ID = get_key_value_from_query_into_array($query);
                     print 
                     select_option('YearID','سهمیه',',',$ID,0,'','','1','rtl',0,'',$yearid,'','75');
                        
                     if ($login_designerCO==1)
                     {
                        $sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM tax_tbcity7digit ostan
                        where substring(ostan.id,3,5)='00000'
                        order by _key  COLLATE utf8_persian_ci";
                        $allg1idostan = get_key_value_from_query_into_array($sqlselect);
                        
                        print select_option('ostan','',',',$allg1idostan,0,'','','1','rtl',0,'',$selectedCityId,'','75');
                     }
                        
                           ?>                           
                      <td  class="label">مساحت&nbsp;از</td>
                      <td  class="data"><input  name="DesignAreafrom" type="text" class="textbox" id="DesignAreafrom" 
                      value="<?php echo $DesignAreafrom ?>" size="1" maxlength="10" /></td>
                        
                     <td class="label">تا</td>
                      <td class="data"><input name="DesignAreato" type="text" class="textbox" id="DesignAreato" 
                      value="<?php echo $DesignAreato  ?>" size="1" maxlength="10" /></td>
                     <?php
                    print select_option('IDorder','ترتیب',',',$IDorder,0,'','','3','rtl',0,'',$IDorderval,"",'100');?> 
                    
                     
                    
                     <?php  
                     
                         print "<td colspan='1' class='label'>همه</td>
                     <td class='data'><input name='showa' type='checkbox' id='showa'";
                         if ($showa>0) echo 'checked';
                         print " /></td>";
                      ?>
                      
                     
                     
                     
                     <td ></td>
                     
                      <td colspan="1"><input    name="submit" type="submit" class="button" id="submit" size="16" value="جستجو" /></td>
                      
                     
				 			                                       
                   </tr>
			   
                   </tbody>
                                     
                </table>
                 
                <table align='center' border='1' id="table2">              
                   <thead>
				  <tr> 
                  
                            <td colspan="16"
                            <span class="f14_fontb" >صورت وضعیت پروژه های سیستم های نوین آبیاری(مبالغ به میلیون ریال)</span>  </td>
                            <th colspan="6"  class="f14_fontc">&nbsp;&nbsp; </th>
                            
				   </tr>
                   
                        <tr>


                            <th <span class="f9_fontb" > رديف  </span> </th>
							<th <span class="f9_fontb" >کد</span> </th>
							<th <span class="f13_fontb"> نام  </span> </th>
							<th <span class="f13_fontb"> نام خانوادگی </span> </th>
							<th <span class="f9_fontb"> مساحت </span> (ha)  </th>
                            <th  class="f13_fontb"> نوع سیستم  </th>
						    <th <span class="f13_fontb">دشت/ شهرستان</span> </th>
							<th <span class="f13_fontb">شركت مجری</span> </th>
							<th <span class="f13_fontb">شركت ناظر</span> </th>
							<th <span class="f14_fontb"> مبلغ کل </span>
						    <th <span class="f13_fontb">وضعیت</span> </th>
						    <th <span class="f13_fontb">ارزشیابی</span> </th>
						    <th <span class="f14_fontb">تاریخ</span> </th>
						    <th <span class="f13_fontb">کمک بلاعوض</span> </th>
						    <th <span class="f13_fontb">نوع اعتبار</span> </th>
							<th <span class="f14_fontb">کد رهگیری</span> </th>
                            <th colspan="8"  class="f14_fontc">&nbsp;&nbsp; </th>
                        </tr>
                        </thead> 
                             <tr class='no-print'>    
							<td class="f14_font"></td>
							<td class="f14_font"></td>
                        <?php print select_option('ApplicantFname','',',',$ID8,0,'','','1','rtl',0,'',$ApplicantFname,'','100%'); ?>
						<?php print select_option('ApplicantName','',',',$ID3,0,'','','1','rtl',0,'',$ApplicantName,'','100%'); ?>
						<?php print select_option('IDArea','',',',$IDArea,0,'','','1','rtl',0,'',$IDAreaval,'','100%'); ?>
					    <?php print select_option('DesignSystemGroupstitle','',',',$ID9,0,'','','1','rtl',0,'',$DesignSystemGroupstitle,'','100%'); ?>
					    <?php print select_option('sos','',',',$ID2,0,'','','1','rtl',0,'',$sos,"",'100%'); ?> 
					    <?php print select_option('operatorcoid','',',',$ID4,0,'','','1','rtl',0,'',$operatorcoid,'','100%') ?> 
					 <?php print select_option('DesignerCoid','',',',$ID11,0,'','','1','rtl',0,'',$DesignerCoidnazer,'','100%') ?> 
					    <?php print select_option('IDprice','',',',$IDprice,0,'','','1','rtl',0,'',$IDpriceval,'','100%'); ?>  
					    <?php print select_option('applicantstatesID','',',',$ID5,0,'','','1','rtl',0,'',$applicantstatesID,'','100%');?>
                        
					   <?php print select_option('IDemtiaz','',',',$IDemtiaz,0,'','','1','rtl',0,'',$IDemtiazval,'','100%'); ?> 
				          <?php print select_option('dateID','',',',$ID6,0,'','','1','rtl',0,'',$dateID,'','100%');?>
				        
					    <?php print select_option('IDbela','',',',$IDbela,0,'','','1','rtl',0,'',$IDbelaval,'','100%'); ?> 
				        <?php print select_option('creditcsourceID','',',',$ID1,0,'','','1','rtl',0,'',$creditcsourceID,'','100%');?>
                        <?php print select_option('BankCode','',',',$ID10,0,'','','1','rtl',0,'',$BankCode,'','100%'); ?>
					   <td colspan="<?php if ($login_RolesID==5 || $login_designerCO==1)
                       print "4"; else print "3";  ?>"><input    name="submit" type="submit" class="button" id="submit" size="16" value="جستجو" /></td>
                       
					 
					 </tr> 
                     
                   <?php
                   $sumDA=0;
                   $emtiazAvg=0;
				   $emtiazcnt=0;
                   $sumM=0;
                   $rown=0;
                   $totalbelaavaz=0;
                    while($row = mysql_fetch_assoc($result)){

                        $fstr1="";
                        $fstr2="";
                        $fstr3="";
                        $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/';
                        $handler = opendir($directory);
                        while ($file = readdir($handler)) 
                        {
                            // if file isn't this directory or its parent, add it to the results
                            if ($file != "." && $file != "..") 
                            {
                                
                                $linearray = explode('_',$file);
                                $ID=$linearray[0];
                                $No=$linearray[1];
                                if (($ID==$row['ApplicantMasterID']) && ($No==1) )
                                    $fstr1="<a href='../../upfolder/$file' ><img style = 'width: 100%;' src='../img/attachment.png' title='فایل اتوکد' ></a>";
                                
                                if (($ID==$row['ApplicantMasterID']) && ($No==2) )
                                    $fstr2="<a href='../../upfolder/$file' ><img style = 'width: 100%;' src='../img/full_page.png' title='دفترچه طراحی' ></a>";
                                
                                if (($ID==$row['ApplicantMasterID']) && ($No==3) )
                                    $fstr3="<a href='../../upfolder/$file' ><img style = 'width: 100%;' src='../img/new_page.png' title='دفترچه محاسبات' ></a>";        
                                
                            }
                        }
                        
                        $Code = $row['Code'];
                        $ID = $row['ApplicantMasterID'].'_4_'.$row['DesignerCoID'].'_'.$row['operatorcoid'].'_'.$row['applicantstatesID'];
                        $ApplicantName = $row['ApplicantName'];
                        $ApplicantFName = $row['ApplicantFName'];
                        $year = $row['year'];
                        $CostPriceListMasterID=$row['CostPriceListMasterID'];
                        $applicantstatestitle=$row['applicantstatestitle'];
                        
						if($row['emtiaz']) {$emtiazcnt++;$emtiazAvg+=$row['emtiaz'];}
                        
						$sumDA+=$row['DesignArea'];
                        
                        if ($row['SaveTime']<$row['msavetime'])
                        $maxdate=$row['msavetime'];
                        else
                        $maxdate=$row['SaveTime'];
                        
                        
                        
                            $sumL=$row['LastTotal'];
                             
                            
                        $totalbelaavaz+=round($row['belaavaz'],1);
                        
                        $sumM+=$sumL ;
                        $rown++;
                        if ($rown%2==1) 
                        $b='b'; else $b='';
   						if ($row['criditType']==1) $criditType='+';else $criditType='';
						$colo="color='blue'";
						if ($row['nazerID']>0) $colo="color='black'";
						

?>                      
                        <tr>    

                            <td
                            <span class="f12_font<?php echo $b; ?>"  >  <?php
                                
                             echo $criditType.'&nbsp;'.$rown; 
							   if ($login_designerCO==1)
                                echo "<br>(".$row['ApplicantMasterID'].")";
                          
							 ?> </span>  </td>
							 
							<td <span class="f10_font<?php echo $b; ?>"  >  <?php echo "($row[sandoghcode])";?>  </span> </td>

                            <td 
							<span class="f12_font<?php echo $b; ?>">  <?php echo $ApplicantFName; ?> </span> </td>
                           
                            <td
							<span class="f12_font<?php echo $b; ?>"> 
								<a target='blank' href=<?php $permitrolsid = array("1", "18", "19"); $permitstatid = array("23", "32", "40", "42", "47");if (in_array($login_RolesID, $permitrolsid) && in_array($row['applicantstatesID'], $permitstatid))
                             						print "..\insert\invoicemaster_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999)
													.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
													.rand(10000,99999).$row['ApplicantMasterID'].rand(10000,99999); ?>>
													<font color='black'> <?php echo $ApplicantName; ?> </font></a> </span> </td>
                           
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo $row['DesignArea']; ?> </span> </td>
                            
                            <td
							<span class="f10_font<?php echo $b; ?>">  <?php echo str_replace(' ', '&nbsp;', $row['DesignSystemGroupstitle']); ?> </span> </td>
                           
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo $row['shahrcityname']; ?> </span> </td>
                            
                            <td
							<span class="f10_font<?php echo $b; ?>">  <?php 
                            
                            
                            if ($row['permdeldate']=='' && in_array($row['ApplicantMasterID'],$arraycontract))
                            echo '***';
                            
                            echo  $row['operatorcotitle']; ?> </span> </td>
                           <td
							<span class="f12_font<?php echo $b; ?>">  <font <?php  echo $colo; ?>> <?php  echo str_replace(' ', '&nbsp;', $row['DesignerCoIDnazertitle']); ?> </font></span> </td>
                           
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo floor($sumL/100000)/10; ?> </span> </td>
                           
                                              
                                                            
                            <td <span class="f9_font<?php echo $b; ?>">  <?php 
                            
                            
                            
                            
                            echo str_replace(' ', '&nbsp;', $applicantstatestitle); ?> </span> </td>
                            
							<td <span class="f10_font<?php echo $b; ?>">  <?php 
                            	if ($row['emtiaz'])
									print "<a  target='".$target."' href='../insert/pymankar_1qq.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$row['ApplicantMasterID']."_6_".$row['applicantstatesID'].rand(10000,99999).
                                    "'>".$row['emtiaz']."</a>";					
	               
                         else   
                            
                            echo $row['emtiaz']; ?>  </span> </td>
                            
							<td <span class="f10_font<?php echo $b; ?>">  <?php echo gregorian_to_jalali($row['laststatedate']); ?>  </span> </td>
                           	<td <span class="f12_font<?php echo $b; ?>">  <?php echo round($row['belaavaz'],1); ?> </span> </td>
                            <td <span class="f9_font<?php echo $b; ?>">  <?php echo $row['creditsourcetitle']; ?> </span> </td>
                                                     
						   <td
							<span class="f10_font<?php echo $b; ?>"> </span> <?php echo str_replace(' ', '&nbsp;', $row['BankCode']); ?> </td>
                            
							  

							 <?php 
                             $permitrolsid = array("16", "19","7","13","14");if (in_array($login_RolesID, $permitrolsid))
                             {
                                 if ($applicantstatestitle=='تایید نهایی پیش فاکتور')                               
                                print "<td class='no-print'><a target='".$target."' href='invoicemasterfree_list.php?uid=".rand(10000,99999).
                                rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                rand(10000,99999).$row['ApplicantMasterID'].'_1_'.$row['DesignerCoID'].'_'.$row['operatorcoid'].rand(10000,99999).
                                "'><img style = 'width: 25px;' src='../img/Actions-document-export-icon.png' title='آزادسازی'></a></td>";
                                else print "<td></td>";
                            
                             }
                             if ($login_RolesID!='16' && $login_RolesID!='7'  && $login_RolesID!='28') 
                             {
                                
                            $permitrolsid = array("1","5","11","13","14","18","20");if (in_array($login_RolesID, $permitrolsid))
                            print "<td class='no-print'><a target='".$target."' href='applicant_manageredit.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$row['ApplicantMasterID'].'_4_'.$row['DesignerCoID'].'_'.$row['operatorcoid'].rand(10000,99999).
                            "'><img style = 'width: 25px;' src='../img/file-edit-icon.png' title=' ويرايش '></a></td>"; 
                           
                          
                         

							if ($row['ApproveA']>0)
                                $imgt='searchPg.png';
                                else if ($row['BOLNO']>0)
                                $imgt='searchPy.png';
                                else if ($row['ApproveP']>0)
                                    $imgt='searchPb.png';
                                    else 
                                        $imgt='search.png';
                         						 
                            ?>
							
							
							
							
                            <td class='no-print'><a  target='<?php echo $target; ?>' href=<?php print "applicantstates_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = "width: 25px;" src="../img/refresh.png" title=' مشاهده ریز عملیات ' ></a></td>
                            <td class='no-print'><a  target='<?php echo $target;?>' href=<?php
                                                          
                            print "../insert/summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = 'width: 25px;' src='../img/<?php echo $imgt; ?>' title=' ريز '></a></td>

					    
							
                            <?php 
                            $permitrolsid = array("1","5","10","11","13","14","18","20");
                            if (in_array($login_RolesID, $permitrolsid))
                           
                             
                            print "<td class='no-print' ><a  target='".$target."' href='opchangestodesign.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['BankCode']."_1".rand(10000,99999).
                            "'>
                            <img style = 'width: 25px;' src='../img/accept_page.png' title=' تغییرات اجرا'></a></td>"; 
                            
                            }
							
					       
                            print "<td class='no-print' ><a  target='".$target."' href='appuploads.php?uid=".rand(10000,99999).rand(10000,99999)
                            .rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['ApplicantMasterID'].rand(10000,99999).
                            "'>
                            <img style = 'width: 25px;' src='../img/calendar_empty.png' title=' مدیریت فایل ها '></a></td>"; 
							
								 if ($row['applicantstatesID']<> 40  && $login_RolesID!='28' && $login_RolesID!='29')
                          print "<td><a  target='".$target."' href='applicant_end.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$row['ApplicantMasterID']."_5_".$row['applicantstatesID'].rand(10000,99999).
                                    "'><img style = 'width: 25px;' 
                                    src='../img/folder_accept.png' title='صورتجلسه تحویل موقت'></a></td>
						 ";	
						else						 
	                     print "<td>";
					
               				
						?>	
						</tr> 
                        <?php
                        	
                             
                    }
                    
                    
                    

?>

                        <tr>
                            
                            <td colspan="12" class="f14_fontcb" ><?php echo ' مجموع مساحت (هكتار)';   ?></td>
                            <td colspan="4"
                            class="f14_fontcb" 
                            ><?php echo $sumDA;   ?></td>
                        </tr>
                        <tr>
                            
                            <td colspan="12" class="f14_fontcb" ><?php echo ' مجموع مبلغ کل (ميليون ريال)';   ?></td>
                            <td colspan="4" 
                            class="f14_fontcb" 
                            ><?php echo round(($sumM/1000000),1);   ?></td>
                        </tr>
                         <tr>
                            
                            <td colspan="12" class="f14_fontcb" ><?php echo ' مجموع  بلاعوض معرفی شده (ميليون ريال)';   ?></td>
                            <td colspan="4" 
                            class="f14_fontcb" 
                            ><?php echo $totalbelaavaz;   ?></td>
                        </tr> 
                     <tr>
                            
                            <td colspan="12" class="f14_fontcb" ><?php echo 'میانگین ارزشیابی';   ?></td>
                            <td colspan="4" 
                            class="f14_fontcb" 
                            ><?php echo round($emtiazAvg/$emtiazcnt,1);   ?></td>
                        </tr> 
               
                </table>
                
                <script src="../js/jquery-1.9.1.js"></script>
				<script src="../js/jquery.freezeheader.js"></script>

			<script language="javascript" type="text/javascript">

        $(document).ready(function () {
         $("#table2").freezeHeader();
		})
 

    </script>
    
                    </tbody>
                   
                      
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
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

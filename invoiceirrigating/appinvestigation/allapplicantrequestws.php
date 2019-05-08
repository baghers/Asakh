<?php 
/*

//appinvestigation/allapplicantrequestws.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/applicant_manageredit.php
-
*/



include('../includes/connect.php'); 

 include('../includes/check_user.php'); 
 
  include('../includes/functions.php'); 
  
  

include('../Chart.php');


if ($login_Permission_granted==0) header("Location: ../login.php");
$showa=0;//نمایش طرح های سایر شهرستان ها برای ناظرین عالی
$showupdate=0;

if ( (!$_POST) && ($login_RolesID==10 //مشاور ناظر
 || $login_RolesID==13 //مدیر آبیاری
  || $login_RolesID==14 //ناظر عالی
  ))
{
    $showm=1;
    $creditsourceID='20';
    //$str.=" and ifnull(applicantmaster.proposestatep,0)=2";
}
else if ( (!$_POST) && ($login_RolesID==18))//مدیر آب و خاک
{
    $showm=1;
    $creditsourceID='20';
    $str.=" and ifnull(applicantmaster.proposestatep,0)=0 and applicantmaster.applicantstatesID=1";
}
else
$creditsourceID='21';



$ch=chartpipe_sqle();


$Permissionvals=supervisorcoderrquirement_sql($login_ostanId); //تابع دریافت اطلاعات پیکربندی سیستم 
if ($_POST['uid']>0)
    $uid=$_POST["uid"];
else
    $uid=$_GET["uid"];

if  ($uid==2)
        $str.=" and applicantstates.applicantstategroupsID>2";



$query="
select 'پر' _key,1 as _value union all
select 'خالی' _key,2 as _value union all 
select '' _key,3 as _value ";
$IDsandoghcode = get_key_value_from_query_into_array($query);

    $IDsandoghcodeval=3;


if ($_POST)
	{
    
    
		if ($_POST['tempsubmit2'])
		{
			if ($login_RolesID==18)//مدیر آب و خاک
			{
			$query = " update applicantmaster set 
			SaveTime = '" . date('Y-m-d H:i:s') . "', 
					SaveDate = '" . date('Y-m-d') . "', 
					ClerkID = '" . $login_userid . "',
			proposestatep=1,ADate2='".date('Y-m-d')."' 
			WHERE (select count(*) from producerapprequest where producerapprequest.ApplicantMasterID=applicantmaster.ApplicantMasterID)>=3   
			and ifnull(proposestatep,0)=0;";
			$result = mysql_query($query);            
			}
			if ($login_RolesID==32)//مدیر آب و خاک
			{
			$query = " update applicantmaster set 
			SaveTime = '" . date('Y-m-d H:i:s') . "', 
					SaveDate = '" . date('Y-m-d') . "', 
					ClerkID = '" . $login_userid . "',
			proposestatep=2,ADate2='".date('Y-m-d')."' 
			WHERE (select count(*) from producerapprequest where producerapprequest.ApplicantMasterID=applicantmaster.ApplicantMasterID)>=3   
			and ifnull(proposestatep,0)=1;";
			$result = mysql_query($query); 
			
			}
		
			
			header("Location: ".$_SERVER['HTTP_REFERER']);
			//header("Location: allapplicantrequest.php");    
			//print $query;
			//exit;
		}
		
		$freestate=$_POST['freestate'];
		$freestateorder=0;
		if ($_POST['freestate']=='آزادسازی شده') $freestateorder=1; 
		if ($_POST['freestate']=='آزادسازی نشده') $freestateorder=2;
		
		$pstate=$_POST['pstate'];
		$pstateorder=0;
		if ($_POST['pstate']=='درحال تولید') $pstateorder=1; 
		if ($_POST['pstate']=='تحویل') $pstateorder=2;
		
		$creditsourceID=$_POST['creditsourceID'];
		$DesignAreafrom=$_POST['DesignAreafrom'];
		$DesignAreato=$_POST['DesignAreato'];
		$DesignerCoIDbazras=$_POST['DesignerCoid'];
		
		
		if ($_POST['showprice']=='on')
		$showprice=1;
		
        if ($_POST['showa']=='on')
		$showa=1;
		if ($_POST['showupdate']=='on')
		$showupdate=1;
		if ($_POST['showm']=='on')
		$showm=1;
		if ($_POST['showp']=='on')
		$showp=1;
		
		if (strlen(trim($_POST['proposestatetitle']))>0)
			$str.=" and concat
			(applicantstates.Title COLLATE utf8_persian_ci,' ',case ifnull(applicantmaster.proposestatep,0) 
			when 0 then case substring(wsval,length(substring_index(wsval,'_',3))+2,length(substring_index(wsval,'_',4))-length(substring_index(wsval,'_',3))-1)>0 when 1 then 'دریافت پیشنهاد' else '' end
			when 1 then concat('ارجاع به مدیر ',prjtype.title) when 2 then 'ارجاع به کارشناس/ناظر عالی'  
			when 3 then 'تایید پیشنهاد'  else '' end
			)='$_POST[proposestatetitle]'";

	$_POST['Datefrom']=jalali_to_gregorian($_POST['Datefrom']);
	$_POST['Dateto']=jalali_to_gregorian($_POST['Dateto']);


    if ($_POST['IDsandoghcode']==1)
        $str.=" and ifnull(applicantmaster.sandoghcode,0)>0";
    
	   if (strlen(trim($_POST['Datefrom']))>0)
			$str.=" and case reqwin.Windate>0 when 1 then reqwin.Windate else applicantmaster.TMDate end>='$_POST[Datefrom]'";
	  if (strlen(trim($_POST['Dateto']))>0)
			$str.=" and case reqwin.Windate>0 when 1 then reqwin.Windate else applicantmaster.TMDate end<='$_POST[Dateto]'";

		
		if (strlen(trim($_POST['name']))>0)
			$str.=" and clerkwin.ClerkID='$_POST[name]'";
		if (strlen(trim($_POST['ProducerscowinTitle']))>0)
			$str.=" and Producerscowin.Title='$_POST[ProducerscowinTitle]'";
		if (strlen(trim($_POST['dateID']))>0)
			$str.=" and case reqwin.Windate>0 when 1 then reqwin.Windate else applicantmaster.TMDate end>='$_POST[dateID]'";
		if (strlen(trim($_POST['BankCode']))>0)
			$str.=" and applicantmaster.BankCode='$_POST[BankCode]'";
		if (strlen(trim($_POST['creditsourcetitle']))>0)
			$str.=" and creditsource.title='$_POST[creditsourcetitle]'";
		if (strlen(trim($_POST['ApplicantFname']))>0)
			$str.=" and applicantmaster.ApplicantFname like'%$_POST[ApplicantFname]%'";
		if (strlen(trim($_POST['ApplicantName']))>0)
			$str.=" and applicantmaster.ApplicantName like '%$_POST[ApplicantName]%'";
		if (strlen(trim($_POST['material']))>0)
			$str.=" and substring(wsval,length(substring_index(wsval,'_',7))+2,length(substring_index(wsval,'_',8))-length(substring_index(wsval,'_',7))-1)='$_POST[material]'";
		if (strlen(trim($_POST['fesharzekhamathajm']))>0)
			$str.=" and substring(wsval,length(substring_index(wsval,'_',8))+2,length(substring_index(wsval,'_',9))-length(substring_index(wsval,'_',8))-1)='$_POST[fesharzekhamathajm]'";
		if (strlen(trim($_POST['level']))>0)
			$str.=" and ifnull(level,0)='$_POST[level]'";
			 
		if (strlen(trim($_POST['bakhsh']))>0)
			$str.=" and bakhsh.id='$_POST[bakhsh]'";
		if (strlen(trim($_POST['size11']))>0)
			$str.=" and substring(wsval,length(substring_index(wsval,'_',6))+2,length(substring_index(wsval,'_',7))-length(substring_index(wsval,'_',6))-1)='$_POST[size11]'";
		
		if (strlen(trim($_POST['DesignAreafrom']))>0)
			$str.=" and Number>='$_POST[DesignAreafrom]'";
		if (strlen(trim($_POST['DesignAreato']))>0)
			$str.=" and Number<='$_POST[DesignAreato]'";
		if (strlen(trim($_POST['sos']))>0)
			$str.=" and shahr.id='$_POST[sos]'";
		else if (strlen(trim($_POST['applicantstategroupsID']))>0)
			$str.=" and applicantstates.applicantstategroupsID='$_POST[applicantstategroupsID]'";     

		if (strlen(trim($_POST['DesignerCoid']))>0)
			$str.=" and  designerco.DesignerCoid='$_POST[DesignerCoid]'";
		
		if (strlen(trim($_POST['Number']))>0)
		  if (trim($_POST['Number'])==1)
			$str.=" and Number>0 and Number<=500";
			else if (trim($_POST['Number'])==2)
			$str.=" and Number>500 and Number<=1000";
			else if (trim($_POST['Number'])==3)
			$str.=" and Number>1000 and Number<=1500";
			else if (trim($_POST['Number'])==4)
			$str.=" and Number>1500 and Number<=2000";
			else if (trim($_POST['Number'])==5)
			$str.=" and Number>2000 and Number<=2500";
			else if (trim($_POST['Number'])==6)
			$str.=" and Number>2500 and Number<=3000";
			else if (trim($_POST['Number'])==7)
			$str.=" and Number>3000 and Number<=3500";
			else if (trim($_POST['Number'])==8)
			$str.=" and Number>3500";
		
		if (strlen(trim($_POST['IDprice']))>0)	
		  if (trim($_POST['IDprice'])==1)
			$str.=" and applicantmaster.TotlainvoiceValues>0 and applicantmaster.TotlainvoiceValues<=1000000000";
			else if (trim($_POST['IDprice'])==2)
			$str.=" and applicantmaster.TotlainvoiceValues>1000000000 and applicantmaster.TotlainvoiceValues<=1500000000";
			else if (trim($_POST['IDprice'])==3)
			$str.=" and applicantmaster.TotlainvoiceValues>1500000000 and applicantmaster.TotlainvoiceValues<=2000000000";
			else if (trim($_POST['IDprice'])==4)
			$str.=" and applicantmaster.TotlainvoiceValues>2000000000 and applicantmaster.TotlainvoiceValues<=3000000000";
			else if (trim($_POST['IDprice'])==5)
			$str.=" and applicantmaster.TotlainvoiceValues>3000000000 and applicantmaster.TotlainvoiceValues<=5000000000";
			else if (trim($_POST['IDprice'])==6)
			$str.=" and applicantmaster.TotlainvoiceValues>5000000000 and applicantmaster.TotlainvoiceValues<=8000000000";
			else if (trim($_POST['IDprice'])==7)
			$str.=" and applicantmaster.TotlainvoiceValues>8000000000 and applicantmaster.TotlainvoiceValues<=10000000000";
			else if (trim($_POST['IDprice'])==8)
			$str.=" and applicantmaster.TotlainvoiceValues>10000000000";
			
		if (trim($_POST['IDbela'])==-2)
			$str.=" and ifnull(applicantmaster.belaavaz,0)=0";
			else if (trim($_POST['IDbela'])==-1)
			$str.=" and ifnull(applicantmaster.belaavaz,0)>0";
			else if (strlen(trim($_POST['IDbela']))>0)	
			if (trim($_POST['IDbela'])==1)
			$str.=" and applicantmaster.belaavaz>0 and applicantmaster.belaavaz<=1000";
			else if (trim($_POST['IDbela'])==2)
			$str.=" and applicantmaster.belaavaz>1000 and applicantmaster.belaavaz<=1500";
			else if (trim($_POST['IDbela'])==3)
			$str.=" and applicantmaster.belaavaz>1500 and applicantmaster.belaavaz<=2000";
			else if (trim($_POST['IDbela'])==4)
			$str.=" and applicantmaster.belaavaz>2000 and applicantmaster.belaavaz<=3000";
			else if (trim($_POST['IDbela'])==5)
			$str.=" and applicantmaster.belaavaz>3000 and applicantmaster.belaavaz<=5000";
			else if (trim($_POST['IDbela'])==6)
			$str.=" and applicantmaster.belaavaz>5000 and applicantmaster.belaavaz<=8000";
			else if (trim($_POST['IDbela'])==7)
			$str.=" and applicantmaster.belaavaz>8000 and applicantmaster.belaavaz<=10000";
			else if (trim($_POST['IDbela'])==8)
			$str.=" and applicantmaster.belaavaz>10000";     
			 
			 
			 
		  if (trim($_POST['IDkindstate'])==1)
			$str.=" and applicantmaster.applicantstatesID in (23,25,51,52,46)";
			else if (trim($_POST['IDkindstate'])==2)
			$str.=" and applicantmaster.applicantstatesID in (53,21)";
			else if (trim($_POST['IDkindstate'])==3)
			$str.=" and applicantmaster.applicantstatesID in (16,54,55,13)";
			else if (trim($_POST['IDkindstate'])==4)
			$str.=" and applicantmaster.applicantstatesID in (20,55,13,1) and ifnull(applicantmaster.proposestatep,0)<>3 ";
		 
			 
	}


$sql = "select distinct ApplicantMasterID as _value,ApplicantMasterID  as _key from producerapprequest ";
$producerapprequest = get_key_value_from_query_into_array($sql);

    if ($_POST && !($creditsourceID>0))
        $creditsourceID=0;
    
    if ($creditsourceID>0) 
	$str.=" and applicantmaster.creditsourceID='$creditsourceID' ";  
      

   $orderby=' order by pishnahad,shahrcityname COLLATE utf8_persian_ci,applicantmaster.ApplicantMasterID';
	 
if  ($uid==2)
$orderby=' order by shahrcityname COLLATE utf8_persian_ci,applicantmaster.ApplicantMasterID';

  switch ($_POST['IDorder']) 
  {
    case 1: $orderby=' order by applicantmaster.ApplicantName COLLATE utf8_persian_ci,applicantmaster.ApplicantMasterID'; break; 
    case 2: $orderby=' order by applicantmaster.ApplicantFName COLLATE utf8_persian_ci,applicantmaster.ApplicantMasterID'; break;
    case 3: $orderby=' order by Number,applicantmaster.ApplicantMasterID'; break;
    case 4: $orderby=' order by DesignSystemGroupstitle,applicantmaster.ApplicantMasterID'; break;    
    case 5: $orderby=' order by shahrcityname COLLATE utf8_persian_ci,applicantmaster.ApplicantMasterID'; break;
    case 6: $orderby=' order by operatorcotitle COLLATE utf8_persian_ci,applicantmaster.ApplicantMasterID'; break;
    case 7: $orderby=' order by applicantmaster.applicantstatesID,shahrcityname COLLATE utf8_persian_ci,proposestatep,Windate desc,reqwin.ClerkID,applicantmaster.ApplicantMasterID'; break;
	case 8: $orderby=' order by cast(applicantmaster.sandoghcode as  decimal(10,0)),applicantmaster.ApplicantMasterID'; break;
	case 9: $orderby=' order by Windate,applicantmaster.ApplicantMasterID'; break;
	case 10: $orderby=' order by invoicetiming.ApproveA,applicantmaster.ApplicantMasterID'; break;
    default: $orderby=' order by shahrcityname COLLATE utf8_persian_ci,applicantmaster.ApplicantMasterID'; break; 
  }
  
  //print $login_RolesID;;exit;
$permitrolsidforviewrequest = array("1","16","6","18","17","26","27","31","32","6","19","16","13","14");

  if (!in_array($login_RolesID, $permitrolsidforviewrequest))
    $str.=" and producerapprequest.state =1 ";


if ($login_RolesID=='10')
    $str.=" and applicantmasterdetail.nazerID='$login_DesignerCoID' ";
    
if ($login_RolesID=='17') 
    $str.=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) ";
//else if (($login_RolesID!=31) && ($showa==0)) 
//        $str.=" and invoicemaster.proposable>0";
else if ($login_RolesID=='26')
{$str.=" and ifnull(applicantmaster.criditType,0)=1 ";$showa=1;}
		
        
if ( $login_RolesID=='18' || $login_RolesID=='16') $showm=1;

 if ($login_RolesID==29)//بازرس
 {
	$showm=1;
//	$str.=" and designerco.DesignerCoid= '$login_DesignerCoID' "; 
	if ($showa==0)
	{
	$str.=" and case invoicetiming.ClerkIDexaminer>0 when 1 then invoicetiming.ClerkIDexaminer else  Producerscowin.ClerkIDexaminer end= '$login_userid' "; 
	$str.=" and ifnull(invoicetiming.ApproveA,0)=0  and ifnull(invoicetiming.ApproveP,0)>0 ";
	}  
 }
 
if ($showa==0)
{
    if ($login_RolesID==32 || $login_RolesID==18)
		$str.=" and applicantstates.applicantstategroupsID<>1";
		$str.=" and applicantmaster.applicantstatesID not in (23,25,51)";    
	//	$str.=" and level='5' ";  //$Permissionvals['level']  
	//    if ($login_RolesID==18)  $str.=" and applicantmaster.applicantstatesID in (1)";
	//	if ($login_RolesID==18)   $str.=" and applicantstates.applicantstategroupsID<>6";
	//    if ($login_RolesID==18)   $str.=" and applicantmaster.proposestatep in (0,1,2)";
//	$orderby=' order by applicantmaster.applicantstatesID,applicantmaster.proposestatep,applicantmaster.ApplicantMasterID ';
}

if ($showm==0)
{
     $str.=" and applicantmaster.applicantstatesID not in (1,21)";    
}

	if ($login_RolesID==16)//صندوق
		$str.=" and applicantmaster.applicantstatesID not in (16,21,23,25,51,46,52,53)";
	if ($login_RolesID==7)
		$str.=" and applicantmaster.applicantstatesID not in (23,25,51,46,52,53)";
	if ($login_RolesID==6)//سرمایه
		$str.=" and applicantmaster.applicantstatesID not in (23,25,51,46,52,53)";
 
    
$selectedCityId=$login_CityId;
if ($_POST['ostan']>0)
        $selectedCityId=$_POST['ostan'];


$sqlnewop="
select distinct applicantmaster.applicantmasterid applicantmasterid1,applicantmaster2.applicantmasterid applicantmasterid2 from producerapprequest
inner join applicantmaster on applicantmaster.applicantmasterid=producerapprequest.applicantmasterid and applicantmaster.operatorcoid>0 and ifnull(applicantmaster.ApplicantMasterIDmaster,0)=0
inner join applicantmaster applicantmaster2 on applicantmaster2.BankCode=applicantmaster.BankCode and applicantmaster2.operatorcoid>0 and ifnull(applicantmaster2.ApplicantMasterIDmaster,0)=0
inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDmaster=applicantmaster2.applicantmasterid
where applicantmaster2.applicantmasterid<>applicantmaster.applicantmasterid";
$result = mysql_query($sqlnewop);
while($row = mysql_fetch_assoc($result))
{
    $array[$row['applicantmasterid1']]=$row['applicantmasterid2'];
	
	      try 
          {		
             mysql_query($sqlnewop); 
          }
          //catch exception
          catch(Exception $e) 
          {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
          }

}


 /*
if ($login_RolesID==1)
$str="and applicantmaster.ApplicantMasterID in (select distinct applicantfreedetail.ApplicantMasterID from applicantfreedetail
inner join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=applicantfreedetail.ApplicantMasterID or
applicantfreedetail.ApplicantMasterID=applicantmasterdetail.ApplicantMasterIDmaster) and applicantmasterdetail.prjtypeid=1
where freestateID in (142,143,144))";*/

//print $orderby;

    /*
    producerapprequest جدول پیشنهادات قیمت
    state وضعیت انتخابی
    producers جدول مشخصات تولیدکنندگان
    producers.rank رتبه تولید کننده
    producers.Title عنوان تولید کننده
    producers.CompanyAddress آدرس تولید کننده
    SaveDate تاریخ
    validday اعتبار پیشنهاد فیمت اعلامی
    producerapprequestID شناسه جدول پیشنهاد قیمت
    boardvalidationdate اعتبار تاریخ هیئت مدیره
    copermisionvalidate تاریخ اعتبار مجوز شرکت
    joinyear تاریخ تاسیس شرکت
    errors پیغام های عدم صلایت کاربر
    PE32 مبلغ  پیشنهادی برای لوله های 32
    PE40 مبلغ  پیشنهادی   برای لوله های 40
    PE80 مبلغ  پیشنهادی   برای لوله های 80
    PE100 مبلغ  پیشنهادی   برای لوله های 100
    PE32app مبلغ تایید شده برای لوله های 32
    PE40app مبلغ تایید شده برای لوله های 40
    PE80app مبلغ تایید شده برای لوله های 80
    PE100app مبلغ تایید شده برای لوله های 100
    prjtype.title عنوان نوع پروژه
    producers.guaranteepayval مبلغ ضمانت نامه شرکت
    producers.guaranteeExpireDate تاریخ اعتبار ضمانت نامه بانکی
    applicantmasterdetail جدول ارتباطی  طرح ها
    ApplicantMasterID شناسه مطالعات
    ApplicantMasterIDmaster شناسه طر اجرایی
    ApplicantMasterIDsurat شناسه طرح صورت وضعیت
    prjtype جدول انواع پروژه ها
    
    */	 
$sql = "SELECT distinct 

case invoicetiming.ApproveA<0 when 0 then 'تحویل' else case reqwin.ProducersID>0 when 1 then 'درحال تولید' else '' end end pstate
,case ifnull(applicantfreedetail.price,0)>0 when 1 then 'آزادسازی شده' else 'آزادسازی نشده' end freestate
,invoicetiming.ApproveA,invoicetiming.BOLNO,invoicetiming.ApproveP,applicantmaster.proposestatep,applicantmaster.surveyDate
,applicantmaster.ApplicantMasterID,applicantmaster.ApplicantFName,applicantmaster.melicode,applicantmaster.mobile
,applicantmaster.ApplicantName,case reqwin.Windate>0 when 1 then reqwin.Windate else applicantmaster.TMDate end Windate,applicantmaster.BankCode
,applicantmaster.DesignArea,applicantmaster.TotlainvoiceValues,applicantmaster.belaavaz,applicantmaster.selfcashhelpval
,designerco.DesignerCoid,designerco.Title DesignerCoIDbazrastitle
,pproposecnt.cnt pproposecntcnt
,operatorco.title operatorcotitle,operatorco.operatorcoid
,shahr.cityname shahrcityname,shahr.id shahrid 
,applicantmaster.TMDate laststatedate,applicantmaster.sandoghcode sandoghcode
,creditsource.title creditsourcetitle,Producerscowin.Title ProducerscowinTitle,Producerscowin.ProducersID ProducersIDw
,clerkwin.CPI,clerkwin.DVFS,clerkwin.ClerkID ClerkIDwin
,reqwin.PE32app,reqwin.PE40app,reqwin.PE80app,reqwin.PE100app
,invoicetiming.ClerkIDexaminer iClerkIDexaminer,Producerscowin.ClerkIDexaminer PClerkIDexaminer
,concat(
	applicantstates.Title ,' ',case ifnull(applicantmaster.proposestatep,0) 
	when 0 then  
		case substring(wsval,length(substring_index(wsval,'_',3))+2,length(substring_index(wsval,'_',4))-length(substring_index(wsval,'_',3))-1)>0 
		when 1 then 'دریافت پیشنهاد' else '' end
	when 1 then concat('ارجاع به مدیر ',prjtype.title) 
	when 2 then 'ارجاع به کارشناس/ناظر عالی'  
	when 3 then 'تایید پیشنهاد'  else '' end
		) proposestateptitle
	
,case substring(wsval,length(substring_index(wsval,'_',3))+2,length(substring_index(wsval,'_',4))-length(substring_index(wsval,'_',3))-1)>0 
	when 1 then 1 else 0 end pishnahad
  
,applicantmaster.applicantstatesID,applicantmasterdetail.prjtypeid,prjtype.title prjtypetitle
,bakhsh.cityname bakhshcityname,bakhsh.id bakhshid,CountyName
,substring(wsval,length(substring_index(wsval,'_',5))+2,length(substring_index(wsval,'_',6))-length(substring_index(wsval,'_',5))-1) Number
,substring(wsval,length(substring_index(wsval,'_',6))+2,length(substring_index(wsval,'_',7))-length(substring_index(wsval,'_',6))-1) size11
,substring(wsval,length(substring_index(wsval,'_',7))+2,length(substring_index(wsval,'_',8))-length(substring_index(wsval,'_',7))-1) material
,substring(wsval,length(substring_index(wsval,'_',8))+2,length(substring_index(wsval,'_',9))-length(substring_index(wsval,'_',8))-1) fesharzekhamathajm
,substring(wsval,length(substring_index(wsval,'_',9))+2,length(substring_index(wsval,'_',10))-length(substring_index(wsval,'_',9))-1) tonaj
,substring(wsval,length(substring_index(wsval,'_',4))+2,length(substring_index(wsval,'_',5))-length(substring_index(wsval,'_',4))-1) taxpercentvalue
,case ifnull(applicantfreedetail.price,0)>0 when 1 then 6 else applicantstates.applicantstategroupsID end applicantstategroupsID

,applicantmaster.CityId,(ifnull(wsquota.val,0)+ifnull(wsquota.val2,0)) wsquotaval,
case ifnull(level,0)=0 when 1 then 9 else level end level,applicantfreedetail.price,'' letterdate
,XUTM1,YUTM1
 ,reqwin.SaveDate producerapprequestSaveDate,reqwin.validday
FROM applicantmaster 
inner join applicantstates on applicantstates.applicantstatesID=applicantmaster.applicantstatesID
left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
left outer join tax_tbcity7digit bakhsh on bakhsh.id=applicantmaster.cityid

left outer join operatorco on operatorco.operatorcoid=applicantmaster.operatorcoid
left outer join creditsource on creditsource.creditsourceid=applicantmaster.creditsourceid

left outer join (SELECT count(*) cnt,ApplicantMasterID 
			FROM producerapprequest 
            group by ApplicantMasterID) pproposecnt on pproposecnt.ApplicantMasterID=applicantmaster.ApplicantMasterID

left outer join (select ApplicantMasterID,ProducersID,ClerkID,PE32app,PE40app,PE80app,PE100app,Windate,SaveDate,validday from producerapprequest where state=1) reqwin on 
reqwin.ApplicantMasterID=applicantmaster.ApplicantMasterID

left outer join producers Producerscowin on Producerscowin.ProducersID=reqwin.ProducersID
left outer join clerk clerkwin on clerkwin.ClerkID=reqwin.ClerkID

left outer join clerk clerkbazras on clerkbazras.ClerkID=Producerscowin.ClerkIDexaminer

left outer join designerco on designerco.DesignerCoid=clerkbazras.MMC

left outer join producerapprequest on case producerapprequest.ApplicantMasterID>0 when 1 then producerapprequest.ApplicantMasterID 
 else -producerapprequest.ApplicantMasterID end=applicantmaster.ApplicantMasterID and state=1 

inner join applicantmasterdetail on 
case applicantmasterdetail.ApplicantMasterIDmaster>0 when 1 then 
applicantmasterdetail.ApplicantMasterIDmaster 
else applicantmasterdetail.ApplicantMasterID end=applicantmaster.ApplicantMasterID
and ifnull(applicantmasterdetail.prjtypeid,0)=1

left outer join prjtype on prjtype.prjtypeid=ifnull(applicantmasterdetail.prjtypeid,0)
inner join wsquota on wsquota.creditsourceID= applicantmaster.creditsourceID and substring(wsquota.CityId,1,4)=substring(applicantmaster.CityId,1,4)
left outer join (select sum(price*(case paytype when 1 then -1 else 1 end)) price,ApplicantMasterID 
from applicantfreedetail group by ApplicantMasterID) applicantfreedetail on applicantfreedetail.ApplicantMasterID=applicantmaster.ApplicantMasterID 

    left outer join (select max(InvoiceMasterID) InvoiceMasterID,max(ProducersID)ProducersID,ApplicantMasterID from invoicemaster
    where invoicemaster.proposable=1 group by ApplicantMasterID) invoicemaster  on invoicemaster.ApplicantMasterID=applicantmaster.ApplicantMasterID 
    left outer join invoicetiming on invoicetiming.InvoiceMasterID=invoicemaster.InvoiceMasterID
    
where substring(applicantmaster.cityid,1,2)=substring('$selectedCityId',1,2)
   $str
$orderby ";

                 

//print $sql;
      try 
          {		
             $result = mysql_query($sql); 
          }
          //catch exception
          catch(Exception $e) 
          {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
          }
          
    $ID1[' ']=' ';
    $ID2[' ']=' ';
    $ID3[' ']=' ';
    $ID4[' ']=' ';
    $ID5[' ']=' ';
    $ID6[' ']=' ';
    $ID7[' ']=' ';
    $ID9[' ']=' ';
    $ID10[' ']=' ';
    $ID11[' ']=' ';
    $ID12[' ']=' ';
    $ID13[' ']=' ';
    $ID14[' ']=' ';
    $ID15[' ']=' ';
    $ID16[' ']=' ';
    //$ID16['0']='0';
    $IDp[' ']=' ';
	$ID18[' ']=' ';
    
    $IDfreestate[' ']=' ';
while($row = mysql_fetch_assoc($result))
{
    $IDp[trim($row['pstate'])]=trim($row['pstate']);
    $IDfreestate[trim($row['freestate'])]=trim($row['freestate']);
    
    $ID16[trim($row['level'])]=trim($row['level']);
    $ID1[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);
    $ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);
    $ID3[trim($row['material'])]=trim($row['material']);
    $ID4[trim($row['bakhshcityname'])]=trim($row['bakhshid']);
    $ID5[trim($row['shahrcityname'])]=trim($row['shahrid']);
    $ID6[trim($row['size11'])]=trim($row['size11']);
    $ID7[trim($row['fesharzekhamathajm'])]=trim($row['fesharzekhamathajm']);
    $ID9[trim($row['creditsourcetitle'])]=trim($row['creditsourcetitle']);
    $ID10[trim($row['BankCode'])]=trim($row['BankCode']);
    $ID11[trim($row['proposestateptitle'])]=trim($row['proposestateptitle']);
    $ID12[trim(gregorian_to_jalali($row['Windate']))]=trim($row['Windate']);
    $ID13[trim($row['ProducerscowinTitle'])]=trim($row['ProducerscowinTitle']);    
    $encrypted_string=$row['CPI'];
    $encryption_key="!@#$8^&*";
    $decrypted_string="";
    for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
        $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
    $encrypted_string=$row['DVFS'];
    $encryption_key="!@#$8^&*";
    $decrypted_string.=" ";
    for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
        $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
    $ID14[$decrypted_string]=trim($row['ClerkIDwin']);;
	$ID15[trim($row['DesignerCoIDbazrastitle'])]=trim($row['DesignerCoid']);
  
	

}
 // $ID11[trim('انعقاد قراردادها')]=trim('انعقاد قراردادها');
 //  $ID11[trim('بانک به صندوقها')]=trim('بانک به صندوقها');
 
//مرتب سازی آرایه های کلید و مقدار
$ID1=mykeyvalsort($ID1);
$ID2=mykeyvalsort($ID2);
$ID3=mykeyvalsort($ID3);
$ID4=mykeyvalsort($ID4);
$ID5=mykeyvalsort($ID5);
$ID6=mykeyvalsort($ID6);
$ID7=mykeyvalsort($ID7);
$ID9=mykeyvalsort($ID9);
$ID10=mykeyvalsort($ID10);
$ID11=mykeyvalsort($ID11);
$ID12=mykeyvalsort($ID12);
$ID13=mykeyvalsort($ID13);
$ID14=mykeyvalsort($ID14);
$ID15=mykeyvalsort($ID15);
$ID16=mykeyvalsort($ID16);
$IDp=mykeyvalsort($IDp);
$IDfreestate=mykeyvalsort($IDfreestate);


mysql_data_seek( $result, 0 );



//پرس و جوی مربوط به کومبوباکس ترتیب						
$query="
select 'نام خانوادگی' _key,1 as _value union all
select 'نام' _key,2 as _value union all 
select 'متراژ' _key,3 as _value union all
select 'نوع سیستم' _key,4 as _value union all
select 'شهرستان' _key,5 as _value union all
select 'شرکت طراح' _key,6 as _value union all
select 'وضعیت' _key,7 as _value union all
select 'کد' _key,8 as _value union all
select 'تاریخ' _key,9 as _value union all
select 'تاریخ تحویل' _key,10 as _value ";
$IDorder = get_key_value_from_query_into_array($query);

if ($_POST['IDorder']>0)
    $IDorderval=$_POST['IDorder'];
    else 
    $IDorderval=5;

	
						
$query="
select 'تکمیل مدارک ' _key,1 as _value union all
select 'مدیریت آب و خاک ' _key,2 as _value union all 
select 'بانک' _key,3 as _value union all
select 'صندوق' _key,4 as _value union all
select 'همه' _key,5 as _value ";
$IDkindstate = get_key_value_from_query_into_array($query);

if ($_POST['IDkindstate']>0)
    $kindstate=$_POST['IDkindstate'];
    else 
    $kindstate=5;


$query="
select '0-500' _key,1 as _value union all 
select '500-1000' _key,2 as _value union all
select '1000-1500' _key,3 as _value union all
select '1500-2000' _key,4 as _value union all
select '2000-2500' _key,5 as _value union all
select '2500-3000' _key,6 as _value union all
select '3000-3500' _key,7 as _value union all
select '<3500' _key,8 as _value ";
$Number = get_key_value_from_query_into_array($query);
if ($_POST['Number']>0)
    $Numberval=$_POST['Number'];
    
$query="
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


$query = "select distinct ClerkIDWaterInspector  as _value, substring(tax_tbcity7digit.id,1,4) as _key from tax_tbcity7digit 
            where ClerkIDWaterInspector>0";
            
/*$query = "select distinct ClerkIDExcellentSupervisor  as _value, substring(tax_tbcity7digit.id,1,4) as _key from tax_tbcity7digit 
            where ClerkIDExcellentSupervisor>0";
            */
$ClerkIDWaterInspectorID = get_key_value_from_query_into_array($query);


if ($creditsourceID>0)
    $query = "select shahr.cityname as _key,(ifnull(wsquota.val,0)+ifnull(wsquota.val2,0)) as _value from tax_tbcity7digit shahr 
    inner join wsquota on wsquota.creditsourceID= '$creditsourceID' and substring(wsquota.CityId,1,4)=substring(shahr.id,1,4)
    where substring(shahr.id,1,2)='19' and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'";
    else 
    $query = "select shahr.cityname as _key,(ifnull(wsquota.val,0)+ifnull(wsquota.val2,0)) as _value from tax_tbcity7digit shahr 
    inner join wsquota on  substring(wsquota.CityId,1,4)=substring(shahr.id,1,4)
    where substring(shahr.id,1,2)='19' and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'";
$shahrID = get_key_value_from_query_into_array($query);
			try 
                  {		
                    mysql_query($query);
                  }
                  //catch exception
                  catch(Exception $e) 
                  {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                  }



?>



<!DOCTYPE html>
<html>
<head>

<STYLE >
p {
    margin: 0;
    padding: 0;
}
 p.page {
    page-break-after: always;
   }
   

</STYLE>


  	<title>پیشنهاد قیمت لوله آبرسانی</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />


    <!-- /scripts -->
    <script>
function myFunction(ids) {
	
	if 	(ids==3) var uid=document.getElementById('uid3').value;
	if 	(ids==2) var uid=document.getElementById('uid2').value;
	window.open (uid,'_blank');
							
}

function setpagereak(id)
{
    alert(1);
    if (document.getElementById(id).className=="page")
        document.getElementById(id).className = "";
    else
        document.getElementById(id).className = "page";
    
}

</script>


  
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

            
            <form action="allapplicantrequestws.php" method="post">
                <table width="95%" align="center">
                    <tbody class='no-print' >
                           <tr>
						   
                            <?php 
                           
                     $query="SELECT creditsourceID as _value,Title as _key FROM `creditsource` 
                     where creditsourceID in (20,21)
                     
                     ORDER BY _key DESC";
    				 $ID = get_key_value_from_query_into_array($query);
			 	try 
                  {		
                    mysql_query($query);
                  }
                  //catch exception
                  catch(Exception $e) 
                  {
                    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
                  }

					 
                     print 
                     select_option('creditsourceID','اعتبار',',',$ID,0,'','','1','rtl',0,'',$creditsourceID,'','50px');
                     
                          
                     if ($login_designerCO==1)
                     {
                        $sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM tax_tbcity7digit ostan
                        where substring(ostan.id,3,5)='00000'
                        order by _key  COLLATE utf8_persian_ci";
                        $allg1idostan = get_key_value_from_query_into_array($sqlselect);
                        	try 
							  {		
								mysql_query($sqlselect);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

                        print select_option('ostan','استان',',',$allg1idostan,0,'','','1','rtl',0,'',$selectedCityId,'','35px');
                     }
                     
                          
                           
                           ?>
		   
                      <td  class="label">مساحت</td>
                      <td  class="data">&nbsp;از<input  name="DesignAreafrom" type="text" class="textbox" id="DesignAreafrom" 
                      value="<?php echo $DesignAreafrom ?>" size="1" maxlength="10" /></td>
                        
                    
                      <td class="data">تا<input name="DesignAreato" type="text" class="textbox" id="DesignAreato" 
                      value="<?php echo $DesignAreato  ?>" size="1" maxlength="10" /></td>
                     
                      <?php print select_option('IDorder','ترتیب',',',$IDorder,0,'','','3','rtl',0,'',$IDorderval,'','75px');
                      print select_option('pstate','وضعیت تولید',',',$IDp,0,'','','1','rtl',0,'',$pstate,'','55px');
                      print select_option('freestate','',',',$IDfreestate,0,'','','1','rtl',0,'',$freestate,'','55px');
                      print select_option('IDkindstate','',',',$IDkindstate,0,'','','1','rtl',0,'',$kindstate,'','55px');                   
						print select_option('DesignerCoid','',',',$ID15,0,'','','1','rtl',0,'',$DesignerCoid,'','55px');                   
                     
                     
?>

					<td  class="data">تاریخ از:</td> <td><input placeholder="انتخاب تاریخ"  name="Datefrom" type="text" class="textbox" id="Datefrom" 
                      value="<?php if (strlen($Datefrom)>0) { echo $Datefrom;} else {echo '1393/01/01'; } ?>" size="9" maxlength="10" />
					 </td> <td> تا:</td> <td>
                      <input placeholder="انتخاب تاریخ" name="Dateto" type="text" class="textbox" id="Dateto" 
                      value="<?php if (strlen($Dateto)>0) { echo $Dateto;} else {echo gregorian_to_jalali(date('Y-m-d')); } ?>" size="9" maxlength="10" />
					  </td>
                     
		
	
<?php 						 
                     
					print "<td colspan='1' class='label'>همه</td>
                     <td class='data'><input name='showa' type='checkbox' id='showa'";
					if ($showa>0) echo 'checked';
					 print " /></td>";
				
				if ($login_RolesID==18 || $login_RolesID==1)
					{
						print "<td colspan='1' class='label'>بروزرسانی</td>
						<td class='data'><input name='showupdate' type='checkbox' id='showupdate'";
						if ($showupdate>0) echo 'checked';
						print " /></td>";
					}
					print "<td colspan='1' class='label'>انعقاد قرارداد</td>
                     <td class='data'><input name='showm' type='checkbox' id='showm'";
						if ($showm>0) echo 'checked';
					 print " /></td>					 
					<td colspan=\"2\"><input   name=\"submit\" type=\"submit\" class=\"button\" id=\"submit\" size=\"12\" value=\"جستجو\" /></td>";
                    
                    if ($login_RolesID==18 || $login_RolesID==32)
                    echo " 
                    
                      <td colspan='18'><input name='tempsubmit2' type='submit' class='button' id='tempsubmit2' 
                      
                      value='ارجاع کلی' title=\"ارجاع طرح های بیشتر از دو پیشنهاد دهنده\" /></td>
                      
                     ";
                    
						 
						 
                         /*
                         
                         <td class=\"f7_font$b'\"><a  target='".$target."' href='../temp/proposerankpercent.html'>
                         <img style = 'width: 25px;' src='../img/chart.png' title=' نمودار درصد رتبه پيشنهاد دهندگان انتخابي '></a></td>";
						<?php $uid3="../reports/Chartsql.php?uid=3";  $uid2="../reports/Chartsql.php?uid=2"; ?>          			
						<td colspan='2' class=\"f7_font$b'\"><a  target='_blank' onclick='myFunction("3")'>
                         <img style = 'width: 25px;' src='../img/chart.png' title='نمودار حجم کالا'></a>نمودار حجم لوله</td>

						 
						<td colspan='6' class=\"f7_font$b'\"><a  target='_blank' onclick='myFunction("2")'>
                         <img style = 'width: 25px;' src='../img/chart.png' title='نمودار تعداد پیش فاکتور'></a>نمودار تعداد پیش فاکتور</td>
     	 
						<input name="uid3" type="hidden" class="textbox" id="uid3"  value="<?php echo $uid3; ?>"  />
						<input name="uid2" type="hidden" class="textbox" id="uid2"  value="<?php echo $uid2; ?>"  />

				   	 
		*/				         ?>


				   
		</tr>
				   
				   
			   
                   </tbody>
                                     
                </table>
				<p></p>
                <table id="records" width="95%" align="center">
                   
                    <thead>
                        
                </table>
                    </thead>
                    <thead>
                    </thead>     
                   <tbody> 
                            
                  
                        
						<input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  />
				  
                   <?php
				    
                  $fiz= "فی <br>ریال";
                   if ($uid==2)
                   {
						if ($login_RolesID=='18' || $login_RolesID=='1' || $login_RolesID=='32')
						{$display='';$col=30;$hidden='';$colm=6;}
						else if ($login_RolesID=='20')
						{$display='display:none';$col=22;$hidden='hidden';$colm=3; $fiz="قسط";}
						else
						{$display='display:none';$col=25;$hidden='hidden';$colm=3;}
						
						 
						echo "
						 <table align=\"center\" border=\"1\" id=\"table2\">  
                         
                        <thead><tr> 

                            <td colspan=\"$col\"
                            <span class=\"f14_fontcb\" >اطلاعات تکمیلی  طرحهای کم فشار آبرسانی انتقال آب با لوله(مبالغ به میلیون ریال)
                            
                            </span>  </td>
							</tr>
                            <tr>
						
                            <th  
                           	<span class=\"f11_fontb\" > رديف  </span> </th>
							<th  
                           	<span class=\"f11_fontb\" > کد  </span> </th>
							<th 
                           	<span class=\"f13_fontb\"> نام</span> </th>
							<th 
                           	<span class=\"f13_fontb\">  نام خانوادگی </span> </th>
							<th 
                           	<span class=\"f13_fontb\">  کد ملی </span> </th>
                            
					    <th  <span style=\"$display\" class=\"f13_fontb\">نام پدر</span> </th>
					    <th  <span style=\"$display\" class=\"f13_fontb\">ش ش</span> </th>
					    <th  <span  class=\"f13_fontb\">مختصات</span> </th>
                        
							<th 
                           	<span class=\"f14_fontb\">  تلفن </span> </th>
							
                             
						    <th <span class=\"f10_fontb\">دشت/ شهرستان</span> </th>
						    <th <span class=\"f12_fontb\">شهر</span> </th>
						    <th <span class=\"f12_fontb\">روستا</span> </th>
						    <th <span class=\"f8_fontb\">مساحت ha</span> </th>
							<th <span class=\"f8_fontb\"> سایز </span> (mm)  </th>
							<th <span class=\"f8_fontb\"> متراژ </span> (m)  </th>
							<th <span class=\"f9_fontb\">مواد</span> </th>
							<th <span class=\"f9_fontb\"> فشار </span> (at)  </th>
						    <th  <span class=\"f10_fontb\">تناژ</span> </th>
						    <th  <span class=\"f11_fontb\">فی</span> </th>
						    <th  <span class=\"f9_fontb\">بلاعوض</span> </th>
						    <th  <span class=\"f8_fontb\">خودیاری</span> </th>
						
					    <th  <span class=\"f11_fontb\">مبلغ کل</span> </th>
                   	    <th  <span class=\"f11_fontb\">قسط</span> </th>
                   	    <th  <span class=\"f14_fontb\">تاریخ</span> </th>
						<th colspan=2 <span class=\"f12_fontb\">وضعیت</span> </th>
						
						<th  <span style=\"$display\" class=\"f11_fontb\">برنده پیشنهاد</span> </th>
					    <th  <span style=\"$display\" class=\"f12_fontb\">مبلغ آزادسازی</span> </th>
					   <th  colspan=2 <span style=\"$display\" class=\"f12_fontb\">پیشنهاد آزادسازی تولید تحویل</span> </th>
					
                        </tr>
                        <tr >    
							<td></td>
                            ".
								select_option('IDsandoghcode','',',',$IDsandoghcode,0,'','','1','rtl',0,'',$IDsandoghcodeval,'','100%').
								select_option('ApplicantFname','',',',$ID1,0,'','','1','rtl',0,'',$ApplicantFnameval,'','100%').
								select_option('ApplicantName','',',',$ID2,0,'','',$colm,'rtl',0,'',$ApplicantNameval,'','100%').
								select_option('sos','',',',$ID5,0,'','','1','rtl',0,'',$sosval,'','100%').
								select_option('bakhsh','',',',$ID4,0,'','','2','rtl',0,'',$bakhshval,'','100%'). 
								select_option('size11','',',',$ID6,5,'','','2','rtl',0,'',$size11val,'','100%'). 
								select_option('Number','',',',$Number,0,'','','1','rtl',0,'',$Numberval,'','100%').
								select_option('material','',',',$ID3,0,'','','1','rtl',0,'',$materialval,'','100%').
								select_option('fesharzekhamathajm','',',',$ID7,0,'','','7','rtl',0,'',$fesharzekhamathajmval,'','100%').
					     
								select_option('dateID','',',',$ID12,0,'','','1','rtl',0,'',$dateIDval,'','100%').
								select_option('proposestatetitle','',',',$ID11,0,'','','1','rtl',0,'',$proposestatetitleval,'','100%').
								select_option('level','',',',$ID16,0,'','','1','rtl',0,'',' ','','100%').
								select_option('ProducerscowinTitle','',',',$ID13,0,'','','2','rtl',0,'',$ProducerscowinTitleval,'','100%',$hidden). 
					     
					       "</tr>
                           
                        </thead>
                        
                        ";
                   }
                   else
                   {
                        echo "
								 <table align=\"center\" border=\"1\" id=\"table2\">  
               
						<thead><tr> 
                            <td colspan=\"24\"
                            <span class=\"f14_fontcb\" >لیست طرح های پیشنهاد قیمت شده لوله پلی اتیلن (مبالغ به میلیون ریال)
                            <a  target='_blank' href='allapplicantrequestws.php?uid=2'>اطلاعات تکمیلی</a>
                            </span>  </td>
							
							<td colspan='2' class=\"f7_fontc\" style=\"background-color:#ffffff;\" >حجم<a  target='_blank' href='../temp/producepipeton.html'>
								<img style = 'width: 20px;' src='../img/chart.png' title='نمودار حجم کالا'></a>  </td>
							<td colspan='2'  class=\"f7_fontc\" style=\"background-color:#ffffff;\">تعداد<a  target='_blank' href='../temp/producepipenum.html'>
								<img style = 'width: 20px;' src='../img/chart.png' title='نمودار تعداد پیش فاکتور'></a> </td>

				
                            </tr>
                            
                            <tr>
                            <th  
                           	<span class=\"f9_fontb\" > رديف  </span> </th>
								<th  
                           	<span class=\"f11_fontb\" > کد  </span> </th>
						
							<th 
                           	<span class=\"f14_fontb\"> نام</span> </th>
							<th 
                           	<span class=\"f13_fontb\">  نام خانوادگی </span> </th>
							<th 
                           	<span class=\"f12_fontb\">  کد ملی / تلفن</span> </th>
							
                             
						    <th <span class=\"f12_fontb\">دشت/ شهرستان</span> </th>
						    <th <span class=\"f13_fontb\">شهر</span> </th>
						    <th <span class=\"f13_fontb\">روستا</span> </th>
						    <th <span class=\"f10_fontb\">مساحت ha</span> </th>
							<th <span class=\"f11_fontb\"> سایز </span> (mm)  </th>
							<th <span class=\"f11_fontb\"> متراژ </span> (m)  </th>
						    <th <span class=\"f10_fontb\">مواد</span> </th>
							<th <span class=\"f10_fontb\"> فشار </span> (at)  </th>
							<th <span class=\"f11_fontb\"> تناژ </span> (kg)  </th>
                            
						    <th  <span class=\"f11_fontb\"> $fiz </span> </th>
							<th  <span class=\"f10_fontb\">بلاعوض</span> </th>
						    <th  <span class=\"f10_fontb\">خودیاری</span> </th>
						    <th  <span class=\"f11_fontb\">تناژ × فی </span> </th>
							<th  <span class=\"f12_fontb\">کد رهگیری</span> </th>
							<th colspan=2 <span class=\"f12_fontb\">وضعیت</span> </th>
							<th  <span class=\"f11_fontb\">تاریخ</span> </th>
						    <th  <span class=\"f11_fontb\">برنده پیشنهاد</span> </th>
						    <th  <span class=\"f11_fontb\">کاربر</span> </th>
							<th width=\"2%\"></th>
							<th width=\"2%\"></th>
                        </tr><thead/>
                        
                        
                        
                        
                          <tr>    
							<td class=\"f14_font\"></td>
                            ".
							select_option('IDsandoghcode','',',',$IDsandoghcode,0,'','','1','rtl',0,'',$IDsandoghcodeval,'','100%').
							select_option('sandoghcode','',',',$ID1,0,'','','1','rtl',0,'',$ApplicantFnameval,'','100%').
							select_option('ApplicantName','',',',$ID2,0,'','','2','rtl',0,'',$ApplicantNameval,'','100%').
							select_option('sos','',',',$ID5,0,'','','1','rtl',0,'',$sosval,'','100%').
							select_option('bakhsh','',',',$ID4,0,'','','2','rtl',0,'',$bakhshval,'','100%'). 
							select_option('size11','',',',$ID6,5,'','','2','rtl',0,'',$size11val,'','100%'). 
                        	select_option('Number','',',',$Number,0,'','','1','rtl',0,'',$Numberval,'','100%').
                        	select_option('material','',',',$ID3,0,'','','1','rtl',0,'',$materialval,'','100%').
							select_option('fesharzekhamathajm','',',',$ID7,0,'','','6','rtl',0,'',$fesharzekhamathajmval,'','100%').
					       
                           
							select_option('BankCode','',',',$ID10,0,'','','1','rtl',0,'',$BankCodeval,'','100%').
							
							select_option('proposestatetitle','',',',$ID11,0,'','','1','rtl',0,'',$proposestatetitleval,'','100%').
							select_option('level','',',',$ID16,0,'','','1','rtl',0,'',' ','','100%').
							select_option('dateID','',',',$ID12,0,'','','1','rtl',0,'',$dateIDval,'','100%').
							select_option('ProducerscowinTitle','',',',$ID13,0,'','','1','rtl',0,'',$ProducerscowinTitleval,'','100%'). 
							select_option('name','',',',$ID14,0,'','','1','rtl',0,'',$nameval,'','100%').
                           " </tr>";
                   }
                   
                    ?>

                        
                         
                        
                   <?php
                   
                   
        
                   $sumtonaj=0;
                   $sum40=0;
                   $sum80=0;
                   $sum100=0;
                   $sumDA=0;
                   $sumM=0;
                   $sumprice=0;
                   $sumSELF=0;
                   $sumbela=0;
                   $sumhektar=0;
                   $rown=0;
                   $totalbelaavaz=0;
                   $sumpproposecntcnt=0;
                   $rep_shahrlevel=array();
                   $rep_shahr=array();
                    while($row = mysql_fetch_assoc($result))
                    {
                    
                    $pipestate=$row['pstate'];
						if ($pstateorder==1 && $pipestate!='درحال تولید') continue;
						if ($pstateorder==2 && $pipestate!='تحویل') continue;
                        
						if ($freestateorder==1 && $row['freestate']!='آزادسازی شده') continue;
						if ($freestateorder==2 && $row['freestate']!='آزادسازی نشده') continue;
                        
                        
                        
                        
                       if ( ($login_RolesID=='31' || $login_RolesID=='13' || $login_RolesID=='14') && ($showa==0 || $login_userid!=564) )
                         {
                            $permit=0;
                            //print_r($ClerkIDExcellentSupervisorID);
                            foreach ($ClerkIDWaterInspectorID as $key => $value)
                            {
                                //print substr($row["CityId"],0,4)."_".$key."<br>";
                                if (substr($row["CityId"],0,4)==$key and $login_userid==$value)
                                    $permit=1;   
                            }
                            if ($permit==0)
                                continue;        
                         }
                         
                            

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
                        if ($row['operatorcoid']>0)
                        $ID = $row['ApplicantMasterID'].'_5_'.$row['operatorcoid'].'_'.$row['ProducersID'].'_'.$selectedCityId;
                        else
                        
                        $ID = $row['ApplicantMasterID'].'_5_0_'.$row['ProducersID'].'_'.$selectedCityId;
                        
                        $ApplicantName = $row['ApplicantName'];
                        $ApplicantFName = $row['ApplicantFName'];
                        $year = $row['year'];
                        $CostPriceListMasterID=$row['CostPriceListMasterID'];
                        
                        $sumDA+=$row['Number'];
                        
                        
                        
                        //print $showprice;
                        if ($row['material']=='PE80')
                        $sumL=($row['PE80app']*$row['tonaj'])*(1+$row['taxpercentvalue']/100);
                        else
                        $sumL=($row['PE100app']*$row['tonaj'])*(1+$row['taxpercentvalue']/100);
                        
                        if ($sumL<=0)
                            $sumL=$row['TotlainvoiceValues'];
                        
                        //print $sumL;
                        $selfhelp=0;
                        //$selfhelp=round($sumL*15/100);
                        //print $row['applicantstategroupsID'].$row['laststatedate'];
                        //if ($row['ApplicantMasterID']!=9130) continue;
						 
						if ($row['level']==1)
							$selfhelp=(60000*$row['tonaj'])*(1+$row['taxpercentvalue']/100)*0.15;
                         else if ($row['level']<6)
							$selfhelp=$row['Number']*selfws($row['size11'],$row['fesharzekhamathajm']);
						else
							$selfhelp=$sumL*15/85;
					
                        $belcolor='blue';
                        $selcolor='blue';
                        if ($row['belaavaz']>0)
                        {
                            $sumL=$row['belaavaz'];
                            $belcolor='';
                        }
                        if ($row['selfcashhelpval']>0)
                        {
                            $selfhelp=$row['selfcashhelpval'];
							$selcolor='';	
                        }
                        /*else 
                        {
                             $query = "UPDATE applicantmaster SET 
                   selfcashhelpval='$selfhelp',belaavaz='$sumL'
		                  WHERE ApplicantMasterID = '$row[ApplicantMasterID]';";
                         // print $query;exit;
                            mysql_query($query);
                   
                        }*/
                        
                       /* $query = "UPDATE applicantmaster SET 
                        
                            selfcashhelpval=(select self from Sheet1 where applicantmaster.sandoghcode=Sheet1.code),
                            belaavaz=(select bela from Sheet1 where applicantmaster.sandoghcode=Sheet1.code)
		                  WHERE ApplicantMasterID = '$row[ApplicantMasterID]';";
                         // print $query;exit;
                            mysql_query($query);
                         */   
                        
                        $sumSELF+=$selfhelp;
                        $bela=$sumL;
                        $sumbela+=$bela;
                        $hektar=round($bela/32000000,1);
                        $sumhektar+=$hektar;
                        if ($row['material']=='PE80') $sumM+=$row['PE80app']*$row['tonaj']; else $sumM+=$row['PE100app']*$row['tonaj'];
                        
                        $sumtonaj+=$row['tonaj'];
						$sumprice+=$row['price'];
                        $sum40+=$row['PE40tonaj'];
                        $sum80+=$row['PE80tonaj'];
                        $sum100+=$row['PE100tonaj'];
						$sumpproposecntcnt+=$row['pproposecntcnt'];
                        $rown++;
                        if ($rown%2==1) 
                        $b='b'; else $b='';
					    
                        $rep_shahr["$row[shahrcityname]"]["$row[applicantstategroupsID]"]["cnt"]++;
                        $rep_shahr["$row[shahrcityname]"]["$row[applicantstategroupsID]"]["Number"]+=$row['Number'];
                        $rep_shahr["$row[shahrcityname]"]["$row[applicantstategroupsID]"]["tonaj"]+=$row['tonaj'];
                        $rep_shahr["$row[shahrcityname]"]["$row[applicantstategroupsID]"]["sumL"]+=$sumL;
                        $rep_shahr["$row[shahrcityname]"]["$row[applicantstategroupsID]"]["selfhelp"]+=$selfhelp;
                        $rep_shahr["$row[shahrcityname]"]["$row[applicantstategroupsID]"]["bela"]+=$bela;
                        $rep_shahr["$row[shahrcityname]"][1]["wsquotaval"]=$row['wsquotaval'];
                        
						
						
						
                        $rep_shahrlevel["$row[shahrcityname]"]["$row[applicantstategroupsID]"]["$row[level]"]["cnt"]++;
                        $rep_shahrlevel["$row[shahrcityname]"]["$row[applicantstategroupsID]"]["$row[level]"]["Number"]+=$row['Number'];
                        $rep_shahrlevel["$row[shahrcityname]"]["$row[applicantstategroupsID]"]["$row[level]"]["tonaj"]+=$row['tonaj'];
                        $rep_shahrlevel["$row[shahrcityname]"]["$row[applicantstategroupsID]"]["$row[level]"]["sumL"]+=$sumL;
                        $rep_shahrlevel["$row[shahrcityname]"]["$row[applicantstategroupsID]"]["$row[level]"]["selfhelp"]+=$selfhelp;
                        $rep_shahrlevel["$row[shahrcityname]"]["$row[applicantstategroupsID]"]["$row[level]"]["bela"]+=$bela;
                        $rep_shahrlevel["$row[shahrcityname]"][1]["$row[level]"]["wsquotaval"]=$row['wsquotaval'];
                        
                        $br="<br>";
						$br=" ";
						if ($uid==2)
                        {
						$br=" ";
                            $linearray = explode('_',$row['CountyName']);
                            $CountyName=$linearray[0];
                            $fathername=$linearray[2];
                            $shenasnamecode=$linearray[4];
                        ?>
                        <tr>    
						
                		<td <span class="f9_font<?php echo $b; ?>"  > 
								<?php if ($login_RolesID=='1' || $login_RolesID=='27' || $login_RolesID=='32' || $login_RolesID=='31' || $login_RolesID=='18' || $login_RolesID=='22' || $login_RolesID=='23') 
							{
							if ( 
						        ($row['proposestate']>=1 && $row['ClerkIDwin']<>24 && $row['ClerkIDwin']<>83)
								|| 
								($row['proposestate']>=2  && $row['ClerkIDwin']<>24 && $row['ClerkIDwin']<>83) 
								)
									echo "<a  target='".$target."' href='allapplicantrequestdetailchart.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
								rand(10000,99999).$ID.rand(10000,99999)."'><img style = 'width: 30%;' src='../img/chart.png' title=' دامنه متناسب پیشنهاد قیمت '>$rown</a> "; 
								
							else echo $rown;
							
							}	
							else echo $rown;
								
                                if ($login_designerCO==1)
                                echo $br."(".$row['ApplicantMasterID'].")";
                               //$br="<br><font color='gray'>";
                                 ?> </span>  
							</td>
						    <td <span class="f8_font<?php echo $b; ?>">  <?php echo $row['sandoghcode']; ?> </span> </td>
                        	
                            <td <span class="f8_font<?php echo $b; ?>">  <?php echo $ApplicantFName; ?> </span> </td>
                            <td <span class="f8_font<?php echo $b; ?>">  <?php echo $ApplicantName; ?> </span> </td>
                            <td <span class="f8_font<?php echo $b; ?>">  <?php echo $row['melicode']; ?> </span> </td>
                            
						   <td <span style="<?php echo $display;?>" class="f8_font<?php echo $b; ?>">  <?php echo $fathername;?> </span> </td>
                           <td <span style="<?php echo $display;?>" class="f8_font<?php echo $b; ?>">  <?php echo $shenasnamecode;?> </span> </td>
                           <td <span  class="f7_font<?php echo $b; ?>">  <?php 
                           $UTM="";
                           if ($row["XUTM1"]>0) $UTM="X=".number_format($row["XUTM1"],0,'','').$br."Y=".number_format($row["YUTM1"],0,'','');
                           echo $UTM;?> </span> </td>
                           
                            <td <span class="f8_font<?php echo $b; ?>">  <?php echo $row['mobile']; ?> </span> </td>
                            <td <span class="f10_font<?php echo $b; ?>">  <?php echo $row['shahrcityname']; ?> </span> </td>
                            <td <span class="f8_font<?php echo $b; ?>">  <?php echo $row['bakhshcityname']; ?> </span> </td>
                            <td <span class="f7_font<?php echo $b; ?>">  <?php 
                            
                            echo $CountyName   ; ?> </span> </td>
                           
                            <td <span class="f9_font<?php echo $b; ?>">  <?php echo $hektar; ?> </span> </td>
                            <td <span class="f10_font<?php echo $b; ?>">  <?php echo $row['size11']; ?> </span> </td>
                            <td <span class="f8_font<?php echo $b; ?>">  <?php echo $row['Number']; ?> </span> </td>
							    <td <span class="f8_font<?php echo $b; ?>">  <?php echo $row['material']; ?> </span> </td>
                            <td <span class="f8_font<?php echo $b; ?>">  <?php echo $row['fesharzekhamathajm']; ?> </span> </td>
                        
							   <td <span class="f8_font<?php echo $b; ?>">  <?php echo $row['tonaj']; ?> </span> </td>
                         
						    <td <span class="f8_font<?php echo $b; ?>">  <?php if ($row['material']=='PE80') echo $row['PE80app']; else echo $row['PE100app'];  ?> </span> </td>
							
							<td <span class="f10_font<?php echo $b; ?>">  <?php echo round($sumL/1000000,1);
                             ?> </span> </td>
                        
                             <td <span class="f10_font<?php echo $b; ?>">  <?php echo round($selfhelp/1000000,1);
                             ?> </span> </td>
						
                            <td <span class="f10_font<?php echo $b; ?>">  <?php 
                            if ($row['material']=='PE80') $fdt=$row['PE80app']*$row['tonaj']; else $fdt=$row['PE100app']*$row['tonaj'];
                            
                            if ($fdt>0) echo round($fdt/1000000,1);
                            //echo round(($selfhelp+$bela)/1000000,1);
                             ?> </span> </td>
                            <td <span class="f10_font<?php echo $b; ?>">  <?php $free=($selfhelp+$bela)*0.9/1000000; echo round($free,2);
                             ?> </span> </td>
                             
							 
					
							<td	<span class="f9_font<?php echo $b; ?>"> </span>
							<?php echo gregorian_to_jalali( $row['Windate']);?> </td>
                            <td colspan="1"	<span class="f8_font<?php echo $b; ?>"> </span>
							<?php if ($login_RolesID=='18' || $login_RolesID=='27' || $login_RolesID=='32' || $login_designerCO==1)
							//echo $row['proposestateptitle'].$br."($row[pproposecntcnt])"; 
							//else 		
							echo $row['proposestateptitle']; 
						
							?> </td>
							<td <span style="<?php echo $display;?>" class="f9_font<?php echo $b; ?>"><?php echo $row['level'];
							?> </span> </td>  

							
							<td <span style="<?php echo $display;?>" class="f9_font<?php echo $b; ?>"><?php echo $row['ProducerscowinTitle'];
							?> </span> </td>  

						   <td <span style="<?php echo $display;?>" class="f9_font<?php echo $b; ?>">  <?php echo $row['price'];?> </span> </td>
            
						   <td <span style="<?php echo $display;?>" class="f9_font<?php echo $b; ?>">  <?php echo  
								gregorian_to_jalali( $row['producerapprequestSaveDate']).' '.compelete_date($row['letterdate']).' '. 
								gregorian_to_jalali($row['ApproveP']).' '. gregorian_to_jalali($row['ApproveA']);?> </span> </td>
						   <td <span style="<?php echo $display;?>" class="f9_font<?php echo $b; ?>">  <?php echo $row['validday'];?> </span> </td>
			
                             </tr>
							 
			 		
                            <?php
                        }
                        else
                        {
						
                        
?>                      
                        <tr>    
							<td <span class="f9_font<?php echo $b; ?>"  > 
					<?php if ($login_RolesID=='1' || $login_RolesID=='27' || $login_RolesID=='32' || $login_RolesID=='31' || $login_RolesID=='18' || $login_RolesID=='22' || $login_RolesID=='23') 
							{
							if ( 
						        ($row['proposestate']>=1 && $row['ClerkIDwin']<>24 && $row['ClerkIDwin']<>83)
								|| 
								($row['proposestate']>=2  && $row['ClerkIDwin']<>24 && $row['ClerkIDwin']<>83) 
								)
								echo "<a  target='".$target."' href='allapplicantrequestdetailchart.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
								rand(10000,99999).$ID.rand(10000,99999)."'><img style = 'width: 25%;' src='../img/chart.png' title=' دامنه متناسب پیشنهاد قیمت '>$rown</a> "; 
								
								else echo $rown;
							
							}	
								else echo $rown;
								
                                if ($login_designerCO==1)
                                echo $br."(".$row['ApplicantMasterID'].")";
                               //$br="<br><font color='gray'>";
                                 ?> </span>  </td>
							<td <span class="f8_font<?php echo $b; ?>">  <?php echo $row['sandoghcode']; ?> </span> </td>
                        	<td <span class="f9_font<?php echo $b; ?>">  <?php echo $ApplicantFName; ?> </span> </td>
                            <td <span class="f10_font<?php echo $b; ?>">  <?php echo $ApplicantName; ?> </span> </td>
                            <td <span class="f8_font<?php echo $b; ?>">  <?php echo $row['melicode'].'  '.$row['mobile']; ?> </span> </td>
                            <td <span class="f9_font<?php echo $b; ?>">  <?php echo $row['shahrcityname']; ?> </span> </td>
                            <td <span class="f8_font<?php echo $b; ?>">  <?php echo $row['bakhshcityname']; ?> </span> </td>
                            <td <span class="f7_font<?php echo $b; ?>">  <?php 
                            $linearray = explode('_',$row['CountyName']);
                            $CountyName=$linearray[0];
                            echo $CountyName   ; ?> </span> </td>
                           
                            <td <span class="f10_font<?php echo $b; ?>">  <?php echo $hektar; ?> </span> </td>
                            <td <span class="f10_font<?php echo $b; ?>">  <?php echo $row['size11']; ?> </span> </td>
                            <td <span class="f8_font<?php echo $b; ?>">  <?php echo $row['Number']; ?> </span> </td>
                            <td <span class="f8_font<?php echo $b; ?>">  <?php echo $row['material']; ?> </span> </td>
                            <td <span class="f8_font<?php echo $b; ?>">  <?php echo $row['fesharzekhamathajm']; ?> </span> </td>
                            <td <span class="f8_font<?php echo $b; ?>">  <?php echo $row['tonaj']; ?> </span> </td>
                            <td <span class="f10_font<?php echo $b; ?>">  <?php 
							if ($login_RolesID==7) {$free=($selfhelp+$bela)*0.9/1000000; echo round($free,2);}
							else
							{
							if ($row['material']=='PE80') echo $row['PE80app']; else echo $row['PE100app']; 
							}
							?> </font></span> </td>
                            
                             <td <span class="f10_font<?php echo $b; ?>">  <font color='<?php echo $belcolor; ?>' ><?php echo round($bela/1000000,1);
                             ?> </font></span> </td>
							   <td <span class="f10_font<?php echo $b; ?>">  <font color='<?php echo $selcolor; ?>' ><?php echo round($selfhelp/1000000,1);
                             ?> </font></span> </td>
                         
                             <td <span class="f10_font<?php echo $b; ?>">  <?php 
                             if ($row['material']=='PE80') $fdt=$row['PE80app']*$row['tonaj']; else $fdt=$row['PE100app']*$row['tonaj'];
                            
                            if ($fdt>0) echo round($fdt/1000000,1);
                             
                             //echo round(($selfhelp+$bela)/1000000,1);
                             ?> </span> </td>
                                                     
							<td <span class="f8_font<?php echo $b; ?>"> </span><?php echo $row['BankCode']; ?> </td>
						    
							<td colspan="2" <span class="f8_font<?php echo $b; ?>"> </span>
							<?php if ($login_RolesID=='18' || $login_RolesID=='27' || $login_designerCO==1)
							echo $row['proposestateptitle'].$br."($row[pproposecntcnt])"; 
							else 		
							echo $row['proposestateptitle']; 
						
							?> </td>
                            
						<td <span class="f9_font<?php echo $b; ?>"> </span>	<?php echo gregorian_to_jalali( $row['Windate']);?> </td>
                        <td <span class="f9_font<?php echo $b; ?>"> 		<?php echo $row['ProducerscowinTitle'];	?> </span> </td>  
						
					 
						<td <span class="f7_font<?php echo $b; ?>">  <?php 
                            
                                $encrypted_string=$row['CPI'];
                                $encryption_key="!@#$8^&*";
                                $decrypted_string="";
                                for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
                                    $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
                                $encrypted_string=$row['DVFS'];
                                $encryption_key="!@#$8^&*";
                                $decrypted_string.=" ";
                                for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
                                    $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
                            
                                echo str_replace(' ', '&nbsp;', $decrypted_string); 
                                
                                $searchpng='../img/search.png';
                                $ID = $row['ApplicantMasterID'].'_4_'.$row['DesignerCoID'].'_'.$row['operatorcoid'].'_'.$row['applicantstatesID'];
                                $target='_blank';
                                ?> </span> </td>  
	
                            <td class='no-print'><a  target='<?php echo $target;?>' href=<?php print "applicantstates_detail.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = "width: 20px;" src="../img/refresh.png" title=' مشاهده ریز عملیات ' ></a></td>
                            <td class='no-print'><a  target='<?php echo $target;?>' href=<?php
                             
                            //if ($login_RolesID==29)
                           // {
							     
                                
                            //}
                            
                                                                      
                            print "../insert/summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            $row['ApplicantMasterID'].'_3_0_0_'.$row['applicantstatesID'].rand(10000,99999); ?>>
                            <img style = 'width: 20px;' src=<?php echo $searchpng;?> title=' ريز '></a></td>
                            
                            <?php
                            $imgtarget="../img/file-edit-icon.png";
							$permitrolsid = array("1", "32","5","6","11","18","19","20","31","32","16","17");if (in_array($login_RolesID, $permitrolsid))
                            print "<td class='no-print'><a target='".$target."' href='applicant_manageredit.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$row['ApplicantMasterID'].'_5_'.$row['DesignerCoID'].'_'.$row['operatorcoid'].rand(10000,99999).
                            "'><img style = 'width: 20px;' src='$imgtarget' title=' ويرايش '></a></td>"; 
                            
                                                  
                            if ($showupdate==1 && $login_RolesID==1)
                            {
                                $rettt= file_get_contents("http://".$_SERVER['HTTP_HOST']."/$home_path_iri/insert/summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            $row['ApplicantMasterID'].'_3_0_0_'.$row['applicantstatesID'].rand(10000,99999));
                            
                            
                            }

                            
           if ($row['applicantstategroupsID']==6)     
		   $imgtarget="../img/dolar.jpg";
           else    
		   $imgtarget="../img/dolar2.jpg";
		   
							//$permitrolsid = array("1", "32","5","11","18","19","20","31","32","16");if (in_array($login_RolesID, $permitrolsid))
							$permitrolsid = array("1", "19","16","18","31","32","6");
                            if (in_array($login_RolesID, $permitrolsid))
                            print "<td class='no-print'><a target='".$target."' href='invoicemasterfree_list.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$row['ApplicantMasterID'].'_5_'.$row['DesignerCoID'].'_'.$row['OperatorCoID'].'_'.$row['ProducersIDw'].rand(10000,99999).
                            "'><img style = 'width: 20px;' src='$imgtarget' title=' آزادسازی '></a></td>"; 
                         
                        
                        
                        	if ($row['ApproveA']>0)
                                $imgtable='table.png';
                                    else if ($row['ApproveP']>0)
                                    $imgtable='table2.png';
                                    else $imgtable='table3.png';
                                    
							$alert28="target='".$target."' href='product_timing.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$row['ApplicantMasterID'].'_6_'.$login_userid.rand(10000,99999).
                            "'";
                            if ($login_RolesID==29 )
                            {
							
							   if ( in_array($row['applicantstatesID'], array("23","32","42","47")))
                                 $alert28="onClick=\"alert('پیش فاکتور در حال تغییر توسط مجری می باشد \\n با مدیریت آب و خاک تماس گرفته شود!');return;\"";
                            }
                            
                                                     						 	            
                           $permitrolsid = array("1","13","14","18","32","29","20","21","23");if (in_array($login_RolesID, $permitrolsid))
                            print "<td class='no-print'><a $alert28 ><img style = 'width: 20px;' src='../img/$imgtable' title=' زمانبندی و تحویل کالا '></a></td>";
                            
        
                            
                            
        $hasa=0;$errorsay="";
        $lennp = (strtotime(date('Y-m-d')) - strtotime($row['surveyDate']))/3600;                        
        if (($login_RolesID==32 || $login_RolesID==31)&& $row['surveyDate']>0)
        {
			 //print $lennp;exit;
             if ($lennp>$Permissionvals['hourcntforproposepselection']) 
             {
                $errorsay.="\\n با توجه به اتمام زمان انتخاب پیشنهاد قیمت لطفا با مدیر آب و خاک هماهنگ شود";
             }	   
        }
       					
	  if (($login_RolesID==18 || $login_RolesID==1) && ($row['proposestatep']==0))
	     {			
			 $lenn = (strtotime(date('Y-m-d')) - strtotime($row['laststatedate']))/86400;		
			 if ($lenn<$Permissionvals['proposedaycnt']) $errorsay.="\\n زمان پیشنهاد قیمت به اتمام نرسیده است!";
			else if (date('Y-m-d')< '2018-03-20') $errorsay.="\\n زمان پیشنهاد قیمت به اتمام نرسیده است!";
			 if ($row['pproposecntcnt']<$Permissionvals['proposenumcnt']) $errorsay.="\\n تعداد پیشنهاد دهنده به حد نصاب نرسیده است!";	
			 
  		 }
	 
	   $linksay="target='".$target."' href='allapplicantrequestdetail2.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
						rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999)."'";      
	   if ($errorsay)	$linksay="onClick=\"	alert('اخطار: $errorsay');\" ";
       else if (($login_RolesID==32 || $login_RolesID==31)&& $row['proposestatep']==2 && !($row['surveyDate']>0) )
       {
        $linksay.="onClick=\"return confirm('مهلت انتخاب برنده پیشنهاد قیمت حداکثر $Permissionvals[hourcntforproposepselection] ساعت پس از باز کردن پیشنهاد قیمت می باشد. آیا صفجه پیشنهاد قیمت باز شود؟');\"";
  	     
       }
 							if ($row['pproposecntcnt'])
							if (
								   ($login_RolesID=='18' || $login_RolesID=='22' || $login_RolesID=='27' || $login_RolesID=='13'  || $login_RolesID=='14')
								|| ($login_RolesID=='32' && $row['proposestatep']>=1 && $row['ClerkIDwin']<>24 && $row['ClerkIDwin']<>83)
								|| ($login_RolesID=='31' && $row['proposestatep']>=2  && $row['ClerkIDwin']<>24
                                 && $row['ClerkIDwin']<>83) 
								||  $login_designerCO==1
								    )
                                {
                                    echo "<td class='no-print'><a ".$linksay.
                                    "><img style = 'width: 20px;' src='../img/pipe.jpg' title=' پیشنهادهای لوله '></a></td>"; 
                                    
                                    if ($array[$row['ApplicantMasterID']]>0)
                                    echo "
                                        <td class='no-print'><a 
                                        href=\"allapplicantrequestdetail_changeop.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                        rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['ApplicantMasterID'].'_'.
                                        $array[$row['ApplicantMasterID']].'_0'.rand(10000,99999)."\"
                                        onClick=\"return confirm('مطمئن هستید که مجری تغییر پیدا کند؟');\"
                                        > <img style = 'width: 20px;' src='../img/refresh.png' title='تغییر مجری'> </a></td>";
                                 }
                                else 
                                    echo '<td/>';
									
							if ($login_RolesID=='18' && ($lennp>$Permissionvals['hourcntforproposepselection'])&& $row['surveyDate']>0)
							{
								echo "<td class='no-print'><a 
												href=\"../appinvestigation/allapplicantrequestdetail_extend.php?uid=".rand(10000,99999).rand(10000,99999).
												rand(10000,99999).rand(10000,99999).rand(10000,99999).
												rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['ApplicantMasterID'].rand(10000,99999)."\"
												onClick=\"return confirm('مجوز بازکردن پیشنهاد قیمت به ناظر عالی داده شود؟');\"
												> <img style = 'width: 20px;' src='../img/lock.jpg' title='اعطاء مجوز'> </a></td>"; 
							}
					
				$permitrolsid = array("1","13","14","17","18","32","20","21","23","31","32","33","10");if (in_array($login_RolesID, $permitrolsid))
							echo "<td class='no-print'><a href='../insert/applicant_timing.php?uid=".rand(10000,99999).rand(10000,99999).
								rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
								rand(10000,99999).$row['ApplicantMasterID'].'_4'.rand(10000,99999)."'>
							   <img style = 'width: 20px;' src='../img/calendar_empty.jpg' title=' ثبت جدول زمانبندي اجرا'></a></td>";
                         
                         
                         
                         
                          $permitrolsid = array("1","13","14","17","18","32","20","21","23","31","32","33");if (in_array($login_RolesID, $permitrolsid))
                            { 
										$titr='  اطلاعات تكميلي سیستم و محصولات '.$ApplicantFName.' '.$ApplicantName.' شهرستان '.$row['shahrcityname'];
                                        $IDf = 'applicantsystemtype_'.$titr.'_0_ApplicantMasterID_'.$row['ApplicantMasterID'];
									   echo "<td class='no-print'> <a href='../codding/codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                       rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$IDf.
                                       rand(10000,99999)."' target=\"_blank\" >
                                     <img style = 'width: 20px;' src='../img/giah.jpg' title=' اطلاعات تكميلي سیستم و محصولات'></a></td>";
                                      $titr=' اطلاعات تكميلي منبع آبی طرح '.$ApplicantFName.' '.$ApplicantName.' شهرستان '.$row['shahrcityname'];
                                        $IDf = 'applicantwsource_'.$titr.'_0_ApplicantMasterID_'.$row['ApplicantMasterID'];
									   echo "<td class='no-print'> <a href='../codding/codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                       rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$IDf.
                                       rand(10000,99999)."' target=\"_blank\" >
                                     <img style = 'width: 20px;' src='../img/ab.jpg' title=' اطلاعات تكميلي منبع آبی'></a></td>";
                                     
                            } 
                            
                            if ($login_RolesID==1 || $login_RolesID==10 )
                            {
                             //مدیرمشاور ناظر یا مدیر پیگیری لیست لوازم را مشاهده می کنند { ?>
        							<td><a href=<?php print "../insert/invoicemaster_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['ApplicantMasterID'].'_0-'.$row['applicantstatesID'].rand(10000,99999); ?>>
                                    <img style = 'width: 20px;' src='../img/full_page.png' title=' مشاهده لیست پیش فاکتور/لیست لوازمها '></a></td>
                                    <?php }      
                         
                         print "</tr>";
                        
                        }
                    }
                    //کاربران مدیر مهندسین مشاور امکان مشاهده جدول خلاصه پایین فرم را ندارند
                    if ($login_RolesID==10) exit();
?>
                        <tr>
                            <td colspan="<?php if ($uid==2) echo '13'; else echo '15' ;?>" class="f14_fontcb" ><?php echo ' مجموع متراژ (m)';   ?></td>
                            <td colspan="9"
                            class="f14_fontcb" 
                            ><?php echo $sumDA;   ?></td>
							
						<?php if ($uid==2 && $col==30) { ?>	 <td colspan="8" rowspan="6"  class='f14_fontcb'><?php echo 'مجموع آزادسازی<br>'.number_format($sumprice).'<br>ریال';   ?></td> <?php } ?>
                			
                        </tr>
						<?php if ($uid!=2) { ?>
                         <tr>
                            
                            <td colspan="<?php if ($uid==2) echo '11'; else echo '15' ;?>" class="f14_fontcb" ><?php echo 'مجموع تناژ (تن)';   ?></td>
                            <td colspan="9" 
                            class="f14_fontcb" 
                            ><?php echo round($sumtonaj/1000,1);   ?></td>
                        </tr> 
                         <?php } ?>
                        <tr>
                       
                	     
                            <td colspan="<?php if ($uid==2) echo '13'; else echo '15' ;?>" class="f14_fontcb" ><?php echo ' مجموع تناژ × فی';   ?></td>
                            <td colspan="9" 
                            class="f14_fontcb" 
                            ><?php echo number_format($sumM);   ?></td>
                        </tr>
                   
                        <tr>
                       
                	     
                            <td colspan="<?php if ($uid==2) echo '13'; else echo '15' ;?>" class="f14_fontcb" ><?php echo ' مجموع مساحت (هکتار)';   ?></td>
                            <td colspan="9" 
                            class="f14_fontcb" 
                            ><?php echo number_format($sumhektar);   ?></td>
                        </tr>
                   
                   
                   
                   
                        <tr>
                            <td colspan="<?php if ($uid==2) echo '13'; else echo '15' ;?>" class="f14_fontcb" ><?php echo ' مجموع مبلغ خودیاری';   ?></td>
                            <td colspan="9" 
                            class="f14_fontcb" 
                            ><?php echo number_format($sumSELF);   ?></td>
                        </tr>
                        <tr>
                            <td colspan="<?php if ($uid==2) echo '13'; else echo '15' ;?>" class="f14_fontcb" ><?php echo ' تعداد پیشنهادات';   ?></td>
                            <td colspan="9" 
                            class="f14_fontcb" 
                            ><?php echo $sumpproposecntcnt;   ?></td>
                        </tr>
                   
                        <tr>
             
                	        
                            <td colspan="<?php if ($uid==2) echo '13'; else echo '15' ;?>" class="f14_fontcb" ><?php echo ' مجموع مبلغ بلاعوض';   ?></td>
                            <td colspan="9" 
                            class="f14_fontcb" 
                            ><?php echo number_format($sumbela); 
							
	//if ($uid!=2) { 						
							 ?>
                            </td></tr>
                            
                            <tr><td> &nbsp </td></tr></table>
							<font color='blue'>*------ بلاعوض تایید نشده</font>
							<?php
							print "
							<p id='psh_newpricetable' ></p>
                            <table>
							
                            <thead>
							
							
							<tr> 
							<td colspan=\"2\"><input class='no-print' id='chksh_newpricetable' type='checkbox' onChange=\"setpagereak('psh_newpricetable')\"/><label class='no-print'>چاپ در ابتدای صفحه</label></td>
							
                            <td colspan=\"29\"
                            <span class=\"f14_fontcb\" >گزارش وضعیت طرح های سیستم های نوین آبیاری کم فشار انتقال آب با لوله (مبالغ به میلیون ریال)</span>  </td>
                            </tr></thead>
                            
                            <tr>
                            <td rowspan=2  <span class=\"f9_fontb\">ردیف</span> </td>
                            <td rowspan=2 colspan=1 <span class=\"f13_fontb\">دشت/ شهرستان</span> </td>
                            <td colspan=4 <span class=\"f13_fontb\">تکمیل مدارک</span> </td>
                            <td colspan=4 <span class=\"f13_fontb\">مدیریت آب و خاک</span> </td>
                            <td colspan=4 <span class=\"f13_fontb\">بانک</span> </td>
						    <th colspan=4 <span class=\"f13_fontb\">صندوق</span> </th>
						    <th colspan=4 <span class=\"f13_fontb\">انعقاد قرارداد</span> </th>
						    <th colspan=4 <span class=\"f13_fontb\">آزادسازی شده</span> </th>
                            
                            <td colspan=5 <span class=\"f13_fontb\">جمع لوله</span> </td>
                            </tr>
                            
                            <tr>
                            <td colspan=1 <span class=\"f9_fontb\">تعداد</span> </td>
                            <td colspan=1 <span class=\"f9_fontb\">متراژ (کیلومتر)</span> </td>
                            <td colspan=1 <span class=\"f9_fontb\">تناژ (تن)</span> </td>
						    <th colspan=1 <span class=\"f9_fontb\">بلاعوض (م ر)</span> </th>
                            
                            <td colspan=1 <span class=\"f9_fontb\">تعداد</span> </td>
                            <td colspan=1 <span class=\"f9_fontb\">متراژ (کیلومتر)</span> </td>
                            <td colspan=1 <span class=\"f9_fontb\">تناژ (تن)</span> </td>
						    <th colspan=1 <span class=\"f9_fontb\">بلاعوض (م ر)</span> </th>
                            
                            <td colspan=1 <span class=\"f9_fontb\">تعداد</span> </td>
                            <td colspan=1 <span class=\"f9_fontb\">متراژ (کیلومتر)</span> </td>
                            <td colspan=1 <span class=\"f9_fontb\">تناژ (تن)</span> </td>
						    <th colspan=1 <span class=\"f9_fontb\">بلاعوض (م ر)</span> </th>
                            
                            <td colspan=1 <span class=\"f9_fontb\">تعداد</span> </td>
                            <td colspan=1 <span class=\"f9_fontb\">متراژ (کیلومتر)</span> </td>
                            <td colspan=1 <span class=\"f9_fontb\">تناژ (تن)</span> </td>
						    <th colspan=1 <span class=\"f9_fontb\">بلاعوض (م ر)</span> </th>
                            
                            <td colspan=1 <span class=\"f9_fontb\">تعداد</span> </td>
                            <td colspan=1 <span class=\"f9_fontb\">متراژ (کیلومتر)</span> </td>
                            <td colspan=1 <span class=\"f9_fontb\">تناژ (تن)</span> </td>
						    <th colspan=1 <span class=\"f9_fontb\">بلاعوض (م ر)</span> </th>
                            
                            <td colspan=1 <span class=\"f9_fontb\">تعداد</span> </td>
                            <td colspan=1 <span class=\"f9_fontb\">متراژ (کیلومتر)</span> </td>
                            <td colspan=1 <span class=\"f9_fontb\">تناژ (تن)</span> </td>
						    <th colspan=1 <span class=\"f9_fontb\">بلاعوض (م ر)</span> </th>
                            
                            <td colspan=1 <span class=\"f12_fontb\">تعداد</span> </td>
                            <td colspan=1 <span class=\"f12_fontb\">متراژ (کیلومتر)</span> </td>
                            <td colspan=1 <span class=\"f12_fontb\">تناژ (تن)</span> </td>
						    <th colspan=1 <span class=\"f12_fontb\">بلاعوض (م ر)</span> </th>
						    <th colspan=1 <span class=\"f12_fontb\">سهمیه</span> </th>
                            </tr>
                            
                            ";
                            
                            
                            
                    //foreach($rep_shahr as $title => $valu)
                    print "";
                    $counter=0;
					$cnt1=0;$Number1=0;$tonaj1=0;$bela1=0;
					$cnt2=0;$Number2=0;$tonaj2=0;$bela2=0;
					$cnt3=0;$Number3=0;$tonaj3=0;$bela3=0;
					$cnt4=0;$Number4=0;$tonaj4=0;$bela4=0;
					$cnt5=0;$Number5=0;$tonaj5=0;$bela5=0;
					$cnt6=0;$Number6=0;$tonaj6=0;$bela6=0;
                    
                    //print_r($rep_shahr);
                    $allwsquotaval=0;
                    foreach($shahrID as $titleall => $valuall)
                    {
                        if ($valuall<=0) continue;
                        $allwsquotaval+=$valuall;
                        $counter++;
					    if ($counter%2==1) 
                        $b=''; else $b='b';
						$cnt1=$rep_shahr[$titleall][1]['cnt']+$cnt1;
							$Number1=$rep_shahr[$titleall][1]['Number']/1000+$Number1;
							$tonaj1=$rep_shahr[$titleall][1]['tonaj']/1000+$tonaj1;
							$bela1=$rep_shahr[$titleall][1]['bela']/1000000+$bela1;
						$cnt2=$rep_shahr[$titleall][2]['cnt']+$cnt2;
							$Number2=$rep_shahr[$titleall][2]['Number']/1000+$Number2;
							$tonaj2=$rep_shahr[$titleall][2]['tonaj']/1000+$tonaj2;
							$bela2=$rep_shahr[$titleall][2]['bela']/1000000+$bela2;
						$cnt3=$rep_shahr[$titleall][3]['cnt']+$cnt3;
							$Number3=$rep_shahr[$titleall][3]['Number']/1000+$Number3;
							$tonaj3=$rep_shahr[$titleall][3]['tonaj']/1000+$tonaj3;
							$bela3=$rep_shahr[$titleall][3]['bela']/1000000+$bela3;
						$cnt4=$rep_shahr[$titleall][4]['cnt']+$cnt4;
							$Number4=$rep_shahr[$titleall][4]['Number']/1000+$Number4;
							$tonaj4=$rep_shahr[$titleall][4]['tonaj']/1000+$tonaj4;
							$bela4=$rep_shahr[$titleall][4]['bela']/1000000+$bela4;
                            
						$cnt5=$rep_shahr[$titleall][5]['cnt']+$cnt5;
							$Number5=$rep_shahr[$titleall][5]['Number']/1000+$Number5;
							$tonaj5=$rep_shahr[$titleall][5]['tonaj']/1000+$tonaj5;
							$bela5=$rep_shahr[$titleall][5]['bela']/1000000+$bela5;
						
						$cnt6=$rep_shahr[$titleall][6]['cnt']+$cnt6;
							$Number6=$rep_shahr[$titleall][6]['Number']/1000+$Number6;
							$tonaj6=$rep_shahr[$titleall][6]['tonaj']/1000+$tonaj6;
							$bela6=$rep_shahr[$titleall][6]['bela']/1000000+$bela6;
                            
                        $upped=0;
					   if ( (round($rep_shahr[$titleall][1]['bela']/1000000,1)+
                            round($rep_shahr[$titleall][2]['bela']/1000000,1)+round($rep_shahr[$titleall][3]['bela']/1000000,1)+
                            round($rep_shahr[$titleall][4]['bela']/1000000,1)+round($rep_shahr[$titleall][5]['bela']/1000000,1)+
                            +round($rep_shahr[$titleall][6]['bela']/1000000,1))>$rep_shahr[$titleall][1]['wsquotaval'] )
                            $upped=1;
                            $color1="";
                            $color2="";
						if ($upped==1)
                        {
                            $color1="<font color=\"red\">";
                            $color2="</font>";
                        }
                        
                         print "</td></tr>
                            
                            <tr>
                            <td  <span  class=\"f13_font$b\">$color1 $counter $color2</span> </td>
                            <td colspan=1 <span  class=\"f13_font$b\">$color1 $titleall $color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".$rep_shahr[$titleall][1]['cnt']."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahr[$titleall][1]['Number']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahr[$titleall][1]['tonaj']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahr[$titleall][1]['bela']/1000000,1)."$color2</span> </td>
                            
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".$rep_shahr[$titleall][2]['cnt']."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahr[$titleall][2]['Number']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahr[$titleall][2]['tonaj']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahr[$titleall][2]['bela']/1000000,1)."$color2</span> </td>
                            
                            
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".$rep_shahr[$titleall][3]['cnt']."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahr[$titleall][3]['Number']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahr[$titleall][3]['tonaj']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahr[$titleall][3]['bela']/1000000,1)."$color2</span> </td>
                            
                            
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".$rep_shahr[$titleall][4]['cnt']."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahr[$titleall][4]['Number']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahr[$titleall][4]['tonaj']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahr[$titleall][4]['bela']/1000000,1)."$color2</span> </td>
                            
                            
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".$rep_shahr[$titleall][5]['cnt']."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahr[$titleall][5]['Number']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahr[$titleall][5]['tonaj']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahr[$titleall][5]['bela']/1000000,1)."$color2</span> </td>
                            
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".$rep_shahr[$titleall][6]['cnt']."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahr[$titleall][6]['Number']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahr[$titleall][6]['tonaj']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahr[$titleall][6]['bela']/1000000,1)."$color2</span> </td>
                            
                            
                            
                            <td colspan=1 <span  class=\"f10_font$b\">$color1".($rep_shahr[$titleall][1]['cnt']+
                            $rep_shahr[$titleall][2]['cnt']+$rep_shahr[$titleall][3]['cnt']+$rep_shahr[$titleall][4]['cnt']+$rep_shahr[$titleall][5]['cnt']+$rep_shahr[$titleall][6]['cnt'])."$color2</span> </td>
                            <td colspan=1 <span  class=\"f10_font$b\">$color1".round(($rep_shahr[$titleall][1]['Number']+
                            $rep_shahr[$titleall][2]['Number']+$rep_shahr[$titleall][3]['Number']+$rep_shahr[$titleall][4]['Number']+$rep_shahr[$titleall][5]['Number']+$rep_shahr[$titleall][6]['Number'])/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f10_font$b\">$color1".round(($rep_shahr[$titleall][1]['tonaj']+
                            $rep_shahr[$titleall][2]['tonaj']+$rep_shahr[$titleall][3]['tonaj']+$rep_shahr[$titleall][4]['tonaj']+$rep_shahr[$titleall][6]['tonaj']+$rep_shahr[$titleall][5]['tonaj'])/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f10_font$b\">$color1".(round($rep_shahr[$titleall][1]['bela']/1000000,1)+
                            round($rep_shahr[$titleall][2]['bela']/1000000,1)+round($rep_shahr[$titleall][3]['bela']/1000000,1)+
                            round($rep_shahr[$titleall][4]['bela']/1000000,1)+round($rep_shahr[$titleall][5]['bela']/1000000,1)
                            +round($rep_shahr[$titleall][6]['bela']/1000000,1))."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1 $valuall $color2</span> </td>
                            
                            ";
                        //print $valu;
                        
                        print "</tr>";
			        }
					
					print "
					
					
					<tr>
					        <td rowspan=2 colspan=2 <span class=\"f13_fontb\">مجموع</span> </td>
                            
                            <td colspan=2 <span style=\"text-align:right\" class=\"f13_fontb\">$cnt1</span> </td>
                            <td colspan=2 <span  style=\"text-align:right\" class=\"f13_fontb\">".round($tonaj1,1)."</span> </td>
						     
						    <td colspan=2 <span  style=\"text-align:right\" class=\"f13_fontb\">$cnt2</span> </td>
                            <td colspan=2 <span  style=\"text-align:right\" class=\"f13_fontb\">".round($tonaj2,1)."</span> </td>
						    
						    <td colspan=2 <span  style=\"text-align:right\" class=\"f13_fontb\">$cnt3</span> </td>
                            <td colspan=2 <span  style=\"text-align:right\" class=\"f13_fontb\">".round($tonaj3,1)."</span> </td>
						     
						   
                            <td colspan=2 <span  style=\"text-align:right\"  class=\"f13_fontb\">$cnt4</span> </td>
                            <td colspan=2 <span  style=\"text-align:right\" class=\"f13_fontb\">".round($tonaj4,1)."</span> </td>
						     
						    <td colspan=2 <span  style=\"text-align:right\" class=\"f13_fontb\">$cnt5</span> </td>
                             <td colspan=2 <span  style=\"text-align:right\" class=\"f13_fontb\">".round($tonaj5,1)."</span> </td>
						     
						    <td colspan=2 <span  style=\"text-align:right\" class=\"f13_fontb\">$cnt6</span> </td>
                            <td colspan=2 <span  style=\"text-align:right\" class=\"f13_fontb\">".round($tonaj6,1)."</span> </td>
						     
						    <td colspan=2 <span  style=\"text-align:right\" class=\"f13_fontb\">".($cnt1+$cnt2+$cnt3+$cnt4+$cnt5+$cnt6)."</span> </td>
                             <td colspan=2 <span  style=\"text-align:right\" class=\"f13_fontb\">".round(($tonaj1+$tonaj2+$tonaj3+$tonaj4+$tonaj5+$tonaj6),1)."</span> </td>
						    <th rowspan=2 <span class=\"f13_fontb\">$allwsquotaval</span> </th>
                            
                            
                            
						  </tr>
                            
								<tr>
					         
                            <td colspan=2 <span  style=\"text-align:left\" class=\"f13_fontb\">".round($Number1,1)."</span> </td>
                            <th colspan=2 <span  style=\"text-align:left\" class=\"f13_fontb\">".round($bela1,1)."</span> </th>
                            
						    <td colspan=2 <span  style=\"text-align:left\" class=\"f13_fontb\">".round($Number2,1)."</span> </td>
                             <th colspan=2 <span  style=\"text-align:left\" class=\"f13_fontb\">".round($bela2,1)."</span> </th>
                            
						    <td colspan=2 <span  style=\"text-align:left\" class=\"f13_fontb\">".round($Number3,1)."</span> </td>
                            <th colspan=2 <span  style=\"text-align:left\" class=\"f13_fontb\">".round($bela3,1)."</span> </th>
                            
						   
                             <td colspan=2 <span  style=\"text-align:left\" class=\"f13_fontb\">".round($Number4,1)."</span> </td>
                            <th colspan=2 <span  style=\"text-align:left\" class=\"f13_fontb\">".round($bela4,1)."</span> </th>
                            
						     <td colspan=2 <span  style=\"text-align:left\" class=\"f13_fontb\">".round($Number5,1)."</span> </td>
                            <th colspan=2 <span  style=\"text-align:left\" class=\"f13_fontb\">".round($bela5,1)."</span> </th>
                            
						     <td colspan=2 <span  style=\"text-align:left\" class=\"f13_fontb\">".round($Number6,1)."</span> </td>
                             <th colspan=2 <span  style=\"text-align:left\" class=\"f13_fontb\">".round($bela6,1)."</span> </th>
                            
						    <td colspan=2 <span  style=\"text-align:left\" class=\"f13_fontb\">".round(($Number1+$Number2+$Number3+$Number4+$Number5+$Number6),1)."</span> </td>
                            <th colspan=2 <span  style=\"text-align:left\" class=\"f13_fontb\">".round(($bela1+$bela2+$bela3+$bela4+$bela5+$bela6),1)."</span> </th>
						    
                            
                            
						  </tr>
                    
							
							
							
                            ";
          // }                 
/////////////////////////////////////////////////////////////////
//$uid=3;
$level='';
	if ($uid==3) { 	
	               foreach($ID16 as $level => $levelssss)
			     //for ($level=1;$level<4;$level++)
					{
					   if (!($level>0) || $level==9)
                        continue;
					//if ($level==2) exit;
                        print "</td></tr>
                            
                            <tr><td>&nbsp</td></tr></table>
							
							<p id='psh_newpricetable' ></p>
                            <table>
							
                            <thead>
							
							
							<tr> 
							<td colspan=\"2\"><input class='no-print' id='chksh_newpricetable' type='checkbox' onChange=\"setpagereak('psh_newpricetable')\"/><label class='no-print'>چاپ در ابتدای صفحه</label></td>
							
                            <td colspan=\"25\"
                            <span class=\"f14_fontcb\" >گزارش وضعیت طرحهای انتقال آب با لوله (مرحله$level)</span>  </td>
                            </tr></thead>
                            
                            <tr>
                            <td rowspan=2  <span class=\"f9_fontb\">ردیف</span> </td>
                            <td rowspan=2 colspan=1 <span class=\"f13_fontb\">دشت/ شهرستان</span> </td>
                            <td colspan=4 <span class=\"f13_fontb\">تکمیل مدارک</span> </td>
                            <td colspan=4 <span class=\"f13_fontb\">مدیریت آب و خاک</span> </td>
                            <td colspan=4 <span class=\"f13_fontb\">بانک</span> </td>
						    <th colspan=4 <span class=\"f13_fontb\">صندوق</span> </th>
						    <th colspan=4 <span class=\"f13_fontb\">انعقاد قرارداد</span> </th>
                            
                            <td colspan=5 <span class=\"f13_fontb\">جمع لوله</span> </td>
                            </tr>
                            
                            <tr>
                            <td colspan=1 <span class=\"f12_fontb\">تعداد</span> </td>
                            <td colspan=1 <span class=\"f12_fontb\">متراژ (کیلومتر)</span> </td>
                            <td colspan=1 <span class=\"f12_fontb\">تناژ (تن)</span> </td>
						    <th colspan=1 <span class=\"f12_fontb\">بلاعوض (م ر)</span> </th>
                            
                            <td colspan=1 <span class=\"f12_fontb\">تعداد</span> </td>
                            <td colspan=1 <span class=\"f12_fontb\">متراژ (کیلومتر)</span> </td>
                            <td colspan=1 <span class=\"f12_fontb\">تناژ (تن)</span> </td>
						    <th colspan=1 <span class=\"f12_fontb\">بلاعوض (م ر)</span> </th>
                            
                            <td colspan=1 <span class=\"f12_fontb\">تعداد</span> </td>
                            <td colspan=1 <span class=\"f12_fontb\">متراژ (کیلومتر)</span> </td>
                            <td colspan=1 <span class=\"f12_fontb\">تناژ (تن)</span> </td>
						    <th colspan=1 <span class=\"f12_fontb\">بلاعوض (م ر)</span> </th>
                            
                            <td colspan=1 <span class=\"f12_fontb\">تعداد</span> </td>
                            <td colspan=1 <span class=\"f12_fontb\">متراژ (کیلومتر)</span> </td>
                            <td colspan=1 <span class=\"f12_fontb\">تناژ (تن)</span> </td>
						    <th colspan=1 <span class=\"f12_fontb\">بلاعوض (م ر)</span> </th>
                            
                            <td colspan=1 <span class=\"f12_fontb\">تعداد</span> </td>
                            <td colspan=1 <span class=\"f12_fontb\">متراژ (کیلومتر)</span> </td>
                            <td colspan=1 <span class=\"f12_fontb\">تناژ (تن)</span> </td>
						    <th colspan=1 <span class=\"f12_fontb\">بلاعوض (م ر)</span> </th>
                            
                            
                            <td colspan=1 <span class=\"f12_fontb\">تعداد</span> </td>
                            <td colspan=1 <span class=\"f12_fontb\">متراژ (کیلومتر)</span> </td>
                            <td colspan=1 <span class=\"f12_fontb\">تناژ (تن)</span> </td>
						    <th colspan=1 <span class=\"f12_fontb\">بلاعوض (م ر)</span> </th>
						    <th colspan=1 <span class=\"f12_fontb\">سهمیه</span> </th>
                            </tr>
                            
                            ";
                            
                            
                            
                    //foreach($rep_shahr as $title => $valu)
                    print "";
                    $counter=0;
					$cnt1=0;$Number1=0;$tonaj1=0;$bela1=0;
					$cnt2=0;$Number2=0;$tonaj2=0;$bela2=0;
					$cnt3=0;$Number3=0;$tonaj3=0;$bela3=0;
					$cnt4=0;$Number4=0;$tonaj4=0;$bela4=0;
					$cnt5=0;$Number5=0;$tonaj5=0;$bela5=0;
					$cnt6=0;$Number6=0;$tonaj6=0;$bela6=0;
                    
                    //print_r($rep_shahr);
                    $allwsquotaval=0;
                    foreach($shahrID as $titleall => $valuall)
                    {
                        if ($valuall<=0) continue;
                        $allwsquotaval+=$valuall;
                        $counter++;
					    if ($counter%2==1) 
                        $b=''; else $b='b';
						$cnt1=$rep_shahrlevel[$titleall][1][$level]['cnt']+$cnt1;
							$Number1=$rep_shahrlevel[$titleall][1][$level]['Number']/1000+$Number1;
							$tonaj1=$rep_shahrlevel[$titleall][1][$level]['tonaj']/1000+$tonaj1;
							$bela1=$rep_shahrlevel[$titleall][1][$level]['bela']/1000000+$bela1;
						$cnt2=$rep_shahrlevel[$titleall][2][$level]['cnt']+$cnt2;
							$Number2=$rep_shahrlevel[$titleall][$level][2]['Number']/1000+$Number2;
							$tonaj2=$rep_shahrlevel[$titleall][$level][2]['tonaj']/1000+$tonaj2;
							$bela2=$rep_shahrlevel[$titleall][$level][2]['bela']/1000000+$bela2;
						$cnt3=$rep_shahrlevel[$titleall][3][$level]['cnt']+$cnt3;
							$Number3=$rep_shahrlevel[$titleall][3][$level]['Number']/1000+$Number3;
							$tonaj3=$rep_shahrlevel[$titleall][3][$level]['tonaj']/1000+$tonaj3;
							$bela3=$rep_shahrlevel[$titleall][3][$level]['bela']/1000000+$bela3;
						$cnt4=$rep_shahrlevel[$titleall][4][$level]['cnt']+$cnt4;
							$Number4=$rep_shahrlevel[$titleall][4][$level]['Number']/1000+$Number4;
							$tonaj4=$rep_shahrlevel[$titleall][4][$level]['tonaj']/1000+$tonaj4;
							$bela4=$rep_shahrlevel[$titleall][4][$level]['bela']/1000000+$bela4;
						$cnt5=$rep_shahrlevel[$titleall][5][$level]['cnt']+$cnt5;
							$Number5=$rep_shahrlevel[$titleall][5][$level]['Number']/1000+$Number5;
							$tonaj5=$rep_shahrlevel[$titleall][5][$level]['tonaj']/1000+$tonaj5;
							$bela5=$rep_shahrlevel[$titleall][5][$level]['bela']/1000000+$bela5;
                            
						$cnt6=$rep_shahrlevel[$titleall][6][$level]['cnt']+$cnt6;
							$Number6=$rep_shahrlevel[$titleall][6][$level]['Number']/1000+$Number6;
							$tonaj6=$rep_shahrlevel[$titleall][6][$level]['tonaj']/1000+$tonaj6;
							$bela6=$rep_shahrlevel[$titleall][6][$level]['bela']/1000000+$bela6;
						
                        $upped=0;
					   if ( (round($rep_shahrlevel[$titleall][1][$level]['bela']/1000000,1)+
                            round($rep_shahrlevel[$titleall][2][$level]['bela']/1000000,1)+round($rep_shahrlevel[$titleall][3][$level]['bela']/1000000,1)+
                            round($rep_shahrlevel[$titleall][4][$level]['bela']/1000000,1)+round($rep_shahrlevel[$titleall][5][$level]['bela']/1000000,1)
                            +round($rep_shahrlevel[$titleall][6][$level]['bela']/1000000,1))>$rep_shahrlevel[$titleall][1][$level]['wsquotaval'] )
                            $upped=1;
                            $color1="";
                            $color2="";
						if ($upped==1)
                        {
                            $color1="<font color=\"red\">";
                            $color2="</font>";
                        }
                        
                         print "</td></tr>
                            
                            <tr>
                            <td  <span  class=\"f13_font$b\">$color1 $counter $color2</span> </td>
                            <td colspan=1 <span  class=\"f13_font$b\">$color1 $titleall $color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".$rep_shahrlevel[$titleall][1][$level]['cnt']."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahrlevel[$titleall][1][$level]['Number']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahrlevel[$titleall][1][$level]['tonaj']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahrlevel[$titleall][1][$level]['bela']/1000000,1)."$color2</span> </td>
                            
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".$rep_shahrlevel[$titleall][2][$level]['cnt']."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahrlevel[$titleall][2][$level]['Number']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahrlevel[$titleall][2][$level]['tonaj']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahrlevel[$titleall][2][$level]['bela']/1000000,1)."$color2</span> </td>
                            
                            
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".$rep_shahrlevel[$titleall][3][$level]['cnt']."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahrlevel[$titleall][3][$level]['Number']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahrlevel[$titleall][3][$level]['tonaj']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahrlevel[$titleall][3][$level]['bela']/1000000,1)."$color2</span> </td>
                            
                            
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".$rep_shahrlevel[$titleall][4][$level]['cnt']."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahrlevel[$titleall][4][$level]['Number']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahrlevel[$titleall][4][$level]['tonaj']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahrlevel[$titleall][4][$level]['bela']/1000000,1)."$color2</span> </td>
                            
                            
                            
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".$rep_shahrlevel[$titleall][5][$level]['cnt']."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahrlevel[$titleall][5][$level]['Number']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahrlevel[$titleall][5][$level]['tonaj']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahrlevel[$titleall][5][$level]['bela']/1000000,1)."$color2</span> </td>
                            
                            
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".$rep_shahrlevel[$titleall][6][$level]['cnt']."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahrlevel[$titleall][6][$level]['Number']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahrlevel[$titleall][6][$level]['tonaj']/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1".round($rep_shahrlevel[$titleall][6][$level]['bela']/1000000,1)."$color2</span> </td>
                            
                            
                            <td colspan=1 <span  class=\"f10_font$b\">$color1".($rep_shahrlevel[$titleall][1][$level]['cnt']+
                            $rep_shahrlevel[$titleall][2][$level]['cnt']+$rep_shahrlevel[$titleall][3][$level]['cnt']+$rep_shahrlevel[$titleall][4][$level]['cnt']+$rep_shahrlevel[$titleall][5][$level]['cnt']+$rep_shahrlevel[$titleall][6][$level]['cnt'])."$color2</span> </td>
                            <td colspan=1 <span  class=\"f10_font$b\">$color1".round(($rep_shahrlevel[$titleall][1][$level]['Number']+
                            $rep_shahrlevel[$titleall][2][$level]['Number']+$rep_shahrlevel[$titleall][3][$level]['Number']+$rep_shahrlevel[$titleall][4][$level]['Number']+$rep_shahrlevel[$titleall][5][$level]['Number']+$rep_shahrlevel[$titleall][6][$level]['Number'])/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f10_font$b\">$color1".round(($rep_shahrlevel[$titleall][1][$level]['tonaj']+
                            $rep_shahrlevellevel[$titleall][2][$level]['tonaj']+$rep_shahrlevellevel[$titleall][3][$level]['tonaj']+$rep_shahrlevel[$titleall][4][$level]['tonaj']+$rep_shahrlevel[$titleall][5][$level]['tonaj']+$rep_shahrlevel[$titleall][6][$level]['tonaj'])/1000,1)."$color2</span> </td>
                            <td colspan=1 <span  class=\"f10_font$b\">$color1".(round($rep_shahrlevel[$titleall][1][$level]['bela']/1000000,1)+
                            round($rep_shahrlevel[$titleall][2][$level]['bela']/1000000,1)+round($rep_shahrlevel[$titleall][3][$level]['bela']/1000000,1)+
                            round($rep_shahrlevel[$titleall][4][$level]['bela']/1000000,1)+round($rep_shahrlevel[$titleall][5][$level]['bela']/1000000,1)+round($rep_shahrlevel[$titleall][6][$level]['bela']/1000000,1))."$color2</span> </td>
                            <td colspan=1 <span  class=\"f9_font$b\">$color1 $valuall $color2</span> </td>
                            
                            ";
                        //print $valu;
                        
                        print "</tr>";
			        }
					
					print "
					
					
					<tr>
					        <td colspan=2 <span class=\"f13_fontb\">مجموع</span> </td>
                            
                            <td colspan=1 <span class=\"f13_fontb\">$cnt1</span> </td>
                            <td colspan=1 <span class=\"f13_fontb\">".round($Number1,1)."</span> </td>
                            <td colspan=1 <span class=\"f13_fontb\">".round($tonaj1,1)."</span> </td>
						    <th colspan=1 <span class=\"f13_fontb\">".round($bela1,1)."</span> </th>
                            
						    <td colspan=1 <span class=\"f13_fontb\">$cnt2</span> </td>
                            <td colspan=1 <span class=\"f13_fontb\">".round($Number2,1)."</span> </td>
                            <td colspan=1 <span class=\"f13_fontb\">".round($tonaj2,1)."</span> </td>
						    <th colspan=1 <span class=\"f13_fontb\">".round($bela2,1)."</span> </th>
                            
						    <td colspan=1 <span class=\"f13_fontb\">$cnt3</span> </td>
                            <td colspan=1 <span class=\"f13_fontb\">".round($Number3,1)."</span> </td>
                            <td colspan=1 <span class=\"f13_fontb\">".round($tonaj3,1)."</span> </td>
						    <th colspan=1 <span class=\"f13_fontb\">".round($bela3,1)."</span> </th>
                            
						   
                            <td colspan=1 <span class=\"f13_fontb\">$cnt4</span> </td>
                            <td colspan=1 <span class=\"f13_fontb\">".round($Number4,1)."</span> </td>
                            <td colspan=1 <span class=\"f13_fontb\">".round($tonaj4,1)."</span> </td>
						    <th colspan=1 <span class=\"f13_fontb\">".round($bela4,1)."</span> </th>
                            
						    <td colspan=1 <span class=\"f13_fontb\">$cnt5</span> </td>
                            <td colspan=1 <span class=\"f13_fontb\">".round($Number5,1)."</span> </td>
                            <td colspan=1 <span class=\"f13_fontb\">".round($tonaj5,1)."</span> </td>
						    <th colspan=1 <span class=\"f13_fontb\">".round($bela5,1)."</span> </th>
                            
						    <td colspan=1 <span class=\"f13_fontb\">$cnt6</span> </td>
                            <td colspan=1 <span class=\"f13_fontb\">".round($Number6,1)."</span> </td>
                            <td colspan=1 <span class=\"f13_fontb\">".round($tonaj6,1)."</span> </td>
						    <th colspan=1 <span class=\"f13_fontb\">".round($bela6,1)."</span> </th>
                            
						    <td colspan=1 <span class=\"f13_fontb\">".($cnt1+$cnt2+$cnt3+$cnt4+$cnt5+$cnt6)."</span> </td>
                            <td colspan=1 <span class=\"f13_fontb\">".round(($Number1+$Number2+$Number3+$Number4+$Number5+$Number6),1)."</span> </td>
                            <td colspan=1 <span class=\"f13_fontb\">".round(($tonaj1+$tonaj2+$tonaj3+$tonaj4+$tonaj5+$tonaj6),1)."</span> </td>
						    <th colspan=1 <span class=\"f13_fontb\">".round(($bela1+$bela2+$bela3+$bela4+$bela5+$bela6),1)."</span> </th>
						    <th colspan=1 <span class=\"f13_fontb\">$allwsquotaval</span> </th>
                            
                            
                            
						  </tr>
                            
                            ";
							
			}
           }                 
					
	
///////////////////////////////////////////////////////////////////					
			
















			
                             ?>
                   
				   
				   
				   
                </table>
                <tr ><td ></td>
                <td style='width:100%;'>*تکمیل مدارک: طرح هایی که پرونده آنها در دست اقدام بوده و به رئیس مهندسی زراعی ارسال نشده است.</td></tr>
                <br />
                <tr ><td ></td>
                <td style='width:100%;'>*مدیریت آب و خاک: طرح هایی که پرونده آنها تکمیل شده و به رئیس مهندسی زراعی ارسال شده است..</td></tr>
                
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
			<?php 
            include('../includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>

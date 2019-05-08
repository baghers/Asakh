<?php 

/*

//appinvestigation/allapplicantrequestp.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود

//appinvestigation/allapplicantrequestdetail2.php
-
*/
include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/functions.php');
include('../Chart.php');
if ($login_Permission_granted==0) header("Location: ../login.php");
$showa=0;//نمایش طرح های سایر شهرستان ها برای ناظرین عالی
$yearid='';//سال

$Permissionvals=supervisorcoderrquirement_sql($login_ostanId);//تابع دریافت اطلاعات پیکربندی سیستم 
// $smalha=$Permissionvals['smallapplicantsize'] حداکثر مساحت پروژه هایی که کوچک 
$smalha=$Permissionvals['smallapplicantsize']*$Permissionvals['percentapplicantsize']/100+$Permissionvals['smallapplicantsize'];


if ($_POST)
{
    $yearid=$_POST['YearID'];//سال
    $DesignAreafrom=$_POST['DesignAreafrom'];//از مساحت
    $DesignAreato=$_POST['DesignAreato'];//تا مساحت
   $DesignerCoIDbazras=$_POST['DesignerCoid'];//شناسه شرکت طراح
     
    
    
    if ($_POST['showa']=='on')//نمایش طرح های سایر شهرستان ها برای ناظرین عالی
        $showa=1;
        
    if (strlen(trim($_POST['proposestatetitle']))>0)//وضعیت پیشنهاد قیمت
        $str.=" and case ifnull(applicantmaster.proposestatep,0) when 0 then  
  'دریافت پیشنهاد'
  
  when 1 then concat('ارجاع به مدیر ',prjtype.title) when 2 then 'ارجاع به کارشناس/ناظر عالی'  
when 3 then 'تایید پیشنهاد'  else '' end='$_POST[proposestatetitle]'";

    if (strlen(trim($_POST['name']))>0)//نام کاربر
        $str.=" and clerkwin.ClerkID='$_POST[name]'";
    if (strlen(trim($_POST['ProducerscowinTitle']))>0)//شرکت مجری منتخب
        $str.=" and Producerscowin.Title='$_POST[ProducerscowinTitle]'";
    if (strlen(trim($_POST['dateID']))>0)//تاریخ انتخاب
        $str.=" and case reqwin.Windate>0 when 1 then reqwin.Windate else applicantmaster.TMDate end='$_POST[dateID]'";
    if (strlen(trim($_POST['BankCode']))>0)//کد رهگیری
        $str.=" and applicantmaster.BankCode='$_POST[BankCode]'";
    if (strlen(trim($_POST['creditsourcetitle']))>0)//منبع تامین اعتبار
        $str.=" and creditsource.title='$_POST[creditsourcetitle]'";
	if (strlen(trim($_POST['ApplicantFname']))>0)//عنوان پروژه
        $str.=" and applicantmaster.ApplicantFname like'%$_POST[ApplicantFname]%'";
	if (strlen(trim($_POST['ApplicantName']))>0)//عنوان پروژه
        $str.=" and applicantmaster.ApplicantName like '%$_POST[ApplicantName]%'";
	if (strlen(trim($_POST['DesignSystemGroupstitle']))>0)//سیستم آبیاری
        $str.=" and designsystemgroups.title like '%$_POST[DesignSystemGroupstitle]%'";
    if (strlen(trim($_POST['sos']))>0)//استان
        $str.=" and shahr.id='$_POST[sos]'";
    if (strlen(trim($_POST['operatorcoid']))>0)//شناسه پیمانکار
        $str.=" and applicantmaster.operatorcoid='$_POST[operatorcoid]'";
    
    if (strlen(trim($_POST['DesignAreafrom']))>0)//از مساحت
        $str.=" and applicantmaster.DesignArea>='$_POST[DesignAreafrom]'";
    if (strlen(trim($_POST['DesignAreato']))>0)//تا مساحت
        $str.=" and applicantmaster.DesignArea<='$_POST[DesignAreato]'";
    if (strlen(trim($_POST['sos']))>0)//استان
        $str.=" and shahr.id='$_POST[sos]'";
    if (strlen(trim($_POST['operatorcoid']))>0)//شناسه پیمانکار
        $str.=" and applicantmaster.operatorcoid='$_POST[operatorcoid]'";
    else if (strlen(trim($_POST['applicantstategroupsID']))>0)//شناسه وضعیت
        $str.=" and applicantstategroups.applicantstategroupsID='$_POST[applicantstategroupsID]'";     


	if (strlen(trim($_POST['DesignerCoid']))>0)//شناسه مشاور طراح
        $str.=" and  designerco.DesignerCoid='$_POST[DesignerCoid]'";
	
	
       if (strlen(trim($_POST['IDArea']))>0)//شناسه مساحت 
		if (trim($_POST['IDArea'])==1)
        $str.=" and applicantmaster.DesignArea>0 and applicantmaster.DesignArea<=10";
		else if (trim($_POST['IDArea'])==2)
        $str.=" and applicantmaster.DesignArea>10 and applicantmaster.DesignArea<=20";
		else if (trim($_POST['IDArea'])==3)
        $str.=" and applicantmaster.DesignArea>20 and applicantmaster.DesignArea<=50";
		else if (trim($_POST['IDArea'])==4)
        $str.=" and applicantmaster.DesignArea>50 and applicantmaster.DesignArea<=100";
		else if (trim($_POST['IDArea'])==5)
        $str.=" and applicantmaster.DesignArea>100 and applicantmaster.DesignArea<=200";
		else if (trim($_POST['IDArea'])==6)
        $str.=" and applicantmaster.DesignArea>200 and applicantmaster.DesignArea<=500";
		else if (trim($_POST['IDArea'])==7)
        $str.=" and applicantmaster.DesignArea>500 and applicantmaster.DesignArea<=1000";
		else if (trim($_POST['IDArea'])==8)
        $str.=" and applicantmaster.DesignArea>1000";
	
    if (strlen(trim($_POST['IDprice']))>0)//شناسه مبلغ	
        if (trim($_POST['IDprice'])==1)
		$str.=" and applicantmaster.LastTotal>0 and applicantmaster.LastTotal<=1000000000";
		else if (trim($_POST['IDprice'])==2)
		$str.=" and applicantmaster.LastTotal>1000000000 and applicantmaster.LastTotal<=1500000000";
		else if (trim($_POST['IDprice'])==3)
		$str.=" and applicantmaster.LastTotal>1500000000 and applicantmaster.LastTotal<=2000000000";
		else if (trim($_POST['IDprice'])==4)
		$str.=" and applicantmaster.LastTotal>2000000000 and applicantmaster.LastTotal<=3000000000";
		else if (trim($_POST['IDprice'])==5)
		$str.=" and applicantmaster.LastTotal>3000000000 and applicantmaster.LastTotal<=5000000000";
		else if (trim($_POST['IDprice'])==6)
		$str.=" and applicantmaster.LastTotal>5000000000 and applicantmaster.LastTotal<=8000000000";
		else if (trim($_POST['IDprice'])==7)
		$str.=" and applicantmaster.LastTotal>8000000000 and applicantmaster.LastTotal<=10000000000";
		else if (trim($_POST['IDprice'])==8)
		$str.=" and applicantmaster.LastTotal>10000000000";
        
    if (trim($_POST['IDbela'])==-2)//مبلغ بلاعوض
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
        

    $Datefrom=$_POST['Datefrom'];$dtf=jalali_to_gregorian($Datefrom);
    $Dateto=$_POST['Dateto'];$dtt=jalali_to_gregorian($Dateto);

		
}
$ch=chartpipe_sqle($dtf,$dtt);//ایجاد نمودار لوله


    

    $sql = "select distinct ApplicantMasterID as _value,ApplicantMasterID  as _key from producerapprequest ";
    $producerapprequest = get_key_value_from_query_into_array($sql);//آرایه کلید و مقدار طرح های پیشنهاد قیمت شده


    
    $sql = "SELECT value  FROM year where YearID='$yearid' ";
    $result = mysql_query($sql);
    $row = mysql_fetch_assoc($result);
    $yearvalue=$row['value'];//سال
        
 
  switch ($_POST['IDorder']) 
  {
    case 1: $orderby=' order by applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break; 
    case 2: $orderby=' order by applicantmaster.ApplicantFName COLLATE utf8_persian_ci'; break;
    case 3: $orderby=' order by applicantmaster.DesignArea'; break;
    case 4: $orderby=' order by DesignSystemGroupstitle'; break;    
    case 5: $orderby=' order by shahrcityname COLLATE utf8_persian_ci'; break;
    case 6: $orderby=' order by operatorcotitle COLLATE utf8_persian_ci'; break;
    case 7: $orderby=' order by proposestatep,reqwin.Windate desc,reqwin.ClerkID,applicantmaster.ApplicantMasterID'; break;
    default: $orderby=' order by proposestatep,reqwin.Windate desc,reqwin.ClerkID,applicantmaster.ApplicantMasterID'; break; 
  }
  
    //نقش هایی که امکان مشاهده پیشنهاد های  انتخاب نشده را دارند
  /*
  1 مدیرپیگیری
  18 مدیر آب و خاک
  13 مدیر آبیاری تحت فشار
  14 ناظر عالی
  17 ناظر مقیم شهرستان
  26 تجمیع
  27 مدیر سامانه ها
  */
  
$permitrolsidforviewrequest = array("1","18", "13","14","17","26","27");

  if (!in_array($login_RolesID, $permitrolsidforviewrequest))
    $str.=" and producerapprequest.state =1 ";
    
    
//if ($login_RolesID=='17') 
//    $str.=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) and (DesignArea<'$smalha' or RIGHT( CountyName,1)=1) ";
    
if ($login_RolesID=='17')//ناظر مقیم 
    $str.=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4)  ";
else if (($login_RolesID=='14') && ($showa==0))//ناظر عالی 
        $str.=" and substring(applicantmaster.cityid,1,4) 
        in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid') ";
else if ($login_RolesID=='26')//تجمیع
{$str.=" and ifnull(applicantmaster.criditType,0)=1 ";$showa=1;}
		
		
if ($showa==0)
{
     $str.=" and ifnull(applicantmaster.applicantstatesID,0) not in (30,35,38,34)";
    
}
    
$selectedCityId=$login_CityId;
if ($_POST['ostan']>0)
        $selectedCityId=$_POST['ostan'];
if ($_POST['clerksup']>0)
{
        $selectedsupId=$_POST['clerksup'];
$str.=" and substring(applicantmaster.cityid,1,4) 
        in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$selectedsupId') ";
}


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
}
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
$sql = "SELECT distinct RIGHT( CountyName,1) apps,applicantmaster.proposestatep,applicantmaster.surveyDate,applicantmaster.ApplicantMasterID,

applicantmaster.ApplicantFName,applicantmaster.ApplicantName,case reqwin.Windate>0 when 1 then reqwin.Windate else applicantmaster.TMDate end Windate  ,applicantmaster.BankCode,
applicantmaster.DesignArea,applicantmaster.LastTotal,applicantmaster.belaavaz,


designerco.DesignerCoid,designerco.Title DesignerCoIDbazrastitle,

pproposecnt.cnt pproposecntcnt
,operatorco.title operatorcotitle,operatorco.operatorcoid, 
shahr.cityname shahrcityname,shahr.id shahrid 
,applicantmaster.TMDate laststatedate
,creditsource.title creditsourcetitle,designsystemgroups.title DesignSystemGroupstitle,Producerscowin.Title ProducerscowinTitle
,clerkwin.CPI,clerkwin.DVFS,clerkwin.ClerkID ClerkIDwin
,reqwin.PE32app,reqwin.PE40app,reqwin.PE80app,reqwin.PE100app
,round(pproposetonaj.PE32tonaj,1) PE32tonaj
,round(pproposetonaj.PE40tonaj,1) PE40tonaj
,round(pproposetonaj.PE80tonaj,1) PE80tonaj
,round(pproposetonaj.PE100tonaj,1) PE100tonaj
,Producerscowin.ProducersID
,case ifnull(applicantmaster.proposestatep,0) when 0 then  
  'دریافت پیشنهاد'
  
  when 1 then concat('ارجاع به مدیر ',prjtype.title) when 2 then 'ارجاع به کارشناس/ناظر عالی'  
when 3 then 'تایید پیشنهاد'  else '' end proposestateptitle
 
,applicantmaster.applicantstatesID,applicantmasterdetail.prjtypeid,prjtype.title prjtypetitle
,applicantstates.title applicantstatestitle
FROM applicantmaster 
left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
left outer join operatorco on operatorco.operatorcoid=applicantmaster.operatorcoid
left outer join creditsource on creditsource.creditsourceid=applicantmaster.creditsourceid

inner join (SELECT count(*) cnt,case ApplicantMasterID>0 when 1 then ApplicantMasterID else -ApplicantMasterID end ApplicantMasterID FROM producerapprequest 
            group by ApplicantMasterID) pproposecnt on pproposecnt.ApplicantMasterID=applicantmaster.ApplicantMasterID


left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 
DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.DesignSystemGroupsid=applicantmaster.DesignSystemGroupsid
left outer join (select case ApplicantMasterID>0 when 1 then ApplicantMasterID else -ApplicantMasterID end ApplicantMasterID
,ProducersID,ClerkID,PE32app,PE40app,PE80app,PE100app,Windate from producerapprequest where state=1) reqwin on 
reqwin.ApplicantMasterID=applicantmaster.ApplicantMasterID
inner join (select distinct PE32tonaj ,PE40tonaj ,PE80tonaj ,PE100tonaj
            ,case ApplicantMasterID>0 when 1 then ApplicantMasterID else -ApplicantMasterID end ApplicantMasterID 
            FROM producerapprequest ) pproposetonaj on pproposetonaj.ApplicantMasterID=applicantmaster.ApplicantMasterID

left outer join producers Producerscowin on Producerscowin.ProducersID=reqwin.ProducersID
left outer join clerk clerkwin on clerkwin.ClerkID=reqwin.ClerkID

left outer join applicantstates on applicantstates.applicantstatesID=applicantmaster.applicantstatesID

left outer join clerk clerkbazras on clerkbazras.ClerkID=Producerscowin.ClerkIDexaminer

left outer join designerco on designerco.DesignerCoid=clerkbazras.MMC


 left outer join producerapprequest on case producerapprequest.ApplicantMasterID>0 when 1 then producerapprequest.ApplicantMasterID 
 else -producerapprequest.ApplicantMasterID end=applicantmaster.ApplicantMasterID and state=1 

inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDmaster=applicantmaster.ApplicantMasterID
and ifnull(applicantmasterdetail.prjtypeid,0)=0
left outer join prjtype on prjtype.prjtypeid=ifnull(applicantmasterdetail.prjtypeid,0)

where substring(applicantmaster.cityid,1,2)=substring('$selectedCityId',1,2)
   $str
$orderby ";
                 
$result = mysql_query($sql);

//print $sql;

    $ID1[' ']=' ';
    $ID2[' ']=' ';
    $ID4[' ']=' ';
    $ID5[' ']=' ';
    $ID6[' ']=' ';
    $ID9[' ']=' ';
    $ID10[' ']=' ';
    $ID11[' ']=' ';
    $ID12[' ']=' ';
    $ID13[' ']=' ';
    $ID14[' ']=' ';
    $ID15[' ']=' ';
    
while($row = mysql_fetch_assoc($result))
{
    $ID1[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);
    $ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);
    $ID4[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupstitle']);
    $ID5[trim($row['shahrcityname'])]=trim($row['shahrid']);
    $ID6[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);
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
//مرتب سازی آرایه های کلید و مقدار
$ID1=mykeyvalsort($ID1);
$ID2=mykeyvalsort($ID2);
$ID4=mykeyvalsort($ID4);
$ID5=mykeyvalsort($ID5);
$ID6=mykeyvalsort($ID6);
$ID9=mykeyvalsort($ID9);
$ID10=mykeyvalsort($ID10);
$ID11=mykeyvalsort($ID11);
$ID12=mykeyvalsort($ID12);
$ID13=mykeyvalsort($ID13);
$ID14=mykeyvalsort($ID14);
$ID15=mykeyvalsort($ID15);


mysql_data_seek( $result, 0 );

//پرس و جوی مربوط به کومبوباکس ترتیب
$query="
select 'نام خانوادگی' _key,1 as _value union all
select 'نام' _key,2 as _value union all 
select 'مساحت' _key,3 as _value union all
select 'نوع سیستم' _key,4 as _value union all
select 'شهرستان' _key,5 as _value union all
select 'شرکت طراح' _key,6 as _value union all
select 'وضعیت' _key,7 as _value ";
$IDorder = get_key_value_from_query_into_array($query);

if ($_POST['IDorder']>0)
    $IDorderval=$_POST['IDorder'];
    else 
    $IDorderval=7;

//پرس و جوی مربوط به کومبوباکس مساحت   
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
//پرس و جوی مربوط به کومبوباکس مبلغ کل 
 
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
//پرس و جوی مربوط به کومبوباکس مبلغ بلاعوض     
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



?>



<!DOCTYPE html>
<html>
<head>
  	<title>پیشنهاد قیمت لوله</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />


    <!-- /scripts -->
    <script>
function myFunction(ids) {
	
	if 	(ids==3) var uid=document.getElementById('uid3').value;
	if 	(ids==2) var uid=document.getElementById('uid2').value;
	window.open (uid,'_blank');
							
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
            
            <form action="allapplicantrequestp.php" method="post">
                <table width="95%" align="center">
                    <tbody class='no-print' >
                           <tr>
                            <?php 
                       /*    
                     $query="SELECT YearID as _value,Value as _key FROM `year` 
                     where YearID in (select YearID from cityquota)
                     
                     ORDER BY year.Value DESC";
    				 $ID = get_key_value_from_query_into_array($query);
                     print 
                     select_option('YearID','سهمیه',',',$ID,0,'','','1','rtl',0,'',$yearid,'','75');
                     */
                          
                     if ($login_designerCO==1)
                     {
                        $sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM tax_tbcity7digit ostan
                        where substring(ostan.id,3,5)='00000'
                        order by _key  COLLATE utf8_persian_ci";
                        $allg1idostan = get_key_value_from_query_into_array($sqlselect);
                        
                        print select_option('ostan','استان',',',$allg1idostan,0,'','','1','rtl',0,'',$selectedCityId,'','75');
                     }
                     
                        	$permitrolsid = array("1","18", "13");
				if (in_array($login_RolesID, $permitrolsid))
                     {
							$sqlsup = "select distinct clerk.CPI,clerk.DVFS,clerk.clerkid from tax_tbcity7digit 
								left outer join tax_tbcity7digit TAX_tbCity7Digitgardesh on substring(TAX_tbCity7Digitgardesh.id,1,4)=substring(tax_tbcity7digit.id,1,4) and substring(TAX_tbCity7Digitgardesh.id,5,3)!='000'
								left outer join clerk on clerk.clerkid=tax_tbcity7digit.ClerkIDExcellentSupervisor
								where substring(tax_tbcity7digit.id,1,2)='19' and substring(tax_tbcity7digit.id,5,3)='000' and substring(tax_tbcity7digit.id,3,4)!='0000' 
									";
							//print $sqlsup;
							$resultsup = mysql_query($sqlsup);
							$allg1idsup[' ']=' ';
							while($rowsup = mysql_fetch_assoc($resultsup))
							{
							$allg1idstr=trim(decrypt($rowsup['CPI'])." ".decrypt($rowsup['DVFS']));
							$allg1idsup[$allg1idstr]=trim($rowsup['clerkid']);
							}
							$allg1idsup=mykeyvalsort($allg1idsup);
							mysql_data_seek( $resultsup, 0 );
						//	print_r ($allg1idsup);
    				    print select_option('clerksup','کارشناس',',',$allg1idsup,0,'','','1','rtl',0,'',$selectedsupId,'','75');
					}
					
         
                           
                           ?>
                      <td  class="label">مساحت</td>
                      <td  class="data">&nbsp;از<input  name="DesignAreafrom" type="text" class="textbox" id="DesignAreafrom" 
                      value="<?php echo $DesignAreafrom ?>" size="1" maxlength="10" /></td>
                        
                    
                      <td class="data">تا<input name="DesignAreato" type="text" class="textbox" id="DesignAreato" 
                      value="<?php echo $DesignAreato  ?>" size="1" maxlength="10" /></td>
                     
                      <?php print select_option('IDorder','ترتیب',',',$IDorder,0,'','','3','rtl',0,'',$IDorderval,"",'100');
                      
                     
                     
?>
						<td colspan='2' class=\"f7_font$b'\"><a  target='_blank' href='../temp/producepipeton.html'>
                         <img style = 'width: 25px;' src='../img/chart.png' title='نمودار حجم کالا'></a> حجم لوله</td>

						 
						<td colspan='3' class=\"f7_font$b'\"><a  target='_blank' href='../temp/producepipenum.html'>
                         <img style = 'width: 25px;' src='../img/chart.png' title='نمودار تعداد پیش فاکتور'></a>تعداد </td>
                      <td  class="data">از:<input placeholder="انتخاب تاریخ"  name="Datefrom" type="text" class="textbox" id="Datefrom" 
                      value="<?php if (strlen($Datefrom)>0) { echo $Datefrom;} else {echo '1395/01/01'; } ?>" size="10" maxlength="10" />
					 تا:
                      <input placeholder="انتخاب تاریخ" name="Dateto" type="text" class="textbox" id="Dateto" 
                      value="<?php if (strlen($Dateto)>0) { echo $Dateto;} else {echo gregorian_to_jalali(date('Y-m-d')); } ?>" size="10" maxlength="10" />
					  </td>
                      

<?php 						 
                     
					print "<td colspan='1' class='label'>همه</td>
                     <td class='data'><input name='showa' type='checkbox' id='showa'";
						if ($showa>0) echo 'checked';
					 print " /></td>					 
					<td colspan=\"2\"><input   name=\"submit\" type=\"submit\" class=\"button\" id=\"submit\" size=\"16\" value=\"جستجو\" /></td>";
						 
						 
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
                <table align='center' border='1' id="table2">  
                <thead>            
                  
                        
				  <tr> 
                  
                            <td colspan="20"
                            <span class="f14_fontcb" >لیست طرح های پیشنهاد قیمت شده لوله پلی اتیلن (مبلغ کل به میلیون ریال)</span>  </td>
                            
				   </tr>
                        <tr>

                            <th  
                           	<span class="f14_fontb" > رديف  </span> </th>
							<th 
                           	<span class="f14_fontb"> نام  </span> </th>
							<th 
                           	<span class="f14_fontb"> نام خانوادگی </span> </th>
							<th  
                            <span class="f12_fontb"> مساحت </span>
							 (ha)  </th>
                            <th   class="f13_fontb"> نوع سیستم  </th>
						    <th 
                            <span class="f13_fontb">دشت/ شهرستان</span> </th>
							<th  
                            <span class="f14_fontb">پروژه</span> </th>
							<th   
                            <span class="f14_fontb">شركت مجری</span> </th>
							<th   
                            <span class="f14_fontb">وضعیت اجرا</span> </th>
							<th  
                            <span class="f11_fontb"> PE32 <br>ریال <br>کیلوگرم</span>
						    <th  <span class="f11_fontb">PE40 <br>ریال <br>کیلوگرم</span> </th>
						    <th  <span class="f11_fontb">PE80 <br>ریال <br>کیلوگرم</span> </th>
						    <th  <span class="f11_fontb">PE100 <br>ریال <br>کیلوگرم</span> </th>
						    <th  <span class="f14_fontb">مبلغ کل </span> </th>
							<th  <span class="f13_fontb">بازرس کنترل کیفیت</span> </th>
							<th  <span class="f13_fontb">کد رهگیری</span> </th>
							<th  <span class="f14_fontb">وضعیت</span> </th>
							<th  <span class="f14_fontb">تاریخ</span> </th>
						    <th  <span class="f14_fontb">برنده پیشنهاد</span> </th>
						    <th  <span class="f14_fontb">کاربر</span> </th>
							<th width="2%"></th>
							<th width="2%"></th>
                        </tr>
                        
                       </thead> 
                          <tr>    
							<td class="f14_font"></td>
                            <?php print select_option('ApplicantFname','',',',$ID1,0,'','','1','rtl',0,'',$ApplicantFnameval,'','100%'); ?>
							 <?php print select_option('ApplicantName','',',',$ID2,0,'','','1','rtl',0,'',$ApplicantNameval,'','100%'); ?>
							<?php print select_option('IDArea','',',',$IDArea,0,'','','1','rtl',0,'',$IDAreaval,'','100%'); ?>
					       <?php print select_option('DesignSystemGroupstitle','',',',$ID4,0,'','','1','rtl',0,'',$DesignSystemGroupstitleval,"",'100%'); ?> 
					       <?php print select_option('sos','',',',$ID5,0,'','','1','rtl',0,'',$sosval,"",'100%'); ?> 
					       <?php print select_option('operatorcoid','',',',$ID6,5,'','','8','rtl',0,'',$operatorcoidval,'','100%') ?> 
							<?php print select_option('DesignerCoid','',',',$ID15,0,'','','1','rtl',0,'',$DesignerCoidbazras,'','100%') ?> 
							<?php print select_option('BankCode','',',',$ID10,0,'','','1','rtl',0,'',$BankCodeval,'','100%'); ?>
					      <?php print select_option('proposestatetitle','',',',$ID11,0,'','','1','rtl',0,'',$proposestatetitleval,'','100%');?>
					      <?php print select_option('dateID','',',',$ID12,0,'','','1','rtl',0,'',$dateIDval,'','100%');?>
					       <?php print select_option('ProducerscowinTitle','',',',$ID13,0,'','','1','rtl',0,'',$ProducerscowinTitleval,'','100%'); ?> 
					       <?php print select_option('name','',',',$ID14,0,'','','1','rtl',0,'',$nameval,'','100%'); ?> 
                       
					 
					 </tr> 
                        
                   <?php
                   $sum32=0;
                   $sum40=0;
                   $sum80=0;
                   $sum100=0;
                   $sumDA=0;
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
                        
                        
                        
                        $apps = $row['apps'];
                        $Code = $row['Code'];
                        if ($row['operatorcoid']>0)
                        $ID = $row['ApplicantMasterID'].'_5_'.$row['operatorcoid'].'_'.$row['ProducersID'].'_'.$selectedCityId;
                        else
                        $ID = $row['ApplicantMasterID'].'_5_0_'.$row['ProducersID'].'_'.$selectedCityId;
                        
                        $ApplicantName = $row['ApplicantName'];
                        $ApplicantFName = $row['ApplicantFName'];
                        $year = $row['year'];
                        $CostPriceListMasterID=$row['CostPriceListMasterID'];
                        
                        $sumDA+=$row['DesignArea'];
                        
                        
                        
                        $sumL=floor(($row['PE32app']*$row['PE32tonaj']
                            +$row['PE40app']*$row['PE40tonaj']
                            +$row['PE80app']*$row['PE80tonaj']
                            +$row['PE100app']*$row['PE100tonaj'])/100000)/10;
                        $totalbelaavaz+=$row['belaavaz'];
                        
                        $sumM+=$sumL ;
                        $sum32+=$row['PE32tonaj'];
                        $sum40+=$row['PE40tonaj'];
                        $sum80+=$row['PE80tonaj'];
                        $sum100+=$row['PE100tonaj'];
                        $rown++;
                        if ($rown%2==1) 
                        $b='b'; else $b='';
						
						
					      
                        
?>                      
                        <tr>    

						    <td
                            <span class="f10_font<?php echo $b; ?>"  > 
					<?php if ($login_RolesID=='1' || $login_RolesID=='27' || $login_RolesID=='13' || $login_RolesID=='14' || $login_RolesID=='18' || $login_RolesID=='22' || $login_RolesID=='23') 
							{if ( 
						        ($row['proposestate']>=1 && $row['ClerkIDwin']<>24 && $row['ClerkIDwin']<>83)
								|| 
								($row['proposestate']>=2 && $row['ClerkIDwin']<>65 && $row['ClerkIDwin']<>24 && $row['ClerkIDwin']<>83) 
								)
								echo "<a  target='".$target."' href='allapplicantrequestdetailchart.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
								rand(10000,99999).$ID.rand(10000,99999)."'><img style = 'width: 30%;' src='../img/chart.png' title=' دامنه متناسب پیشنهاد قیمت '>$rown</a> "; 
								
								else echo $rown;
							
							}	
								else echo $rown;
								
                                if ($login_designerCO==1)
                                echo "<br>(".$row['ApplicantMasterID'].")";
                               $br="<br><font color='gray'>";
                                 ?> </span>  </td>
							
                            <td 
							<span class="f10_font<?php echo $b; ?>">  <?php echo $ApplicantFName; ?> </span> </td>
                           
                            <td
							<span class="f10_font<?php echo $b; ?>">  <?php echo $ApplicantName; ?> </span> </td>
                           
                            <td
							<span class="f10_font<?php echo $b; ?>">  <?php echo $row['DesignArea']; ?> </span> </td>
                            
                            <td
							<span class="f7_font<?php echo $b; ?>">  <?php echo $row['DesignSystemGroupstitle']; ?> </span> </td>
                           
                            <td
							<span class="f10_font<?php echo $b; ?>">  <?php echo $row['shahrcityname']; ?> </span> </td>
                            
                            <td
							<span class="f8_font<?php echo $b; ?>">  <?php echo $row['prjtypetitle']; ?> </span> </td>
                           
                            <td
							<span class="f8_font<?php echo $b; ?>">  <?php echo $row['operatorcotitle']; ?> </span> </td>
                           <td
							<span class="f8_font<?php echo $b; ?>">  <?php echo $row['applicantstatestitle']; ?> </span> </td>
                           
                            <td
							<span class="f10_font<?php echo $b; ?>">  <?php echo $row['PE32app'].$br.$row['PE32tonaj']; ?></font> </span> </td>
                            <td
							<span class="f10_font<?php echo $b; ?>">  <?php echo $row['PE40app'].$br.$row['PE40tonaj']; ?> </font></span> </td>
                            <td
							<span class="f10_font<?php echo $b; ?>">  <?php echo $row['PE80app'].$br.$row['PE80tonaj']; ?> </font></span> </td>
                           
                                              
                           
                            <td <span class="f10_font<?php echo $b; ?>">  <?php echo $row['PE100app'].$br.$row['PE100tonaj']; ?> </font></span> </td>
                            <td <span class="f10_font<?php echo $b; ?>">  <?php echo $sumL;
                             ?> </span> </td>
                           <td
							<span class="f8_font<?php echo $b; ?>"> </span><?php echo $row['DesignerCoIDbazrastitle']; ?> </td>
                                                     
						   <td
							<span class="f8_font<?php echo $b; ?>"> </span><?php echo str_replace(' ', '&nbsp;', $row['BankCode']); ?> </td>
						    
						<td
							<span class="f8_font<?php echo $b; ?>"> </span>
							<?php if ($login_RolesID=='18' || $login_RolesID=='27' || $login_designerCO==1)
							echo str_replace(' ', '&nbsp;', $row['proposestateptitle'])."<br>($row[pproposecntcnt])"; 
							else 		
							echo str_replace(' ', '&nbsp;', $row['proposestateptitle']); 
						
							?> </td>
                           	
                            
						<td
							<span class="f9_font<?php echo $b; ?>"> </span>
							<?php echo gregorian_to_jalali( $row['Windate']);?> </td>
                            
                            
                        <td <span class="f9_font<?php echo $b; ?>">  
							<?php echo $row['ProducerscowinTitle'];
							?> </span> </td>  
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
                                echo str_replace(' ', '&nbsp;', $decrypted_string); ?> </span> </td>  
		<?php
        $hasa=0;$errorsay="";
        $lennp = (strtotime(date('Y-m-d')) - strtotime($row['surveyDate']))/3600;                        
        if (($login_RolesID==13 || $login_RolesID==14)&& $row['surveyDate']>0)
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
			 if ($row['DesignArea']>10.9)			 
			 if ($row['pproposecntcnt']<$Permissionvals['proposenumcnt']) $errorsay.="\\n تعداد پیشنهاد دهنده به حد نصاب نرسیده است!";	
 		 }
	   $linksay="target='".$target."' href='allapplicantrequestdetail2.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
						rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999)."'";      
	   if ($errorsay)	$linksay="onClick=\"	alert('اخطار: $errorsay');\" ";
       else if (($login_RolesID==13 || $login_RolesID==14 || $login_RolesID==17)&& $row['proposestatep']==2 && !($row['surveyDate']>0) )
       {
        $linksay.="onClick=\"return confirm('مهلت انتخاب برنده پیشنهاد قیمت حداکثر $Permissionvals[hourcntforproposepselection] ساعت پس از باز کردن پیشنهاد قیمت می باشد. آیا صفجه پیشنهاد قیمت باز شود؟');\"";
       }
							if ($row['pproposecntcnt'])
  							 if (
									($login_RolesID=='18' || $login_RolesID=='22' || $login_RolesID=='27')
									|| 
									($login_RolesID=='17' && ($apps==1 || $row['DesignArea']<$smalha) && $row['ClerkIDwin']<>65 && $row['ClerkIDwin']<>24)
									|| ($login_RolesID=='17' && $row['proposestatep']>=2)
									|| 
									($login_RolesID=='13' && $row['proposestatep']>=1 && $row['ClerkIDwin']<>24 && $row['ClerkIDwin']<>83)
									|| 
									($login_RolesID=='14' && $row['proposestatep']>=2 && $row['ClerkIDwin']<>65 && $row['ClerkIDwin']<>24
									 && $row['ClerkIDwin']<>83) 
									|| 
									$login_designerCO==1
								)
                                {
                                   
								   echo "<td class='no-print'><a ".$linksay.
                                    "><img style = 'width: 20px;' src='../img/pipe.jpg' title=' پیشنهادهای لوله '></a></td>"; 
                                    
                                    if ($array[$row['ApplicantMasterID']]>0)
                                    echo "
                                        <td><a 
                                        href=\"allapplicantrequestdetail_changeop.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                        rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['ApplicantMasterID'].'_'.
                                        $array[$row['ApplicantMasterID']].'_0'.rand(10000,99999)."\"
                                        onClick=\"return confirm('مطمئن هستید که مجری تغییر پیدا کند؟');\"
                                        > <img style = 'width: 25px;' src='../img/refresh.png' title='تغییر مجری'> </a></td>";
                            
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
      
	    $ID = $row['ApplicantMasterID'].'_6_0_'.$row['ProducersID'].'_'.$selectedCityId;
	  	$permitrolsid = array("1","18","14", "13");
		if (in_array($login_RolesID, $permitrolsid) && $row['ProducerscowinTitle'] && ($row['applicantstatesID']<>30 && $row['applicantstatesID']<>35 ))
        {
			   $linksay="target='".$target."' href='allapplicantrequestdetail2.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
						rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999)."'";      
			   $linksay.="onClick=\"return confirm('اصلاح قیمت انجام پذیرد؟');\"";
				echo "<td class='no-print'><a ".$linksay.
                                    "><img style = 'width: 20px;' src='../img/photo_2016-12-10_15-46-20.jpg' title='اصلاح قیمت'></a></td>"; 

        }
    
                             ?>
                            
                            
							
							
							 
                        </tr><?php

                    }
                    
                    
                    

?>

                        <tr>
                            
                            <td colspan="13" class="f14_fontcb" ><?php echo ' مجموع مساحت (هكتار)';   ?></td>
                            <td colspan="6"
                            class="f14_fontcb" 
                            ><?php echo $sumDA;   ?></td>
                        </tr>
                         <tr>
                            
                            <td colspan="13" class="f14_fontcb" ><?php echo '  لوله های PE32 (تن)';   ?></td>
                            <td colspan="6" 
                            class="f14_fontcb" 
                            ><?php echo round($sum32/1000,1);   ?></td>
                        </tr> 
                         <tr>
                            
                            <td colspan="13" class="f14_fontcb" ><?php echo '  لوله های PE40 (تن)';   ?></td>
                            <td colspan="6" 
                            class="f14_fontcb" 
                            ><?php echo round($sum40/1000,1);   ?></td>
                        </tr> 
                         <tr>
                            
                            <td colspan="13" class="f14_fontcb" ><?php echo '  لوله های PE80 (تن)';   ?></td>
                            <td colspan="6" 
                            class="f14_fontcb" 
                            ><?php echo round($sum80/1000,1);   ?></td>
                        </tr> 
                         <tr>
                            
                            <td colspan="13" class="f14_fontcb" ><?php echo '  لوله های PE100 (تن)';   ?></td>
                            <td colspan="6" 
                            class="f14_fontcb" 
                            ><?php echo round($sum100/1000,1);   ?></td>
                        </tr> 
                        <tr>
                            
                            <td colspan="13" class="f14_fontcb" ><?php echo ' مجموع مبلغ کل (ميليون ريال)';   ?></td>
                            <td colspan="6" 
                            class="f14_fontcb" 
                            ><?php echo round($sumM,1);   ?></td>
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
			<?php 
            include('../includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>

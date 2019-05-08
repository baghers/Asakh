<?php 
/*
reorts/aaapplicantfree.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


/*
    proposable  پیشنهاد قیمت لوله
    applicantstatesID شناسه وضعیت پروژه
    TMDate تاریخ جلسه کمیته فنی
    DesignerCoIDnazer شناسه مشاور ناظر طرح
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
    creditsourceID منبع تامین اعتبار طرح
    creditsource جدول منابع اعتباری
    invoicemaster لیست پیش فاکتورها
    operatorcoid شناسه پیمانکار
    private شخصی بودن طرح
    
    Debi دبی طرح
    DesignArea مساحت طرح
    Code سریال طرح
    BankCode کد رهگیری طرح
    ApplicantName عنوان طرح
    ApplicantFName عنوان اول طرح
    SaveTime زمان ثبت طرح
    SaveDate تاریخ ثبت طرح
    ClerkID کاربر ثبت
    CityId شناسه شهر طرح
    CountyName روستای طرح
    numfield شماره پرونده طرح
    criditType تجمیع بودن یا نبودن طرح
    ClerkIDsurveyor شناسه کاربر نقشه بردار
    YearID سال طرح
    mobile تلفن همراه
    melicode کد/شناسه ملی
    SurveyArea مساحت نقشه برداری شده
    surveyDate تاریخ نقشه برداری
    coef5 ضریب منطقه ای طرح
    CostPriceListMasterID شناسه فهرست بهای آبیاری تحت فشار
    DesignSystemGroupsID نوع سیستم آبیاری
    TransportCostTableMasterID شناسه جدول هزینه حمل طرح
    RainDesignCostTableMasterID شناسه جدول هزینه های طراحی طرح های بارانی
    DropDesignCostTableMasterID شناسه جدول هزینه های طراحی طرح های قطره ای
    DesignerID شناسه طراح طرح
    StationNumber تعداد ایستگاه های طرح
    XUTM1 یو تی ام ایکس
    YUTM1 یو تی ام وای
    SoilLimitation محدودیت بافت خاک دارد یا خیر
    */

if ($login_Permission_granted==0) header("Location: ../login.php");
    $showa=0;
    $yearid=13;
if ($_POST)
{   
    $yearid=$_POST['YearID'];
	 $DesignAreafrom=$_POST['DesignAreafrom'];
    $DesignAreato=$_POST['DesignAreato'];
    $sos=$_POST['sos'];
    $sob=$_POST['sob'];
    $operatorcoid=$_POST['operatorcoid'];
    $applicantstatesID=$_POST['applicantstatesID'];
    $creditcsourceID=$_POST['creditcsourceID'];
    
    $BankCode=$_POST['BankCode'];
    
    $dateID=$_POST['dateID'];
    
    //$applicantstategroupsID=$_POST['applicantstategroupsID'];
	$ApplicantFname=$_POST['ApplicantFname'];
    $Applicantname=$_POST['ApplicantName'];
    $DesignSystemGroupstitle=$_POST['DesignSystemGroupstitle'];
   
    if ($_POST['showa']=='on')
    $showa=1;
}

	if (trim($_POST['creditcsourceID'])==-2)
        $str.=" and ifnull(applicantmasterop.creditsourceID,0)=0";
    else if (trim($_POST['creditcsourceID'])==-1)
        $str.=" and ifnull(applicantmasterop.creditsourceID,0)>0";
    else if (strlen(trim($_POST['creditcsourceID']))>0)
        $str.=" and applicantmasterall.creditsourceID='$_POST[creditcsourceID]'"; 


    if (strlen(trim($_POST['sos']))>0)
        $str.=" and shahr.id='$_POST[sos]'";
    if (strlen(trim($_POST['operatorcoid']))>0)
        $str.=" and applicantmaster.operatorcoid='$_POST[operatorcoid]'";
    if (strlen(trim($_POST['applicantstatesID']))>0)
        $str.=" and applicantstates.applicantstatesID='$_POST[applicantstatesID]'";  
	if (strlen(trim($_POST['ApplicantFname']))>0)
        $str.=" and applicantmaster.ApplicantFname like'%$_POST[ApplicantFname]%'";
	if (strlen(trim($_POST['ApplicantName']))>0)
        $str.=" and applicantmaster.ApplicantName like '%$_POST[ApplicantName]%'";
    if (strlen(trim($_POST['IDArea']))>0)
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

    if (trim($_POST['IDprice1'])==-2)
        $str.=" and ifnull(applicantmaster.LastTotal,0)=0";
    else if (trim($_POST['IDprice1'])==-1)
        $str.=" and ifnull(applicantmaster.LastTotal,0)>0";
    else if (strlen(trim($_POST['IDprice1']))>0)	
        if (trim($_POST['IDprice1'])==1)
		$str.=" and applicantmaster.LastTotal>0 and applicantmaster.LastTotal<=1000000000";
		else if (trim($_POST['IDprice1'])==2)
		$str.=" and applicantmaster.LastTotal>1000000000 and applicantmaster.LastTotal<=1500000000";
		else if (trim($_POST['IDprice1'])==3)
		$str.=" and applicantmaster.LastTotal>1500000000 and applicantmaster.LastTotal<=2000000000";
		else if (trim($_POST['IDprice1'])==4)
		$str.=" and applicantmaster.LastTotal>2000000000 and applicantmaster.LastTotal<=3000000000";
		else if (trim($_POST['IDprice1'])==5)
		$str.=" and applicantmaster.LastTotal>3000000000 and applicantmaster.LastTotal<=5000000000";
		else if (trim($_POST['IDprice1'])==6)
		$str.=" and applicantmaster.LastTotal>5000000000 and applicantmaster.LastTotal<=8000000000";
		else if (trim($_POST['IDprice1'])==7)
		$str.=" and applicantmaster.LastTotal>8000000000 and applicantmaster.LastTotal<=10000000000";
		else if (trim($_POST['IDprice1'])==8)
		$str.=" and applicantmaster.LastTotal>10000000000";


        if (trim($_POST['IDprice2'])==-2)
        $str.=" and ifnull(applicantmaster.belaavaz,0)=0";
    else if (trim($_POST['IDprice2'])==-1)
        $str.=" and ifnull(applicantmaster.belaavaz,0)>0";
    else if (strlen(trim($_POST['IDprice2']))>0)	
        if (trim($_POST['IDprice2'])==1)
		$str.=" and applicantmaster.belaavaz>0 and applicantmaster.belaavaz<=1000";
		else if (trim($_POST['IDprice2'])==2)
		$str.=" and applicantmaster.belaavaz>1000 and applicantmaster.belaavaz<=1500";
		else if (trim($_POST['IDprice2'])==3)
		$str.=" and applicantmaster.belaavaz>1500 and applicantmaster.belaavaz<=2000";
		else if (trim($_POST['IDprice2'])==4)
		$str.=" and applicantmaster.belaavaz>2000 and applicantmaster.belaavaz<=3000";
		else if (trim($_POST['IDprice2'])==5)
		$str.=" and applicantmaster.belaavaz>3000 and applicantmaster.belaavaz<=5000";
		else if (trim($_POST['IDprice2'])==6)
		$str.=" and applicantmaster.belaavaz>5000 and applicantmaster.belaavaz<=8000";
		else if (trim($_POST['IDprice2'])==7)
		$str.=" and applicantmaster.belaavaz>8000 and applicantmaster.belaavaz<=10000";
		else if (trim($_POST['IDprice2'])==8)
		$str.=" and applicantmaster.belaavaz>10000";  
                

    if (trim($_POST['IDprice3'])==-2)
        $str.=" and ifnull(applicantfreedetail1.Price,0)=0";
    else if (trim($_POST['IDprice3'])==-1)
        $str.=" and ifnull(applicantfreedetail1.Price,0)>0";
    else if (strlen(trim($_POST['IDprice3']))>0)	
        if (trim($_POST['IDprice3'])==1)
		$str.=" and applicantfreedetail1.Price>0 and applicantfreedetail1.Price<=1000000000";
		else if (trim($_POST['IDprice3'])==2)
		$str.=" and applicantfreedetail1.Price>1000000000 and applicantfreedetail1.Price<=1500000000";
		else if (trim($_POST['IDprice3'])==3)
		$str.=" and applicantfreedetail1.Price>1500000000 and applicantfreedetail1.Price<=2000000000";
		else if (trim($_POST['IDprice3'])==4)
		$str.=" and applicantfreedetail1.Price>2000000000 and applicantfreedetail1.Price<=3000000000";
		else if (trim($_POST['IDprice3'])==5)
		$str.=" and applicantfreedetail1.Price>3000000000 and applicantfreedetail1.Price<=5000000000";
		else if (trim($_POST['IDprice3'])==6)
		$str.=" and applicantfreedetail1.Price>5000000000 and applicantfreedetail1.Price<=8000000000";
		else if (trim($_POST['IDprice3'])==7)
		$str.=" and applicantfreedetail1.Price>8000000000 and applicantfreedetail1.Price<=10000000000";
		else if (trim($_POST['IDprice3'])==8)
		$str.=" and applicantfreedetail1.Price>10000000000";

    if (trim($_POST['IDprice4'])==-2)
        $str.=" and ifnull(applicantfreedetail2.Price,0)=0";
    else if (trim($_POST['IDprice4'])==-1)
        $str.=" and ifnull(applicantfreedetail2.Price,0)>0";
    else if (strlen(trim($_POST['IDprice4']))>0)	
        if (trim($_POST['IDprice4'])==1)
		$str.=" and applicantfreedetail2.Price>0 and applicantfreedetail2.Price<=1000000000";
		else if (trim($_POST['IDprice4'])==2)
		$str.=" and applicantfreedetail2.Price>1000000000 and applicantfreedetail2.Price<=1500000000";
		else if (trim($_POST['IDprice4'])==3)
		$str.=" and applicantfreedetail2.Price>1500000000 and applicantfreedetail2.Price<=2000000000";
		else if (trim($_POST['IDprice4'])==4)
		$str.=" and applicantfreedetail2.Price>2000000000 and applicantfreedetail2.Price<=3000000000";
		else if (trim($_POST['IDprice4'])==5)
		$str.=" and applicantfreedetail2.Price>3000000000 and applicantfreedetail2.Price<=5000000000";
		else if (trim($_POST['IDprice4'])==6)
		$str.=" and applicantfreedetail2.Price>5000000000 and applicantfreedetail2.Price<=8000000000";
		else if (trim($_POST['IDprice4'])==7)
		$str.=" and applicantfreedetail2.Price>8000000000 and applicantfreedetail2.Price<=10000000000";
		else if (trim($_POST['IDprice4'])==8)
		$str.=" and applicantfreedetail2.Price>10000000000";
 
     if (trim($_POST['IDprice5'])==-2)
        $str.=" and ifnull(applicantfreedetail3.Price,0)=0";
    else if (trim($_POST['IDprice5'])==-1)
        $str.=" and ifnull(applicantfreedetail3.Price,0)>0";
    else if (strlen(trim($_POST['IDprice5']))>0)	
        if (trim($_POST['IDprice5'])==1)
		$str.=" and applicantfreedetail3.Price>0 and applicantfreedetail3.Price<=1000000000";
		else if (trim($_POST['IDprice5'])==2)
		$str.=" and applicantfreedetail3.Price>1000000000 and applicantfreedetail3.Price<=1500000000";
		else if (trim($_POST['IDprice5'])==3)
		$str.=" and applicantfreedetail3.Price>1500000000 and applicantfreedetail3.Price<=2000000000";
		else if (trim($_POST['IDprice5'])==4)
		$str.=" and applicantfreedetail3.Price>2000000000 and applicantfreedetail3.Price<=3000000000";
		else if (trim($_POST['IDprice5'])==5)
		$str.=" and applicantfreedetail3.Price>3000000000 and applicantfreedetail3.Price<=5000000000";
		else if (trim($_POST['IDprice5'])==6)
		$str.=" and applicantfreedetail3.Price>5000000000 and applicantfreedetail3.Price<=8000000000";
		else if (trim($_POST['IDprice5'])==7)
		$str.=" and applicantfreedetail3.Price>8000000000 and applicantfreedetail3.Price<=10000000000";
		else if (trim($_POST['IDprice5'])==8)
		$str.=" and applicantfreedetail3.Price>10000000000";

     if (trim($_POST['IDprice6'])==-2)
        $str.=" and ifnull(applicantfreedetail4.Price,0)=0";
    else if (trim($_POST['IDprice6'])==-1)
        $str.=" and ifnull(applicantfreedetail4.Price,0)>0";
    else if (strlen(trim($_POST['IDprice6']))>0)	
        if (trim($_POST['IDprice6'])==1)
		$str.=" and applicantfreedetail4.Price>0 and applicantfreedetail4.Price<=1000000000";
		else if (trim($_POST['IDprice6'])==2)
		$str.=" and applicantfreedetail4.Price>1000000000 and applicantfreedetail4.Price<=1500000000";
		else if (trim($_POST['IDprice6'])==3)
		$str.=" and applicantfreedetail4.Price>1500000000 and applicantfreedetail4.Price<=2000000000";
		else if (trim($_POST['IDprice6'])==4)
		$str.=" and applicantfreedetail4.Price>2000000000 and applicantfreedetail4.Price<=3000000000";
		else if (trim($_POST['IDprice6'])==5)
		$str.=" and applicantfreedetail4.Price>3000000000 and applicantfreedetail4.Price<=5000000000";
		else if (trim($_POST['IDprice6'])==6)
		$str.=" and applicantfreedetail4.Price>5000000000 and applicantfreedetail4.Price<=8000000000";
		else if (trim($_POST['IDprice6'])==7)
		$str.=" and applicantfreedetail4.Price>8000000000 and applicantfreedetail4.Price<=10000000000";
		else if (trim($_POST['IDprice6'])==8)
		$str.=" and applicantfreedetail4.Price>10000000000";

     if (trim($_POST['IDprice7'])==-2)
        $str.=" and ifnull(applicantfreedetail.Price,0)=0";
    else if (trim($_POST['IDprice7'])==-1)
        $str.=" and ifnull(applicantfreedetail.Price,0)>0";
    else if (strlen(trim($_POST['IDprice7']))>0)	
        if (trim($_POST['IDprice7'])==1)
		$str.=" and applicantfreedetail.Price>0 and applicantfreedetail.Price<=1000000000";
		else if (trim($_POST['IDprice7'])==2)
		$str.=" and applicantfreedetail.Price>1000000000 and applicantfreedetail.Price<=1500000000";
		else if (trim($_POST['IDprice7'])==3)
		$str.=" and applicantfreedetail.Price>1500000000 and applicantfreedetail.Price<=2000000000";
		else if (trim($_POST['IDprice7'])==4)
		$str.=" and applicantfreedetail.Price>2000000000 and applicantfreedetail.Price<=3000000000";
		else if (trim($_POST['IDprice7'])==5)
		$str.=" and applicantfreedetail.Price>3000000000 and applicantfreedetail.Price<=5000000000";
		else if (trim($_POST['IDprice7'])==6)
		$str.=" and applicantfreedetail.Price>5000000000 and applicantfreedetail.Price<=8000000000";
		else if (trim($_POST['IDprice7'])==7)
		$str.=" and applicantfreedetail.Price>8000000000 and applicantfreedetail.Price<=10000000000";
		else if (trim($_POST['IDprice7'])==8)
		$str.=" and applicantfreedetail.Price>10000000000";
      if ($login_RolesID=='16')
            $str.=" and ifnull(app22.ApplicantMasterID,0)>0 and appchangestate.applicantstatesID=30";    
    else   if ($login_RolesID=='7')
            $str.=" and ifnull(app37.ApplicantMasterID,0)>0 and appchangestate.applicantstatesID=30";  
            
                                                                       
    if ($login_RolesID=='17') 
    $str.=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) ";
else if (($login_RolesID=='14') && ($showa==0))
        $str=" where substring(applicantmaster.cityid,1,4) in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid') ";
    
 	
	
	switch ($_POST['IDorder']) 
     {
    case 1: $orderby=' order by applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break; 
    case 2: $orderby=' order by applicantmaster.ApplicantFName COLLATE utf8_persian_ci,applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
    case 3: $orderby=' order by applicantmaster.DesignArea,applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
    case 4: $orderby=' order by shahrcityname COLLATE utf8_persian_ci,applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
    case 5: $orderby=' order by operatorcotitle COLLATE utf8_persian_ci,applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
    case 6: $orderby=' order by appchangestate.SaveDate,applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
	case 7: $orderby=' order by cast(applicantmasterall.sandoghcode as  decimal(10,0)),applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
	default: 
    if ($login_RolesID=='7' || $login_RolesID=='16') $orderby='order by cast(applicantmasterall.sandoghcode as  decimal(10,0)),applicantmaster.ApplicantName COLLATE utf8_persian_ci';
    else $orderby='order by  applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;  
    }
 
 
    $sql = "SELECT distinct applicantmaster.applicantmasterid,applicantmaster.ApplicantName,applicantmaster.ApplicantFName
	,applicantmaster.DesignArea,applicantmaster.CityId,creditsource.title creditsourcetitle ,creditsource.creditsourceid
	,case ifnull(applicantmaster.belaavaz,0) when 0 then applicantmasterall.belaavaz else applicantmaster.belaavaz end belaavaz
    ,applicantmasterall.sandoghcode,applicantmaster.LastTotal,applicantmasterall.LastTotal LastTotald
	,applicantmasterall.selfcashhelpval,applicantmasterall.selfnotcashhelpval
    ,applicantmasterall.selfcashhelpval+applicantmasterall.selfnotcashhelpval selfhelp
    ,(applicantfreedetail1.Price) Price1,(applicantfreedetail2.Price) Price2 ,(applicantfreedetail3.Price) Price3 
    ,(applicantfreedetail4.Price) Price4,(applicantfreedetail.Price) Priceall
    ,operatorco.title operatorcotitle,shahr.cityname shahrcityname,shahr.id shahrid,operatorco.operatorcoid 
  FROM `applicantmaster`

inner join appchangestate on appchangestate.applicantmasterid =applicantmaster.applicantmasterid and applicantstatesID in (30,37)
inner join operatorco on operatorco.operatorcoid=applicantmaster.operatorcoid
left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
left outer join (select applicantmasterid,sum(Price) Price from applicantfreedetail where freestateID=141 group by applicantmasterid) applicantfreedetail1 on applicantfreedetail1.applicantmasterid =applicantmaster.applicantmasterid
left outer join (select applicantmasterid,sum(Price) Price from applicantfreedetail where freestateID=142 group by applicantmasterid) applicantfreedetail2 on applicantfreedetail2.applicantmasterid =applicantmaster.applicantmasterid
left outer join (select applicantmasterid,sum(Price) Price from applicantfreedetail where freestateID=143 group by applicantmasterid) applicantfreedetail3 on applicantfreedetail3.applicantmasterid =applicantmaster.applicantmasterid
left outer join (select applicantmasterid,sum(Price) Price from applicantfreedetail where freestateID=144 group by applicantmasterid) applicantfreedetail4 on applicantfreedetail4.applicantmasterid =applicantmaster.applicantmasterid

left outer join (select applicantmasterid,sum(Price) Price from applicantfreedetail group by applicantmasterid) applicantfreedetail on applicantfreedetail.applicantmasterid =applicantmaster.applicantmasterid

inner join applicantmaster applicantmasterall on applicantmaster.BankCode=applicantmasterall.BankCode 
 and substring(applicantmaster.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
 
 left outer join (select distinct ApplicantMasterID from appchangestate where applicantstatesID='22') app22 on app22.ApplicantMasterID=applicantmasterall.ApplicantMasterID
left outer join (select distinct ApplicantMasterID from appchangestate where applicantstatesID='37') app37 on app37.ApplicantMasterID=applicantmasterall.ApplicantMasterID
 
 
inner join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmasterall.ApplicantMasterID and state=1 
and applicantmaster.operatorcoID=operatorapprequest.operatorcoID   

left outer join creditsource on creditsource.creditsourceid=case 
ifnull(applicantmaster.creditsourceid,0) when 0 then applicantmasterall.creditsourceid else applicantmaster.creditsourceid end

where applicantmasterall.yearid='$yearid' and ifnull(applicantmaster.ApplicantMasterIDmaster,0)=0 and applicantfreedetail.Price>0
and substring(applicantmasterall.cityid,1,2)=substring('$login_CityId',1,2)
$str
$orderby
;";
   //print $sql;
try 
    {		
        $result = mysql_query($sql);
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }    
    
     

    $ID1[' ']=' ';
    $ID2[' ']=' ';
    $ID3[' ']=' ';
    $ID4[' ']=' ';
    $ID5[' ']=' ';
$dasrow=0;
while($row = mysql_fetch_assoc($result))
{
    $dasrow=1;    
    $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);
    $ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);
    $ID3[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);
    $ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);   
    $ID5[trim($row['creditsourcetitle'])]=trim($row['creditsourceid']);

}
$ID1=mykeyvalsort($ID1);
$ID2=mykeyvalsort($ID2);
$ID3=mykeyvalsort($ID3);
$ID4=mykeyvalsort($ID4);
$ID5=mykeyvalsort($ID5);

if ($dasrow)
mysql_data_seek( $result, 0 );



$query="
select 'نام خانوادگی' _key,1 as _value union all
select 'نام' _key,2 as _value union all 
select 'مساحت' _key,3 as _value union all
select 'شهرستان' _key,4 as _value union all
select 'شرکت طراح' _key,5 as _value union all
select 'تاریخ' _key,6 as _value union all
select 'کد' _key,7 as _value ";

$IDorder = get_key_value_from_query_into_array($query);

if (!$_POST['IDorder'])
    $IDorderval=1;
else $IDorderval=$_POST['IDorder'];

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
    

?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست آزادسازی</title>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	
<script type="text/javascript" language='javascript' src='../assets/jquery2.js'></script>

<script type="text/javascript" src="../lib/jquery2.js"></script>
<script type='text/javascript' src='../lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='../lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='../lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='../jquery.autocomplete.js'></script>
<script type='text/javascript' src='localdata.js'></script>
<link rel="stylesheet" type="text/css" href="main.css" />
<link rel="stylesheet" type="text/css" href="../jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="../lib/thickbox.css" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />

    <script>

          
    </script>
    
<style>

.f14_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:12pt;line-height:200%;font-weight: bold;font-family:'B Nazanin';                        
}
.f13_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}
.f11_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:11pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}


.f10_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:10pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_font{
		background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:9pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f8_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:8pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }
.f7_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }
.f13_fontb{
	background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}
.f11_fontb{
	background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:11pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}

.f10_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:10pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:9pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f8_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:8pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }
.f7_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

   .f131_font{
	border:1px solid black;border-color:#000000 #000000;text-align:right;font-size:14pt;line-height:150%;font-weight: bold;font-family:'B lotus';                        
}
.f131_fontb{
	background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:right;font-size:14pt;line-height:150%;font-weight: bold;font-family:'B Nazanin';                        
}
.f132_font{
	border:1px solid black;border-color:#000000 #000000;text-align:left;font-size:14pt;line-height:150%;font-weight: bold;font-family:'B lotus';                        
}
.f132_fontb{
	background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:left;font-size:14pt;line-height:150%;font-weight: bold;font-family:'B Nazanin';                        
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
            <?php include('../includes/header.php');  ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
            
            <form action="aaapplicantfree.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data">
                 <div id="loading-div-background">
                <div id="loading-div" class="ui-corner-all" >
                  <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
                  <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
                 </div>
                </div>
                
                
                <table id="records" width="95%" align="center">
                
                  <tr> 
                         <?php  
                            $query="SELECT YearID as _value,Value as _key FROM `year` 
                             where YearID in (select YearID from cityquota)
                             
                             ORDER BY year.Value DESC";
            				 $ID = get_key_value_from_query_into_array($query);
                             print 
                             select_option('YearID','سهمیه',',',$ID,0,'','','1','rtl',0,'',$yearid,'','75');
                          
						    print select_option('IDorder','ترتیب',',',$IDorder,0,'','','3','rtl',0,'',$IDorderval,"",'100');
                       
                            print select_option('creditcsourceID','اعتبار',',',$ID5,0,'','','1','rtl',0,'',$creditcsourceID,'','95');
							
							print "<td colspan='1' class='label'>همه</td>
                         <td class='data'><input name='showa' type='checkbox' id='showa'";
                             if ($showa>0) echo 'checked';
                             print " /></td>";
                          ?>
  					      
                            <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
		                  <td colspan="1"><input    name="submit" type="submit" class="button" id="submit" size="16" value="جستجو" /></td>
    
				   </tr>
				   
				   
		        </table>
                 <table align='center' border='1' id="table2">              
                   <thead>           
				   <tr>
                              <td colspan="20"
                            <span class="f14_font" >لیست آزاد سازی (مبالغ به میلیون ریال)</span>  
                            </td>
                    <tr>        
					 <tr>
                            <th class="f9_font" > رديف   </th>
                            <th class="f9_font" >کد   </th>
							<th class="f13_font"> نام   </th>
							<th class="f13_font"> نام خانوادگی  </th>
							<th class="f9_font"> مساحت </span>(ha)  </th>
						    <th class="f9_font">دشت/ شهرستان </th>
							<th class="f9_font">شركت مجری </th>
							<th class="f13_font"> مبلغ کل طراحی</th>
							<th class="f13_font"> مبلغ کل اجرا *</th>
							<th class="f9_font"> اختلاف طراحی و اجرا</th>
							<th class="f13_font">کمک بلاعوض</th>
							<th class="f9_font">خودیاری نقد</th>
							<th class="f9_font">خودیاری غیرنقد</th>
							<th class="f13_font"> جمع خودیاری</th>
							<th class="f13_font">قسط اول </th>
							<th class="f13_font">قسط دوم </th>
							<th class="f13_font">قسط سوم </th>
							<th class="f13_font">قسط چهارم </th>
							<th class="f14_font">مجموع </th>
							<th class="f13_font">مانده در صندوق </th>
                            <th class="f14_font" > </th>
                        </tr>
                        
                        </thead> 
                        <tr class='no-print'>    
							<td class="f14_font"></td>
                            <td class="f14_font"></td>
                            <?php print select_option('ApplicantFname','',',',$ID4,0,'','','1','rtl',0,'',$ApplicantFname,'','50'); ?>
							 <?php print select_option('ApplicantName','',',',$ID2,0,'','','1','rtl',0,'',$ApplicantName,'','70'); ?>
							<?php print select_option('IDArea','',',',$IDArea,0,'','','1','rtl',0,'',$IDArea,'','30'); ?>
					       <?php print select_option('sos','',',',$ID1,0,'','','1','rtl',0,'',$sos,"",'50'); ?>  
					       <?php print select_option('operatorcoid','',',',$ID3,0,'','','1','rtl',0,'',$operatorcoid,'','60') ?>
							<td></td> 
					       <?php print select_option('IDprice1','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice1,'','50'); ?>  
					       <td></td> 
                            <?php print select_option('IDprice2','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice2,'','50'); ?> 
                            <td></td> 
					        <td></td> 
					        <td></td> 
					       <?php print select_option('IDprice3','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice3,'','40'); ?> 
					       <?php print select_option('IDprice4','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice4,'','40'); ?> 
					       <?php print select_option('IDprice5','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice5,'','40'); ?> 
					       <?php print select_option('IDprice6','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice6,'','40'); ?> 
					       <?php print select_option('IDprice7','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice7,'','50'); ?> 
                           
					  <td colspan="1"><input    name="submit" type="submit" class="button" id="submit" size="0" value="جستجو" /></td>
                       
					 
					 </tr> 
                     
<?php
                    $Total=0;
                    $rown=0;
                    $Description="";
					$sumarea=0;
                    $sum1=0;
                    $sum2=0;
                    $sum3=0;
                    $sum4=0;
                    $sumall=0;
                    $LastTotal=0;
                    $LastTotald=0;
                    $LastTotaldif=0;
                    $selfnotcashhelpval=0;
					$selfcashhelpval=0;
					$selfhelp=0;
                    $belaavaz=0;
                    $remain=0;
                    while($resquery = mysql_fetch_assoc($result))
                    { 
					    $sumarea+=$resquery["DesignArea"];
                        $sum1+=$resquery["Price1"];
                        $sum2+=$resquery["Price2"];
                        $sum3+=$resquery["Price3"];
                        $sum4+=$resquery["Price4"];
                        $sumall+=$resquery["Priceall"];
                        $LastTotal+=$resquery["LastTotal"];
                        $LastTotald+=$resquery["LastTotald"];
                        if ($resquery["LastTotal"]<$resquery["LastTotald"])	$LastTotaldifr=$resquery["LastTotal"]-$resquery["LastTotald"]; else $LastTotaldifr=0;
                        $LastTotaldif+=$LastTotaldifr;
						$selfnotcashhelpval+=$resquery["selfnotcashhelpval"];
                        $selfcashhelpval+=$resquery["selfcashhelpval"];
                    	$selfhelp+=$resquery["selfhelp"];
                        
						$remains=(floor($resquery["selfhelp"]/100000)/10+round($resquery['belaavaz'],1)-floor($resquery["Priceall"]/100000)/10);
                        $remain+=$remains;
                        
						$belaavaz+=round($resquery['belaavaz'],1);
                            $rown++;
                            if ($rown%2==1) 
                            $b='b'; else $b='';
                             print "<tr '>";      
?>                      
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $rown; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo "($resquery[sandoghcode])" ;?></td>
														
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery['ApplicantFName']; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["ApplicantName"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["DesignArea"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["shahrcityname"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["operatorcotitle"] ; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["LastTotald"]/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["LastTotal"]/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor(($LastTotaldifr)/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo round($resquery['belaavaz'],1); ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["selfcashhelpval"]/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["selfnotcashhelpval"]/100000)/10; ?></td>
							  <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["selfhelp"]/100000)/10; ?></td>
																				
						  <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["Price1"]/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["Price2"]/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["Price3"]/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["Price4"]/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["Priceall"]/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo 
							(floor($resquery["selfhelp"]/100000)/10+round($resquery['belaavaz'],1)-floor($resquery["Priceall"]/100000)/10); ?></td>
                        
                        
                          <?php
                            $permitrolsid = array("1","18", "16","17","7","13","14");
                            if ( in_array($login_RolesID, $permitrolsid))
                             {
                                 "<td class=\"f7_font$b'\"><a target='_blank' href='invoicemasterfree_list.php?uid=".rand(10000,99999).
                                rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                rand(10000,99999).$resquery['applicantmasterid'].'_2_0_'.$resquery['operatorcoid'].rand(10000,99999).
                                "'><img style = 'width: 25px;' src='../img/search.png' title='آزادسازی'></a></td>";
                            
                             }
                                
                            echo "
                                                  
                        
                        </tr>
                        ";                     
                    }               
                         
?>

                   <tr>
                            <td rowspan="2" colspan="4" class="f14_font" ><?php echo 'مجموع';   ?></td>
                            <td rowspan="2" colspan="3" class="f14_font" ><?php echo floor($sumarea).' هکتار';   ?></td>
                            <td colspan="2" class="f131_font" ><?php echo floor($LastTotald/1000000);   ?></td>
                            <td colspan="2" class="f131_font" ><?php echo floor($LastTotaldif/1000000);   ?></td>
							<td rowspan="1" colspan="1" class="f131_font" ><?php echo floor($selfcashhelpval/1000000);   ?></td>
			                <td colspan="2" class="f131_font" ><?php echo floor($selfnotcashhelpval/1000000);   ?></td>
               
                            <td colspan="2" class="f131_font" ><?php echo floor($sum1/1000000);  ?></td>
                         	<td colspan="2" class="f131_font" ><?php echo floor($sum3/1000000);  ?></td>
                            <td colspan="2" class="f131_font" ><?php echo floor($sumall/1000000);   ?></td>
                       
				   </tr>
  
                   <tr>
        					<td colspan="2" class="f132_font" ><?php echo floor($LastTotal/1000000);   ?></td>
							
							<td colspan="2" class="f132_font" ><?php echo $belaavaz;   ?></td>
                            <td colspan="3" class="f132_font" ><?php echo floor($selfhelp/1000000);   ?></td>
                         	
			            
			                <td colspan="2" class="f132_font" ><?php echo floor($sum2/1000000);  ?></td>
                            <td colspan="2" class="f132_font" ><?php echo floor($sum4/1000000);  ?></td>
                        	<td colspan="2" class="f132_font" ><?php echo $remain;   ?></td>
                   </tr>
    
	
                </table>
                
                
                <script src="../js/jquery-1.9.1.js"></script>
				<script src="../js/jquery.freezeheader.js"></script>

			<script language="javascript" type="text/javascript">

        $(document).ready(function () {
         $("#table2").freezeHeader();
		})
 

    </script>
	
                   <tr>
	
        					<td colspan="18" class="f11_font" ><?php echo '' ;   ?></td>
                   </tr>
                
                   <tr>
	
        					<td colspan="18" class="f11_font" ><?php echo '* در ستون مبلغ کل اجرا ، در پروژه های به اتمام رسیده مبلغ کل صورت وضعیت نهایی و در پروژه های در حال اجرا مبلغ کل پیش فاکتورهای اجرایی آورده شده است.';   ?></td>
                   </tr>
       
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

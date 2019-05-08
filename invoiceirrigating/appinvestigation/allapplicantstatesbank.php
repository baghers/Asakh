<?php 

/*

//appinvestigation/allapplicantstatesbank.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/allapplicantstates.php
 
-
*/
 
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php
require ('../includes/gPoint.php');
require ('../includes/functions.php');  

//echo $login_CityId;

if ($login_Permission_granted==0) header("Location: ../login.php");
$showa=0;//نمایش همه
$yearid='';//سال
  /*
  18 مدیر آب و خاک
  13 مدیر آبیاری تحت فشار
  14 ناظر عالی
  17 ناظر مقیم شهرستان
  20 بازرس
  21 مدیر بازرسی
  31 کارشناس آبرسانی
  32 مدیر آبرسانی 
  */
	$permitrolsid = array("13","14","17","18","19","20","21");
	 if (in_array($login_RolesID, $permitrolsid)) $yearid=$yearid_select;

if (in_array($login_RolesID, array("17", "31","32")) && !$_POST)  
{
    $_POST['DesignerCoID']=67;
    //$_POST['showm']='on';
}  

if ($_POST)
{
    $yearid=$_POST['YearID'];//شناسه سال
    $DesignAreafrom=$_POST['DesignAreafrom'];//از مساحت
    $DesignAreato=$_POST['DesignAreato'];//تا مساحت
    $sos=$_POST['sos'];//استان
    $sob=$_POST['sob'];//بخش
    $DesignerCoID=$_POST['DesignerCoID'];//طراح
    $applicantstatesID=$_POST['applicantstatesID'];//وضعیت
    $creditcsourceID=$_POST['creditcsourceID'];//اعتبار
    $credityear=$_POST['credityear'];//سال اعتبار
    
    $BankCode=$_POST['BankCode'];//کد رهگیری
    
    $dateID=$_POST['dateID'];//تاریخ
    
    $applicantstategroupsID=$_POST['applicantstategroupsID'];//گروه وضعیت
	$ApplicantFname=$_POST['ApplicantFname'];//متقاضی
    $Applicantname=$_POST['ApplicantName'];//عنوان پروژه
    $DesignSystemGroupstitle=$_POST['DesignSystemGroupstitle'];//عنوان سیستم
    
    
    if ($_POST['showprice']=='on')//بروز رسانی
    $showprice=1;
    
    
    if ($_POST['showa']=='on')//نمایش طرح های سایر شهرستان ها برای ناظرین عالی
    $showa=1;
    if ($_POST['showm']=='on')//نمایش طرح های وضعیت اولیه
    $showm=1;
    if ($_POST['showp']=='on')//نمایش طرح های شخصی
    $showp=1;
    if ($_POST['showt']=='on')//نمایش طرح های انعقاد قرارداد شده
    $showt=1;
    if ($_POST['showc']=='on')//نمایش تجمیع ها
    $showc=1;
            if (trim($BankCode)==-2)//کد رهگیری
        $str.=" and ifnull(applicantmaster.BankCode,0)=0";
    else if (trim($BankCode)==-1)
        $str.=" and ifnull(applicantmaster.BankCode,0)>0";
    else if (strlen(trim($BankCode))>0 && $BankCode<>'0')
        $str.=" and applicantmaster.BankCode='$BankCode'";
        
    if (strlen(trim($_POST['DesignAreafrom']))>0)//از مساحت
        $str.=" and DesignArea>='$_POST[DesignAreafrom]'";
    if (strlen(trim($_POST['DesignAreato']))>0)//تا مساحت
        $str.=" and DesignArea<='$_POST[DesignAreato]'";
     if (strlen(trim($_POST['sos']))>0)//استان
        $str.=" and shahr.id='$_POST[sos]'";
   if (strlen(trim($_POST['DesignerCoID']))>0)//طراح
        $str.=" and applicantmaster.DesignerCoID='$_POST[DesignerCoID]'";
    if (strlen(trim($_POST['applicantstatesID']))>0)//وضعیت
        $str.=" and applicantmaster.applicantstatesID='$_POST[applicantstatesID]'";   
        
    if (strlen(trim($_POST['dateID']))>0)//تاریخ
        $str.=" and TMDate='$_POST[dateID]'";  
    
    if (trim($_POST['creditcsourceID'])==-2)//اعتبار
        $str.=" and ifnull(applicantmaster.creditsourceID,0)=0";
    else if (trim($_POST['creditcsourceID'])==-1)
        $str.=" and ifnull(applicantmaster.creditsourceID,0)>0";
    else if (strlen(trim($_POST['creditcsourceID']))>0 || strlen(trim($_POST['credityear']))>0)
			if (strlen(trim($_POST['creditcsourceID']))>0)
				$str.=" and applicantmaster.creditsourceID='$_POST[creditcsourceID]'"; 
			else if (strlen(trim($_POST['credityear']))>0)
				$str.=" and creditsource.credityear='$_POST[credityear]'"; 
	
	if (strlen(trim($_POST['DesignSystemGroupstitle']))>0)//سیستم آبیاری
        $str.=" and designsystemgroups.designsystemgroupsid ='$_POST[DesignSystemGroupstitle]'";
		
	if (strlen(trim($_POST['ApplicantFname']))>0)//متقاضی
        $str.=" and applicantmaster.ApplicantFname like'%$_POST[ApplicantFname]%'";
	if (strlen(trim($_POST['ApplicantName']))>0)//عنوان پروژه
        $str.=" and applicantmaster.ApplicantName like '%$_POST[ApplicantName]%'";
    
    if (strlen(trim($_POST['applicantstategroupsID']))>0)//گروه وضعیت
        $str.=" and applicantstategroups.applicantstategroupsID='$_POST[applicantstategroupsID]'";  
	
    if (strlen(trim($_POST['IDArea']))>0)//فیلتر مساحت
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
	
    if (strlen(trim($_POST['IDprice']))>0)	//فیلتر مبلغ کل
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
        
    if (trim($_POST['IDbela'])==-2)//فیلتر بلاعوض
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
    
    if (trim($_POST['IDself'])==-2)//فیلتر خودیاری
        $str.=" and ifnull(applicantmaster.selfcashhelpval+applicantmaster.selfnotcashhelpval,0)=0";
    else if (trim($_POST['IDself'])==-1)
        $str.=" and ifnull(applicantmaster.selfcashhelpval+applicantmaster.selfnotcashhelpval,0)>0";
    else if (strlen(trim($_POST['IDself']))>0)	
        if (trim($_POST['IDself'])==1)
		$str.=" and ((applicantmaster.selfcashhelpval+applicantmaster.selfnotcashhelpval)/1000000)>0 and ((applicantmaster.selfcashhelpval+applicantmaster.selfnotcashhelpval)/1000000)<=1000";
		else if (trim($_POST['IDself'])==2)
		$str.=" and ((applicantmaster.selfcashhelpval+applicantmaster.selfnotcashhelpval)/1000000)>1000 and ((applicantmaster.selfcashhelpval+applicantmaster.selfnotcashhelpval)/1000000)<=1500";
		else if (trim($_POST['IDself'])==3)
		$str.=" and ((applicantmaster.selfcashhelpval+applicantmaster.selfnotcashhelpval)/1000000)>1500 and ((applicantmaster.selfcashhelpval+applicantmaster.selfnotcashhelpval)/1000000)<=2000";
		else if (trim($_POST['IDself'])==4)
		$str.=" and ((applicantmaster.selfcashhelpval+applicantmaster.selfnotcashhelpval)/1000000)>2000 and ((applicantmaster.selfcashhelpval+applicantmaster.selfnotcashhelpval)/1000000)<=3000";
		else if (trim($_POST['IDself'])==5)
		$str.=" and ((applicantmaster.selfcashhelpval+applicantmaster.selfnotcashhelpval)/1000000)>3000 and ((applicantmaster.selfcashhelpval+applicantmaster.selfnotcashhelpval)/1000000)<=5000";
		else if (trim($_POST['IDself'])==6)
		$str.=" and ((applicantmaster.selfcashhelpval+applicantmaster.selfnotcashhelpval)/1000000)>5000 and ((applicantmaster.selfcashhelpval+applicantmaster.selfnotcashhelpval)/1000000)<=8000";
		else if (trim($_POST['IDself'])==7)
		$str.=" and ((applicantmaster.selfcashhelpval+applicantmaster.selfnotcashhelpval)/1000000)>8000 and ((applicantmaster.selfcashhelpval+applicantmaster.selfnotcashhelpval)/1000000)<=10000";
		else if (trim($_POST['IDself'])==8)
		$str.=" and ((applicantmaster.selfcashhelpval+applicantmaster.selfnotcashhelpval)/1000000)>10000";		  
  
	  if($yearid>0)  $str.=" and applicantmaster.yearid='$yearid' ";
                 
}
else
{
    if($login_RolesID==11)
        $showm=1;
    if($login_RolesID==16 || $login_RolesID==7|| $login_RolesID==17|| $login_RolesID==14)
        $showt=1;
    /*if($login_RolesID==11)
        $applicantstategroupsID=26;
    else     
        $applicantstategroupsID=27;
    $str.=" and applicantstategroups.applicantstategroupsID='$applicantstategroupsID'";  
    */
}
    $sql = "SELECT value  FROM year where YearID='$yearid' ";
    $result = mysql_query($sql);
    $row = mysql_fetch_assoc($result);
    $yearvalue=$row['value'];
    
	if($login_RolesID==26) {$showc=1;$showm=1;$showt=1;}
    
    if (($showm!=1)) $str.=" and applicantmaster.applicantstatesID<>23 ";
    if (($showt!=1)) $str.=" and applicantmaster.applicantstatesID not in (22,37,1) ";
    if (($_POST['showc']=='on')) $str.=" and ifnull(applicantmaster.criditType,0)=1 ";
      if ($showc==1) $str.=" and ifnull(applicantmaster.criditType,0)=1 ";

	if (!($_POST['showp']=='on')) $str.=" and ifnull(applicantmaster.private,0)=0 ";
  
  switch ($_POST['IDorder']) 
  {
    case 1: $orderby=' order by ApplicantName COLLATE utf8_persian_ci'; break; 
    case 2: $orderby=' order by ApplicantFName COLLATE utf8_persian_ci'; break;
    case 3: $orderby=' order by DesignArea'; break;
	case 4: $orderby=' order by DesignSystemGroupstitle COLLATE utf8_persian_ci'; break;
    case 5: $orderby=' order by shahrcityname COLLATE utf8_persian_ci'; break;
    case 6: $orderby=' order by DesignerCotitle COLLATE utf8_persian_ci'; break;
    case 7: $orderby=' order by applicantstatestitle COLLATE utf8_persian_ci'; break;
    case 8: $orderby=' order by TMDate'; break;
    case 9: $orderby=' order by cast(applicantmaster.numfield as  decimal(10,0))'; break;
    default: 
    if ($login_RolesID=='7' || $login_RolesID=='16')
        $orderby=' order by cast(applicantmaster.sandoghcode as  decimal(10,0))';
    else     
        $orderby=' order by shahrcityname COLLATE utf8_persian_ci,applicantmaster.numfield,TMDate '; break; 
  }
  
$strappnum="";

if ($login_RolesID=='6') 
    $strappnum=" and applicantmaster.applicantstatesID in (36,37,1,12,22) ";
    
if ($login_RolesID=='7') 
    $strappnum=" and applicantmaster.applicantstatesID in (36,37,1) ";
if ($login_RolesID=='16') 
    $strappnum=" and applicantmaster.applicantstatesID in (12,22,1) ";
if ($login_RolesID=='17') 
    $str.=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) ";
    
else if (($login_RolesID=='14') && ($showa==0)) 
        $str.=" and substring(applicantmaster.cityid,1,4) in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid') ";
    


$selectedCityId=$login_CityId;
if ($_POST['ostan']>0)
        $selectedCityId=$_POST['ostan'];

	
if (in_array($login_RolesID, array("17", "31","32")))
        $str.=" and ifnull(applicantmasterdetail.prjtypeid,0)=1 ";
else
        $str.=" and ifnull(applicantmasterdetail.prjtypeid,0)<>1 "; 
    /*
    applicantmaster جدول مشخصات طرح
    operatorco جدول پیمانکار
    operatorco.Title عنوان پیمانکار
    operatorcoID شناسه پیمانکار
    proposestatep وضعیت پیشنهاد قیمت
    ApplicantMasterID شناسه طرح
    creditsourcetitle عنوان منبع تامین اعتبار
    credityear سال اعتبار طرح
    ApplicantName عنوان طرح
    ApplicantFName عنوان اول طرح
    ADate تاریخ شروع پیشنهاد قیمت
    BankCode کد رهگیری طرح
    designername عنوان طراح
    designsystemgroupstitle سیستم آبیاری
    shahrcityname نام شهر
    designer.LName نام خانوادگی طراح
    designer.FName نام طراح
    operatorapprequest جدول پیشنهاد قیمت های طرح
    state برنده شدن یا نشدن
    clerk جدول کاربران
    Debi دبی طرح
    DesignArea مساحت طرح
    Code سریال طرح
    SaveTime زمان ثبت طرح
    SaveDate تاریخ ثبت طرح
    ClerkID کاربر ثبت
    CityId شناسه شهر طرح
    CountyName روستای طرح
    private شخصی بودن طرح
    numfield شماره پرونده طرح
    criditType تجمیع بودن یا نبودن طرح
    ClerkIDsurveyor شناسه کاربر نقشه بردار
    year جدول سال
    YearID سال طرح
    mobile تلفن همراه
    melicode کد/شناسه ملی
    SurveyArea مساحت نقشه برداری شده
    surveyDate تاریخ نقشه برداری
    coef5 ضریب منطقه ای طرح
    designer جدول طراحان
    DesignerCoIDnazer شناسه مشاور ناظر طرح
    operatorcoid شناسه پیمانکار
    DesignerCoID شناسه مشاور طراح
    costpricelistmaster جدول فهرست بها
    CostPriceListMasterID شناسه فهرست بهای آبیاری تحت فشار
    DesignerID شناسه طراح طرح
    applicantstatesID شناسه وضعیت طرح
    corank رتبه شرکت
    firstperiodcoprojectarea مجموع مساحت پروژه های انجام داده اول دوره شرکت
    firstperiodcoprojectnumber تعداد  پروژه های انجام داده اول دوره شرکت
    coprojectsum مجموع تعدادی پروژه های شرکت
    projecthektardone پروژه های انجام داده شرکت
    simultaneouscnt تعداد پروژه های همزمان
    thisyearprgarea مساحت پرژه های امسال
    above20cnt تعداد پروژه های بالای 20 هکتار
    above55cnt تعداد پروژه های بالای 55 هکتار
    currentprgarea مساحت پروژه های جاری
    projectcountdone تعداد پروژه های انجام داده شرکت
    clerk.clerkid شناسه کاربر
    designerinfo.designercnt تعداد کارشناسان طراح شرکت
    designerinfo.dname نام کارشناس طراح
    designerinfo.duplicatedesigner داشتن کارشناسی که در دو شرکت فعالیت نماید
    membersinfo.duplicatemembers عضو هیئت مدیره که در دو شرکت فعالیت نماید
    allreq.cnt reqcnt تعداد پیشنهادات ارسال شده
    allwinreq.wincnt تعداد پیشنهادات انتخاب شده
    avgpmreq.avg میانگین ظرایب پیشنهاد قیمت های شرکت
    avgpmreqa.avga میانگین ظرایب پیشنهاد قیمت های انتخابی
    coef1 ضریب اول اجرای طرح
    coef2 ضریب دوم اجرای طرح
    coef3 ضریب سوم اجرای طرح
    ent_DateFrom شروع انتظامی بودن شرکت
    ent_DateTo پایان انتظامی بودن شرکت
    ent_Hectar هکتار انتظامی بودن شرکت
    ent_Num تعداد انتظامی بودن شرکت
    percentapplicantsize درصد افزایش اندازه پروژه
    applicantmasterdetail جدول ارتباطی طرح ها
    */         
$sql = "SELECT distinct designsystemgroups.DesignSystemGroupsid,creditsource.credityear,
creditsource.creditsourceid,ifnull(applicantmaster.proposestate,0) proposestatenn,applicantmaster.*,applicantstategroups.applicantstategroupsID,applicantstategroups.Title applicantstategroupsTitle, 
designsystemgroups.title DesignSystemGroupstitle
,designerco.title DesignerCotitle,case proposestatep when -1 then 'انصراف متقاضی' else applicantstates.title end applicantstatestitle,
case proposestatep when -1 then -1 else applicantstates.applicantstatesID end applicantstatesID
,shahr.cityname shahrcityname,shahr.id shahrid 
,creditsource.title creditsourcetitle
,applicantmaster.belaavaz belaavazl,applicantmaster.TMDate laststatedate,ifnull(applicantmasterdetail.ApplicantMasterIDmaster,0) ApplicantMasterIDop
FROM applicantmaster 
inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterID=applicantmaster.ApplicantMasterID
inner join applicantstates on applicantstates.applicantstatesID=applicantmaster.applicantstatesID $strappnum


inner join designerco on designerco.DesignerCoID=applicantmaster.DesignerCoID
left outer join applicantstategroups on applicantstategroups.applicantstategroupsID=applicantstates.applicantstategroupsID

left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) 
and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'


left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.DesignSystemGroupsid=applicantmaster.DesignSystemGroupsid


left outer join creditsource on creditsource.creditsourceid=applicantmaster.creditsourceid
where substring(applicantmaster.cityid,1,2)=substring('$selectedCityId',1,2)   $str
$orderby ";

//print $sql;               
$result = mysql_query($sql);
							try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

    $ID1[' ']=' ';
    $ID1[' خالی']='-2';
    $ID1[' غیرخالی']='-1';
	
    $ID2[' ']=' ';
    $ID3[' ']=' ';
    $ID4[' ']=' ';
    $ID5[' ']=' ';
    $ID6[' ']=' ';
    $ID8[' ']=' ';
    $ID9[' ']=' ';
    $ID10[' ']=' ';
    $ID10[' خالی']='-2';
    $ID10[' غیرخالی']='-1';
    $ID11[' ']=' ';
	$ID12[' ']=' ';
$dasrow=0;
while($row = mysql_fetch_assoc($result))
{
    $dasrow=1;
    $ID1[trim($row['creditsourcetitle'])]=trim($row['creditsourceid']);
    $ID12[trim($row['credityear'])]=trim($row['credityear']);
    $ID2[trim($row['shahrcityname'])]=trim($row['shahrid']);
    $ID3[trim($row['ApplicantName'])]=trim($row['ApplicantName']);
    $ID4[trim($row['DesignerCotitle'])]=trim($row['DesignerCoID']);
    $ID5[trim($row['applicantstatestitle'])]=trim($row['applicantstatesID']);
    $ID6[trim(gregorian_to_jalali($row['laststatedate']))]=trim($row['laststatedate']);
    $ID8[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);
    $ID9[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsid']);
    $ID10[trim($row['BankCode'])]=trim($row['BankCode']);
    $ID11[trim($row['applicantstategroupsTitle'])]=trim($row['applicantstategroupsID']);
	
}
$ID1=mykeyvalsort($ID1);
$ID2=mykeyvalsort($ID2);
$ID3=mykeyvalsort($ID3);
$ID4=mykeyvalsort($ID4);
$ID5=mykeyvalsort($ID5);
$ID6=mykeyvalsort($ID6);
$ID8=mykeyvalsort($ID8);
$ID9=mykeyvalsort($ID9);
$ID10=mykeyvalsort($ID10);
$ID11=mykeyvalsort($ID11);
$ID12=mykeyvalsort($ID12);
if ($dasrow)
mysql_data_seek( $result, 0 );
//print $sql;


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
$IDself= get_key_value_from_query_into_array($query);

$IDselfval=$_POST['IDself'];
    


$query="
select 'نام خانوادگی' _key,1 as _value union all
select 'نام' _key,2 as _value union all 
select 'مساحت' _key,3 as _value union all
select 'نوع سیستم' _key,4 as _value union all
select 'شهرستان' _key,5 as _value union all
select 'شرکت طراح' _key,6 as _value union all
select 'وضعیت' _key,7 as _value union all
select 'تاریخ' _key,8 as _value union all
select 'کد' _key,9 as _value ";
$IDorder = get_key_value_from_query_into_array($query);

if (!$_POST['IDorder'])
    $IDorderval=7;
else $IDorderval=$_POST['IDorder'];

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
$IDbela= get_key_value_from_query_into_array($query);
if ($_POST['IDbela']>0)
    $IDbelaval=$_POST['IDbela'];


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

$query = "select distinct case ifnull(applicantmasterop.belaavaz,0) when 0 then applicantmasterall.belaavaz 
else applicantmasterop.belaavaz end as _value,applicantmasterall.BankCode as _key 
from applicantmaster applicantmasterop
inner join applicantmaster applicantmasterall on applicantmasterop.BankCode=applicantmasterall.BankCode 
 and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4)
inner join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmasterall.ApplicantMasterID and state=1 
and applicantmasterop.operatorcoID=operatorapprequest.operatorcoID";
$belaavazID = get_key_value_from_query_into_array($query);
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
  	<title>ليست طرحهاي طراحي</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
    <script src="http://api.mygeoposition.com/api/geopicker/api.js" type="text/javascript"></script>
    <script type="text/javascript">
        function lookupGeoData(Lat,Lng) {
            myGeoPositionGeoPicker({
                returnFieldMap            : {'geoposition5' : '<LAT>,<LNG>'},
                startPositionLat        : Lat,
                startPositionLng        : Lng
            });
        }
    </script> 
    

    <!-- /scripts -->
    
  
  
</head>
<body >

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">

			<!-- top -->
        	<?php 

            
            include('../includes/top.php'); ?>
            <!-- /top -->

            <!-- main navigation -->
            <?php 

            include('../includes/navigation.php'); ?>
            <!-- /main navigation -->
			<!-- main navigation -->
            <?php include('../includes/subnavigation.php'); ?>
            <!-- /main navigation -->

			<!-- header -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
            
            
            
			<div id="content">
            
            <form action="allapplicantstatesbank.php" method="post">
                <table width="95%" align="center">
                    <tbody class='no-print' >
                           <tr>
                           <?php 

                           
                     $query="SELECT YearID as _value,Value as _key FROM `year` 
                     where YearID in (select YearID from cityquota)
                     
                     ORDER BY year.Value DESC";
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
                     select_option('YearID','سهمیه',',',$ID,0,'','','1','rtl',0,'',$yearid,'','75');
                     
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
                     
                     print
                     select_option('applicantstategroupsID','',',',$ID11,0,'','','1','rtl',0,'',$applicantstategroupsID,'','100').
                     select_option('IDorder','ترتیب',',',$IDorder,0,'','','3','rtl',0,'',$IDorderval,"",'100');?> 
                    <td colspan="1" class="label" >ثبت&nbsp;اولیه</td>
                     <td class="data" ><input name="showm" type="checkbox" id="showm"  <?php if ($showm>0) echo "checked"; ?> /></td>
                    <td colspan="1" class="label">تایید&nbsp;نهایی</td>
                     <td class="data"><input name="showt" type="checkbox" id="showt" <?php if ($showt>0) echo "checked"; ?> /></td>
                    <td colspan="1" class="label">تجمیع</td>
                     <td class="data"><input name="showc" type="checkbox" id="showc" <?php if ($showc>0) echo "checked"; ?> /></td>
                     
                     <?php  if ($login_designerCO==1)
                     {                    
                         print "<td colspan='1' class='label'>شخصی</td>
                         <td class='data'><input name='showp' type='checkbox' id='showp'";
                         if ($showp>0) echo 'checked';
                         print " /></td>";
                         
                         
                     }
                     
                         print "<td colspan='1' class='label'>همه</td>
                     <td class='data'><input name='showa' type='checkbox' id='showa'";
                         if ($showa>0) echo 'checked';
                         print " /></td>";
                      ?>
                     <td colspan="1" class="label">بروز</td>
                     <td class="data"><input name="showprice" type="checkbox" id="showprice" <?php if ($showprice>0) echo "checked"; ?> /></td>
    			     <?php print select_option('credityear','',',',$ID12,0,'','','1','rtl',0,'',$credityear,'','40');?>

      
				 			                                       
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

	 <?php 


                    $permitrolsid = array("1","5", "19","13","14");
                    if (in_array($login_RolesID, $permitrolsid))
                    {
					$directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/sandugh/';
		         	$handler = opendir($directory);
					$i=0;
					while ($file = readdir($handler)) 
                     {
						 
                        if ($file != "." && $file != "..") 
                        { $i++;
                            $linearray = explode('_',$file);
							
                           // $IDU[$i]=$linearray[0];
							$IDU[$i]=explode('p',$linearray[0]);
							$AppMasterID=$IDU[$i][1];
							$imgfile[$AppMasterID]=$file;
					    }
				     }
					}
			      ?> 
		
	    
		
				   
                <table align='center' border='1' id="table2">              
                   <thead>
				  <tr> 
                  
                            <td colspan="18"
                            <span class="f14_fontb" >  آخرین وضعیت پروژه های مطالعاتی سیستم های نوین آبیاری(مبالغ به میلیون ریال)</span>  </td>
                            
				   </tr>
				   
                        <tr>

                            <th <span class="f9_fontb" > رديف  </span> </th>
							<th <span class="f9_fontb" >کد</span> </th>
							<th <span class="f14_fontb"> نام  </span> </th>
							<th <span class="f11_fontb"> نام خانوادگی </span> </th>
							<th <span class="f9_fontb" >
                            <?php if (in_array($login_RolesID, array("17", "31","32"))) echo 'متراژ'; else echo "مساحت </span> (ha)";  ?> </th>
                            <th  class="f14_fontb"> نوع سیستم  </th>
						    <th <span class="f11_fontb">دشت/ شهرستان</span> </th>
							<th <span class="f14_fontb"> مبلغ کل </span>
						    <th <span class="f14_fontb">وضعیت</span> </th>
						    <th <span class="f14_fontb">کمک بلاعوض</span> </th>
						           <th  class="f11_fontb"> سهم خودیاری</th>
                 	
							<th <span class="f13_fontb">شماره نامه</span> </th>
							    <th <span class="f14_fontb">تاریخ</span> </th>
						
						    <th <span class="f14_fontb">نوع اعتبار</span> </th>
							<th <span class="f11_fontb">همراه</span> </th>
                        	<th <span class="f11_fontb">کدملی</span> </th>
                        	<th <span class="f11_fontb">کد</span> </th>
                            <th  class="f13_fontb" style = 'width: 25px;'>&nbsp;&nbsp; </th>
                             <?php 
                             if ($login_RolesID==5 || $login_designerCO==1)
                             print "<th style = 'width: 25px;'>&nbsp;&nbsp; </th>"; ?>
                            
                       <!--     <th <span class="f11_font">کد رهگیری <br />(کد صندوق/ بانک) </span> </th>
					    //if ($row['sandoghcode']<>'') echo "<br>($row[sandoghcode])";
					   -->
                        </tr>
                       </thead> 
                        <tr>    
						
							<td class="f14_font"></td>
                        	<td class="f14_font"></td>
                             <?php print select_option('ApplicantFname','',',',$ID8,0,'','','1','rtl',0,'',$ApplicantFname,'','55'); ?>
							 <?php print select_option('ApplicantName','',',',$ID3,0,'','','1','rtl',0,'',$ApplicantName,'','85'); ?>
							 <?php print select_option('IDArea','',',',$IDArea,0,'','','1','rtl',0,'',$IDAreaval,'','30'); ?>
					         <?php print select_option('DesignSystemGroupstitle','',',',$ID9,0,'','','1','rtl',0,'',$DesignSystemGroupstitle,'','50'); ?>
					         <?php print select_option('sos','',',',$ID2,0,'','','1','rtl',0,'',$sos,"",'55'); ?> 
					         <?php print select_option('IDprice','',',',$IDprice,0,'','','1','rtl',0,'',$IDpriceval,'','50'); ?>  
					         <?php print select_option('applicantstatesID','',',',$ID5,0,'','','1','rtl',0,'',$applicantstatesID,'','60');?>
					         <?php print select_option('IDbela','',',',$IDbela,0,'','','1','rtl',0,'',$IDbelaval,'','50'); ?> 
                 	         <?php print select_option('IDself','',',',$IDself,0,'','','1','rtl',0,'',$IDselfval,'','50'); ?> 

							 <?php print select_option('letterno','',',',$ID4,0,'','','1','rtl',0,'',$letterno,'','55') ?> 
					          <?php print select_option('letterdate','',',',$ID6,0,'','','1','rtl',0,'',$letterdate,'','60');?>
					        
					         <?php print select_option('creditcsourceID','',',',$ID1,0,'','','1','rtl',0,'',$creditcsourceID,'','80');?>
                             <?php print select_option('BankCode','',',',$ID10,0,'','','1','rtl',0,'',$BankCode,'','70'); ?>
					         <?php print select_option('melicode','',',',$ID10,0,'','','1','rtl',0,'',$melicode,'','70'); ?>
					  <td class='no-print' colspan="<?php 
                       if ($login_RolesID==5 || $login_designerCO==1)
                       print "4"; else print "3";  ?>"><input   name="submit" type="submit" class="button" id="submit" size="12" value="جستجو" /></td>
                       
					 
					 </tr> 
                     
                        
                   <?php
                   $sumDA=0;
                   $sumM=0;
                   $rown=0;
                   $totalbelaavaz=0;
                   $totalself=0;
                    while($row = mysql_fetch_assoc($result))
                    {

					if ($login_RolesID=='7' || $login_RolesID=='16')
						$sandoghcode=$row['sandoghcode'];
					else  if ($login_RolesID=='17')
                    	$sandoghcode=$row["numfield2"]; 
                    else   
						$sandoghcode=$row["numfield"]; 
	
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
                        $ID = $row['ApplicantMasterID'].'_4_'.$row['DesignerCoID'].'_'.$row['operatorcoid'].'_'.$row['applicantstatesID']
                        .'_'.$login_RolesID;
                        if ($row['criditType']==1) $criditType='+';else $criditType='';
                        $ApplicantName = $row['ApplicantName'];
                        $ApplicantFName = $row['ApplicantFName'];
                        $year = $row['year'];
                        $CostPriceListMasterID=$row['CostPriceListMasterID'];
                        $applicantstatestitle=$row['applicantstatestitle'];
                        
                        $sumDA+=$row['DesignArea'];
                        
                        if ($row['SaveTime']<$row['msavetime'])
                        $maxdate=$row['msavetime'];
                        else
                        $maxdate=$row['SaveTime'];
                        
                       // print $maxdate;
                        
                        if ((substr($row['LastChangeDate'],1,10)<substr($maxdate,1,10)  && $applicantstatestitle!='ثبت اولیه' && $applicantstatestitle!='مدیر مشاور به کاربرطراح') || ($showprice==1) )
                        {
                        
                        }
                        else 
                            $sumL=$row['LastTotal'];
                            
                            
                            foreach ($belaavazID as $key => $value)
                            {
                                if ($row['BankCode']==$value)
                                    $belaavazl=round($key,1);     
                            }
                        $belaavazl=round($row['belaavazl'],1);
                        $totalbelaavaz+=$row['belaavazl'];
                        
                        $sumM+=$sumL ;
                        $rown++;
                        if ($rown%2==1) 
                        $b='b'; else $b='';
                        
?>                      
                        <tr>    

                            <td <span class="f10_font<?php echo $b; ?>"  >  <?php echo $criditType.'&nbsp;'.$rown; ?> </span>  </td>
							<td <span class="f10_font<?php echo $b; ?>"  >  <?php echo "($sandoghcode)";?>  </span> </td>
							
							<td <span class="f9_font<?php echo $b; ?>">  <?php echo $ApplicantFName; ?> </span> </td>
                            <td	<span class="f9_font<?php echo $b; ?>">  <?php echo $ApplicantName; ?> </span> </td>
                            <td	<span class="f10_font<?php echo $b; ?>">  <?php echo $row['DesignArea']; ?> </span> </td>
                            <td <span class="f7_font<?php echo $b; ?>">  <?php echo $row['DesignSystemGroupstitle']; ?> </span> </td>
                            <td	<span class="f10_font<?php echo $b; ?>">  <?php echo $row['shahrcityname']; ?> </span> </td>
                            <td	<span class="f11_font<?php echo $b; ?>">  <?php echo floor($sumL/100000)/10; ?> </span> </td>
                                                           
                            <td <span class="f7_font<?php echo $b; ?>">  <?php echo str_replace(' ', '&nbsp;', $applicantstatestitle); ?> </span> </td>
                            <td <span class="f11_font<?php echo $b; ?>">  <?php echo $belaavazl; ?> </span> </td>
							<td	<span class="f10_font<?php echo $b; ?>">  <?php $totalself+=floor(($row['selfcashhelpval']+$row['selfnotcashhelpval'])/100000)/10; echo floor(($row['selfcashhelpval']+$row['selfnotcashhelpval'])/100000)/10; ?> </span> </td>  

						    <td	<span class="f9_font<?php echo $b; ?>">  <?php echo  $row['letterno']; ?> </span> </td>
							<td <span class="f9_font<?php echo $b; ?>">  <?php echo $row['letterdate']; ?>  </span> </td>
                           
                    	
                            <td <span class="f7_font<?php echo $b; ?>">  <?php echo str_replace(' ', '&nbsp;', $row['creditsourcetitle']); ?> </span> </td>
                                                     
						    <td <span class="f8_font<?php echo $b; ?>">  <?php echo $row['mobile'];?> </td>
							<td <span class="f8_font<?php echo $b; ?>">  <?php echo $row['melicode'];?> </td>
                            <td <span class="f10_font<?php echo $b; ?>"  >  <?php echo $row['sandoghcode'];?>  </span> </td>
	         	            <?php 
                            $AppMasterID=$row['ApplicantMasterID'];
					     
						  $fstr1="<td><a target='_blank' href='../../upfolder/sandugh/$imgfile[$AppMasterID]' ><img style = 'width: 20px;' src='../img/full_page.png' title='نامه ارسال پرونده' ></a></td>";
                    	 if($imgfile[$AppMasterID])  print $fstr1;
							
							?>
							
                                
							 
                        </tr><?php

                    }
                    
                    
                    

?>

                        <tr>
                            
                            <td colspan="12" class="f14_fontb" ><?php 
                            
                            if (in_array($login_RolesID, array("17", "31","32")))
                            echo ' مجموع متراژ';
                            else
                            echo ' مجموع مساحت (هكتار)';   ?></td>
                            <td colspan="5"
                            class="f14_fontb" 
                            ><?php echo $sumDA;   ?></td>
                        </tr>
                        <tr>
                            
                            <td colspan="12" class="f14_fontb" ><?php echo ' مجموع مبلغ کل (ميليون ريال)';   ?></td>
                            <td colspan="5" 
                            class="f14_fontb" 
                            ><?php echo round(($sumM/1000000),1);   ?></td>
                        </tr>
                         <tr>
                            
                            <td colspan="12" class="f14_fontb" ><?php echo ' مجموع  بلاعوض معرفی شده (ميليون ريال)';   ?></td>
                            <td colspan="5" 
                            class="f14_fontb" 
                            ><?php echo floor($totalbelaavaz);   ?></td>
                        </tr> 
                        
                         <tr>
                            
                            <td colspan="12" class="f14_fontb" ><?php echo 'مجموع سهم خودیاری(ميليون ريال)';   ?></td>
                            <td colspan="5" 
                            class="f14_fontb" 
                            ><?php echo $totalself;   ?></td>
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

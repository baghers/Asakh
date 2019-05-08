<?php 

/*

//appinvestigation/allapplicantstates.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/applicant_manageredit.php
/insert/summaryinvoice.php
-
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php
require ('../includes/gPoint.php');
require ('../includes/functions.php');  

//echo $login_CityId;

	if ($login_Permission_granted==0) header("Location: ../login.php");
	$showa=0;$showc=0;$yearid='';$prjtypeid='';$BankCode='';
	if (in_array($login_RolesID, array( "31","32")) && !$_POST)  $_POST['DesignerCoID']=67;  
    //نقش هایی که امکان مشاهده پیشنهاد های  انتخاب نشده را دارند
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
          
	$permitrolsid = array("13","14","17","18","19","20","21","31","32");
	 if (in_array($login_RolesID, $permitrolsid)) $yearid=$yearid_select;
//print $yearid;exit;
if ($_POST)
{
    $yearid=$_POST['YearID'];//سال سهمیه
    $DesignAreafrom=$_POST['DesignAreafrom'];//از مساحت
    $DesignAreato=$_POST['DesignAreato'];//تا مساحت
    $sos=$_POST['sos'];//استان
    $sob=$_POST['sob'];//بخش
    $DesignerCoID=$_POST['DesignerCoID'];//شرکت مشاور طراح
    $applicantstatesID=$_POST['applicantstatesID'];//شناسه وضعیت
    $creditcsourceID=$_POST['creditcsourceID'];//شناسه منبع
    $credityear=$_POST['credityear'];//سال اعتبار
    $dateID=$_POST['dateID'];//تاریخ
	$applicantstategroupsID=$_POST['applicantstategroupsID'];//شناصه گروه وضعیت
	$ApplicantFname=$_POST['ApplicantFname'];//نام
    $Applicantname=$_POST['ApplicantName'];//عنوان پروژه
    $DesignSystemGroupstitle=$_POST['DesignSystemGroupstitle'];//سیستم آبیاری
    
    if ($_POST['showa']=='on')//نمایش طرح های سایر شهرستان ها برای ناظرین عالی
    $showa=1;
    if ($_POST['showm']=='on')//نمایش فقط پروژه های آبیارری تحت فشار
    $showm=1;
    if ($_POST['showp']=='on')//نمایش طرح های شخصی
    $showp=1;
    if ($_POST['showt']=='on')//طرح های انعقاد شده بانک و صندوق
    $showt=1;
    if ($_POST['showc']=='on')//تجمیع
    $showc=1;
    if (trim($_POST['BankCode'])==-2)//کد رهگیری
        $str.=" and ifnull(applicantmaster.BankCode,0)=0";
    else if (trim($_POST['BankCode'])==-1)
        $str.=" and ifnull(applicantmaster.BankCode,0)>0";
    else if (strlen(trim($_POST['BankCode']))>0 && $_POST['BankCode']<>'0')
        $str.=" and applicantmaster.BankCode='$_POST[BankCode]'";
        
    if (strlen(trim($_POST['DesignAreafrom']))>0)//از تاریخ
        $str.=" and DesignArea>='$_POST[DesignAreafrom]'";
    if (strlen(trim($_POST['DesignAreato']))>0)//تا تاریخ
        $str.=" and DesignArea<='$_POST[DesignAreato]'";
    if (strlen(trim($_POST['sos']))>0)//استان
        $str.=" and substring(shahr.id,1,4)=substring('$_POST[sos]',1,4)";
	if (strlen(trim($_POST['DesignerCoID']))>0)//مشاور
        $str.=" and applicantmaster.DesignerCoID='$_POST[DesignerCoID]'";
    if (strlen(trim($_POST['applicantstatesID']))>0)//وضعیت
        $str.=" and applicantstates.applicantstatesID='$_POST[applicantstatesID]'";   
    if (strlen(trim($_POST['dateID']))>0)//تاریخ
        $str.=" and applicantmaster.TMDate='$_POST[dateID]'";  
    if (trim($_POST['creditcsourceID'])==-2)//منبع تامین اعتبار
        $str.=" and ifnull(applicantmaster.creditsourceID,0)=0";
    else if (trim($_POST['creditcsourceID'])==-1)
        $str.=" and ifnull(applicantmaster.creditsourceID,0)>0";
    else if (strlen(trim($_POST['creditcsourceID']))>0 || $_POST['credityear']>0 )
    {
			if (strlen(trim($_POST['creditcsourceID']))>0)
				$str.=" and applicantmaster.creditsourceID='$_POST[creditcsourceID]'"; 
			else if ($_POST['credityear']>0)
				$str.=" and creditsource.credityear='$_POST[credityear]'";
    } 
	
	if (strlen(trim($_POST['DesignSystemGroupstitle']))>0)//سیستم آبیاری
        $str.=" and designsystemgroups.designsystemgroupsid ='$_POST[DesignSystemGroupstitle]'";
	if (strlen(trim($_POST['ApplicantFname']))>0)//نام متقاضی
        $str.=" and applicantmaster.ApplicantFname like'%$_POST[ApplicantFname]%'";
	if (strlen(trim($_POST['ApplicantName']))>0)//عنوان پروژه
        $str.=" and applicantmaster.ApplicantName like '%$_POST[ApplicantName]%'";
    if (strlen(trim($_POST['applicantstategroupsID']))>0)//شناسه سیستم
        $str.=" and applicantstategroups.applicantstategroupsID='$_POST[applicantstategroupsID]'";  
	if (strlen(trim($_POST['prjtypeid']))>0)//نوع پروژه
        $str.=" and applicantmasterdetail.prjtypeid='$_POST[prjtypeid]'";  
	if (strlen(trim($_POST['IDArea']))>0)//شناسه فیلتر مساحت
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
	
    if (strlen(trim($_POST['IDprice']))>0)// شناسه مبلغ	
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
        
    if (trim($_POST['IDbela'])==-2)//شناسه بلاعوض
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
    if (trim($_POST['IDself'])==-2)//خودیاری
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
    if($login_RolesID==11)//مدیر مشاور ناظر
        $str.=" and applicantmaster.applicantstatesID in (5,6,7) ";
    if($login_RolesID==16 || $login_RolesID==7)//بانک یا صندوق
        {$showt=1;}
    if($login_RolesID==17|| $login_RolesID==14)//ناظر مقیم یا ناظر عالی
        {$showm=1; $str.=" and ifnull(applicantmasterdetail.prjtypeid,0)=0 ";   }
    
    /*if($login_RolesID==11)
        $applicantstategroupsID=26;
    else     
        $applicantstategroupsID=27;
    $str.=" and applicantstategroups.applicantstategroupsID='$applicantstategroupsID'";  
    */
}


    $sql = "SELECT value  FROM year where YearID='$yearid' ";
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

    $row = mysql_fetch_assoc($result);
    $yearvalue=$row['value'];

	if($login_RolesID==26) {$showc=1;$showm=1;$showt=1;}
   
    if (($showm!=1)) $str.=" and applicantmaster.applicantstatesID<>23 ";
    if (($showt!=1)) $str.=" and applicantmaster.applicantstatesID not in (1,22,37) ";
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
    case 8: $orderby=' order by applicantmaster.TMDate'; break;
    case 9: $orderby=' order by cast(applicantmaster.numfield as  decimal(10,0))'; break;
    default: 
    if ($login_RolesID=='7' || $login_RolesID=='16')//بانک صندوق
        $orderby=' order by cast(applicantmaster.sandoghcode as  decimal(10,0))';
    else if ($login_RolesID=='19')    
        $orderby=' order by shahrcityname COLLATE utf8_persian_ci,applicantmaster.numfield,applicantmaster.TMDate ';
    else     
        $orderby=' order by shahrcityname COLLATE utf8_persian_ci,applicantstatestitle COLLATE utf8_persian_ci,applicantmaster.TMDate  ';
		break; 
  }
  
$strappnum="";

if ($login_RolesID=='6') 
    $strappnum=" and applicantstates.applicantstatesID in (36,37,1,12,22) ";
if ($login_RolesID=='7') 
    $strappnum=" and applicantstates.applicantstatesID in (36,37,1) ";
if ($login_RolesID=='16') 
    $strappnum=" and applicantstates.applicantstatesID in (12,22,1) ";
if ($login_RolesID=='17') 
    $str.=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) ";
else if (($login_RolesID=='14') && ($showa==0)) 
        $str.=" and substring(applicantmaster.cityid,1,4) in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid') ";

$selectedCityId=$login_CityId;
if ($_POST['ostan']>0)
        $selectedCityId=$_POST['ostan'];
if (in_array($login_RolesID, array( "31","32")))
        $str.=" and ifnull(applicantmasterdetail.prjtypeid,0)=1 ";
//else if (! in_array($login_RolesID, array( "17")))
  //      $str.=" and ifnull(applicantmasterdetail.prjtypeid,0)<>1 "; 

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
,creditsource.title creditsourcetitle,prjtype.title prjtypetitle,prjtype.prjtypeid
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
left outer join prjtype on prjtype.prjtypeid=applicantmasterdetail.prjtypeid

where substring(applicantmaster.cityid,1,2)=substring('$selectedCityId',1,2)   $str
$orderby ";

//print $sql;               
$result = mysql_query($sql.$login_limited);
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
	$ID13[' ']=' ';
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
    $ID13[trim($row['prjtypetitle'])]=trim($row['prjtypeid']);
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
$ID13=mykeyvalsort($ID13);
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
            
            <form action="allapplicantstates.php" method="post">
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
                     
                     print
                     select_option('applicantstategroupsID','',',',$ID11,0,'','','1','rtl',0,'',$applicantstategroupsID,'','100').
					   select_option('prjtypeid','پروژه',',',$ID13,0,'','','2','rtl',0,'',$prjtypeid,"",'100').
                     select_option('IDorder','ترتیب',',',$IDorder,0,'','','2','rtl',0,'',$IDorderval,"",'100');?> 
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
                  
                            <td colspan="16"
                            <span class="f14_fontcb" >  آخرین وضعیت پروژه های مطالعاتی سیستم های نوین آبیاری(مبالغ به میلیون ریال) 
							                                              
                           <a  target='<?php echo $target;?>' href='allapplicantstatesbank.php'>اطلاعات تکمیلی</a> </span> </td>	 
					        <th colspan="6"  class="f14_fontc">&nbsp;&nbsp; </th>
                                                 
				   </tr>
				   
                        <tr>

                            <th <span class="f12_fontb" > رديف  </span> </th>
							<th <span class="f12_fontb" >کد</span> </th>
							<th <span class="f14_fontb"> نام  </span> </th>
							<th <span class="f14_fontb"> نام خانوادگی </span> </th>
							<th <span class="f13_fontb">
                            <?php if (in_array($login_RolesID, array("17", "31","32"))) echo 'متراژ'; else echo "مساحت </span> (ha)";  ?>
                               </th>
                            <th  class="f14_fontb"> نوع سیستم  </th>
						    <th <span class="f14_fontb">دشت/ شهرستان</span> </th>
							<th <span class="f14_fontb">شركت طراح</span> </th>
							<th <span class="f14_fontb"> مبلغ کل </span>
						    <th <span class="f14_fontb">وضعیت</span> </th>
							<th <span class="f14_fontb">تاریخ</span> </th>
						    <th <span class="f14_fontb">کمک بلاعوض</span> </th>
						    <th colspan="2" <span class="f14_fontb">نوع اعتبار</span> </th>
							<th <span class="f14_fontb">کد رهگیری </span> </th>
                            <th  class="f13_fontb"> سهم خودیاری</th>
                            <th colspan="7"  class="f14_fontc">&nbsp;&nbsp; </th>
                             <?php 
                             if ($login_RolesID==5 || $login_designerCO==1)
                             print "<th style = 'width: 25px;'>&nbsp;&nbsp; </th>"; ?>
                            
                       <!--     <th <span class="f11_font">کد رهگیری <br />(کد صندوق/ بانک) </span> </th>
					    //if ($row['sandoghcode']<>'') echo "<br>($row[sandoghcode])";
					   -->
                        </tr>
                       </thead> 
					   
					   
					   
                        <tr>    
						
							<td class="f10_font"></td>
                        	<td class="f10_font"></td>
                             <?php print select_option('ApplicantFname','',',',$ID8,0,'','','1','rtl',0,'',$ApplicantFname,'','','','',''); ?>
							 <?php print select_option('ApplicantName','',',',$ID3,0,'','','1','rtl',0,'',$ApplicantName,'',''); ?>
							 <?php print select_option('IDArea','',',',$IDArea,0,'','','1','rtl',0,'',$IDAreaval,'',''); ?>
					         <?php print select_option('DesignSystemGroupstitle','',',',$ID9,0,'','','1','rtl',0,'',$DesignSystemGroupstitle,'',''); ?>
					         <?php print select_option('sos','',',',$ID2,0,'','','1','rtl',0,'',$sos,"",''); ?> 
					         <?php print select_option('DesignerCoID','',',',$ID4,0,'','','1','rtl',0,'',$DesignerCoID,'','') ?> 
					         <?php print select_option('IDprice','',',',$IDprice,0,'','','1','rtl',0,'',$IDpriceval,'',''); ?>  
					         <?php print select_option('applicantstatesID','',',',$ID5,0,'','','1','rtl',0,'',$applicantstatesID,'','');?>
							 <?php print select_option('dateID','',',',$ID6,0,'','','1','rtl',0,'',$dateID,'','');?>
					         
					         <?php print select_option('IDbela','',',',$IDbela,0,'','','1','rtl',0,'',$IDbelaval,'',''); ?> 
							 
					         <?php print select_option('creditcsourceID','',',',$ID1,0,'','','1','rtl',0,'',$creditcsourceID,'','');?>
						     <?php print select_option('credityear','',',',$ID12,0,'','','1','rtl',0,'',$credityear,'','');?>
						
                             <?php print select_option('BankCode','',',',$ID10,0,'','','1','rtl',0,'',$BankCode,'',''); ?>
					         <?php print select_option('IDself','',',',$IDself,0,'','','1','rtl',0,'',$IDselfval,'',''); ?> 
					  <td class='no-print' colspan="<?php 
                             if ($login_RolesID==5 || $login_designerCO==1)
                             print "4"; else print "3";  ?>"><input   name="submit" type="submit" class="button" id="submit" size="16" value="جستجو" /></td>
					 </tr> 
                     


					 
                   <?php
                   $sumDA=0;
                   $sumM=0;
                   $rown=0;
                   $totalbelaavaz=0;
                   $totalself=0;
                    while($row = mysql_fetch_assoc($result))
                    {
                        if ($login_RolesID=='11')// بازبین
                        {
                            $permit=0;
                            $foud=0;
                            if ($row["DesignerCoIDnazer"]>0 && $row["DesignerCoID"]>0)
                            {
                                if ($row["DesignerCoIDnazer"]==$login_userid)
                                $permit=1;
                                else continue; 
                            }
                            else
                            {
                                
                                $query = "select distinct ClerkIDinspector  as _value, substring(tax_tbcity7digit.id,1,4) as _key from tax_tbcity7digit 
                                            where ClerkIDinspector>0";
                                $ClerkIDinspectorID = get_key_value_from_query_into_array($query);
                                foreach ($ClerkIDinspectorID as $key => $value)
                                {
                                    //print substr($row["CityId"],0,4)."_".$key."<br>";
                                    if (substr($row["CityId"],0,4)==$key)
                                        $foud=1;    
                                    if (substr($row["CityId"],0,4)==$key and $login_userid==$value)
                                        $permit=1;   
                                }
                                if ($permit==0 && $foud==1)
                                    continue;                                 
                            }
                        }
                        

						if ($login_RolesID=='7' || $login_RolesID=='16')
							$sandoghcode=$row['sandoghcode'];
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
                        
                        
                            $linearray = explode('_',$row['CountyName']);
                            $apps=$linearray[5];
    
                        $ApplicantName = $row['ApplicantName'];
                        $ApplicantFName = $row['ApplicantFName'];
                        $year = $row['year'];
                        $CostPriceListMasterID=$row['CostPriceListMasterID'];
                        $applicantstatestitle=$row['applicantstatestitle'];
						if ($row['criditType']==1) $criditType='+';else $criditType='';
                        $sumDA+=$row['DesignArea'];
                        
                        if ($row['SaveTime']<$row['msavetime'])
                        $maxdate=$row['msavetime'];
                        else
                        $maxdate=$row['SaveTime'];
                        
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
						
						if ($login_designerCO==1 || $login_RolesID=='19')
						if ($row['CostPriceListMasterID']==13) $fb='ف:95';else if ($row['CostPriceListMasterID']==12) $fb='ف:94';
						else if ($row['CostPriceListMasterID']==11) $fb='ف:93';else if ($row['CostPriceListMasterID']==9) $fb='ف:92';else if ($row['CostPriceListMasterID']==10) $fb='ف:94';else  $fb='';
                        
?>                      
                        <tr>    

                            <td <span class="f12_font<?php echo $b; ?>"  >  <?php 
                            if ($login_designerCO==1)
                                echo "(".$row['ApplicantMasterID'].")</br>";
                            echo $criditType.'&nbsp;'.$rown; 
                            if ($apps==1) echo "(ک)";
                            ?> </span>  </td>
							<td <span class="f10_font<?php echo $b; ?>"  >  <?php echo "($sandoghcode) <br>$fb";?>  </span> </td>
							<td <span class="f12_font<?php echo $b; ?>">  <?php echo $ApplicantFName; ?> </span> </td>
                            <td	<span class="f12_font<?php echo $b; ?>">  <?php echo $ApplicantName; ?> </span> </td>
                            <td	<span class="f12_font<?php echo $b; ?>">  <?php echo $row['DesignArea']; ?> </span> </td>
                            <td <span class="f9_font<?php echo $b; ?>">  <?php echo $row['DesignSystemGroupstitle']; ?> </span> </td>
                               <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo $row['shahrcityname']; ?> </span> </td>
                        		 
                            <td	<span class="f10_font<?php echo $b; ?>">  <?php echo str_replace(' ', '&nbsp;', $row['DesignerCotitle']); ?> </span> </td>
                            <td	<span class="f12_font<?php echo $b; ?>">  <?php echo floor($sumL/100000)/10; ?> </span> </td>
                                                            
                            <td <span class="f9_font<?php echo $b; ?>">  <?php echo $applicantstatestitle; ?> </span> </td>
							<td <span class="f11_font<?php echo $b; ?>">  <?php if ($row['laststatedate']>0) echo gregorian_to_jalali($row['laststatedate']); ?>  </span> </td>
                            
                            <td <span class="f9_font<?php echo $b; ?>">  <?php echo $belaavazl; ?> </span> </td>
                            <td colspan="2" <span class="f8_font<?php echo $b; ?>">  <?php echo $row['creditsourcetitle']; ?> </span> </td>
                                                     
						    <td <span class="f10_font<?php echo $b; ?>">  <?php 
							if ($login_designerCO==1)
							echo $row['BankCode'] ;
							else 
							echo $row['BankCode'];
							?> </td>
                            <td	<span class="f12_font<?php echo $b; ?>">  <?php $totalself+=floor(($row['selfcashhelpval']+$row['selfnotcashhelpval'])/100000)/10; echo floor(($row['selfcashhelpval']+$row['selfnotcashhelpval'])/100000)/10; ?> </span> </td>  

							 <?php
                            $IDS=rand(10000,99999).rand(10000,99999).$row['ApplicantMasterID'].'0a'.rand(10000,99999).rand(10000,99999).$login_userid.'0arep';								 
                            $permitrolsid = array("1", "13","5","6","11","14","18","19","20","7","16","17","26","27");if (in_array($login_RolesID, $permitrolsid))
                            print "<td class='no-print'><a target='".$target."' href='applicant_manageredit.php?uid=".rand(10000,99999).rand(10000,99999)
                            .rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                            .$row['ApplicantMasterID'].'_1_'.$row['DesignerCoID'].'_'.$row['operatorcoid'].rand(10000,99999).
                            "'><img style = 'width: 20px;' src='../img/file-edit-icon.png' title=' ويرايش '></a></td>"; 
                            
                            $permitrolsid = array("1", "13","14","5","11","18","19","20","7","16",'17','22','26','27','30','31','32');if (in_array($login_RolesID, $permitrolsid))
                            print "<td class='no-print'><a  target='".$target."' href='applicantstates_detail.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).
                            "'><img style = \"width: 20px;\" src=\"../img/refresh.png\" title=' مشاهده ریز عملیات ' ></a></td>"; 
                            
                            $permitrolsid = array("1", "13","5","11","18","19","20",'17','22','26','31','32');if (in_array($login_RolesID, $permitrolsid))
                            print "<td class='no-print'><a  target='".$target."' 
                            href='../insert/summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).
                            "'><img style = 'width: 20px;' src='../img/search.png' title=' ريز '></a></td>";
                            
                            $permitrolsid = array("1", "24","25","19");if (in_array($login_RolesID, $permitrolsid))
                            print "<td class='no-print'><a  target='".$target."' 
                            href='../insert/approvedocumentapplicantmaster.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).
                            "'><img style = 'width: 20px;' src='../img/search.png' title=' مدارک طرح '></a></td>"; 
                            
                            
                            if (!in_array($login_RolesID, array("2", "31","32"))) 
                            {  
                                        $IDf = 'applicantsystemtype_t_0_ApplicantMasterID_'.$row['ApplicantMasterID'];
									   echo "<td> <a href='../codding/codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                       rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$IDf.
                                       rand(10000,99999)."' target=\"_blank\" >
                                     <img style = 'width: 20px;' src='../img/Editinf.jpg' title=' اطلاعات تكميلي سیستم و محصولات'></a></td>";
                                     
                                        $IDf = 'applicantwsource_t_0_ApplicantMasterID_'.$row['ApplicantMasterID'];
									   echo "<td> <a href='../codding/codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                       rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$IDf.
                                       rand(10000,99999)."' target=\"_blank\" >
                                     <img style = 'width: 20px;' src='../img/Editinf.jpg' title=' اطلاعات تكميلي منبع آبی'></a></td>";
                                     
                                        $IDf = 'applicantsurvey_survey_0_ApplicantMasterID_'.$row['ApplicantMasterID'];
                                     echo "<td> <a href='../codding/codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                       rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$IDf.
                                       rand(10000,99999)."' target=\"_blank\" >
                                     <img style = 'width: 20px;' src='../img/process.png' title=' اطلاعات نقشه برداری '></a></td>";
                            } 
                                     
                            
						   
						   if ($row['XUTM1']>0 && $row['YUTM1']>0)
                            {
                                $myHome =& new gPoint();
                                $myHome->setUTM( $row['XUTM1'], $row['YUTM1'], "40V");
                                $myHome->convertTMtoLL(); 
                                echo "
                                <td class='no-print'>
                                <a onclick=\"lookupGeoData(".$myHome->Lat().",".$myHome->Long().")\" href=\"#\">
                                <img style = 'width: 15px;' 
                            src='../img/gmap.png' title=' موقعیت '></a></td>
                            "; 
                                
                            }
                            else echo "<td> <label style = 'width: 15px;'/></td>";
                            
                            
                            if ( (in_array($row['applicantstatesID'], array("37","22","1")) && in_array($login_RolesID, array("7",'16')))  
                            
                             || ($login_RolesID==5 && ! in_array($row['applicantstatesID'], array("37","22","1"))) )
                             {
                                if ($row['ApplicantMasterIDop']>0)//طرح های مطالعاتی دارای پیش فاکتور
                                print "<td class='no-print'><a 
                                    href='allapplicantstates_return.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).$ID = $row['ApplicantMasterID'].'_3_'.$row['DesignerCoID'].'_'.$row['operatorcoid'].'_'.$row['applicantstatesID']
                        .'_'.$login_RolesID.rand(10000,99999).
                                    "' onClick=\"return confirm('آیا مطمئن هستید که انصراف متقاضی از اجرای طرح مطالعاتی $ApplicantName $ApplicantFName ثبت شود');\"
                                    > <img style = 'width: 20px;' src='../img/nextr.png' title='انصراف متقاضی'> </a></td>";
                                
                                else if (!($row['proposestatenn']>0))
                                    print "<td class='no-print'><a 
                                    href='allapplicantstates_return.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).$ID."_$login_RolesID".rand(10000,99999).
                                    "' onClick=\"return confirm('در صورت شرکت در پیشنهاد قیمت، تمام پیشنهاد های دریافت شده حذف خواده شد. مطمئن هستید که به کارتابل منتقل شود ؟');\"
                                    > <img style = 'width: 20px;' src='../img/next.png' title='برگشت به کارتابل'> </a></td>";
                                
                                
                                    
                                else 
                                    print "<td class='no-print'><a 
                                    href='allapplicantstates_return.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).$ID."_$login_RolesID".rand(10000,99999).
                                    "' onClick=\"return confirm('در صورت شرکت در پیشنهاد قیمت، تمام پیشنهاد های دریافت شده حذف خواده شد.دریافت پیشنهاد این طرح انجام شده است. مطمئن هستید که به کارتابل منتقل شود ؟');\"
                                    > <img style = 'width: 20px;' src='../img/nextr.png' title='برگشت به کارتابل'> </a></td>";    
                             }
                             
                            
                            print "<td class='no-print' ><a  target='".$target."' href='appuploads.php?uid=".rand(10000,99999).rand(10000,99999)
                            .rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['ApplicantMasterID'].rand(10000,99999).
                            "'>
                            <img style = 'width: 25px;' src='../img/calendar_empty.png' title=' مدیریت فایل ها '></a></td>"; 
                            
                             ?>
                            
							
							 
                        </tr><?php

                    }
                    
                    
                    

?>

                        <tr>
                            
                            <td colspan="12" class="f14_fontcb" ><?php 
                            
                            if (in_array($login_RolesID, array("17", "31","32")))
                            echo ' مجموع متراژ';
                            else
                            echo ' مجموع مساحت (هكتار)';   ?></td>
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
                            ><?php echo floor($totalbelaavaz);   ?></td>
                        </tr> 
                        
                         <tr>
                            
                            <td colspan="12" class="f14_fontb" ><?php echo 'مجموع سهم خودیاری(ميليون ريال)';   ?></td>
                            <td colspan="4" 
                            class="f14_fontcb" 
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

<?php 
/*

//appinvestigation/allapplicantrequestwitherrors.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود

-
*/


include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php
require ('../includes/functions.php');
if ($login_Permission_granted==0) header("Location: ../login.php");
$showa=0;//نمایش طرح های سایر شهرستان ها برای ناظرین عالی
$yearid='';

if ($_POST)
{
    $yearid=$_POST['YearID'];//شناسه سال
    $DesignAreafrom=$_POST['DesignAreafrom'];//از مساحت
    $DesignAreato=$_POST['DesignAreato'];//تا مساحت
	$Datefrom=$_POST['Datefrom'];//از تاریخ
    $Dateto=$_POST['Dateto'];//تا تاریخ
	$Datefroms=jalali_to_gregorian($_POST['Datefrom']);//از تاریخ شمسی
    $Datetos=jalali_to_gregorian($_POST['Dateto']);//تا تاریخ شمسی
	
     if ($_POST['showa']=='on')//نمایش طرح های سایر شهرستان ها برای ناظرین عالی
    $showa=1;
    if ($_POST['showb']=='on')//نمایش طرح های دارای دارای پیمانکار
    $showb=1;
    
    if (strlen(trim($_POST['proposestatetitle']))>0)//عنوان وضعیت پیشنهاد
        $str.=" and case ifnull(applicantmaster.proposestate,0) when 0 then 'دریافت پیشنهاد' when 1 then 'ارجاع به مدیر آبیاری' when 2 then 'ارجاع به ناظرین'  
    when 3 then case applicantmasterop.applicantstatesID when 38 then 'تحویل موقت' when 30 then 'تایید پیش فاکتورها' 
    when 35 then 'آزادسازی ظرفیت' else   concat(case ifnull(applicantmasterop.ApplicantMasterID,0) when 0 then '*' else '' end,'تایید پیشنهاد') end end='$_POST[proposestatetitle]'";
    if (strlen(trim($_POST['name']))>0)//کاربر
        $str.=" and clerkwin.ClerkID='$_POST[name]'";
    if (strlen(trim($_POST['operatorcowinTitle']))>0)//عنوان پیمانکار منتخب
        $str.=" and operatorcowin.Title='$_POST[operatorcowinTitle]'";
    if (strlen(trim($_POST['applicantstatestitle']))>0)//عنوان وضعیت پروژه
        $str.=" and applicantstates.title='$_POST[applicantstatestitle]'";
    if (strlen(trim($_POST['dateID']))>0)//شناسه تاریخ
        $str.=" and applicantmaster.ADate='$_POST[dateID]'";
    if (strlen(trim($_POST['BankCode']))>0)//کد رهگیری
        $str.=" and applicantmaster.BankCode='$_POST[BankCode]'";
    if (strlen(trim($_POST['creditsourcetitle']))>0)//عنوان منبع تامین اعتبار
        $str.=" and creditsource.title='$_POST[creditsourcetitle]'";
	if (strlen(trim($_POST['credityear']))>0)//سال اعتبار
	   $str.=" and creditsource.credityear='$_POST[credityear]'"; 
	if (strlen(trim($_POST['ApplicantFname']))>0)//نام متقاضی
        $str.=" and applicantmaster.ApplicantFname like'%$_POST[ApplicantFname]%'";
	if (strlen(trim($_POST['ApplicantName']))>0)//عنوان پروژه
        $str.=" and applicantmaster.ApplicantName like '%$_POST[ApplicantName]%'";
	if (strlen(trim($_POST['DesignSystemGroupstitle']))>0)//سیستم آبیاری
        $str.=" and designsystemgroups.title like '%$_POST[DesignSystemGroupstitle]%'";
    if (strlen(trim($_POST['sos']))>0)//محل پروژه
        $str.=" and shahr.id='$_POST[sos]'";
    if (strlen(trim($_POST['DesignerCoID']))>0)//مشاور طراح پروژه
        $str.=" and applicantmaster.DesignerCoID='$_POST[DesignerCoID]'";
    if (strlen(trim($_POST['DesignerCoIDnazer']))>0)//مشاور ناظر پروژه
        $str.=" and applicantmasterdetail.nazerID='$_POST[DesignerCoIDnazer]'";
    if (strlen(trim($_POST['DesignAreafrom']))>0)//مساحت از
        $str.=" and applicantmaster.DesignArea>='$_POST[DesignAreafrom]'";
    if (strlen(trim($_POST['DesignAreato']))>0)//مساحت تا
        $str.=" and applicantmaster.DesignArea<='$_POST[DesignAreato]'";
    if (strlen(trim($_POST['Datefrom']))>0)//از تاریخ
        $str.=" and applicantmaster.ADate>='$Datefroms'";
    if (strlen(trim($_POST['Dateto']))>0)//تا تاریخ
        $str.=" and applicantmaster.ADate<='$Datetos'";
   if (strlen(trim($_POST['IDy']))>0)//تاریخ تغییر وضعیت
		{$_POST['IDy']=substr(jalali_to_gregorian($_POST['IDy']),0,4);
         $str.=" and substring(applicantmaster.ADate,1,4)='$_POST[IDy]'";}
    else if (strlen(trim($_POST['applicantstategroupsID']))>0)//شناسه گروه وضعیت
        $str.=" and applicantstategroups.applicantstategroupsID='$_POST[applicantstategroupsID]'";     
       //فیلتر کردن بازه مساحت انتخابی
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
	//فیلتر کردن بازه مبلغ کل انتخابی
    if (strlen(trim($_POST['IDprice']))>0)	
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
    //فیلتر کردن بازه مبلغ بلاعوض انتخابی    
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
		if (strlen(trim($_POST['corank']))>0)//فیلتر شرکت های با رتبه مشخص
        $str.=" and operatorcowin.corank='$_POST[corank]'";
}
if ($yearid>0) $string=" and applicantmaster.yearid='$yearid' ";//فیلتر سال پروژه    
 

 ////////////////////////////////////////////////ایجاد نمودار رتبه و درصد پیشنهاد دهندگان ////////////////////////
 /*
 
 operatorapprequest جدول پیشنهاد قیمت
 applicantmaster جدول مشخصات طرح
 ApplicantMasterID شناسه جدول مشخصات طرح
 Price مبلغ پیشنهادی
 state وضعیت انتخاب شدن یا نشدن توسط مجری
 rank رتبه در پیشنهاد قیمت
 */

        $sql="select cnt rank,count(*)*100/
        (select count(*) from (
        SELECT  operatorapprequestout.ApplicantMasterID,
        (select count(*)+1 from operatorapprequest operatorapprequestin
        inner join  applicantmaster on applicantmaster.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and applicantmaster.yearid='$yearid' 
        where operatorapprequestin.Price<operatorapprequestout.Price and operatorapprequestin.ApplicantMasterID=operatorapprequestout.ApplicantMasterID
        ) cnt
         FROM operatorapprequest operatorapprequestout
         inner join  applicantmaster on applicantmaster.ApplicantMasterID=operatorapprequestout.ApplicantMasterID and applicantmaster.yearid='$yearid'
        where operatorapprequestout.state=1) view2) percent
         from (
        SELECT  operatorapprequestout.ApplicantMasterID,
        (select count(*)+1 from operatorapprequest operatorapprequestin
        inner join  applicantmaster on applicantmaster.ApplicantMasterID=operatorapprequestin.ApplicantMasterID and applicantmaster.yearid='$yearid'
        where operatorapprequestin.Price<operatorapprequestout.Price and operatorapprequestin.ApplicantMasterID=operatorapprequestout.ApplicantMasterID
        ) cnt
         FROM operatorapprequest operatorapprequestout
         inner join      applicantmaster on applicantmaster.ApplicantMasterID=operatorapprequestout.ApplicantMasterID and applicantmaster.yearid='$yearid'
        where operatorapprequestout.state=1 ) view1
        group by cnt
        order by rank";
        //print $sql;
        
                try 
            {		
                $result = mysql_query($sql.$login_limited);
            }
	
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
            } 
            
        
        $arrayNamey='';
        $i=1;
        while ($row = mysql_fetch_assoc($result))
        {
            $arrayNamey[$i]['name']=str_replace('ي', 'ي', $row['rank']);//رتبه 
            $arrayNamey[$i]['y']=$row['percent'];//درصد
            $i++;
        }
	/*	
    	$Path='temp/proposerankpercent.html';
		$XMLPath='temp/proposerankpercent.xml';
  		$Chart=new Chart();
  		$Chart->arrayNamey=$arrayNamey;
  		$Chart->type=1;
  		$Chart->Path=$Path;
  		$Chart->XMLPath=$XMLPath;
  		$Chart->ChartTitle='درصد رتبه پيشنهاد دهندگان انتخابي'  ;
		$Chart->CreateHtmlFile();
        
    */



    /*
    year جدول سال ها
    YearID شناسه سال
    value عنوان سال
    */
    $sql = "SELECT value  FROM year where YearID='$yearid' ";
    try 
    {		
        $result = mysql_query($sql);
    }
	//catch exception
    catch(Exception $e) 
    {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
    }
    
    
    $row = mysql_fetch_assoc($result);
    $yearvalue=$row['value'];
    
 if ($yearid>0) $str.=" and applicantmaster.yearid='$yearid' ";    
  switch ($_POST['IDorder']) 
  {
    case 1: $orderby=' order by applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break; //مرتب بر اساس عنوان پروژه
    case 2: $orderby=' order by applicantmaster.ApplicantFName COLLATE utf8_persian_ci'; break;//مرتب بر اساس متقاضی
    case 3: $orderby=' order by applicantmaster.DesignArea'; break;//مرتب بر اساس مساحت
    case 4: $orderby=' order by DesignSystemGroupstitle'; break;    //مرتب بر اساس سیستم آبیاری
    case 5: $orderby=' order by shahrcityname COLLATE utf8_persian_ci'; break;//مرتب بر اساس شهر
    case 6: $orderby=' order by DesignerCotitle COLLATE utf8_persian_ci'; break;//مرتب بر اساس شرکت مشاور طراح
    case 7: $orderby=' order by DesignerCotitlenazer COLLATE utf8_persian_ci'; break;//مرتب بر اساس مشاور ناظر
    case 8: $orderby=' order by proposestate,ADate desc,reqwin.ClerkID,applicantmaster.ApplicantMasterID'; break;//مرتب بر اساس وضعیت پیشنهاد قیمت
	case 9: $orderby=' order by ADate desc,reqwin.ClerkID,applicantmaster.ApplicantMasterID'; break;//مرتب بر اساس تاریخ تغییر وضعیت
	case 10: $orderby=' order by operatorcowin.corank,reqwin.ClerkID,applicantmaster.ApplicantMasterID'; break;//مرتب بر اساس رتبه
    default: $orderby=' order by proposestate,ADate desc,reqwin.ClerkID,applicantmaster.ApplicantMasterID'; break; //مرتب بر اساس وضعیت و تاریخ و...
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
    $str.=" and reqwin.state =1 ";//وضعیت انتخاب شده
    
if ($login_RolesID=='17')//ناظر مقیم 
    $str.=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) ";//فیلتر طرح های شهر ناظر مقیم مربوطه
    else if (($login_RolesID=='14') && ($showa==0))//ناظر عالی 
        $str.=" and substring(applicantmaster.cityid,1,4) 
        in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid') ";//فیلتر مشاهده طرح های شهرهای ناظر عالی مربوطه
        else if ($login_RolesID=='26')//تجمیع
            {$str.=" and ifnull(applicantmaster.criditType,0)=1 ";$showa=1;}//فیلتر مشاهده طرح های تجمیعی
		
$selectedCityId=$login_CityId;
if ($_POST['ostan']>0)
        $selectedCityId=$_POST['ostan'];
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
$sql = "
SELECT applicantmaster.*
,operatorapprequest.errors,operatorapprequest.ecept, 
designerco.title DesignerCotitle, 
shahr.cityname shahrcityname,shahr.id shahrid 
,applicantmasterop.TMDate laststatedate
,creditsource.title creditsourcetitle,designsystemgroups.title DesignSystemGroupstitle,operatorcowin.Title operatorcowinTitle
,clerkwin.CPI,clerkwin.DVFS,clerkwin.ClerkID ClerkIDwin
,case ifnull(applicantmaster.proposestate,0) when 0 then 'دریافت پیشنهاد' when 1 then 'ارجاع به مدیر آبیاری' when 2 then 'ارجاع به ناظرین'  
when 3 then case applicantmasterop.applicantstatesID when 30 then 'تایید پیش فاکتورها' when 35 then 'تحویل موقت' else   'تایید پیشنهاد' end end proposestatetitle 
,case applicantmasterop.applicantstatesID when 35 then 100 when 30 then 99 else applicantmaster.proposestate end proposestate
FROM applicantmaster 
left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
left outer join designerco on designerco.DesignerCoID=applicantmaster.DesignerCoID
left outer join creditsource on creditsource.creditsourceid=applicantmaster.creditsourceid
left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.DesignSystemGroupsid=applicantmaster.DesignSystemGroupsid
left outer join (select ApplicantMasterID,operatorcoID,ClerkID from operatorapprequest where state=1) reqwin on 
reqwin.ApplicantMasterID=applicantmaster.ApplicantMasterID
left outer join operatorco operatorcowin on operatorcowin.operatorcoID=reqwin.operatorcoID
left outer join clerk clerkwin on clerkwin.ClerkID=reqwin.ClerkID
 left outer join operatorapprequest on operatorapprequest.ApplicantMasterID=applicantmaster.ApplicantMasterID and state=1 
 left outer join applicantmaster applicantmasterop on applicantmaster.BankCode=applicantmasterop.BankCode 
 and substring(applicantmaster.cityid,1,4)=substring(applicantmasterop.cityid,1,4) and applicantmasterop.operatorcoID=operatorapprequest.operatorcoID 


where ifnull(operatorapprequest.operatorcoid,0)>0 and (length(operatorapprequest.errors)>0)  and substring(applicantmaster.cityid,1,2)=substring('$selectedCityId',1,2) 
and applicantmaster.ApplicantMasterID in (select ApplicantMasterID from operatorapprequest) and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0   $str
$orderby
 ";
try 
    {		
        $result = mysql_query($sql);
    }
    catch(Exception $e) 
    {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    } 
                     


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
    
while($row = mysql_fetch_assoc($result))
{
    $ID1[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);//نام متقاضی
    $ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);//عنوان پروژه
    $ID4[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupstitle']);//سیستم آبیاری
    $ID5[trim($row['shahrcityname'])]=trim($row['shahrid']);//شهر
    $ID6[trim($row['DesignerCotitle'])]=trim($row['DesignerCoID']);//شماور طراح
    $ID9[trim($row['creditsourcetitle'])]=trim($row['creditsourcetitle']);//منبع تامین اعتبار
    $ID10[trim($row['BankCode'])]=trim($row['BankCode']);//کد رهگیری
    $ID11[trim($row['proposestatetitle'])]=trim($row['proposestatetitle']);//عنوان وضعیت
    $ID12[trim(gregorian_to_jalali($row['ADate']))]=trim($row['ADate']);//تاریخ شروع پیشنهاد قیمت
    $ID13[trim($row['operatorcowinTitle'])]=trim($row['operatorcowinTitle']);//پیمانکار انتخابی    
    $encrypted_string=$row['CPI'];//نام کاربری
    $encryption_key="!@#$8^&*";
    $decrypted_string="";//دیکود نام کاربر
    for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
        $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
    $encrypted_string=$row['DVFS'];//نام خانوادگی کاربر
    $encryption_key="!@#$8^&*";
    $decrypted_string.=" ";//دیکود نام کاربر
    for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
        $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
    $ID14[$decrypted_string]=trim($row['ClerkIDwin']);//شناسه کاربر انتخابی

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
  	<title>پیشنهاد قیمت</title>
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
            
            <form action="allapplicantrequestwitherrors.php" method="post">
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
                        
                     
                      <?php print select_option('IDorder','ترتیب',',',$IDorder,0,'','','3','rtl',0,'',$IDorderval,"",'100');
                      
                     
                         print "<td colspan='1' class='label'>همه</td>
                     <td class='data'><input name='showa' type='checkbox' id='showa'";
                         if ($showa>0) echo 'checked';
                         print " /></td>
                         
                         <td colspan=\"2\"><input   name=\"submit\" type=\"submit\" class=\"button\" id=\"submit\" size=\"16\" value=\"جستجو\" /></td>
                         
                         <td class=\"f7_font$b'\"><a  target='_blank' href='../temp/proposerankpercent.html'>
                         <img style = 'width: 25px;' src='../img/chart.png' title=' نمودار درصد رتبه پيشنهاد دهندگان انتخابي '></a></td>";
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
                  
                            <td colspan="15"
                            <span class="f14_fontb" >لیست پیشنهاد های دارای مجوز</span>  </td>
                            
				   </tr>
                        <tr>

                            <th  
                           	<span class="f14_fontb" > رديف  </span> </th>
						        <th 	<span class="f14_fontb"> نام  </span> </th>
							<th 
                           	<span class="f14_fontb"> نام خانوادگی </span> </th>
							<th  
                            <span class="f14_fontb"> مساحت </span><br>
							 (ha)  </th>
						    <th 
                            <span class="f14_fontb">دشت/ شهرستان</span> </th>
							<th  
                            <span class="f14_fontb"> مبلغ کل </span>
						    <th  <span class="f14_fontb">کمک بلاعوض</span> </th>
							<th  <span class="f14_fontb">کد رهگیری</span> </th>
							<th  <span class="f14_fontb">وضعیت</span> </th>
							<th  <span class="f14_fontb">تاریخ</span> </th>
						    <th  <span class="f14_fontb">برنده پیشنهاد</span> </th>
						    <th  <span class="f14_fontb">کاربر</span> </th>
						    <th  <span class="f14_fontb">عدم صلاحیت</span> </th>
						    <th  colspan="2" <span class="f14_fontb">تاییدیه</span> </th>
							<th ></th>
                        </tr>
                        
                       </thead> 
                          <tr>    
							<td class="f14_font"></td>
						    <?php print select_option('ApplicantFname','',',',$ID1,0,'','','1','rtl',0,'',$ApplicantFnameval,'',''); ?>
							 <?php print select_option('ApplicantName','',',',$ID2,0,'','','1','rtl',0,'',$ApplicantNameval,'',''); ?>
							<?php print select_option('IDArea','',',',$IDArea,0,'','','1','rtl',0,'',$IDAreaval,'',''); ?>
					       <?php print select_option('sos','',',',$ID5,0,'','','1','rtl',0,'',$sosval,"",''); ?> 
					       <?php print select_option('IDprice','',',',$IDprice,0,'','','1','rtl',0,'',$IDpriceval,'',''); ?>  
					       <?php print select_option('IDbela','',',',$IDbela,0,'','','1','rtl',0,'',$IDbelaval,'',''); ?> 
                            <?php print select_option('BankCode','',',',$ID10,0,'','','1','rtl',0,'',$BankCodeval,'',''); ?>
					      <?php print select_option('proposestatetitle','',',',$ID11,0,'','','1','rtl',0,'',$proposestatetitleval,'','');?>
					      <?php print select_option('dateID','',',',$ID12,0,'','','1','rtl',0,'',$dateIDval,'','');?>
					       <?php print select_option('operatorcowinTitle','',',',$ID13,0,'','','1','rtl',0,'',$operatorcowinTitleval,'',''); ?> 
					       <?php print select_option('name','',',',$ID14,0,'','','1','rtl',0,'',$nameval,'',''); ?> 
                            
							<td ></td>
					 
					 </tr> 
                        
                   <?php
                   $sumDA=0;
                   $sumM=0;
                   $rown=0;
                   $totalbelaavaz=0;
                    while($row = mysql_fetch_assoc($result)){

                   

                       ///////////////////فایل مجوز/////////////////////////
                        $fstrm="";$fstrmerr="";
                        $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/proposm/';
                        $handler = opendir($directory);
        
                        while ($file = readdir($handler)) 
                        {
                            // if file isn't this directory or its parent, add it to the results
                            if ($file != "." && $file != "..") 
                            {
                                $linearray = explode('_',$file);
                                $ID=$linearray[0];
                                if ($ID==$row['ApplicantMasterID'])
                                {
									$fstrm="<td><a target='$target' href='../../upfolder/proposm/$file' ><img style = 'width: 25px;' src='../img/accept_page.png'  title='اسکن' ></a></td>";
									$fstrmerr="<td><a target='$target' href='../../upfolder/proposm/$file' ><img style = 'width: 25px;' src='../img/delete.png'  title='اسکن' ></a></td>";
                                }
                            }
                        }
                        //////////////////////////////////////////////////////
 				   
                        $Code = $row['Code'];
                        $ID = $row['ApplicantMasterID'].'_5_'.$row['DesignerCoID'].'_'.$row['operatorcoid'].'_'.$selectedCityId;
                        $ApplicantName = $row['ApplicantName'];
                        $ApplicantFName = $row['ApplicantFName'];
                        $year = $row['year'];
                        $CostPriceListMasterID=$row['CostPriceListMasterID'];
                        
                        $sumDA+=$row['DesignArea'];
                        
                        
                        //print $showprice;
                        
                        $sumL=$row['LastTotal'];
                        $totalbelaavaz+=$row['belaavaz'];
                        
                        $sumM+=$sumL ;
                        $rown++;
                        if ($rown%2==1) 
                        $b='b'; else $b='';
                        
?>                      
                        <tr>    

                            <td
                            <span class="f10_font<?php echo $b; ?>"  >  <?php echo $rown; ?> </span>  </td>
							
                             <td 
							<span class="f12_font<?php echo $b; ?>">  <?php echo $ApplicantFName; ?> </span> </td>
                           
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo $ApplicantName; ?> </span> </td>
                           
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo $row['DesignArea']; ?> </span> </td>
                            
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo $row['shahrcityname']; ?> </span> </td>
                            
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo floor($sumL/100000)/10; ?> </span> </td>
                           
                                              
                           
                            <td <span class="f12_font<?php echo $b; ?>">  <?php echo $row['belaavaz']; ?> </span> </td>
                                                     
						   <td
							<span class="f12_font<?php echo $b; ?>"> </span><?php echo str_replace(' ', '&nbsp;', $row['BankCode']); ?> </td>
                            
						<td
							<span class="f12_font<?php echo $b; ?>"> </span><?php echo str_replace(' ', '&nbsp;', $row['proposestatetitle']); ?> </td>
                           	
                            
						<td
							<span class="f12_font<?php echo $b; ?>"> </span><?php if ($row['ADate']!="") echo gregorian_to_jalali( $row['ADate']); ?> </td>
                            
                            
                            <td <span class="f12_font<?php echo $b; ?>">  <?php echo $row['operatorcowinTitle']; ?> </span> </td>  
							<td <span class="f12_font<?php echo $b; ?>">  <?php 
                            
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
                            
                             ?> </span> </td>  
							<td <span class="f12_font<?php echo $b; ?>"><font color='red'>  <?php
								$linearray = explode('_',$row['errors']);
						        $err=$linearray[0];
                                $errname=$linearray[1];
                        		$errdate=$linearray[2];
								if($errdate) $errdate=gregorian_to_jalali($errdate);
                        		echo  substr($err,4); ?> </font></span> </td>  
								
                               <?php 
                            if ($row["ecept"]>0)
							{
								if ($fstrm)
									echo $fstrm;
									else
                                    echo "<td class='f12_font$b' ><img style = 'width:20px;' src='../img/accept.png' title=''></td>";
							}
							else 
							{
								if ($fstrmerr)
									echo $fstrmerr;
									else
								echo "<td class='f12_font$b' ><img style = 'width:20px;' src='../img/reject.png' title=''></td>"; 
							}
                            ?>
                        
                        
						<td <span class="f12_font<?php echo $b; ?>">  <?php 
						       
						echo  $errname.''.$errdate; ?> </span> </td>  
							
						    
                            <?php
                            
                                
                                 
                                if (
								   ($login_RolesID=='18' || $login_RolesID=='32' )
								|| ($login_RolesID=='13' && $row['proposestate']>=1 && $row['ClerkIDwin']<>24 && $row['ClerkIDwin']<>83)
								|| ( ($login_RolesID=='14' || $login_RolesID=='17') && $row['proposestate']>=2 && $row['ClerkIDwin']<>65 && $row['ClerkIDwin']<>24
                                 && $row['ClerkIDwin']<>83) 
								||  $login_designerCO==1
								    )
                                {
                                    
                                
                                  echo "<td class='no-print'><a  target='_blank' href='allapplicantrequestdetail.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$ID.rand(10000,99999)."'><img style = 'width:25px;' src='../img/search.png' title=' ريز '></a> </td>";
                                }
                                       
                                
                                
                                
                            
                             ?>
                            
                           
							
							
							 
                        </tr><?php

                    }
                    
                    
                    

?>

                        <tr>
                            
                            <td colspan="12" class="f14_fontb" ><?php echo ' مجموع مساحت (هكتار)';   ?></td>
                            <td colspan="3"
                            class="f14_fontb" 
                            ><?php echo $sumDA;   ?></td>
                        </tr>
                        <tr>
                            
                            <td colspan="12" class="f14_fontb" ><?php echo ' مجموع مبلغ کل (ميليون ريال)';   ?></td>
                            <td colspan="3" 
                            class="f14_fontb" 
                            ><?php echo round(($sumM/1000000),1);   ?></td>
                        </tr>
                         <tr>
                            
                            <td colspan="12" class="f14_fontb" ><?php echo ' مجموع  بلاعوض معرفی شده (ميليون ريال)';   ?></td>
                            <td colspan="3" 
                            class="f14_fontb" 
                            ><?php echo $totalbelaavaz;   ?></td>
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

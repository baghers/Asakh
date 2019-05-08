<?php

/*

//appinvestigation/allapplicantstatesop.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/allapplicantstatesoplist.php
/appinvestigation/allapplicantstates_return.php
 
-
*/

 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php
require ('../includes/functions.php');




if ($login_Permission_granted==0) header("Location: ../login.php");
$showa=0;//نمایش همه
$yearid='';//سال

if ($_POST)
{
    $yearid=$_POST['YearID'];//شناسه سال
    $DesignAreafrom=$_POST['DesignAreafrom'];//فیلتر مساحت از
    $DesignAreato=$_POST['DesignAreato'];//فیلتر مساحت تا
    $sos=$_POST['sos'];//شهر
    $sob=$_POST['sob'];//بخش
    $operatorcoid=$_POST['operatorcoid'];//شناسه پیمانکار
    $applicantstatesID=$_POST['applicantstatesID'];//شناسه وضعیت طرح
    $creditcsourceID=$_POST['creditcsourceID'];//منبع تامین اعتبار
    
    $BankCode=$_POST['BankCode'];//کد رهگیری
    $pproducerstitle=$_POST['pproducerstitle'];//وضعیت پیشنهاد
	$pstateorder=0;
	if ($_POST['pstate']=='درحال تولید') $pstateorder=1; 
	if ($_POST['pstate']=='تحویل') $pstateorder=2; 

	
    $dateID=$_POST['dateID'];//تاریخ
    
	$ApplicantFname=$_POST['ApplicantFname'];//نام متقاضی
    $Applicantname=$_POST['ApplicantName'];//عنوان طرح
    $DesignSystemGroupstitle=$_POST['DesignSystemGroupstitle'];//سیستم آبیاری
    
    if ($_POST['showa']=='on')//نمایش طرح های سایر شهرستان ها برای ناظرین عالی
    $showa=1;
    if ($_POST['showm']=='on')//نمایش طرح های وضعیت اولیه
    $showm=1;
    if ($_POST['showp']=='on')//نمایش طرح های شخصی
    $showp=1;
    if ($_POST['showprice']=='on')//نمایش مبالغ
    $showprice=1;
    
    
    
        if (trim($BankCode)==-2)//کد رهگیری
        $str.=" and ifnull(applicantmasterop.BankCode,0)=0";
    else if (trim($BankCode)==-1)
        $str.=" and ifnull(applicantmasterop.BankCode,0)>0";
    else if (strlen(trim($BankCode))>0)
        $str.=" and applicantmasterop.BankCode='$BankCode'";
    else if (strlen(trim($pproducerstitle))>0)
        $str.=" and pproducerstitle='$pproducerstitle'";
    
	    
    if (strlen(trim($_POST['DesignAreafrom']))>0)//فیلتر مساحت از
        $str.=" and applicantmasterop.DesignArea>='$_POST[DesignAreafrom]'";
    if (strlen(trim($_POST['DesignAreato']))>0)//فیلتر مساحت تا
        $str.=" and applicantmasterop.DesignArea<='$_POST[DesignAreato]'";
    if (strlen(trim($_POST['sos']))>0)//فیلتر استان
        $str.=" and shahr.id='$_POST[sos]'";
    if (strlen(trim($_POST['operatorcoid']))>0)//فیلتر پیمانکار
        $str.=" and applicantmasterop.operatorcoid='$_POST[operatorcoid]'";
    if (strlen(trim($_POST['applicantstatesID']))>0)//فیلتر شناسه وضعیت طرح
        $str.=" and applicantstates.applicantstatesID='$_POST[applicantstatesID]'";   
        
     if (strlen(trim($_POST['dateID']))>0)//تاریخ
        $str.=" and applicantmasterop.TMDate='$_POST[dateID]'";  
           
	if (strlen(trim($_POST['DesignSystemGroupstitle']))>0)//سیستم
        $str.=" and designsystemgroups.designsystemgroupsid='$_POST[DesignSystemGroupstitle]'";		
	 if (strlen(trim($_POST['DesignerCoIDnazertitle']))>0)//ناظر
       	 $str.=" and designerco.Title ='$_POST[DesignerCoIDnazertitle]'";		
   
   if (trim($_POST['creditcsourceID'])==-2)//اعتبار
        $str.=" and ifnull(applicantmasterop.creditsourceID,0)=0";
    else if (trim($_POST['creditcsourceID'])==-1)
        $str.=" and ifnull(applicantmasterop.creditsourceID,0)>0";
    else if (strlen(trim($_POST['creditcsourceID']))>0)
        $str.=" and applicantmasterall.creditsourceID='$_POST[creditcsourceID]'"; 
        
	if (strlen(trim($_POST['ApplicantFname']))>0)//نام متقاضی
        $str.=" and applicantmasterop.ApplicantFname like'%$_POST[ApplicantFname]%'";
	if (strlen(trim($_POST['ApplicantName']))>0)//عنوان طرح
        $str.=" and applicantmasterop.ApplicantName like '%$_POST[ApplicantName]%'";
	
    if (strlen(trim($_POST['IDArea']))>0)//فیلتر مساحت در بازه مورد نظر
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
	
    if (trim($_POST['IDprice'])==-2)//فیلتر مبلغ کل هزینه های طرح بر اساس بازه انتخابی
        $str.=" and ifnull(applicantmasterop.LastTotal,0)=0";
    else if (trim($_POST['IDprice'])==-1)
        $str.=" and ifnull(applicantmasterop.LastTotal,0)>0";
    else if (strlen(trim($_POST['IDprice']))>0)	
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
        
        if (trim($_POST['IDbela'])==-2)//فیلتر مبلغ کل بلاعوض  طرح بر اساس بازه انتخابی	
        $str.=" and ifnull(applicantmasterop.belaavaz,0)=0";
    else if (trim($_POST['IDbela'])==-1)
        $str.=" and ifnull(applicantmasterop.belaavaz,0)>0";
    else if (strlen(trim($_POST['IDbela']))>0)	
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
         
	  if($yearid>0)  $str.=" and applicantmasterall.yearid='$yearid' ";
         
}
    
if($login_RolesID==26) {$showc=1;$showm=1;$showt=1;}
if ($showc==1) $str.=" and ifnull(applicantmasterop.criditType,0)=1 ";
    
    //if (($_POST['showm']=='on')) $str.=" and appchangestate.applicantstatesID<>23 ";
    $sql = "SELECT value  FROM year where YearID='$yearid' ";
    $result = mysql_query($sql);
    $row = mysql_fetch_assoc($result);
    $yearvalue=$row['value'];
    
    
  switch ($_POST['IDorder']) 
  {
    case 1: $orderby=' order by applicantmasterop.ApplicantName COLLATE utf8_persian_ci'; break; 
    case 2: $orderby=' order by ApplicantFName COLLATE utf8_persian_ci'; break;
    case 3: $orderby=' order by DesignArea'; break;
    case 4: $orderby=' order by DesignSystemGroupstitle'; break;    
    case 5: $orderby=' order by shahrcityname COLLATE utf8_persian_ci'; break;
    case 6: $orderby=' order by operatorcotitle COLLATE utf8_persian_ci'; break;
    case 7: $orderby=' order by applicantstatestitle COLLATE utf8_persian_ci'; break;
    case 8: $orderby=' order by applicantmasterop.TMDate'; break;
	case 9: $orderby=' order by cast(applicantmasterall.sandoghcode as  decimal(10,0))'; break;
	
default: 
    if ($login_RolesID=='7' || $login_RolesID=='16')
        $orderby=' order by cast(applicantmasterall.sandoghcode as  decimal(10,0))';
    else     
        $orderby='order by applicantstates.applicantstatesID,applicantmasterop.TMDate'; break;  
  
  }
  
  $strjoin="";
  if ($login_RolesID=='16')//صندوق
  {
    $str.=" and ifnull(app22.ApplicantMasterID,0)>0 and applicantmasterop.applicantstatesID in (30,35,34,38)"; 
    $strjoin="left outer join applicantmaster app22 on app22.applicantstatesID='22' and app22.ApplicantMasterID=applicantmasterall.ApplicantMasterID";   
  } 
    else   if ($login_RolesID=='7')//بانک
    {
        $str.=" and ifnull(app37.ApplicantMasterID,0)>0 and applicantmasterop.applicantstatesID in (30,35,34,38)";              
        $strjoin="left outer join applicantmaster app37 on app37.applicantstatesID='37' and app37.ApplicantMasterID=applicantmasterall.ApplicantMasterID";   
        
    }
  if ($login_RolesID=='10')//مشاور طراح
            $str.=" and case ifnull(applicantmasterdetail.nazerID,0) when 0 then tax_tbcity7digitnazer.DesignerCoIDnazer else applicantmasterdetail.nazerID end='$login_DesignerCoID'";   
        
if ($login_RolesID=='17') //ناظر مقیم
    $str.=" and substring(applicantmasterop.cityid,1,4)=substring('$login_CityId',1,4) ";
else if (($login_RolesID=='14') && ($showa==0)) //ناظر عالی
        $str.=" and substring(applicantmasterop.cityid,1,4) in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid') ";
else if (($login_RolesID=='29') && ($showa==0)) //بازرس
        $str.=" and ifnull(invoicetiming.ApproveA,'')='' ";
else if ($showa==0 && $login_RolesID!='30') 
        $str.=" and applicantmasterop.applicantstatesID not in (34,35) ";
else if ($showa==0 && $login_RolesID=='30') 
        $str.=" and applicantmasterop.applicantstatesID not in (34,35,30) "; 
        //print $str;   
$selectedCityId=$login_CityId;
if ($_POST['ostan']>0)
        $selectedCityId=$_POST['ostan'];

if ($login_userid==564)
        $str.=" and ifnull(applicantmasterdetail.prjtypeid,0)=1 ";
else
        $str.=" and ifnull(applicantmasterdetail.prjtypeid,0)<>1 "; 
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
$sql = "SELECT distinct invoicetiming.ApproveA,invoicetiming.BOLNO,invoicetiming.ApproveP,creditsource.creditsourceid
,applicantmasterop.criditType criditType,
designsystemgroups.DesignSystemGroupsid,
applicantmasterop.ApplicantFName,applicantmasterop.Freestate,
case ifnull(applicantmasterop.belaavaz,0) when 0 then applicantmasterall.belaavaz else applicantmasterop.belaavaz end  belaavaz
,applicantmasterop.LastTotal,applicantmasterop.SaveTime SaveTime,applicantmasterop.LastChangeDate,applicantmasterall.sandoghcode,
applicantmasterop.operatorcoid,applicantmasterop.ApplicantMasterID,applicantmasterop.BankCode,applicantmasterop.DesignArea
,applicantmasterop.ApplicantName
,applicantmasterop.ApplicantFName,applicantmasterop.ApplicantName
,operatorco.title operatorcotitle 
,applicantstates.title applicantstatestitle,applicantstates.applicantstatesID, 
shahr.cityname shahrcityname,shahr.id shahrid 
  
,creditsource.title creditsourcetitle,designsystemgroups.title DesignSystemGroupstitle,applicantmasterdetail.nazerID DesignerCoIDnazer,applicantmasterop.CityId
,applicantmasterop.TMDate laststatedate,ppipe.ApplicantMasterID ppipeApplicantMasterID,pproducerstitle
,applicantmasterop.proposestatep

,applicanttiming10.errnum,applicanttiming10.emtiaz emtiaz_moshaver
    ,applicanttiming10.m_emtiaz emtiaz_nazerali,applicanttiming2.emtiaz emtiaz_anjoman,applicanttiming2.m_emtiaz emtiaz_nazermoghim
    ,substring(SUBSTRING_INDEX(applicantmasterop.numfield2, '_', -1),1,10) tempdeldate
    ,case invoicemaster.ProducersID>0 when 1 then 1 else 0 end sendtopipepropose
	
	,case invoicetiming.ApproveA<0 when 0 then 'تحویل' else case pproducerstitle>0 when 0 then 'درحال تولید' else '' end end pstate 
	
	,appchangestate.Description,applicantmasterdetail.ApplicantMasterIDsurat
	,designerco.Title DesignerCoIDnazertitle
    
FROM applicantmaster applicantmasterop
left outer join appchangestate on appchangestate.applicantstatesID=applicantmasterop.applicantstatesID and appchangestate.ApplicantMasterID = 
applicantmasterop.ApplicantMasterID and appchangestate.savedate=applicantmasterop.tmdate

inner join applicantstates on applicantstates.applicantstatesID=applicantmasterop.applicantstatesID

left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmasterop.cityid,1,4) 
and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
left outer join tax_tbcity7digit tax_tbcity7digitnazer on substring(tax_tbcity7digitnazer.id,1,4)=substring(applicantmasterop.cityid,1,4) 
and substring(tax_tbcity7digitnazer.id,5,3)='000'
inner join operatorco on operatorco.operatorcoid=applicantmasterop.operatorcoid
left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.DesignSystemGroupsid=applicantmasterop.DesignSystemGroupsid

inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDmaster=applicantmasterop.ApplicantMasterID
inner join applicantmaster applicantmasterall on applicantmasterdetail.ApplicantMasterID=applicantmasterall.ApplicantMasterID 

left outer join designerco on designerco.DesignerCoid=case ifnull(applicantmasterdetail.nazerID,0) when 0 then 
tax_tbcity7digitnazer.DesignerCoIDnazer else applicantmasterdetail.nazerID end

left outer join creditsource on creditsource.creditsourceid=applicantmasterall.creditsourceid
left outer join (select distinct producerapprequest.ApplicantMasterID,producers.title pproducerstitle from invoicemaster 
inner join producerapprequest on producerapprequest.ApplicantMasterID=invoicemaster.ApplicantMasterID and producerapprequest.state=1
inner join producers on producers.ProducersID=producerapprequest.ProducersID
where ifnull(proposable,0)=1) ppipe 
on ppipe.ApplicantMasterID=applicantmasterop.ApplicantMasterID


    left outer join (select max(InvoiceMasterID) InvoiceMasterID,max(ProducersID)ProducersID,ApplicantMasterID from invoicemaster
    where invoicemaster.proposable=1 group by ApplicantMasterID) invoicemaster  on invoicemaster.ApplicantMasterID=applicantmasterop.ApplicantMasterID 
    left outer join invoicetiming on invoicetiming.InvoiceMasterID=invoicemaster.InvoiceMasterID
    
left outer join (select applicanttiming.ApplicantMasterID ,applicanttiming.errnum ,
                applicanttiming.emtiaz ,applicanttiming.m_emtiaz from applicanttiming 
                where applicanttiming.RoleID='10') applicanttiming10 on applicanttiming10.ApplicantMasterID=applicantmasterop.ApplicantMasterID

left outer join (select applicanttiming.ApplicantMasterID ,applicanttiming.errnum ,
                applicanttiming.emtiaz ,applicanttiming.m_emtiaz from applicanttiming 
                where applicanttiming.RoleID='2') applicanttiming2 on applicanttiming2.ApplicantMasterID=applicantmasterop.ApplicantMasterID

				
                                 
$strjoin
where substring(applicantmasterop.cityid,1,2)=substring('$selectedCityId',1,2) and ifnull(applicantmasterop.ApplicantMasterIDmaster,0)=0   $str
$orderby";


//$orderby

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

//print $sql;
//exit;

  
  
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
   $IDnazer[' ']=' ';
$dasrow=0;

while($row = mysql_fetch_assoc($result))

{
    /*if ($row['applicantstatestitle']=='تایید نهایی پیش فاکتور')
    {
        $query="select ApplicantMasterID from applicantmaster where ApplicantMasterIDmaster='$row[ApplicantMasterID]' ";  
        $result1 = mysql_query($query); 
       if (!mysql_fetch_assoc($result1))
       {
        insertsurat($row['ApplicantMasterID'],$Description,$login_userid,$_server, $_server_user, $_server_pass,$_server_db);
        print '<br>sa'.$row['ApplicantMasterID'];
       } 
    }
    */
    $dasrow=1;
    $ID1[trim($row['creditsourcetitle'])]=trim($row['creditsourceid']);
    $ID2[trim($row['shahrcityname'])]=trim($row['shahrid']);
    $ID3[trim($row['ApplicantName'])]=trim($row['ApplicantName']);
    $ID4[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);
    $ID5[trim($row['applicantstatestitle'])]=trim($row['applicantstatesID']);
    $ID6[trim(gregorian_to_jalali($row['laststatedate']))]=trim($row['laststatedate']);
    $ID7[trim($row['applicantstategroupsTitle'])]=trim($row['applicantstategroupsID']);
    $ID8[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);
    $ID9[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupsid']);
    $ID10[trim($row['BankCode'])]=trim($row['BankCode']);
    $ID11[trim($row['pproducerstitle'])]=trim($row['pproducerstitle']);
	$IDp[trim($row['pstate'])]=trim($row['pstate']);
	$IDnazer[trim($row['DesignerCoIDnazertitle'])]=trim($row['DesignerCoIDnazertitle']);
	
    
    
    
 }

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
$IDp=mykeyvalsort($IDp);
$IDnazer=mykeyvalsort($IDnazer);

if ($dasrow)
mysql_data_seek( $result, 0 );



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
    

	
	
/*$query = "select distinct DesignerCoIDnazer  as _value, substring(tax_tbcity7digit.id,1,4) as _key from tax_tbcity7digit 
            where DesignerCoIDnazer>0";
$DesignerCoIDnazerID = get_key_value_from_query_into_array($query);
*/
    


$directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/contract/';
$handler = opendir($directory);
$arraycontract=array();
$i=0;
while ($file = readdir($handler)) 
{
    // if file isn't this directory or its parent, add it to the results
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
    //print_r($arraycontract);
?>



<!DOCTYPE html>
<html>
<head>
  	<title>ليست طرحهاي اجرايي</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />


    <!-- /scripts -->
    
  
</head>
<body onload='$("#loading-div-background").hide();'>


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
            
            <form action="allapplicantstatesop.php" method="post">
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
                  
                            <td colspan="17"
                            <span class="f14_fontb" >  آخرین وضعیت پیش فاکتورها و پروژه های آبیاری تحت فشار(مبالغ به میلیون ریال)</span>  </td>
	  				        <th colspan="6"  class="f14_fontc">&nbsp;&nbsp; </th>
    	
                            
				   </tr>
                   
                        <tr>

                            <th <span class="f9_fontb" > رديف  </span> </th>
							<th <span class="f9_fontb" >کد</span> </th>
							<th <span class="f13_fontb"> نام  </span> </th>
							<th <span class="f13_fontb"> نام خانوادگی </span> </th>
							<th <span class="f9_fontb"> مساحت </span> (ha)  </th>
                            <th  class="f14_fontb"> نوع سیستم  </th>
						    <th <span class="f13_fontb">دشت/ شهرستان</span> </th>
							<th <span class="f13_fontb">شركت مجری</span> </th>
							<th <span class="f13_fontb">تولیدکننده لوله</span> </th>
							<th <span class="f13_fontb">وضعیت لوله</span> </th>
							<th <span class="f13_fontb">شرکت ناظر</span> </th>
							<th <span class="f14_fontb"> مبلغ کل </span>
						    <th <span class="f13_fontb">وضعیت</span> </th>
						    <th <span class="f14_fontb">تاریخ</span> </th>
						    <th <span class="f13_fontb">کمک بلاعوض</span> </th>
						    <th <span class="f14_fontb">نوع اعتبار</span> </th>
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
					       <?php print select_option('pproducerstitle','',',',$ID11,0,'','','1','rtl',0,'',$pproducerstitle,'','100%') ?> 
					       <?php print select_option('pstate','',',',$IDp,0,'','','1','rtl',0,'',$pstate,'','100%') ?> 
						        <?php print select_option('DesignerCoIDnazertitle','',',',$IDnazer,0,'','','1','rtl',0,'',$DesignerCoIDnazertitle,'','100%') ?> 
					  
					       <?php print select_option('IDprice','',',',$IDprice,0,'','','1','rtl',0,'',$IDpriceval,'','100%'); ?>  
					      <?php print select_option('applicantstatesID','',',',$ID5,0,'','','1','rtl',0,'',$applicantstatesID,'','100%');?>
				      <?php print select_option('dateID','',',',$ID6,0,'','','1','rtl',0,'',$dateID,'','100%');?>
							  
					       <?php print select_option('IDbela','',',',$IDbela,0,'','','1','rtl',0,'',$IDbelaval,'','100%'); ?> 
					      <?php print select_option('creditcsourceID','',',',$ID1,0,'','','1','rtl',0,'',$creditcsourceID,'','100%');?>
                            <?php print select_option('BankCode','',',',$ID10,0,'','','1','rtl',0,'',$BankCode,'','100%'); ?>
					  <td colspan="<?php 
                             if ($login_RolesID==5 || $login_designerCO==1)
                             print "4"; else print "3";  ?>"><input    name="submit" type="submit" class="button" id="submit" size="16" value="جستجو" /></td>
                       
					 
					 </tr> 
                     
                   <?php
                   $sumDA=0;
                   $sumM=0;
                   $rown=0;
                   $totalbelaavaz=0;
                    while($row = mysql_fetch_assoc($result)){
                        if ($login_RolesID==29 && !($row['ppipeApplicantMasterID']>0))
                            continue;
						$pipestate=$row['pstate'];
						if ($pstateorder==1 && $pipestate!='درحال تولید') continue;
						if ($pstateorder==2 && $pipestate!='تحویل') continue;
						
						
                       /* if (!($row["DesignerCoIDnazer"]>0))
                            {
                                foreach ($DesignerCoIDnazerID as $key => $value)
                                {
                                    if (substr($row["CityId"],0,4)==$key)
                                    {
                                        //print "update applicantmaster set SaveTime='".date('Y-m-d H:i:s')."',DesignerCoIDnazer='$value' where ApplicantMasterID='$row[ApplicantMasterID]';";
                                        mysql_query("update applicantmaster set SaveTime='".date('Y-m-d H:i:s')."',DesignerCoIDnazer='$value' where ApplicantMasterID='$row[ApplicantMasterID]'");
                                    }
                                        
                                }                                
                            } */
                            
                        

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
//##########################           
           $laststatedate=$row['laststatedate'];
		   $applicantstatestitle=$row['applicantstatestitle'];
          //           if (($applicantstatestitle=='آزادسازی ظرفیت' || $applicantstatestitle=='تایید نهایی پیش فاکتور') && $row['Timmingend']>0)      {
			//		        $applicantstatestitle='تحویل موقت';if ($laststatedate<$row['Timmingend']) $laststatedate=$row['Timmingend'];
				//	       }				
//##########################           
						
                        
                        
                        if ($row['criditType']==1) $criditType='+';else $criditType='';
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
                        
?>                      
                        <tr>    

                            <td <span class="f12_font<?php echo $b; ?>"  >  <?php 
                            echo $criditType.'&nbsp;'.$rown; 
							if ($login_designerCO==1)
                                echo "<br>(".$row['ApplicantMasterID'].")";
                            
            	 		$searchpng='../img/search.png';
						
						if ($row['ppipeApplicantMasterID']>0) $searchpng='../img/searchPy.png';
					    if ($row['proposestatep']==1 || $row['proposestatep']==2) $searchpng='../img/searchPb.png';
					    if ($row['proposestatep']==3) $searchpng='../img/searchPg.png';
                        
							if ($row['ApproveA']>0)
									$searchpng='../img/searchPg.png';
                                else if ($row['BOLNO']>0)
									$searchpng='../img/searchPy.png';
                                else if ($row['ApproveP']>0)
                                    $searchpng='../img/searchPb.png';
                                else 
                                    $searchpng='../img/search.png';
                           // if ($row['pstate']==0) $pipestate='';
						   
							
							?> </span>  </td>
							<td <span class="f10_font<?php echo $b; ?>"  >  <?php echo " ($row[sandoghcode])";?>  </span> </td>
						    <td <span class="f12_font<?php echo $b; ?>">  <?php echo $ApplicantFName; ?> </span> </td>
							
                         	     <td
							<span class="f12_font<?php echo $b; ?>"> 
								<a target='blank' href=<?php $permitrolsid = array("1", "18", "19"); $permitstatid = array("23", "32", "40", "42", "47");if (in_array($login_RolesID, $permitrolsid) && in_array($row['applicantstatesID'], $permitstatid))
                             						print "..\insert\invoicemaster_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999)
													.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
													.rand(10000,99999).$row['ApplicantMasterID'].rand(10000,99999); ?>>
													<font color='black'> <?php echo $ApplicantName; ?> </font></a> </span> </td>
                    
							
                            <td <span class="f12_font<?php echo $b; ?>">  <?php echo $row['DesignArea']; ?> </span> </td>
                            <td <span class="f10_font<?php echo $b; ?>">  <?php echo $row['DesignSystemGroupstitle']; ?> </span> </td>
                            <td <span class="f12_font<?php echo $b; ?>">  <?php echo $row['shahrcityname']; ?> </span> </td>
                            <td <span class="f10_font<?php echo $b; ?>">  <?php 
                            if ($row['tempdeldate']=='' && in_array($row['ApplicantMasterID'],$arraycontract))
                            echo '***';
                            echo $row['operatorcotitle']; ?> </span> </td>
                            <td <span class="f10_font<?php echo $b; ?>">  <?php echo $row['pproducerstitle']; ?> </span> </td>
                            <td <span class="f10_font<?php echo $b; ?>">  <?php echo $pipestate; ?> </span> </td>
						    <td <span class="f10_font<?php echo $b; ?>">  <?php echo $row['DesignerCoIDnazertitle']; ?> </span> </td>
                       	
                            <td <span class="f12_font<?php echo $b; ?>">  <?php echo floor($sumL/100000)/10; ?> </span> </td>
                            <td <span title="<?php echo $row['Description']; ?>" class="f9_font<?php echo $b; ?>">  <?php echo str_replace(' ', '&nbsp;', $applicantstatestitle); ?> </span> </td>
					         <td <span class="f10_font<?php echo $b; ?>">  <?php echo gregorian_to_jalali($laststatedate); ?>  </span> </td>
                   		
                            <td <span class="f12_font<?php echo $b; ?>">  <?php echo round($row['belaavaz'],1); ?> </span> </td>
                            <td <span class="f9_font<?php echo $b; ?>">  <?php echo $row['creditsourcetitle']; ?> </span> </td>
                            <td <span class="f9_font<?php echo $b; ?>"> </span> <?php echo str_replace(' ', '&nbsp;', $row['BankCode']); ?> </td>
                            
							 <?php 
                             $permitrolsid = array("16", "19","7","13","14");if (in_array($login_RolesID, $permitrolsid))
                             {
                                
                                 if (in_array($row['applicantstatesID'], array("30","35","34","38")))                               
                                print "<td class='no-print'><a target='".$target."' href='invoicemasterfree_list.php?uid=".rand(10000,99999).
                                rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                rand(10000,99999).$row['ApplicantMasterID'].'_1_'.$row['DesignerCoID'].'_'.$row['operatorcoid'].rand(10000,99999).
                                "'><img style = 'width: 25px;' src='../img/Actions-document-export-icon.png' title='آزادسازی'></a></td>";
                                else print "<td></td>";
                            
                             }
                             if ($login_RolesID!='16' && $login_RolesID!='7'&& $login_RolesID!='28') 
                             {
                                if ($row['ppipeApplicantMasterID']>0)
                                $imgtarget="../img/file-edit-icon2.png";
                                else
                                $imgtarget="../img/file-edit-icon.png";
							
							 
			            
                                
                            $permitrolsid = array("1", "13","5","11","13","14","17","18","19","20","31");if (in_array($login_RolesID, $permitrolsid))
                            print "<td class='no-print'><a target='".$target."' href='applicant_manageredit.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$row['ApplicantMasterID'].'_3_'.$row['DesignerCoID'].'_'.$row['operatorcoid'].rand(10000,99999).
                            "'><img style = 'width: 25px;' src='$imgtarget' title=' ويرايش '></a></td>"; ?>
							
							

							
                            <td class='no-print'><a  target='<?php echo $target;?>' href=<?php print "applicantstates_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = "width: 25px;" src="../img/refresh.png" title=' مشاهده ریز عملیات ' ></a></td>
                            <td class='no-print'><a  target='<?php echo $target;?>' href=<?php
                             
                            //if ($login_RolesID==29)
                           // {
							     
                                
                            //}
                            
                                                                      
                            print "../insert/summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = 'width: 25px;' src=<?php echo $searchpng;?> title=' ريز '></a></td>
							
							<?php 
                            $permitrolsid = array("1","10","13","14","17","18");if (in_array($login_RolesID, $permitrolsid))
                            {
    			 	 	         $errnum=$row['errnum'];
                                $emtiaz=round(($row['emtiaz_moshaver']+$row['emtiaz_nazerali']+$row['emtiaz_anjoman']+$row['emtiaz_nazermoghim'])/4);
        
                               if ($errnum>7)
    									$tablepng='../img/table.png';else $tablepng='../img/table2.png';
    								print "<td><a  target='".$target."' href='../insert/applicant_timing.php?uid=".rand(10000,99999).rand(10000,99999).
    								rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
    								rand(10000,99999).$row['ApplicantMasterID'].'_3_'.rand(10000,99999).
    								"'><img style = 'width: 20px;' src=$tablepng title=' ثبت جدول زمانبندي'></a></td>"; 
                                    
                                 print "<td/>";                                
                            }

                             
                               
                                        
                             
							if ($row['ApproveA']>0)
                                $imgtable='table.png';
                                else if ($row['BOLNO']>0)
                                $imgtable='table.png';
                                else if ($row['ApproveP']>0)
                                    $imgtable='table.png';
                                    else 
                                    $imgtable='table2.png';
							$alert28="target='".$target."' href='product_timing.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$row['ApplicantMasterID'].'_4_'.$row['DesignerCoID'].'_'.$row['operatorcoid'].rand(10000,99999).
                            "'";
                            if ($login_RolesID==29 )
                            {
							
							   //if ( in_array($row['applicantstatesID'], array("23","32","42","47")))
                               if (!($row['ApplicantMasterIDsurat']>0))
                                 $alert28="onClick=\"alert('پیش فاکتور تایید نهایی نشده است \\n با مدیریت آب و خاک تماس گرفته شود!');return;\"";
                            }
                            
                                                     						 	            
                           $permitrolsid = array("1","13","14","29","20","21","23");if (in_array($login_RolesID, $permitrolsid))
                            print "<td class='no-print'><a $alert28 ><img style = 'width: 25px;' src='../img/$imgtable' title=' زمانبندی و تحویل کالا '></a></td>";
                            
                            
							
                            ?>
						   
						   
                            <?php 
                            $permitrolsid = array("1","10","13","14","17","18");
                            if (in_array($login_RolesID, $permitrolsid))
                            {
                             print "<td class='no-print' ><a  target='".$target."' href='opchangestodesign.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['BankCode'].rand(10000,99999).
                            "'>
                            <img style = 'width: 25px;' src='../img/accept_page.png' title=' تغییرات اجرا نسبت به طراحی '></a></td>";
                            
                            
                            print "<td class='no-print' ><a  target='".$target."' href='appuploads.php?uid=".rand(10000,99999).rand(10000,99999)
                            .rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['ApplicantMasterID'].rand(10000,99999).
                            "'>
                            <img style = 'width: 25px;' src='../img/calendar_empty.png' title=' مدیریت فایل ها '></a></td>";
                                
                            } 
                             ?>
							
                        
					   
                        </tr> 
                             <?php
                            }
                             
                    }
                    
                    
                    

?>

                        <tr>
                            
                            <td colspan="12" class="f14_fontcb" ><?php echo ' مجموع مساحت (هكتار)';   ?></td>
                            <td colspan="5"
                            class="f14_fontcb" 
                            ><?php echo $sumDA;   ?></td>
                        </tr>
                        <tr>
                            
                            <td colspan="12" class="f14_fontcb" ><?php echo ' مجموع مبلغ کل (ميليون ريال)';   ?></td>
                            <td colspan="5" 
                            class="f14_fontcb" 
                            ><?php echo round(($sumM/1000000),1);   ?></td>
                        </tr>
                        <tr>
                            
                            <td colspan="12" class="f14_fontcb" ><?php echo ' مجموع  بلاعوض معرفی شده (ميليون ريال)';   ?></td>
                            <td colspan="5" 
                            class="f14_fontcb" 
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

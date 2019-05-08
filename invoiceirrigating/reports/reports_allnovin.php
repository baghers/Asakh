<?php 
/*
reorts/reports_allnovin.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

//if ($login_Permission_granted==0) header("Location: ../login.php");
    $showa=0;
 $yearid='';
	
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
	
if ($_POST)
{   
    $yearid=$_POST['YearID'];
	$DesignAreafrom=$_POST['DesignAreafrom'];
    $DesignAreato=$_POST['DesignAreato'];
	if ($_POST['sostot']>0) $_POST['sos']=$_POST['sostot'];
//	$sostotval=$_POST['sostot'];
	
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
			$str.=" and applicantmaster.creditsourceID='$_POST[creditcsourceID]'"; 
    
    if ($applicantstatesID>0)   
        $str.=" and applicantmastersurat.applicantstatesID='$applicantstatesID'"; 

    if (strlen(trim($_POST['sos']))>0)
        $str.=" and shahr.id='$_POST[sos]'";

    if ((trim($_POST['DesignSystemGroupsid']))>0)
        $str.=" and applicantmaster.DesignSystemGroupsid='$_POST[DesignSystemGroupsid]'";
   
   // if ((trim($_POST['DesignerCoid']))>0)
   //     $str.=" and applicantmaster.DesignerCoid='$_POST[DesignerCoid]'";
                
        
        
    
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
        $str.=" and ifnull(applicantfreedetail.Price,0)=0";
    else if (trim($_POST['IDprice3'])==-1)
        $str.=" and ifnull(applicantfreedetail.Price,0)>0";
    else if (strlen(trim($_POST['IDprice3']))>0)	
	
        if (trim($_POST['IDprice3'])==1)
		$str.=" and applicantfreedetail.Price>0 and applicantfreedetail.Price<=1000000000";
		else if (trim($_POST['IDprice3'])==2)
		$str.=" and applicantfreedetail.Price>1000000000 and applicantfreedetail.Price<=1500000000";
		else if (trim($_POST['IDprice3'])==3)
		$str.=" and applicantfreedetail.Price>1500000000 and applicantfreedetail.Price<=2000000000";
		else if (trim($_POST['IDprice3'])==4)
		$str.=" and applicantfreedetail.Price>2000000000 and applicantfreedetail.Price<=3000000000";
		else if (trim($_POST['IDprice3'])==5)
		$str.=" and applicantfreedetail.Price>3000000000 and applicantfreedetail.Price<=5000000000";
		else if (trim($_POST['IDprice3'])==6)
		$str.=" and applicantfreedetail.Price>5000000000 and applicantfreedetail.Price<=8000000000";
		else if (trim($_POST['IDprice3'])==7)
		$str.=" and applicantfreedetail.Price>8000000000 and applicantfreedetail.Price<=10000000000";
		else if (trim($_POST['IDprice3'])==8)
		$str.=" and applicantfreedetail.Price>10000000000";



     if (trim($_POST['IDprice7'])==-2)
        $str.=" and ifnull(applicantfreedetailall.Price,0)=0";
    else if (trim($_POST['IDprice7'])==-1)
        $str.=" and ifnull(applicantfreedetailall.Price,0)>0";
    else if (strlen(trim($_POST['IDprice7']))>0)	
        if (trim($_POST['IDprice7'])==1)
		$str.=" and applicantfreedetailall.Price>0 and applicantfreedetailall.Price<=1000000000";
		else if (trim($_POST['IDprice7'])==2)
		$str.=" and applicantfreedetailall.Price>1000000000 and applicantfreedetailall.Price<=1500000000";
		else if (trim($_POST['IDprice7'])==3)
		$str.=" and applicantfreedetailall.Price>1500000000 and applicantfreedetailall.Price<=2000000000";
		else if (trim($_POST['IDprice7'])==4)
		$str.=" and applicantfreedetailall.Price>2000000000 and applicantfreedetailall.Price<=3000000000";
		else if (trim($_POST['IDprice7'])==5)
		$str.=" and applicantfreedetailall.Price>3000000000 and applicantfreedetailall.Price<=5000000000";
		else if (trim($_POST['IDprice7'])==6)
		$str.=" and applicantfreedetailall.Price>5000000000 and applicantfreedetailall.Price<=8000000000";
		else if (trim($_POST['IDprice7'])==7)
		$str.=" and applicantfreedetailall.Price>8000000000 and applicantfreedetailall.Price<=10000000000";
		else if (trim($_POST['IDprice7'])==8)
		$str.=" and applicantfreedetailall.Price>10000000000";
		
		
	  if($yearid>0)  $str.=" and applicantmaster.yearid='$yearid' ";
	
	
      if ($login_RolesID=='16')
            $str.=" and ifnull(app22.ApplicantMasterID,0)>0 and applicantmaster.applicantstatesID in(30,34,35,38)";    
    else   if ($login_RolesID=='7')
            $str.=" and ifnull(app37.ApplicantMasterID,0)>0 and applicantmaster.applicantstatesID in(30,34,35,38)";  
            
                                                                       
    if ($login_RolesID=='17') 
    $str.=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) ";
else if (($login_RolesID=='14') && ($showa==0))
        $str.=" and substring(applicantmaster.cityid,1,4) in (select substring(id,1,4) from tax_tbcity7digit where ClerkIDExcellentSupervisor='$login_userid') ";
    
    if (strlen(trim($_POST['prjtypeid']))>0)
    {
        $prjtypeid=$_POST['prjtypeid'];
         $str.=" and applicantmasterdetail.prjtypeid='$prjtypeid'";
    }
    else if (!$_POST) 
    {
      $prjtypeid=1;  
    $str.=" and applicantmasterdetail.prjtypeid='1'";
    }
        
 	if ($showa==0)
    {
        if ($prjtypeid==0)
            $str.=" and applicantmaster.applicantstatesID=22";  
        else
            $str.=" and applicantmaster.applicantstatesID=1"; 
    }
	
	switch ($_POST['IDorder']) 
     {
    case 1: $orderby=' order by applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break; 
    case 2: $orderby=' order by applicantmaster.ApplicantFName COLLATE utf8_persian_ci,applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
    case 3: $orderby=' order by applicantmaster.DesignArea,applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
    case 4: $orderby=' order by shahrcityname COLLATE utf8_persian_ci,applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
    case 5: $orderby=' order by operatorcoTitle COLLATE utf8_persian_ci,applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
    case 6: $orderby=' order by applicantmaster.TMDate,applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
	case 7: $orderby=' order by creditsource.creditsourceid,applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
	case 8: $orderby=' order by cast(applicantmaster.sandoghcode as  decimal(10,0)),applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;
	default: 
    if ($login_RolesID=='7' || $login_RolesID=='16') $orderby='order by cast(applicantmaster.sandoghcode as  decimal(10,0)),applicantmaster.ApplicantName COLLATE utf8_persian_ci';
    else $orderby='order by  applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;  
    }
 
 
 
 $selectedCityId=$login_CityId;
if ($_POST['ostan']>0)
        $selectedCityId=$_POST['ostan'];
	$str.=" and substring(applicantmaster.cityid,1,2)=substring('$selectedCityId',1,2)";
    
    

    
    if ($login_RolesID=='17') 
    $str.=" and substring(applicantmaster.cityid,1,4)=substring('$login_CityId',1,4) ";
    else
    $str.=" and applicantmaster.applicantstatesID<>23 ";
         
    if ($login_RolesID=='10')
            $str.=" and case ifnull(applicantmasterdetail.nazerID,0) when 0 then tax_tbcity7digitnazer.DesignerCoIDnazer else applicantmasterdetail.nazerID end='$login_DesignerCoID'";   
  
  
    $kejra1=0;$kejra2=0;$kejra3=0;$kejra4=0;$sqlPricekejra=0;$Pricek=0;$Pricekejra=0;
    
    $sql="
	SELECT shahr.cityname shahrcityname,shahr.id shahrid,substring_index(applicantmaster.countyname,'_',1) countyname
	,applicantmaster.ApplicantName,applicantmaster.ApplicantFName
,license_number,issuance_date,designerco.Title DesignerCoTitle,applicantmaster.DesignerCoid,DesignerCoIDnazer.CPI,DesignerCoIDnazer.DVFS
,designsystemgroups.title designsystemgroupstitle,applicantmaster.DesignSystemGroupsid,Wdebi,applicantmaster.SurveyArea,applicantmaster.DesignArea,applicantmaster.lasttotal,soiltexture.Title soiltextureTitle
,watergroups.title watergroupstitle,applicantmaster.XUTM1,applicantmaster.YUTM1,implanttype.sttt,implanttype.title implanttypetitle
,substring_index(applicantmaster.numfield2,'_',1)sandoghletterno,substring(applicantmaster.numfield2,length(substring_index(applicantmaster.numfield2,'_',1))+2) sandoghletterdate
,case applicantmasterdetail.level=1 and applicantmasterop.belaavaz>0 when 1 then  applicantmasterop.belaavaz else applicantmaster.belaavaz end belaavaz,ifnull(applicantmaster.selfcashhelpval,0) selfcashhelpval
,ifnull(applicantmaster.selfnotcashhelpval,0) selfnotcashhelpval
,ifnull(applicantmaster.selfcashhelpval,0)+ifnull(applicantmaster.selfnotcashhelpval,0) selfhelpval,
case applicantmasterop.ADate>0 when 1 then applicantmasterop.ADate else applicantmaster.ADate end ADate,
operatorco.Title operatorcoTitle
,designercomoshavernazer.title designercomoshavernazertitle,applicantmasterop.lasttotal lasttotalop
,applicantmastersurat.lasttotal lasttotalsurat,prjtype.title prjtypetitle,license_number,watersourceTitle,issuance_date,applicantmasterdetail.prjtypeid
,creditsource.title creditsourcetitle,credityear,pipeproducers.title pipeproducerstitle,applicantstates.title applicantstatestitle,
applicantstates.applicantstatesID


,appchangestatetempdelivery.savedate tempdeliverydate
,case applicantmastersurat.applicantmasterid>0 when 1 
then ifnull(applicantmastersurat.othercosts1,0)+ifnull(applicantmastersurat.othercosts2,0)+ifnull(applicantmastersurat.othercosts3,0)+ifnull(applicantmastersurat.othercosts4,0)
else ifnull(applicantmasterop.othercosts1,0)+ifnull(applicantmasterop.othercosts2,0)+ifnull(applicantmasterop.othercosts3,0)+ifnull(applicantmasterop.othercosts4,0) end othercost

,case applicantmastersurat.applicantmasterid>0 when 1 then ifnull(applicantmastersurat.othercosts5,0)
else case applicantmasterop.applicantmasterid>0 when 1 then ifnull(applicantmasterop.othercosts5,0) else ifnull(applicantmaster.othercosts5,0) end end othercosts5
,applicantfreedetail.price Price1,applicantfreedetailall.price Priceall,pump.title pumptitle
,case applicantmastersurat.ApplicantMasterID>0 when 1 then applicantmastersurat.ApplicantMasterID else
case applicantmasterop.ApplicantMasterID>0 when 1 then applicantmasterop.ApplicantMasterID else applicantmaster.ApplicantMasterID end
 end ApplicantMasterID,operatorco.operatorcoID
 FROM `applicantmaster`
inner join applicantmasterdetail on applicantmasterdetail.applicantmasterid=applicantmaster.applicantmasterid
left outer join prjtype on prjtype.prjtypeid=ifnull(applicantmasterdetail.prjtypeid,0)
left outer join creditsource on creditsource.creditsourceid=applicantmaster.creditsourceid


left outer join producerapprequest on (producerapprequest.ApplicantMasterID=applicantmasterdetail.applicantmasterid ||
producerapprequest.ApplicantMasterID=applicantmasterdetail.ApplicantMasterIDmaster) and producerapprequest.state=1
left outer join producers pipeproducers on pipeproducers.ProducersID=producerapprequest.ProducersID

left outer join applicantmaster applicantmasterop on applicantmasterop.applicantmasterid=applicantmasterdetail.applicantmasteridmaster
left outer join applicantmaster applicantmastersurat on applicantmastersurat.applicantmasterid=applicantmasterdetail.applicantmasteridsurat

left outer join tax_tbcity7digit tax_tbcity7digitnazer on substring(tax_tbcity7digitnazer.id,1,4)=substring(applicantmasterop.cityid,1,4) 
and substring(tax_tbcity7digitnazer.id,5,3)='000'

left outer join applicantstates on applicantstates.applicantstatesID=
case applicantmastersurat.applicantstatesID>0 when 1 then applicantmastersurat.applicantstatesID else
case applicantmasterop.applicantstatesID>0 when 1 then applicantmasterop.applicantstatesID else applicantmaster.applicantstatesID end   end

left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) 
and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
left outer join (select ApplicantMasterID,max(license_number)license_number,max(issuance_date)issuance_date,max(watersource.Title)watersourceTitle,sum(Wdebi)Wdebi
,max(WaterGroupsID)WaterGroupsID from applicantwsource
inner join watersource on watersource.WaterSourceID=applicantwsource.WaterSourceID
 group by ApplicantMasterID) applicantwsource on applicantwsource.ApplicantMasterID=
 case applicantmasterdetail.applicantmasteridmaster>0 when 1 then applicantmasterdetail.applicantmasteridmaster
 else applicantmasterdetail.ApplicantMasterID end
left outer join designerco on designerco.DesignerCoid=applicantmaster.DesignerCoid
left outer join (SELECT clerkID ID,clerk.CPI,DVFS  FROM clerk where city=11 and  substring(clerk.cityid,1,2)=19) DesignerCoIDnazer 
on DesignerCoIDnazer.ID=
case applicantmasterop.DesignerCoIDnazer>0 when 1 then applicantmasterop.DesignerCoIDnazer else
applicantmaster.DesignerCoIDnazer end
left outer join designsystemgroups on designsystemgroups.designsystemgroupsid=applicantmaster.designsystemgroupsid
left outer join soiltexture on soiltexture.SoilTextureID=applicantmaster.SoilLimitation
left outer join watergroups on watergroups.watergroupsid=applicantwsource.watergroupsid
left outer join (	select  implanttype.title,max(concat(implanttype.title,' ',convert(PlantArea,char),' هکتار')) sttt,ApplicantMasterID from applicantsystemtype 
    inner join implanttype on implanttype.ImplantTypeID=applicantsystemtype.ImplantTypeID
	group by ApplicantMasterID) implanttype on implanttype.applicantmasterid=case applicantmasterdetail.applicantmasteridmaster>0 when 1 then applicantmasterdetail.applicantmasteridmaster
 else applicantmasterdetail.ApplicantMasterID end
left outer join operatorco on operatorco.operatorcoID=
case applicantmasterdetail.prjtypeid=1 when 1 then 
case applicantmasterop.operatorcoIDbandp>0 when 1 then applicantmasterop.operatorcoIDbandp else applicantmaster.operatorcoIDbandp end
 else case applicantmasterop.operatorcoid>0 when 1 then applicantmasterop.operatorcoid else applicantmaster.operatorcoid end end

left outer join designerco designercomoshavernazer on designercomoshavernazer.DesignerCoid=applicantmasterdetail.nazerID
left outer join (

select max(savedate) savedate,ApplicantMasterID from (
select savedate,ApplicantMasterID from appchangestate where applicantstatesID=38
union all select workdeliveryend,ApplicantMasterID from applicanttiming
union all select workdeliverystart,ApplicantMasterID  from applicanttiming) view1 group by ApplicantMasterID


) appchangestatetempdelivery on appchangestatetempdelivery.ApplicantMasterID=case applicantmasterdetail.applicantmasteridmaster>0 when 1 then applicantmasterdetail.applicantmasteridmaster
 else applicantmasterdetail.ApplicantMasterID end


left outer join (SELECT sum(Price)Price,ApplicantMasterID FROM `applicantfreedetail` WHERE freestateID=141
group by ApplicantMasterID) applicantfreedetail on (applicantfreedetail.ApplicantMasterID=applicantmaster.ApplicantMasterID or 
applicantfreedetail.ApplicantMasterID=applicantmasterop.ApplicantMasterID)

left outer join (SELECT sum(Price)Price,ApplicantMasterID FROM `applicantfreedetail` 
group by ApplicantMasterID) applicantfreedetailall on (applicantfreedetailall.ApplicantMasterID=applicantmaster.ApplicantMasterID or 
applicantfreedetailall.ApplicantMasterID=applicantmasterop.ApplicantMasterID)

left outer join (SELECT invoicemaster.applicantmasterid,max(concat(gadget2.Title,' ',gadget3.title))
         title
         FROM invoicedetail
inner join toolsmarks on toolsmarks.toolsmarksid=invoicedetail.toolsmarksid
inner join gadget3 on gadget3.gadget3id=toolsmarks.gadget3id and gadget3.gadget2id in (523,408,501,410,409,322,411,440,442,441)
inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
inner join invoicemaster on invoicemaster.invoicemasterid=invoicedetail.invoicemasterid
group by invoicemaster.applicantmasterid
) pump
on pump.applicantmasterid=case applicantmasterdetail.applicantmasteridmaster>0 when 1 then applicantmasterdetail.applicantmasteridmaster
 else applicantmasterdetail.ApplicantMasterID end
where 1=1 $str
$orderby";
	
	
try 
    {		
        $result = mysql_query($sql.$login_limited);
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
    $ID6[' ']=' ';
    $ID8[' ']=' ';
    $ID9[' ']=' ';
   $ID10[' ']=' ';
   $ID11[' ']=' ';
   $ID12[' ']=' ';
   $ID13[' ']=' ';
   $ID14[' ']=' ';
   $ID15[' ']=' ';
   $ID16[' ']=' ';
   $ID17[' ']=' ';
   $ID18[' ']=' ';
   $ID19[' ']=' ';
$dasrow=0;

while($row = mysql_fetch_assoc($result))
{
    $dasrow=1;    
    $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);
    $ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);
    $ID3[trim($row['operatorcoTitle'])]=trim($row['operatorcoTitle']);
    $ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);   
    $ID5[trim($row['creditsourcetitle'])]=trim($row['creditsourceid']);
    $ID6[trim($row['applicantstatestitle'])]=trim($row['applicantstatesID']);
    
	$ID8[trim($row['designsystemgroupstitle'])]=trim($row['DesignSystemGroupsid']);
	$ID9[trim($row['DesignerCoTitle'])]=trim($row['DesignerCoid']);
   $ID10[trim($row['credityear'])]=trim($row['credityear']);
  $ID11[trim($row['designercomoshavernazertitle'])]=trim($row['designercomoshavernazertitle']);
    $ID12[trim($row['pipeproducerstitle'])]=trim($row['pipeproducerstitle']);
   $ID13[trim($row['soiltextureTitle'])]=trim($row['soiltextureTitle']);
  $ID14[trim($row['watersourceTitle'])]=trim($row['watersourceTitle']);
  $ID15[trim($row['watergroupstitle'])]=trim($row['watergroupstitle']);
  $ID16[trim($row['implanttypetitle'])]=trim($row['implanttypetitle']);
  $ID17[trim($row['watersourceTitle'])]=trim($row['watersourceTitle']);
  $ID18[trim($row['watersourceTitle'])]=trim($row['watersourceTitle']);
  $ID19[trim($row['watersourceTitle'])]=trim($row['watersourceTitle']);
 
 
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
$ID14=mykeyvalsort($ID14);
$ID15=mykeyvalsort($ID15);
$ID16=mykeyvalsort($ID16);
$ID17=mykeyvalsort($ID17);
$ID18=mykeyvalsort($ID18);
$ID19=mykeyvalsort($ID19);

if ($dasrow)
mysql_data_seek( $result, 0 );




$query="
select 'کل' _key,1 as _value union all
select 'فهرست بهای اجرای' _key,2 as _value union all 
select 'لوازم' _key,3 as _value  union all 
select 'سایر هزینه ها' _key,4 as _value";

$IDTotlalType = get_key_value_from_query_into_array($query);
if (!$_POST['IDTotlalType'])
    $IDTotlalTypeval=1;
else $IDTotlalTypeval=$_POST['IDTotlalType'];

$query="
select 'نام خانوادگی' _key,1 as _value union all
select 'نام' _key,2 as _value union all 
select 'مساحت' _key,3 as _value union all
select 'شهرستان' _key,4 as _value union all
select 'شرکت طراح' _key,5 as _value union all
select 'تاریخ' _key,6 as _value union all
select 'اعتبار' _key,7 as _value union all
select 'کد' _key,8 as _value ";

$IDorder = get_key_value_from_query_into_array($query);

if (!$_POST['IDorder'])
    $IDorderval=7;
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
  	<title>گزارش سامانه های نوین</title>

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
            
            <form action="reports_allnovin.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data">
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
                         
                                         
                          
                    if ($login_designerCO==1)
                     {
                        $sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM tax_tbcity7digit ostan
                        where substring(ostan.id,3,5)='00000'
					    order by _key  COLLATE utf8_persian_ci";
                        $allg1idostan = get_key_value_from_query_into_array($sqlselect);
                        print select_option('ostan','',',',$allg1idostan,0,'','','1','rtl',0,'',$selectedCityId,'','75');
                     }
					 
	                    $sqlselect="select distinct sostot.CityName _key,sostot.id _value  FROM tax_tbcity7digit sostot
                    	where substring(sostot.id,5,3)='000' and substring(sostot.id,3,5)<>'00000'
						and substring(sostot.id,1,2)=substring('$login_CityId',1,2)
                        order by _key  COLLATE utf8_persian_ci";
                        $allg1idsos = get_key_value_from_query_into_array($sqlselect);
                        print select_option('sostot','شهرستان',',',$allg1idsos,0,'','','1','rtl',0,'',$sos,'','75');
  				 
					 
                    
							$query = "SELECT '1' _value, 'آبرسانی' _key 
								union all SELECT '0' _value, 'آبیاری تحت فشار' _key
								order by _key  COLLATE utf8_persian_ci";
							$ID7 = get_key_value_from_query_into_array($query);
                            print select_option('prjtypeid','پروژه',',',$ID7,0,'','','1','rtl',0,'',$prjtypeid,'','100%');;
                     
						    print select_option('IDTotlalType','نوع',',',$IDTotlalType,0,'','','3','rtl',0,'',$IDTotlalTypeval,"",'100');
                       
						    print select_option('IDorder','ترتیب',',',$IDorder,0,'','','3','rtl',0,'',$IDorderval,"",'100');
                            print select_option('creditcsourceID','اعتبار',',',$ID5,0,'','','1','rtl',0,'',$creditcsourceID,'','95');
							
							print "<td colspan='1' class='label'>همه</td>
                         <td class='data'><input name='showa' type='checkbox' id='showa'";
                             if ($showa>0) echo 'checked';
                             print " /></td>";
                          ?>
  					         <td></td> 
							
                            <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
		                  <td colspan="1"><input    name="submit" type="submit" class="button" id="submit" size="15" value="جستجو" /></td>
    
				   </tr>
				   
				   
		        </table>
                 <table align='center' border='1' id="table2">              
                   <thead>           
				   <tr>
                              <td colspan="36"
                            <span class="f14_fontb" >گزارش سامانه های نوین آبیاری</span>  
                            </td>
                    <tr>        
					 <tr>
                            <th class="f7_fontb" > رديف   </th>
                            <th class="f9_fontb" >کد   </th>
							<th class="f9_fontb"> نام   </th>
							<th class="f9_fontb"> نام خانوادگی  </th>
						   <th class="f9_fontb">دشت/ شهرستان </th>
						   <th class="f9_fontb">روستا</th>
						   <th class="f9_fontb"> پمپ</th>
							
							<th class="f9_fontb">نوع پروژه </th>
							<th class="f9_fontb">نوع سیستم </th>
							<th class="f9_fontb">طراح</th>
							<th class="f8_fontb"> مساحت </span>(ha)  </th>
							<th class="f9_fontb">بافت خاک</th>
							<th class="f9_fontb">دبی منبع</th>
							<th class="f9_fontb">شماره/تاریخ پروانه آب</th>
						  	<th class="f9_fontb">کلاس آب</th>
							<th class="f9_fontb">مختصات X/Y</th>
								<th class="f9_fontb">الگوی کشت</th>
							
							<th class="f9_fontb"> مبلغ کل طراحی</th>
							<th class="f8_fontb"> بلاعوض مصوب</th>
							<th class="f9_fontb"> بازبین</th>
							<th class="f9_fontb">نوع اعتبار</th>
							<th class="f9_fontb"> سال</th>
							<th class="f8_fontb"> شماره / تاریخ معرقی</th>
					 			
							<th class="f9_fontb">مجری </th>
							<th class="f9_fontb">تاریخ انعقاد </th>
							<th class="f9_fontb">پیش فاکتور / صورت وضعیت</th>
							<th class="f9_fontb">مشاور ناظر </th>
							<th class="f8_fontb">تولید کننده لوله </th>
							
						 	<th class="f9_fontb">آخرین وضعیت</th>
                   	
							<th class="f7_fontb">خودیاری نقد(سهم شریک) <br>  غیرنقد (تسهیلات)</th>
							<th class="f8_fontb"> جمع خودیاری</th>
							<th class="f8_fontb">قسط اول </th>
							<th class="f8_fontb">مجموع آزادسازی</th>
							<th class="f9_fontb">مانده </th>
							<th class="f8_fontb">تحویل موقت </th>
							
							
                         </tr>
                        
                        </thead> 
                        <tr class='no-print'>    
							<td class="f14_font"></td>
                            <td class="f14_font"></td>
                            <?php print select_option('ApplicantFname','',',',$ID4,0,'','','1','rtl',0,'',$ApplicantFname,'','100%'); ?>
							 <?php print select_option('ApplicantName','',',',$ID2,0,'','','1','rtl',0,'',$ApplicantName,'','100%'); ?>
							 <?php print select_option('sos','',',',$ID1,0,'','','1','rtl',0,'',$sos,'','100%'); ?>
							  <td colspan="3"></td>  
							    
                                
                             <?php print select_option('DesignSystemGroupsid','',',',$ID8,0,'','','1','rtl',0,'','','','100%'); ?> 
						     <?php print select_option('DesignerCoid','',',',$ID9,0,'','','1','rtl',0,'','','','100%'); ?> 
							<?php print select_option('IDArea','',',',$IDArea,0,'','','1','rtl',0,'',$IDArea,'','100%'); ?>
							<?php print select_option('soiltextureTitle','',',',$ID13,0,'','','1','rtl',0,'',$soiltextureTitle,'','100%'); ?>
							<?php print select_option('watersourceTitle','',',',$ID14,0,'','','1','rtl',0,'',$watersourceTitle,'','100%'); ?>
								<td></td> 
							<?php print select_option('watergroupstitle','',',',$ID15,0,'','','1','rtl',0,'',$watergroupstitle,'','100%'); ?>
								  <td></td> 
								<?php print select_option('implanttypetitle','',',',$ID16,0,'','','1','rtl',0,'',$implanttypetitle,'','100%'); ?>
							 
								  <td></td> 
								  <td></td> 
							  <td></td> 
							
						        <?php print select_option('creditcsourceID','',',',$ID5,0,'','','1','rtl',0,'',$creditcsourceID,'','100%'); ?> 
                    	       <?php print select_option('credityear','',',',$ID10,0,'','','1','rtl',0,'',$credityear,'','100%'); ?> 
                    			  <td></td> 
							
					         <?php print select_option('operatorcoid','',',',$ID3,0,'','','1','rtl',0,'',$operatorcoid,'','100%') ?>
									  <td></td> 
								  <td></td> 
						 
							  <?php print select_option('designercomoshavernazertitle','',',',$ID11,0,'','','1','rtl',0,'',$designercomoshavernazertitle,'','100%') ?>
							  <?php print select_option('pipeproducerstitle','',',',$ID12,0,'','','1','rtl',0,'',$pipeproducerstitle,'','100%') ?>
											   
								  
					      <?php print select_option('applicantstatesID','',',',$ID6,0,'','','1','rtl',0,'',$applicantstatesID,'','100%');?>
                    
                            <td></td> 
					       <?php print select_option('IDprice1','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice1,'','100%'); ?>  
					       <td></td>  
						   <td></td> 
                            <?php print select_option('IDprice2','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice2,'','100%'); ?> 
                            <?php print select_option('IDprice2','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice2,'','100%'); ?> 
                           <td></td> 
						 
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
					
							$numfield2array = explode('_',$resquery["numfield2s"]);
							$contracdate=$numfield2array[1];
					   
					        $rown++;
                            if ($rown%2==1) 
                            $b='b'; else $b='';
                             print "<tr '>";      
?>                      
                            <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo $rown; ?></td>
                            <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo "($resquery[sandoghcode])" ;?></td>
														
                            <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo  $resquery['ApplicantFName']; ?></td>
                            <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php 
                            
                            print "<a target='_blank' href='../appinvestigation/applicant_manageredit.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$resquery['ApplicantMasterID'].'_5_'.$resquery['DesignerCoid'].'_'.$resquery['operatorcoID'].rand(10000,99999).
                            "'>$resquery[ApplicantName]</a>"; 
                            
                            ?></td>
                            <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo  $resquery["shahrcityname"]; ?></td>
				            <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo  $resquery["countyname"]; ?></td>
				            <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo  $resquery["pumptitle"]; ?></td>
							<td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:7.0pt;font-family:'B Nazanin';"><?php echo $resquery["prjtypetitle"] ; ?></td>
							<td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo $resquery["designsystemgroupstitle"] ; ?></td>
					        <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo $resquery["DesignerCoTitle"] ; ?></td>
					        <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php 
                             if ($resquery["prjtypeid"]==1)
                             echo round($resquery["belaavaz"]/32000000,1) ;
                             else 
                             echo $resquery["DesignArea"] ; ?></td>
					        <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo $resquery["soiltextureTitle"] ; ?></td>
						    <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo $resquery["Wdebi"].'<br>'.$resquery["watersourceTitle"] ; ?></td>
						    <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo $resquery["license_number"].'<br>';
                            if (($resquery["issuance_date"])>0)
                            echo ($resquery["issuance_date"]) ; ?></td>
					 	    <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo $resquery["watergroupstitle"] ; ?></td>
						    <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:7.0pt;font-family:'B Nazanin';"><?php echo 'X='.number_format($resquery["XUTM1"],0,'','').' '.'Y='.number_format($resquery["YUTM1"],0,'','') ; ?></td>
					 	   
							<td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo $resquery["sttt"] ; ?></td>
							<td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php 
                            
                            if ($resquery["lasttotalop"]>0 && $resquery["lasttotal"]<=0) echo floor($resquery["lasttotalop"]/100000)/10 ;
                            else
                            echo floor($resquery["lasttotal"]/100000)/10 ; ?></td>
							<td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php 
                           if ($resquery["prjtypeid"]==1)
                           echo floor($resquery["belaavaz"]/100000)/10 ;
                           else 
                           echo floor($resquery["belaavaz"]) ; ?></td>
						   
						    <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php 
                            
                            if (decrypt($resquery['DVFS'])<>'ج')
                                echo trim(decrypt($resquery['CPI'])." ".decrypt($resquery['DVFS']));
                                 ?></td>
						    <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:7.0pt;font-family:'B Nazanin';"><?php echo $resquery["creditsourcetitle"] ; ?></td>
						    <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo $resquery["credityear"] ; ?></td>
						    <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo $resquery["sandoghletterno"].'<br>'.$resquery["sandoghletterdate"] ; ?></td>
					     
						   
		                   <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo $resquery["operatorcoTitle"] ; ?></td>
		                   <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php 
                           if (($resquery["ADate"])>0)
                            echo gregorian_to_jalali($resquery["ADate"]) ;
                            ?></td>
		                   <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo (floor($resquery["lasttotalop"]/100000)/10).'<br>'.floor($resquery["lasttotalsurat"]/100000)/10  ; ?></td>
		                   <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo $resquery["designercomoshavernazertitle"] ; ?></td>
		                   <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo $resquery["pipeproducerstitle"] ; ?></td>
						   
					 	
                            <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:7.0pt;font-family:'B Nazanin';"><?php  echo $resquery['applicantstatestitle'];?></td>
		              
                            <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["selfcashhelpval"]/100000)/10; ?>
									<?php echo "<br>".floor($resquery["selfnotcashhelpval"]/100000)/10;if($resquery["othercosts5"]>0) {echo "<br>".floor($resquery["othercosts5"]/100000)/10;} ?></td>
							<td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo floor(($resquery["selfhelpval"]+$resquery["othercosts5"])/100000)/10; ?></td>
																				
							<td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["Price1"]/100000)/10; ?></td>
                            <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["Priceall"]/100000)/10; ?></td>
                            <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php echo 
											round((floor(($resquery["selfhelp"]+$resquery["othercosts5"])/100000)/10+round($bel,1)-floor($resquery["Priceall"]/100000)/10),1); ?></td>
                            <td class="f9_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:8.0pt;font-family:'B Nazanin';"><?php 
                            if ($resquery["tempdeliverydate"]!='')
                            
                            echo gregorian_to_jalali($resquery["tempdeliverydate"]); ?></td>
               
                        
                          <?php
						  $permitrolsid = array("9");
						  //$permitrolsid = array("1");
						  if ( in_array($login_RolesID, $permitrolsid) || $login_username=='entezam' || $login_username=='saradr')
						  {
  							 print  "<td class=\"f7_font$b'\"><a target='".$target."' href='aaapplicantfreep.php?uid=".rand(10000,99999).
                                rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                rand(10000,99999).$resquery['applicantmasterid'].'_1_'.$resquery["applicantstatesIDsurat"].'_'.
                                $resquery['operatorcoid']."_".$resquery['applicantmasteridsurat'].rand(10000,99999).
                                "'><img style = 'width: 22px;' src='../img/process.png' title=' پیشنهاد آزادسازی'></a></td>";
                           }
                                
                            echo "
                                                  
                        
                        </tr>
                        ";                     
                    }               
                         
?>

         
			
                </table>
                
                
                <script src="../js/jquery-1.9.1.js"></script>
				<script src="../js/jquery.freezeheader.js"></script>

			<script language="javascript" type="text/javascript">
/*
        $(document).ready(function () {
         $("#table2").freezeHeader();
		})
*/ 

    </script>
	
	    
                 <tr > <span colsapn="1" id="fooBar">  &nbsp;</span> </tr>
	
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

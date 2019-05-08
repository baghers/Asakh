<?php 

/*

//appinvestigation/sendtoanjoman.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود

insert/equip_detail.php

*/

include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php'); 

if ($_POST)//کلیک شدن دکمه سابمیت
{
    $sos=$_POST['sos'];//شناسه شهر
    $sob=$_POST['sob'];//شناسه بخش
    $DesignerCoID=$_POST['DesignerCoID'];//شناسه مشاور طراح
    $applicantstatesID=$_POST['applicantstatesID'];//شناسه وضعیت طرح
    $creditcsourceID=$_POST['creditcsourceID'];//منبع تامین اعتبار
    $BankCode=$_POST['BankCode'];//کد رهگیری
    $applicantstategroupsID=$_POST['applicantstategroupsID'];//شناسه گروه وضعیت پروژه
	$ApplicantFname=$_POST['ApplicantFname'];//نام
    $Applicantname=$_POST['ApplicantName'];//نام خانوادگی
    $DesignSystemGroupstitle=$_POST['DesignSystemGroupstitle'];//عنوان سیستم آبیاری
}
  switch ($_POST['IDorder'])//شناسه ترتیب گزارش 
  {
    case 1: $orderby='  order by applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break;//عنوان پروژه 
    case 2: $orderby='  order by ords,applicantmaster.ApplicantFName COLLATE utf8_persian_ci'; break;//نام متقاضی
    case 3: $orderby='  order by ords,applicantmaster.DesignArea'; break;//مساحت پروژه
	case 5: $orderby='  order by ords,applicantmaster.cityID '; break;//شناسه شهر
    case 6: $orderby='  order by ords,applicantmaster.DesignerCoID '; break;//شناسه مشاور طراح
    case 7: $orderby='  order by ords,applicantstatestitle COLLATE utf8_persian_ci'; break;//عنوان وضعیت پروژه
    case 8: $orderby='  order by ords,applicantmaster.TMDate'; break;//تاریخ جلسه کمیته فنی
    case 9: $orderby='  order by ords,cast(applicantmaster.sandoghcode as  decimal(10,0))'; break;//کد صندوق
    default:
    //$login_RolesID=='7' بانک
    //$login_RolesID=='16' صندوق 
    //prjtypeid نوع پروژه
    //apptitle عنوان پروژه
    if ($login_RolesID=='7' || $login_RolesID=='16')
        $orderby='  order by prjtypeid,cast(applicantmaster.sandoghcode as  decimal(10,0)),cityID,applicantmaster.TMDate,applicantmaster.ApplicantName COLLATE utf8_persian_ci';
    else     
        $orderby='  order by prjtypeid,apptitle,ords,cityID,applicantmaster.TMDate,applicantmaster.ApplicantName COLLATE utf8_persian_ci'; break; 
  }
  //$login_RolesID=31 کارشناس آب رسانی
  //$login_RolesID=32 مدیر آبرسانی
  if (in_array($login_RolesID, array("31", "32")))   $login_DesignerCoID='67';

$formname='sendtoanjoman';//نام فرم فعلی
$tblname='applicantmaster';//نام جدول مشخصات طرح

if ($login_Permission_granted==0) header("Location: ../login.php");
//در صورتی که کاربر مشاور طراح بود فیلتر شرکت مشاور طراح افزوده می شود
if ($login_DesignerCoID>0 &&  ($login_RolesID=='9')) $condition=" and applicantmaster.DesignerCoID='$login_DesignerCoID' ";
//در صورتی که شرکت پیمانکار بود فیلتر شرکت پیمانکار افزوده می شود
else if ($login_OperatorCoID>0) $condition=" and applicantmaster.operatorcoid='$login_OperatorCoID'  and ifnull(applicantmaster.operatorcoid,0)<>0 "; 
else if ($login_RolesID==26) 
    $condition=" and applicantmaster.applicantstatesID in (23,59)  and applicantmaster.melicode in (select clerk.melicode from clerk where clerk.ClerkID='$login_userid')"  ;
    

$per_page = 1000000;//تعداد رکورد های هر صفحه
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;//رکورد شروع صفحه قعلی
//----------

$count = 1000;//تعداد کل رکوردها
$pages = ceil($count / $per_page);//تعداد صفخات
//----------
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
    $sql = "SELECT distinct ppropos.proposable
	,applicantmaster.applicantstatesID
	,appstatesee.applicantstatesID appstateseeid
	,applicantmaster.TMDate appchangestateSaveDate
	,applicantmaster.*
	,case applicantmasterprev.DesignerCoIDnazer>0 when 1 then applicantmasterprev.DesignerCoIDnazer else applicantmaster.DesignerCoIDnazer end DesignerCoIDnazercorrectd
	,applicantstates.title applicantstatestitle
	,case ifnull(applicantmaster.applicantstatesID,0) when 41 then 1 when 43 then 2 when 44 then 3 else 0 end ords
    ,dsg.hektar dsghektar
	,ifnull(applicantmasterdetail.prjtypeid,0) prjtypeid
	,applicantmasterdetail.nazerID DesignerCoIDnazercorrect
	,creditsource.Title creditsourceTitle
	,case ifnull(applicantmaster.ApplicantMasterIDmaster,0)>0 when 1 then 1 else case ifnull(applicantmaster.DesignerCoID,0)>0 when 1 then 2 else 3 end end apptitle            

    FROM applicantmaster 
    left outer join applicantmasterdetail on (applicantmasterdetail.ApplicantMasterID=applicantmaster.applicantmasterid or 
    applicantmasterdetail.ApplicantMasterIDmaster=applicantmaster.applicantmasterid or
    applicantmasterdetail.ApplicantMasterIDsurat=applicantmaster.applicantmasterid)
    
    inner join applicantmaster applicantmasterd on applicantmasterd.ApplicantMasterID=applicantmasterdetail.ApplicantMasterID
    
    left outer join applicantmaster applicantmasterprev on applicantmasterprev.ApplicantMasterID=applicantmasterdetail.ApplicantMasterIDmaster
    
    left outer join (select max(hektar) hektar,ApplicantMasterID from designsystemgroupsdetail group by ApplicantMasterID) dsg 
    on dsg.ApplicantMasterID=applicantmaster.ApplicantMasterID
	
    inner join applicantstates on applicantstates.applicantstatesID=applicantmaster.applicantstatesID
 
    left outer join appstatesee on appstatesee.applicantstatesID=applicantmaster.applicantstatesID and appstatesee.RolesID='$login_RolesID'
    and appstatesee.ostan=substring('$login_CityId',1,2) and ifnull(appstatesee.prjtypeid,0)=ifnull(applicantmasterdetail.prjtypeid,0)
   
   left outer join creditsource on creditsource.creditsourceID=applicantmasterd.creditsourceID
    
   left outer join (select max(proposable) proposable,ApplicantMasterID from invoicemaster group by ApplicantMasterID) ppropos 
    on ppropos.ApplicantMasterID=applicantmaster.ApplicantMasterID
   
     where  ifnull(appstatesee.applicantstatesID,0)>0 and 
     case ifnull(applicantmaster.operatorcoid,0) when 0 then ifnull(applicantmaster.private,0) else 0 end=0 and
    substring(applicantmaster.cityid,1,2)=substring('$login_CityId',1,2) $condition  
    $orderby 
    ;";
    try 
        {		
            //print $sql;
            $result = mysql_query($sql);
        }
        //catch exception
    catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
        } 

/*
tax_tbcity7digit جدول شهرها
cityname نام شهر
id شناسه شهر
*/  
$query = "select distinct 
                 substring(tax_tbcity7digit.id,1,4) as _value,cityname  as _key 
				 from tax_tbcity7digit 
        where substring(tax_tbcity7digit.id,5,3)='000' and substring(tax_tbcity7digit.id,3,5)<>'00000' ";
$cityID = get_key_value_from_query_into_array($query);
/*
tax_tbcity7digit جدول شهرها
ClerkIDExcellentSupervisor شناسه کاربر ناظر عالی مربوط به شهر
id شناسه شهر
*/
$query = "select distinct ClerkIDExcellentSupervisor  as _value, substring(tax_tbcity7digit.id,1,4) as _key from tax_tbcity7digit 
            where ClerkIDExcellentSupervisor>0";
$ClerkIDExcellentSupervisorID = get_key_value_from_query_into_array($query);
/*
tax_tbcity7digit جدول شهرها
ClerkIDWaterInspector شناسه کاربر ناظر آبرسانی مربوط به شهر
id شناسه شهر
*/
$query = "select distinct ClerkIDWaterInspector  as _value, substring(tax_tbcity7digit.id,1,4) as _key from tax_tbcity7digit 
            where ClerkIDWaterInspector>0";
$ClerkIDWaterInspectorID = get_key_value_from_query_into_array($query);
/*
tax_tbcity7digit جدول شهرها
ClerkIDinspector شناسه کاربر بازرس مربوط به شهر
id شناسه شهر
*/
$query = "select distinct ClerkIDinspector  as _value, substring(tax_tbcity7digit.id,1,4) as _key from tax_tbcity7digit 
            where ClerkIDinspector>0";
$ClerkIDinspectorID = get_key_value_from_query_into_array($query);
/*
tax_tbcity7digit جدول شهرها
DesignerCoIDnazer شناسه کاربر مشاور ناظر مربوط به شهر
id شناسه شهر
*/
$query = "select distinct DesignerCoIDnazer  as _value, substring(tax_tbcity7digit.id,1,4) as _key from tax_tbcity7digit 
            where DesignerCoIDnazer>0";
$DesignerCoIDnazerID = get_key_value_from_query_into_array($query);
/*
designerco جدول شرکت های مهندس مشاور
DesignerCoID شناسه شرکت  
Title عنوان شرکت
*/
$query = "select distinct DesignerCoID as _value,Title  as _key from designerco ";
$designercoID = get_key_value_from_query_into_array($query);
/*
operatorcoid جدول شرکت های پیمانکار
DesignerCoID شناسه شرکت  
Title عنوان شرکت
*/
$query = "select distinct operatorcoID as _value,Title  as _key from operatorco ";
$operatorcoID = get_key_value_from_query_into_array($query);
/*
applicantmaster جدول مشخصات طرح
cityid شناسه شهر  
BankCode کد رهگیری طرح
operatorapprequest جدول پیشنهادات قیمت
ApplicantMasterID شناسه طرح
operatorcoid جدول شرکت های پیمانکار
state وضعیت انتخاب شدن یا نشدن
*/
$query = "select distinct substring(applicantmaster.cityid ,1,4) as _value,applicantmaster.BankCode as _key 
from operatorapprequest
inner join applicantmaster  on applicantmaster.ApplicantMasterID=operatorapprequest.ApplicantMasterID
where operatorapprequest.operatorcoid='$login_OperatorCoID' and operatorapprequest.state=1";
$operatorapprequestID = get_key_value_from_query_into_array($query);
    

$query="
select 'نام خانوادگی' _key,1 as _value union all
select 'نام' _key,2 as _value union all 
select 'مساحت' _key,3 as _value union all
select 'شهرستان' _key,5 as _value union all
select 'شرکت طراح' _key,6 as _value union all
select 'وضعیت' _key,7 as _value union all
select 'تاریخ' _key,8 as _value union all
select 'کد' _key,9 as _value ";
$IDorder = get_key_value_from_query_into_array($query);
if (!$_POST['IDorder'])
    $IDorderval=5;
else $IDorderval=$_POST['IDorder'];



    $IDState[' ']=' ';
while($row = mysql_fetch_assoc($result))
{
    $IDState[trim($row['applicantstatestitle'])]=trim($row['applicantstatesID']);//شناسه وضعیت طرح
}    
$IDState=mykeyvalsort($IDState);//مرتب سازی آرایه کلید و مقدار
mysql_data_seek( $result, 0 );//تغییر اشاره گر به ابتدای آرایه

?>
<!DOCTYPE html>
<html>
<head>
  	<title>تغییر وضعیت طرح ها</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>
	 <script>
		function selectpage(obj){
			window.location.href = '?page=' + obj.value;
		}
	</script>
		<!-- /scripts -->
	
	<style>
	.f_font{
		background-color:#f1f1f1;border:0px solid black;border-color:#D1D1D1 #D1D1D1;
		text-align:center;font-size:10pt;line-height:100%;               
	}
	</style>
	
</head>
<body>

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
			 <form action="sendtoanjoman.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data">
           
                <table width="95%" align="center" >
                    <tbody>
                        <tr>
                        
                        <h1 style = "display: block;margin-left: auto;margin-right: auto;text-align: center;font-size:16.0pt;font-family:'B Nazanin';">
						لیست طرح های مختلف <?php print $login_fullname;  ?></h1>
                          <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
                          <INPUT type="hidden" id="txturl" value="<?php print "$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php"; ?>"/>
                           <!--INPUT type="button" value="افزودن طرح جدید" onclick="add()"/-->
                            <td width="50%" align="left"><?php

										if ($pages > 1){
											echo '<select name="pagination" id="pagination" onChange="selectpage(this);">';
											for($i = 1; $i <= $pages; $i++){
												echo '<option value="'.$i.'"';
												if ($page == $i) echo ' selected';
												echo '>'.$i.'</option>';
											}
											echo '</select>';
										}
								if (strlen($enrolmsg)>0)
								{
									echo $enrolmsg;
									exit;
								}
              ?></td>
				  
				  <?php print select_option('IDorder','ترتیب',',',$IDorder,0,'','','3','rtl',0,'',$IDorderval,"",'100');
                  print select_option('IDState','وضعیت',',',$IDState,0,'','','3','rtl',0,'',$IDStateval,"",'100');
                  ?> 
                   <td class='no-print' colspan="1"><input   name="submit" type="submit" class="button" id="submit" size="16" value="جستجو" /></td>
                 
                        </tr>
						
                   </tbody>
                </table>
			    <table id="records" width="95%"  style = "display: block;margin-left: auto;margin-right: auto;text-align: center;font-size:13.0pt;font-family:'B Nazanin';">
                    <thead>
                        <tr>
                            <th width="1%"></th>
                           <th width="1%">کد</th>
                        	<th width="12%">مجری/طراح</th>
                            <th width="5%">شهرستان</th>
                            <th width="2%">هکتار/متر</th>
                            <th width="30%">نام متقاضي</th>
                            <th colspan="2" width="20%">وضعیت</th>
                            <th width="12%">کد&nbsp;رهگیری</th>
                            <th width="8%">تاریخ </th>
							<th width="10%">اعتبار </th>
                        
                            <th width="3%">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody >
                   <?php
                    $Rowcnt=0;        
                    if ($result)
                    while($row = mysql_fetch_assoc($result))
                    {
					
						
								
							
                        if (in_array($login_RolesID, array("31", "32")) )
                        {
                            if  ( ($row["DesignerCoID"]==67) ||($row["DesignerCoID"]>0 && $row["applicantstatesID"]==25) )
                         $p=1;
                         else 
                            continue;
                        }

                        if ($login_RolesID=='17' || $login_RolesID=='26')
                        {
                            if (( substr($row["CityId"],0,4)!=substr($login_CityId,0,4)))
                            continue;
                        if (($row["prjtypeid"]==0 && $row["DesignArea"]>10.99) && $row["applicantstatesID"]!=23)
                            continue;
                        if ($row["operatorcoid"]>0 && $row["applicantstatesID"]==23)
                            continue;
                        }
                                                   
                        if ($login_RolesID=='31')
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
                         } else if ($login_RolesID=='14')
                         {
                            $permit=0;
                            //print_r($ClerkIDExcellentSupervisorID);
                            foreach ($ClerkIDExcellentSupervisorID as $key => $value)
                            {
                                //print substr($row["CityId"],0,4)."_".$key."<br>";
                                if (substr($row["CityId"],0,4)==$key and $login_userid==$value)
                                    $permit=1;   
                            }
                            if ($permit==0)
                                continue;        
                         } else if ($login_RolesID=='13')
                         {
                            $permit=0;
                            //print_r($ClerkIDExcellentSupervisorID);
                            foreach ($ClerkIDExcellentSupervisorID as $key => $value)
                            {
                                //print substr($row["CityId"],0,4)."_".$key."<br>";
                                if (substr($row["CityId"],0,4)==$key and $login_userid==$value)
                                    $permit=1;   
                            }
						    if ( ! in_array($row["applicantstatesID"],array(26,28,29,43)))
                                $permit=1;
                            if ($permit==0)
                                continue;   
                         } else if ($login_RolesID=='10')
                         {
                            //print $row["DesignerCoID"];
                            $permit=0;
                            if ($row["DesignerCoID"]==$login_DesignerCoID)
                                $permit=1;
                            if ($row["DesignerCoIDnazercorrect"]==$login_DesignerCoID)
                                $permit=1;
                            else if (!($row["DesignerCoIDnazercorrect"]>0))
                            {
                                foreach ($DesignerCoIDnazerID as $key => $value)
                                {
                                    if (substr($row["CityId"],0,4)==$key and $login_DesignerCoID==$value && $row["operatorcoid"]>0)
                                        $permit=1;     
                                }                                
                            }                            
                            if ($permit==0)
                                continue; 
                            
                         }
                         else if ($login_RolesID=='2')
                         {
                            
                              //print $row["DesignerCoID"];
                            if (!($row["DesignerCoIDnazercorrect"]>0))
                            {
                                foreach ($DesignerCoIDnazerID as $key => $value)
                                {
                                    if (substr($row["CityId"],0,4)==$key and $row["operatorcoid"]>0)
                                        mysql_query("update applicantmasterdetail set nazerID='$value' 
                                         ApplicantMasterID='$row[ApplicantMasterID]' 
                                         or ApplicantMasterIDmaster='$row[ApplicantMasterID]' 
                                         or ApplicantMasterIDsurat='$row[ApplicantMasterID]'");
                                }                                
                            }                            
                            
                            //print $row["DesignerCoID"];
                            $permit=0;
                            
                            //print_r($operatorapprequestID);
                            foreach ($operatorapprequestID as $key => $value)
                            {
                                //print "<br>".substr($row["CityId"],0,4)."_".$value."_".$row["BankCode"]."_".$key;
                                if (substr($row["CityId"],0,4)==$value and $row["BankCode"]==$key)
                                    $permit=1;     
                            }
                            
                            if ($permit==0)
                                continue; 
                            
                          }
                            else if ($login_RolesID=='11')// بازبین
                          {
                            $permit=0;
                            $foud=0;
                            if ($row["DesignerCoIDnazercorrectd"]>0 && $row["DesignerCoID"]>0)
                            {
                                if ($row["DesignerCoIDnazercorrectd"]==$login_userid)
                                $permit=1;
                                else continue; 
                            }
                            else
                            {
                                foreach ($ClerkIDinspectorID as $key => $value)
                                {
                                    //print substr($row["CityId"],0,4)."_".$key."<br>";
                                    if (substr($row["CityId"],0,4)==$key)
                                        $foud=1;    
                                    if (substr($row["CityId"],0,4)==$key and $login_userid==$value)
                                    {
                                        mysql_query("update applicantmaster set SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
                                        
                                        DesignerCoIDnazer='$login_userid' where ApplicantMasterID='$row[ApplicantMasterID]'");
                                        $permit=1;
                                    }
                                           
                                }
                                if ($permit==0 && $foud==1)
                                    continue;                                 
                            }
                          }
                          
                         $query1 = "
                        select distinct operatorapprequest.errors,operatorapprequest.ecept,operatorapprequest.ApplicantMasterID						
                        FROM operatorapprequest
                        inner join applicantmaster applicantmasterall on applicantmasterall.ApplicantMasterID=operatorapprequest.ApplicantMasterID
                        inner join applicantmaster applicantmasterop on applicantmasterop.BankCode=applicantmasterall.BankCode 
                        and applicantmasterop.operatorcoID=operatorapprequest.operatorcoID and substring(applicantmasterop.cityid,1,4)=substring(applicantmasterall.cityid,1,4) 
              		    where ifnull(applicantmasterop.operatorcoid,0)>0 and 
                        (applicantmasterop.coef1>operatorapprequest.coef1 or applicantmasterop.coef2>operatorapprequest.coef2 or 
                        round(applicantmasterop.coef3,3)>round(operatorapprequest.coef3,3) or (length(operatorapprequest.errors)>0 and ifnull(operatorapprequest.ecept,0)=0)) 
                        and applicantmasterop.ApplicantMasterID='$row[ApplicantMasterID]'  
                        and operatorapprequest.state=1 
                        ";
						
						$errnum=0;
                        $result1 = mysql_query($query1);
                  		$row1 = mysql_fetch_assoc($result1);
				    $permittiming=0;
					if ($login_RolesID==2 || ( $login_RolesID==10 && $row['ApplicantMasterIDmaster']>0 ) )
                    {
							$query11="select applicanttiming.errnum,applicanttiming.RoleID,applicanttiming.emtiaz from applicanttiming 
							left outer join applicantmaster on applicantmaster.ApplicantMasterID='$row[ApplicantMasterID]'
							where applicanttiming.RoleID='$login_RolesID' and (applicanttiming.ApplicantMasterID=applicantmaster.ApplicantMasterIDmaster or
							applicanttiming.ApplicantMasterID='$row[ApplicantMasterID]')";
							$result11 = mysql_query($query11);
							$row11 = mysql_fetch_assoc($result11);
							//print $query11;
							$errnum=$row11['errnum']; //print $errnum.'</br>';
						
						if ($row['applicantstatesID']==41 || $row['applicantstatesID']==23) $permittiming=1;
						if ($row['emtiaz']<20 && $login_RolesID==10) $erremtiaz='<br> خواهشمند است جدول ارزشیابی پیمانکار را تکمیل نمایید.';
                    }
                            	
                        $operatorviolated=0;
                        if (strlen($row1['errors'])>0 && $row1['ecept']==0)
                            $operatorviolated=2;
                        
						else if (strlen($row1['ApplicantMasterID'])>0)
                            $operatorviolated=1;
                                    
						$sandoghcode = $row['sandoghcode'];
                        $Code = $row['Code'];
						$ID = $row['ApplicantMasterID'].'_3_0_0_'.$row['applicantstatesID'];
                        $ApplicantName = $row['ApplicantFName'].' '.$row['ApplicantName'];
                        $year = $row['year'];
                        $monthtitle = $row['monthtitle'];
                        $BankCode=$row['BankCode'];
                        $CostPriceListMasterID=$row['CostPriceListMasterID'];
                        $applicantstatestitle=$row['applicantstatestitle'];
                        $appchangestateSaveDate=$row['appchangestateSaveDate'];
						$applicantstatesID=$row['applicantstatesID'];
						$creditsourceTitle=$row["creditsourceTitle"];
						$Rowcnt++;
//if ($row["ApplicantMasterIDmaster"]>0 ) {$baccolor=' style="background-color:#C2FFD8"';$apptitle=' صورت وضعیت ';} 
//else if ($row["DesignerCoID"]>0 ) {$baccolor='';$apptitle=' لیست لوازم ';}  
//else {$baccolor='';$apptitle=' پیش فاکتور ';}       

if ($row["apptitle"]==1 ) {$baccolor=' style="background-color:#C2FFD8"';$apptitle=' صورت وضعیت ';} 
else if ($row["apptitle"]==2 ) {$baccolor='';$apptitle=' لیست لوازم ';}  
else {$baccolor='';$apptitle=' پیش فاکتور ';}       
 ?>                      

                        <tr >
                            <td ><?php echo $Rowcnt; ?></td>
                            <td  class="f_font"><?php echo $sandoghcode; ?></td>
                            <td class="f_font"><?php 
										foreach ($designercoID as $key => $value)
											if ($row["DesignerCoID"]==$value)
												echo $key;
										
										foreach ($operatorcoID as $key => $value)
											if ($row["operatorcoid"]==$value)
												echo $key;?>
							</td>
                            <td class="f_font"><?php 
									//print $CntError;
										foreach ($cityID as $key => $value)
											if (substr($row["CityId"],0,4)==$value)
												echo $key; ?>
							</td>
							<td class="f_font"><?php echo $row["DesignArea"]; ?></td>
                            <td class="f_font"><?php echo $ApplicantName; ?></td>
                            <td class="f_font"><?php echo $apptitle; ?></td>
                            <td class="f_font"><?php echo $applicantstatestitle; ?></td>
                            <td class="f_font"><?php echo $BankCode; ?></td>
							<td class="f_font"><?php echo gregorian_to_jalali($appchangestateSaveDate); ?></td>
							<td class="f_font"><?php echo $creditsourceTitle; ?></td>
						
                            <?php 
							$rett=0;
							if ($row["appstateseeid"]==23 && $login_RolesID==17) $rett=1;
                            $detailstr="";
                            if ($login_OperatorCoID>0)
                            $ret1="return false;";
                            else $ret1="";
                            if ($operatorviolated==1)
                                $detailstr.= "\"onClick=\"alert('شرکت مجری محترم. ضرایب بالاسری و تجهیز و پلوس مینوس طرح با مقادیرر پیشنهادی در پیشنهاد قیمت شما یکسان نمی باشد'); $ret1; \"";
                            else if ($operatorviolated==2)
                                $detailstr.= "\"onClick=\"alert('شرکت مجری محترم شما در زمان انتخاب به عنوان مجری این طرح صلاحیت لازم را دارا نبوده اید. لطفا جهت تایید با مدیر آب و خاک تماس حاصل نمایید.'); return false; \"";
                            
							else if ($errnum<8 && $permittiming==1 && $row["prjtypeid"]==0)
                                $detailstr.= "\"onClick=\"alert('شرکت محترم خواهشمند است جدول زمانبندی طرح را تکمیل نمایید. $erremtiaz'); return false; \"";
                            
                            else if (strlen(str_replace('-', '', $BankCode))==0 && (in_array($login_RolesID, array(5,2,9)) || ($login_RolesID==17 && $row["prjtypeid"]==0) ) )
                            $detailstr.= "\"onClick=\"alert('لطفا قبل از تغییر وضعیت طرح کد رهگیری را وارد نمایید'); return false; \"";
							
                            else if (!($rett==1) && !($row["dsghektar"]>0) && !($row["operatorcoid"]>0) && $row["prjtypeid"]==0)
                            $detailstr.= "\"onClick=\"alert('لطفا قبل از تغییر وضعیت جدول تفکیک سطح را وارد نمایید.'); return false; \"";
							
                            
                            
                            else if (!($row["creditsourceID"]>0) && in_array($login_RolesID, array(5)) && ($login_ostan==19) )
                            $detailstr.= "\"onClick=\"alert('لطفا قبل از تغییر وضعیت طرح  منبع تامین اعتبار را وارد نمایید');  \"";
							
							else if (($applicantstatesID==23 /*|| $applicantstatesID==40*/) && $CntError>9 && ($login_opDisabled==8 || $login_opDisabled==11))
						   			$detailstr.= "\"onClick=\"window.open('../members_operatorpay.php', '_blank'); \"";
			
							else if (($applicantstatesID==23 || $applicantstatesID==40) && ($login_opDisabled==8 || $login_opDisabled==11))
								$detailstr.= "\"onClick=\"alert('اطلاعیه $CntError : $enrolmsgt'); return false; \"";
							
                            /*else if (!($row["belaavaz"]>0) && $login_RolesID==5)
                            echo "\"onClick=\"alert('لطفا قبل از تغییر وضعیت طرح مبلغ بلاعوض  را وارد نمایید'); return false; \"";
                            */
                            $detailstr.= "\"";
                            ?>	
                            <td><a href="<?php print "../insert/summaryinvoice.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).$detailstr; 
                            ?> >
                            <img style = "width: 25px;" src="../img/process.png" title=' بررسی ' ></a></td>
                            
                            <td><a href="<?php print "../insert/smalsummaryinvoice.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).$detailstr; 
                            ?> >
                            <img style = "width:15px;" src="../img/gear.png" title=' تغییر وضعیت سریع ' ></a></td>
                            
                        <?php
						if (in_array($login_RolesID, array(1,5)))
                        {
							print "<td class='no-print'><a target='".$target."' href='applicant_manageredit.php?uid=".rand(10000,99999).rand(10000,99999)
								.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
								.$row['ApplicantMasterID'].'_1_'.$row['DesignerCoID'].'_'.$row['operatorcoid'].rand(10000,99999).
								"'><img style = 'width: 20px;' src='../img/file-edit-icon.png' title=' ويرايش '></a></td>"; 
						}
						
                        if ( !($row['proposable']>0) && $row["ApplicantMasterIDmaster"]==0 && 
								( in_array($login_RolesID, array(13,14)) || ($login_RolesID==17 && $row['operatorcoid']>0 && $row["prjtypeid"]==0 && $row["DesignArea"]<10.99 ) )
							)
							print "<td class='no-print'><a target='".$target."' href='applicant_manageredit.php?uid=".rand(10000,99999).rand(10000,99999)
								.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
								.$row['ApplicantMasterID'].'_1_'.$row['DesignerCoID'].'_'.$row['operatorcoid'].rand(10000,99999).
								"'><img style = 'width: 20px;' src='../img/pipe.jpg' title=' ويرايش '></a></td>"; 
						else print "<td></td>";
   						
                        if (($row["operatorcoid"]>0 && $login_RolesID==10)||(in_array($login_RolesID, array(13,14,27,5,11))) )
                        {
                            print "<td><a href='../insert/manualcostlist_pluscostlist_list2.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$row['ApplicantMasterID'].'_1'.rand(10000,99999)."'>
                            <img style = 'width: 20px;' src='../img/fm.png' title=' فهرست بهای دستی آبیاری تحت فشار'></a></td>
                            
                            <td><a href='../insert/manualcostlist_pluscostlist_list2.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$row['ApplicantMasterID'].'_2'.rand(10000,99999)."'>
                            <img style = 'width: 20px;' src='../img/fs.png' title='  فهرست بهای آبیاری تحت فشار '></a></td>
                            <td><a href='../insert/foundation_list.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$row['ApplicantMasterID'].'_0_'.'سازه های'.'_1'.rand(10000,99999)."'>
                            <img style = 'width: 25px;' src='../img/saze.png' title=' سازه های طرح'></a></td>
                            <td><a href='../insert/foundation_list.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$row['ApplicantMasterID'].'_1_'.'کارهای قیمت جدید'.'_1'.rand(10000,99999)."'>
                            <img style = 'width: 25px;' src='../img/saze2.png' title=' آیتم های قیمت جدید'></a></td>";
                        }
                         print "</tr>";
    }
?>              
                    </tbody>                 
                </table>
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
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

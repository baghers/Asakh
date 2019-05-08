<?php 
/*
reorts/report_evaluationop.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php
require ('../includes/functions.php');

if ($login_Permission_granted==0) header("Location: ../login.php");
$showa='';
$joinstr=" inner join (SELECT distinct ApplicantMasterID FROM `applicanttiming` WHERE emtiaz>0) apptimed 
        on apptimed.ApplicantMasterID=applicantmaster.ApplicantMasterID ";
if ($_POST)
{
    $showa=$_POST['showa'];
    $operatorcoid=$_POST['operatorcoid'];
    
    if ($_POST['showa']=='on')
        $joinstr="";
        
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
    $cond1="";
    if (strlen(trim($_POST['corank']))>0)
        $cond1.=" and operatorco.corank='$_POST[corank]'";  
        
    if (strlen(trim($_POST['laststatedate']))>0)
        $cond1.=" and operatorco.copermisionvalidate='$_POST[laststatedate]'";
	
    
     if (strlen(trim($_POST['IDcnt']))>0)
		if (trim($_POST['IDcnt'])==1)
        $cond1.=" and apps.cnt>0 and apps.cnt<=5";
		else if (trim($_POST['IDcnt'])==2)
        $cond1.=" and apps.cnt>5 and apps.cnt<=10";
		else if (trim($_POST['IDcnt'])==3)
        $cond1.=" and apps.cnt>10 and apps.cnt<=20";
		else if (trim($_POST['IDcnt'])==4)
        $cond1.=" and apps.cnt>20 and apps.cnt<=100";
        
    if (strlen(trim($_POST['IDArea']))>0)
		if (trim($_POST['IDArea'])==1)
        $cond1.=" and apps.DesignArea>0 and apps.DesignArea<=10";
		else if (trim($_POST['IDArea'])==2)
        $cond1.=" and apps.DesignArea>10 and apps.DesignArea<=20";
		else if (trim($_POST['IDArea'])==3)
        $cond1.=" and apps.DesignArea>20 and apps.DesignArea<=50";
		else if (trim($_POST['IDArea'])==4)
        $cond1.=" and apps.DesignArea>50 and apps.DesignArea<=100";
		else if (trim($_POST['IDArea'])==5)
        $cond1.=" and apps.DesignArea>100 and apps.DesignArea<=200";
		else if (trim($_POST['IDArea'])==6)
        $cond1.=" and apps.DesignArea>200 and apps.DesignArea<=500";
		else if (trim($_POST['IDArea'])==7)
        $cond1.=" and apps.DesignArea>500 and apps.DesignArea<=1000";
		else if (trim($_POST['IDArea'])==8)
        $cond1.=" and apps.DesignArea>1000";
        
        
            if (trim($_POST['IDprice'])==-2)
        $cond1.=" and ifnull(apps.LastTotal,0)=0";
    else if (trim($_POST['IDprice'])==-1)
        $cond1.=" and ifnull(apps.LastTotal,0)>0";
    else if (strlen(trim($_POST['IDprice']))>0)	
        if (trim($_POST['IDprice'])==1)
		$cond1.=" and apps.LastTotal>0 and apps.LastTotal<=1000000000";
		else if (trim($_POST['IDprice'])==2)
		$cond1.=" and apps.LastTotal>1000000000 and apps.LastTotal<=1500000000";
		else if (trim($_POST['IDprice'])==3)
		$cond1.=" and apps.LastTotal>1500000000 and apps.LastTotal<=2000000000";
		else if (trim($_POST['IDprice'])==4)
		$cond1.=" and apps.LastTotal>2000000000 and apps.LastTotal<=3000000000";
		else if (trim($_POST['IDprice'])==5)
		$cond1.=" and apps.LastTotal>3000000000 and apps.LastTotal<=5000000000";
		else if (trim($_POST['IDprice'])==6)
		$cond1.=" and apps.LastTotal>5000000000 and apps.LastTotal<=8000000000";
		else if (trim($_POST['IDprice'])==7)
		$cond1.=" and apps.LastTotal>8000000000 and apps.LastTotal<=10000000000";
		else if (trim($_POST['IDprice'])==8)
		$cond1.=" and apps.LastTotal>10000000000";
        
        $emtiazstr="case (case applicanttiming.emtiaz>0 when 1 then 1 else 0 end+case applicanttiming.m_emtiaz>0 when 1 then 1 else 0 end+
            case applicanttiming2.emtiaz>0 when 1 then 1 else 0 end+case applicanttiming2.m_emtiaz>0 when 1 then 1 else 0 end)>0
            when 1 then round((ifnull(applicanttiming.emtiaz,0)+ifnull(applicanttiming.m_emtiaz,0)+
            ifnull(applicanttiming2.emtiaz,0)+ifnull(applicanttiming2.m_emtiaz,0))/
            (case applicanttiming.emtiaz>0 when 1 then 1 else 0 end+case applicanttiming.m_emtiaz>0 when 1 then 1 else 0 end+
            case applicanttiming2.emtiaz>0 when 1 then 1 else 0 end+case applicanttiming2.m_emtiaz>0 when 1 then 1 else 0 end),1)
            else 0 end";
            
	 if (strlen(trim($_POST['IDemtiaz']))>0)
		if (trim($_POST['IDemtiaz'])==1)
        $cond1.=" and $emtiazstr>0 and $emtiazstr<=50";
		else if (trim($_POST['IDemtiaz'])==2)
        $cond1.=" and $emtiazstr>50 and $emtiazstr<=60";
		else if (trim($_POST['IDemtiaz'])==3)
        $cond1.=" and $emtiazstr>60 and $emtiazstr<=70";
		else if (trim($_POST['IDemtiaz'])==4)
        $cond1.=" and $emtiazstr>70 and $emtiazstr<=80";
		else if (trim($_POST['IDemtiaz'])==5)
        $cond1.=" and $emtiazstr>80 and $emtiazstr<=90";
		else if (trim($_POST['IDemtiaz'])==6)
        $cond1.=" and $emtiazstr>90 and $emtiazstr<=100";


	 if (strlen(trim($_POST['IDemtiazna']))>0)
		if (trim($_POST['IDemtiazna'])==1)
        $cond1.=" and round(applicanttiming.m_emtiaz)>0 and round(applicanttiming.m_emtiaz)<=50";
		else if (trim($_POST['IDemtiazna'])==2)
        $cond1.=" and round(applicanttiming.m_emtiaz)>50 and round(applicanttiming.m_emtiaz)<=60";
		else if (trim($_POST['IDemtiazna'])==3)
        $cond1.=" and round(applicanttiming.m_emtiaz)>60 and round(applicanttiming.m_emtiaz)<=70";
		else if (trim($_POST['IDemtiazna'])==4)
        $cond1.=" and round(applicanttiming.m_emtiaz)>70 and round(applicanttiming.m_emtiaz)<=80";
		else if (trim($_POST['IDemtiazna'])==5)
        $cond1.=" and round(applicanttiming.m_emtiaz)>80 and round(applicanttiming.m_emtiaz)<=90";
		else if (trim($_POST['IDemtiazna'])==6)
        $cond1.=" and round(applicanttiming.m_emtiaz)>90 and round(applicanttiming.m_emtiaz)<=100";

	 if (strlen(trim($_POST['IDemtiaznm']))>0)
		if (trim($_POST['IDemtiaznm'])==1)
        $cond1.=" and round(applicanttiming2.emtiaz)>0 and round(applicanttiming2.emtiaz)<=50";
		else if (trim($_POST['IDemtiaznm'])==2)
        $cond1.=" and round(applicanttiming2.emtiaz)>50 and round(applicanttiming2.emtiaz)<=60";
		else if (trim($_POST['IDemtiaznm'])==3)
        $cond1.=" and round(applicanttiming2.emtiaz)>60 and round(applicanttiming2.emtiaz)<=70";
		else if (trim($_POST['IDemtiaznm'])==4)
        $cond1.=" and round(applicanttiming2.emtiaz)>70 and round(applicanttiming2.emtiaz)<=80";
		else if (trim($_POST['IDemtiaznm'])==5)
        $cond1.=" and round(applicanttiming2.emtiaz)>80 and round(applicanttiming2.emtiaz)<=90";
		else if (trim($_POST['IDemtiaznm'])==6)
        $cond1.=" and round(applicanttiming2.emtiaz)>90 and round(applicanttiming2.emtiaz)<=100";

	 if (strlen(trim($_POST['IDemtiazmo']))>0)
		if (trim($_POST['IDemtiazmo'])==1)
        $cond1.=" and round(applicanttiming.emtiaz)>0 and round(applicanttiming.emtiaz)<=50";
		else if (trim($_POST['IDemtiazmo'])==2)
        $cond1.=" and round(applicanttiming.emtiaz)>50 and round(applicanttiming.emtiaz)<=60";
		else if (trim($_POST['IDemtiazmo'])==3)
        $cond1.=" and round(applicanttiming.emtiaz)>60 and round(applicanttiming.emtiaz)<=70";
		else if (trim($_POST['IDemtiazmo'])==4)
        $cond1.=" and round(applicanttiming.emtiaz)>70 and round(applicanttiming.emtiaz)<=80";
		else if (trim($_POST['IDemtiazmo'])==5)
        $cond1.=" and round(applicanttiming.emtiaz)>80 and round(applicanttiming.emtiaz)<=90";
		else if (trim($_POST['IDemtiazmo'])==6)
        $cond1.=" and round(applicanttiming.emtiaz)>90 and round(applicanttiming.emtiaz)<=100";
                	
    if (strlen(trim($_POST['operatorcoid']))>0)
        $innerstr=" and operatorcoid='$_POST[operatorcoid]'";
    
        




		

             
}
     
  switch ($_POST['IDorder']) 
  {
	case 1: $orderby=' order by DesignArea'; break;
    case 2: $orderby=' order by operatorcotitle COLLATE utf8_persian_ci'; break;
    case 3: $orderby=' order by laststatedate '; break;
    case 4: $orderby=' order by corank,emtiaz'; break;
    case 5: $orderby=' order by LastTotal'; break;
    case 6: $orderby=' order by cnt,emtiaz'; break;
	case 7: $orderby=' order by emtiaz desc'; break;

	default: 
  	    $orderby=' order by emtiaz desc,cnt desc'; break; 
  }
  //$login_OperatorCoID=44;
    if ($login_OperatorCoID>0)
        $str.=" and operatorcoid='$login_OperatorCoID'";
 	
$selectedCityId=$login_CityId;
if ($_POST['ostan']>0)
        $selectedCityId=$_POST['ostan'];
 
    $sql = "select operatorco.operatorcoid
            ,operatorco.title operatorcotitle 
            ,operatorco.corank corank 
            ,operatorco.copermisionvalidate laststatedate,DesignArea,LastTotal,cnt
            
            ,case (case applicanttiming.emtiaz>0 when 1 then 1 else 0 end+case applicanttiming.m_emtiaz>0 when 1 then 1 else 0 end+
            case applicanttiming2.emtiaz>0 when 1 then 1 else 0 end+case applicanttiming2.m_emtiaz>0 when 1 then 1 else 0 end)>0
            when 1 then round((ifnull(applicanttiming.emtiaz,0)+ifnull(applicanttiming.m_emtiaz,0)+
            ifnull(applicanttiming2.emtiaz,0)+ifnull(applicanttiming2.m_emtiaz,0))/
            (case applicanttiming.emtiaz>0 when 1 then 1 else 0 end+case applicanttiming.m_emtiaz>0 when 1 then 1 else 0 end+
            case applicanttiming2.emtiaz>0 when 1 then 1 else 0 end+case applicanttiming2.m_emtiaz>0 when 1 then 1 else 0 end),1)
            else 0 end emtiaz
            ,round(applicanttiming.emtiaz) moshaver
            ,round(applicanttiming.m_emtiaz) nazerali
            ,round(applicanttiming2.emtiaz) nazer
            ,round(applicanttiming2.m_emtiaz) anjoman
        
            from operatorco 
            inner join (select operatorcoid,round(sum(DesignArea),1) DesignArea,sum(LastTotal) LastTotal,count(*) cnt
            from applicantmaster
            $joinstr
            inner join applicantmasterdetail on applicantmasterdetail.applicantmasteridmaster=applicantmaster.applicantmasterid
            where operatorcoid<>115 and operatorcoid<>108 and substring(cityid,1,2)=substring('$selectedCityId',1,2)
            $innerstr
            group by operatorcoid
             ) apps on apps.operatorcoid=operatorco.operatorcoid
                left outer join 
                (
                select applicantmaster.operatorcoid ,avg(applicanttiming.emtiaz) emtiaz,avg(em2.m_emtiaz) m_emtiaz from applicanttiming 
                inner join applicantmaster on applicantmaster.applicantmasterid=applicanttiming.applicantmasterid 
                left outer join (select applicantmaster.operatorcoid ,avg(applicanttiming.m_emtiaz) m_emtiaz from applicanttiming 
                inner join applicantmaster on applicantmaster.applicantmasterid=applicanttiming.applicantmasterid 
                where applicanttiming.RoleID='2' and applicanttiming.m_emtiaz>0 and applicantmaster.operatorcoid>0
                group by applicantmaster.operatorcoid) em2 on em2.operatorcoid=applicantmaster.operatorcoid
                where applicanttiming.RoleID='2' and applicanttiming.emtiaz>0 and applicantmaster.operatorcoid>0
                group by applicantmaster.operatorcoid
                ) applicanttiming2 on  
                applicanttiming2.operatorcoid=operatorco.operatorcoid
                left outer join (
                select applicantmaster.operatorcoid ,avg(applicanttiming.emtiaz) emtiaz,avg(em2.m_emtiaz) m_emtiaz from applicanttiming 
                inner join applicantmaster on applicantmaster.applicantmasterid=applicanttiming.applicantmasterid 
                left outer join (select applicantmaster.operatorcoid ,avg(applicanttiming.m_emtiaz) m_emtiaz from applicanttiming 
                inner join applicantmaster on applicantmaster.applicantmasterid=applicanttiming.applicantmasterid 
                where applicanttiming.RoleID='10' and applicanttiming.m_emtiaz>0 and applicantmaster.operatorcoid>0
                group by applicantmaster.operatorcoid) em2 on em2.operatorcoid=applicantmaster.operatorcoid
                where applicanttiming.RoleID='10' and applicanttiming.emtiaz>0 and applicantmaster.operatorcoid>0
                group by applicantmaster.operatorcoid
                ) applicanttiming on  
                applicanttiming.operatorcoid=operatorco.operatorcoid
             
             
            where 1=1 $cond1
            $orderby
            ";
	      //print $sql;
	
try 
    {		
        $result = mysql_query($sql.$login_limited);
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }

//exit;

  
    $ID4[' ']=' ';
    $ID5[' ']=' ';
    $ID6[' ']=' ';
    $ID7[' ']=' ';
  	
$dasrow=0;
while($row = mysql_fetch_assoc($result))
{
    $dasrow=1;
    $ID4[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);
    $ID5[trim($row['corank'])]=trim($row['corank']);
    $ID6[trim($row['laststatedate'])]=trim($row['laststatedate']);
     
}

$ID4=mykeyvalsort($ID4);
$ID5=mykeyvalsort($ID5);
$ID6=mykeyvalsort($ID6);

if ($dasrow)
mysql_data_seek( $result, 0 );



$query="
select 'مساحت' _key,1 as _value union all
select 'شرکت مجری' _key,2 as _value union all
select 'اعتبار' _key,3 as _value union all
select 'پایه' _key,4 as _value union all
select 'مبلغ' _key,5 as _value union all
select 'تعداد' _key,6 as _value union all
select 'ارزشیابی' _key,7 as _value ";

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
select '0-50' _key,1 as _value union all 
select '50-60' _key,2 as _value union all
select '60-70' _key,3 as _value union all
select '70-80' _key,4 as _value union all
select '80-90' _key,5 as _value union all
select '90-100' _key,6 as _value";
$IDemtiaz = get_key_value_from_query_into_array($query);
if ($_POST['IDemtiaz']>0)
    $IDemtiazval=$_POST['IDemtiaz'];

if ($_POST['IDemtiazna']>0)
    $IDemtiazvalna=$_POST['IDemtiazna'];
    
if ($_POST['IDemtiaznm']>0)
    $IDemtiazvalnm=$_POST['IDemtiaznm'];
if ($_POST['IDemtiazmo']>0)
    $IDemtiazvalmo=$_POST['IDemtiazmo'];
     
 $query="
select '0-5' _key,1 as _value union all 
select '5-10' _key,2 as _value union all
select '10-20' _key,3 as _value union all
select '>20' _key,6 as _value";
$IDcnt = get_key_value_from_query_into_array($query);
if ($_POST['IDcnt']>0)
    $IDcntval=$_POST['IDcnt'];
 
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
	$hide='hide-row';	
	if ($login_designerCO==1)
	$hide='';
  								
?>

<!DOCTYPE html>
<html>
<head>
  	<title>ارزشیابی</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
   <style>
.hide-row { display:none; }
  <style>

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
			<div id="content">
            
            <form action="report_evaluationop.php" method="post">
                <table colspan="12" align="center">
                    <tbody class='no-print' >
                         <tr class="<?php echo $hide;?>">
                            <?php 
									if ($login_designerCO==1)
									 {
										$sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM tax_tbcity7digit ostan
										where substring(ostan.id,3,5)='00000'
										order by _key  COLLATE utf8_persian_ci";
										$allg1idostan = get_key_value_from_query_into_array($sqlselect);
										print select_option('ostan','',',',$allg1idostan,0,'','','1','rtl',0,'',$selectedCityId,'','75');
									 }
			                
							print select_option('IDorder','ترتیب',',',$IDorder,0,'','','3','rtl',0,'',$IDorderval,"",'100');?> 
                      <?php  
                          print "<td colspan='1' class='label'>همه</td>
								<td class='data'><input name='showa' type='checkbox' id='showa'";
							if ($showa=='on') echo 'checked';
							print " /></td>";
                      ?>
                       
                      <td colspan="1"><input    name="submit" type="submit" class="button" id="submit" size="16" value="جستجو" /></td>
                    </tr>
			   
                   </tbody>
                                     
                </table>
                 
                <table align='center' border='1' id="table2">              
                   <thead>
						<tr> 
                            <td colspan="12"
                            <span class="f14_fontb" >ارزشیابی پروژه های تمام شده شرکتهای پیمانکار</span>  </td>
                            <th colspan="1"  class="f14_fontc">&nbsp;&nbsp; </th>
						</tr>
                   
                        <tr>
                            <th <span class="f9_fontb" > رديف  </span> </th>
							<th <span class="f13_fontb">شركت مجری</span> </th>
							<th <span class="f14_fontb"> پایه </span>
							<th <span class="f14_fontb"> اعتبار مجوز </span>
							<th <span class="f13_fontb"> تعداد </span>
							<th <span class="f11_fontb"> مساحت </span> (ha)  </th>
							<th <span class="f14_fontb"> مبلغ کل </span>
							<th <span class="f13_fontb">ارزشیابی ناظرعالی</span> </th>
							<th <span class="f13_fontb">ارزشیابی ناظر مقیم</span> </th>
							<th <span class="f13_fontb">ارزشیابی مشاور</span> </th>
							<th <span class="f13_fontb">میانگین ارزشیابی</span> </th>
							<th <span class="f13_fontb">عدم صلاحیت</span> </th>
					     </tr>
                     </thead> 
                           <tr  class='no-print'>    
							<td class="f14_font"></td>
								<?php print select_option('operatorcoid','',',',$ID4,0,'','','1','rtl',0,'',$operatorcoid,'','100%') ?> 
								<?php print select_option('corank','',',',$ID5,0,'','','1','rtl',0,'',$rank,'','100%') ?> 
								<?php print select_option('laststatedate','',',',$ID6,0,'','','1','rtl',0,'',$laststatedate,'','100%') ?> 
								<?php print select_option('IDcnt','',',',$IDcnt,0,'','','1','rtl',0,'',$IDcntval,'','100%') ?> 
								<?php print select_option('IDArea','',',',$IDArea,0,'','','1','rtl',0,'',$IDAreaval,'','100%'); ?>
								<?php print select_option('IDprice','',',',$IDprice,0,'','','1','rtl',0,'',$IDpriceval,'','100%'); ?>  
								<?php print select_option('IDemtiazna','',',',$IDemtiaz,0,'','','1','rtl',0,'',$IDemtiazvalna,'','100%'); ?> 
								<?php print select_option('IDemtiaznm','',',',$IDemtiaz,0,'','','1','rtl',0,'',$IDemtiazvalnm,'','100%'); ?> 
								<?php print select_option('IDemtiazmo','',',',$IDemtiaz,0,'','','1','rtl',0,'',$IDemtiazvalmo,'','100%'); ?> 
								<?php print select_option('IDemtiaz','',',',$IDemtiaz,0,'','','1','rtl',0,'',$IDemtiazval,'','100%'); ?> 
							<td class="f14_font"></td>
							</tr> 
                     
                   <?php
                   $sumDA=0;
                   $emtiazAvg=0;
				   $emtiazcnt=0;
                   $sumM=0;
                   $rown=0;
                   while($row = mysql_fetch_assoc($result)){

               			if($row['emtiaz']) {$emtiazcnt++;$emtiazAvg+=$row['emtiaz'];}
						$sumDA+=$row['DesignArea'];
                         $sumL=$row['LastTotal'];
                        $sumM+=$sumL ;
						$sumcnt+=$row['cnt'] ;
						$rown++;
                        if ($rown%2==1) 
                        $b='b'; else $b='';
   					
						$cl='000000';
						if (compelete_date($row["laststatedate"])<gregorian_to_jalali(date('Y-m-d'))) $cl='ff00ff';
?>                      
                        <tr>    

                            <td
                            <span class="f12_font<?php echo $b; ?>" style="color:#<?php echo $cl; ?>"; >  <?php echo $rown; ?> </span>  </td>
							<td
							<span class="f10_font<?php echo $b; ?>" style="color:#<?php echo $cl; ?>";>  <?php echo  $row['operatorcotitle']; ?> </span> </td>
							<td
							<span class="f10_font<?php echo $b; ?>" style="color:#<?php echo $cl; ?>";>  <?php echo  $row['corank']; ?> </span> </td>
							<td
							<span class="f10_font<?php echo $b; ?>" style="color:#<?php echo $cl; ?>";>  <?php echo  $row['laststatedate']; ?> </span> </td>
							<td
							<span class="f10_font<?php echo $b; ?>" style="color:#<?php echo $cl; ?>";>   <?php echo $row['cnt']; ?> </span> </td>
                            <td
							<span class="f12_font<?php echo $b; ?>" style="color:#<?php echo $cl; ?>";>  <?php echo $row['DesignArea']; ?> </span> </td>
                            <td
							<span class="f12_font<?php echo $b; ?>" style="color:#<?php echo $cl; ?>";>  <?php echo floor($sumL/100000)/10; ?> </span> </td>
                            <td
							<span class="f12_font<?php echo $b; ?>" style="color:#<?php echo $cl; ?>";>  <?php echo $row['nazerali']; ?> </span> </td>
                           <td
							<span class="f12_font<?php echo $b; ?>" style="color:#<?php echo $cl; ?>";>  <?php echo $row['nazer']; ?> </span> </td>
                           <td
							<span class="f12_font<?php echo $b; ?>" style="color:#<?php echo $cl; ?>";>  <?php echo $row['moshaver']; ?> </span> </td>
                           <td
							<span class="f12_font<?php echo $b; ?>" style="color:#<?php echo $cl; ?>";>  <?php echo $row['emtiaz']; ?> </span> </td>
                           <td
							<span class="f12_font<?php echo $b; ?>" style="color:#<?php echo $cl; ?>";>  <?php echo ''; ?> </span> </td>
       				
						<?php 
                        if ($showa=='on')
                        echo '<td></td>';
                        else
                        {
                            if ($row['ApproveA']>0)
                                $imgt='searchPg.png';
                              else 
                                 $imgt='search.png';
                        
                            print "<td class='no-print'><a  target='$target' href='../appinvestigation/allapplicantstatesoplist.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['operatorcoid']."_on_".rand(10000,99999).
                            "'>
                            <img style = 'width: 25px;' src='../img/$imgt' title=' ريز '></a></td>"; 
                            
                        }
                            
					}
					?>
                        <tr>
                            <td colspan="8" class="f14_fontcb" ><?php echo ' مجموع مساحت (هكتار)';   ?></td>
                            <td colspan="4"
                            class="f14_fontcb" 
                            ><?php echo $sumDA;   ?></td>
                        </tr>
                        <tr>
                            <td colspan="8" class="f14_fontcb" ><?php echo ' مجموع مبلغ کل (ميليون ريال)';   ?></td>
                            <td colspan="4" 
                            class="f14_fontcb" 
                            ><?php echo round(($sumM/1000000),1);   ?></td>
                        </tr>
                         <tr>
                            <td colspan="8" class="f14_fontcb" ><?php echo 'مجموع تعداد پروژه';   ?></td>
                            <td colspan="4" 
                            class="f14_fontcb" 
                            ><?php echo $sumcnt;   ?></td>
                        </tr> 
                     <tr>
                            <td colspan="8" class="f14_fontcb" ><?php echo 'میانگین ارزشیابی';   ?></td>
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

<?php 
/*

//appinvestigation/allapplicantquotaaggregated.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود
/reports/aaapplicantfree.php
-
*/

include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/functions.php');

if ($login_Permission_granted==0) header("Location: ../login.php");
//شناسه وضعیت طرح های در وضعیت طراحی
$indesignstates=array("2","3","4","5","6","7","11","24","25");
$showc=0;//نمایش طرح های با اعتبار بانک یا صندوق
$selectedCityId=$login_CityId;//شناسه شهر کاربر لاگین نموده
if ($_POST['ostan']>0)//شناسه استان
$selectedCityId=$_POST['ostan'];
//نمایش طرح های شهرستان انتخاب شده
$cond="and substring(applicantmaster.cityid,1,2)=substring($selectedCityId,1,2)";

$ds65=0.67;//محاسبه بلاعوض با ضریب بالا
$ds25=0.33;//محاسبه بلاعوض با ضریب بالاسری
if ($_POST['showb']=='on') //بلاعوض بدون در نظر گرفتن نوع سیستم
  {$showb=1;$ds65=0;$ds25=0;}//ضرایب محاسبه بلاعوض
 switch ($_POST['IDorder']) 
  {
    case 1: $orderby=' order by CityName COLLATE utf8_persian_ci'; break;//مرتب بر اساس شهر 
    case 2: $orderby=' order by cityquota.val  desc'; break;//مرتب بر اساس سهمیه
    case 3: $orderby=' order by CAST(pr.pr AS DECIMAL(10,6)) desc'; break;//مرتب بر اساس پروژه 
    default: $orderby=' order by CAST(pr.pr AS DECIMAL(10,6)) desc'; break;//مرتب بر اساس پروژه 
  }
if ($_POST['showc']=='on')//اعتبار بانک یا صندوق
    $showc=1;
if ($showc==1) $cond.=" and ifnull(applicantmaster.criditType,0)=1 ";//اعتبار بانکی

$yearid=-1;//سال نمایش پروژه ها
if ($_POST['YearID']!=-1)
{
    if ($_POST['YearID']>0)
        $yearid=$_POST['YearID'];
        else $yearid=14;    
}
      
if ($yearid>0) $cond.="and applicantmaster.YearID='$yearid'";//فیلتر سال پروژه 

if ($_POST['DesignSystemGroupsID']>0)//سیستم آبیاری تحت فشار
{
    $DesignSystemGroupsID=$_POST['DesignSystemGroupsID'];
    $cond.="and applicantmaster.DesignSystemGroupsID=$DesignSystemGroupsID ";//فیلتر سیستم آبیاری تحت فشار
}

$creditsourceID=$_POST['creditsourceID'];//شناسه منبع تامین اعتبار
if ($creditsourceID>0) $cond.="and applicantmaster.creditsourceID='$creditsourceID'";//فیلتر شناسه منبع تامین اعتبار 


$credityear=$_POST['credityear'];//سال اعتبار
if ($credityear>0) $cond.="and creditsource.credityear='$credityear'"; //فیلتر سال اعتبار

try 
    {		
        $result = mysql_query(retqueryaggregated($cond));
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }

while($row = mysql_fetch_assoc($result))
{   
    $totalvals[$row['CityId']][0]+=$row['progress'];//پیشرفت پروژه بر اساس وضعیت
    $hekvals[$row['CityId']][0]+=$row['DesignSystemGroupsID1'];//هکتار
    $belavals[$row['CityId']][0]+=$row['DesignSystemGroupsID3'];//بلاعوض
    if ($row['DesignSystemGroupsID3']>0)
    {
        $totalvals[$row['CityId']][51]+=$row['DesignSystemGroupsID3'];//مبلغ کل
        $hekvals[$row['CityId']][51]+=$row['DesignAread'];//هکتار
    }
    $totalvals[$row['CityId']][1]++;
    $hekvals[$row['CityId']][1]+=$row['DesignAread'];//هکتار
    $belavals[$row['CityId']][1]+=$row['belaavazd'];//بلاعوض
    if (in_array($row['applicantstatesIDd'],$indesignstates))//مرحله طراحی
    {
        $totalvals[$row['CityId']][2]++;//تعداد
        $hekvals[$row['CityId']][2]+=$row['DesignAread'];//هکتار
        $belavals[$row['CityId']][2]+=$row['belaavazd'];//بلاعوض
    }
    else  if (in_array($row['applicantstatesIDd'],array("12","36","22","37")))//ارسال به صندوق یا بانک
    {
        $totalvals[$row['CityId']][4]++;//تعداد
        $hekvals[$row['CityId']][4]+=$row['DesignAread'];//هکتار
        $belavals[$row['CityId']][4]+=$row['belaavazd'];
        if (in_array($row['applicantstatesIDd'],array("12","36")))//تکمیل تضامین
        {
            $totalvals[$row['CityId']][5]++;//تعداد
            $hekvals[$row['CityId']][5]+=$row['DesignAread'];//هکتار
            $belavals[$row['CityId']][5]+=$row['belaavazd'];//بلاعوض   
        }
        else //انعقاد قرارداد
        {
            $totalvals[$row['CityId']][6]++;//تعداد
            $hekvals[$row['CityId']][6]+=$row['DesignAread'];//هکتار
            $belavals[$row['CityId']][6]+=$row['belaavazd'];//بلاعوض    
            if(!($row['applicantstatesIDop']>0))//درحال پیشنهاد قیمت
            {
                $totalvals[$row['CityId']][7]++;//تعداد
                $hekvals[$row['CityId']][7]+=$row['DesignAread'];//هکتار
                $belavals[$row['CityId']][7]+=$row['belaavazd'];//بلاعوض
            }       
            else if (in_array($row['applicantstatesIDop'],array("30","35","38")))//تایید نهایی پیشفاکتور و آزادسازی 
            {
                $totalvals[$row['CityId']][9]++;//تعداد
                $hekvals[$row['CityId']][9]+=$row['DesignAreaop'];//هکتار
                $belavals[$row['CityId']][9]+=$row['belaavazop'];//بلاعوض   
                
                if ($row['permanentfree']==1 && $row['applicantstatesIDoplist']==45)//ـحویل دائم
                {
                    $totalvals[$row['CityId']][13]++;//تعداد
                    $hekvals[$row['CityId']][13]+=$row['DesignAreaoplist'];//هکتار
                    $belavals[$row['CityId']][13]+=$row['belaavazoplist'];//بلاعوض   
                }
                else if ($row['applicantstatesIDoplist']==45)//ـحویل موقت
                {
                    $totalvals[$row['CityId']][12]++;//تعداد
                    $hekvals[$row['CityId']][12]+=$row['DesignAreaoplist'];//هکتار
                    $belavals[$row['CityId']][12]+=$row['belaavazoplist'];//بلاعوض   
                }
                else if ($row['applicantstatesIDop']==35)//آزادسازی ظرفیت
                {
                    $totalvals[$row['CityId']][11]++;//تعداد
                    $hekvals[$row['CityId']][11]+=$row['DesignAreaoplist'];//هکتار
                    $belavals[$row['CityId']][11]+=$row['belaavazoplist'];//بلاعوض   
                }
                else //درحال اجرا
                {
                    $totalvals[$row['CityId']][10]++;//تعداد
                    $hekvals[$row['CityId']][10]+=$row['DesignAreaop'];//هکتار
                    $belavals[$row['CityId']][10]+=$row['belaavazop'];//بلاعوض   
                }
                
            }
            else if ($row['applicantstatesIDop']==34)//انصراف از اجرا
            {
                $totalvals[$row['CityId']][14]++;//تعداد
                $hekvals[$row['CityId']][14]+=$row['DesignAreaop'];//هکتار
                $belavals[$row['CityId']][14]+=$row['belaavazop'];//بلاعوض
                
            }
                
            else//تهیه پیش فاکتور
            {
                $totalvals[$row['CityId']][8]++;//تعداد
                $hekvals[$row['CityId']][8]+=$row['DesignAread'];//هکتار
                $belavals[$row['CityId']][8]+=$row['belaavazd'];//بلاعوض
            }  
        }
    }
    else//تکمیل پرونده
    {
        $totalvals[$row['CityId']][3]++;//تعداد
        $hekvals[$row['CityId']][3]+=$row['DesignAread'];//هکتار
        $belavals[$row['CityId']][3]+=$row['belaavazd'];//بلاعوض
    }    
}
if ($yearid>0)//انتخاب سال
{
    /*
        CityId شناسه شهر
        cityquota جدول سهمیه شهرستان ها
        val مقدار سهمیه
        val2 مقدار سهمیه افزایشی
        ClerkIDExcellentSupervisor شناسه کاربر ناظر عالی مربوطه شهرستان
        valnum تعداد طرح سهمیه شهرستان
        valnum2 تعداد ثانویه طرح سهمیه شهرستان
        year جدول سالها
        value مقدار سهمیه سالانه
        YearID شناسه شهر
        tax_tbcity7digit جدول شهرها
        id شناسه شهر
    */
    $quin=" select '-1' CityId,'-1' pr ";
    $sql = "SELECT substring(cityquota.CityId,1,4) CityId,cityquota.val,cityquota.val2,ClerkIDExcellentSupervisor,cityquota.valnum
    ,cityquota.valnum2,CityName,year.value  FROM cityquota 
    left outer join year on year.YearID='$yearid'
    left outer join tax_tbcity7digit on substring(tax_tbcity7digit.id,1,4)=substring(cityquota.cityid,1,4) and 
    substring(tax_tbcity7digit.id,5,3)='000' and substring(tax_tbcity7digit.id,1,2)=substring('$selectedCityId',1,2) 
    and substring(tax_tbcity7digit.id,3,5)<>'00000'
    where cityquota.YearID='$yearid' and substring(cityquota.CityId,1,4)=substring(tax_tbcity7digit.id,1,4) ";    
    try 
        {		
            $result = mysql_query($sql); 
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }        
    while($row = mysql_fetch_assoc($result))
    {   
        /*
        CityId شناسه شهر
        val مقدار سهمیه
        valnum تعداد طرح سهمیه شهرستان
        val2 مقدار سهمیه افزایشی
        valnum2 تعداد ثانویه طرح سهمیه شهرستان
        */
        $totalvals[$row['CityId']][50]=  round(($hekvals[$row['CityId']][1]* ($totalvals[$row['CityId']][0]/$totalvals[$row['CityId']][1]) )*($totalvals[$row['CityId']][1]-$hekvals[$row['CityId']][0]*$ds25-$belavals[$row['CityId']][0]*$ds65)/($row['val']*$row['valnum']),1);
        $totalvals[$row['CityId']][49]=$row['CityName'];
        $totalvals[$row['CityId']][48]=$row['valnum']."<br>".$row['valnum2'];
        $totalvals[$row['CityId']][47]=$row['val']."<br>".$row['val2'];
        $quin.=" union all select '$row[CityId]' CityId,".$totalvals[$row['CityId']][50]." pr ";
    }
    /*
        CityId شناسه شهر
        cityquota جدول سهمیه شهرستان ها
        YearID شناسه شهر
        tax_tbcity7digit جدول شهرها
        id شناسه شهر
    */
    $sql = "SELECT substring(cityquota.CityId,1,4) CityId,CityName,pr.pr  FROM cityquota 
                       inner join ($quin) pr on pr.CityId=substring(cityquota.CityId,1,4)
                       left outer join tax_tbcity7digit on substring(tax_tbcity7digit.id,1,4)=substring(cityquota.cityid,1,4) and 
                        substring(tax_tbcity7digit.id,5,3)='000' and substring(tax_tbcity7digit.id,1,2)=substring('$selectedCityId',1,2) 
                        and substring(tax_tbcity7digit.id,3,5)<>'00000'
                        
                        where cityquota.YearID='$yearid'
                        $orderby ";
    try 
        {		
            $result = mysql_query($sql.$login_limited); 
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
          
    
}
else
{
    /*
        CityId شناسه شهر
        cityquota جدول سهمیه شهرستان ها
        val مقدار سهمیه
        val2 مقدار سهمیه افزایشی
        ClerkIDExcellentSupervisor شناسه کاربر ناظر عالی مربوطه شهرستان
        valnum تعداد طرح سهمیه شهرستان
        valnum2 تعداد ثانویه طرح سهمیه شهرستان
        year جدول سالها
        value مقدار سهمیه سالانه
        YearID شناسه شهر
        tax_tbcity7digit جدول شهرها
        id شناسه شهر
    */
    $quin=" select '-1' CityId,'-1' pr ";
    $sql = "SELECT substring(tax_tbcity7digit.id,1,4) CityId,0 val,0 val2
                       ,ClerkIDExcellentSupervisor,0 valnum,0 valnum2,CityName,-1 value  FROM tax_tbcity7digit 
                       where  substring(tax_tbcity7digit.id,5,3)='000' and substring(tax_tbcity7digit.id,1,2)=substring('$selectedCityId',1,2) 
                        and substring(tax_tbcity7digit.id,3,5)<>'00000' ";
                        
                        
    $result = mysql_query($sql);
    $strallval="";
    while($row = mysql_fetch_assoc($result))
    {   
        /*
        CityId شناسه شهر
        val مقدار سهمیه
        valnum تعداد طرح سهمیه شهرستان
        val2 مقدار سهمیه افزایشی
        valnum2 تعداد ثانویه طرح سهمیه شهرستان
        */
        $totalvals[$row['CityId']][50]=  round(($hekvals[$row['CityId']][1]* ($totalvals[$row['CityId']][0]/$totalvals[$row['CityId']][1]) )*($totalvals[$row['CityId']][1]-$hekvals[$row['CityId']][0]*$ds25-$belavals[$row['CityId']][0]*$ds65)/($row['val']*$row['valnum']),1);
        $totalvals[$row['CityId']][49]=$row['CityName'];
        $totalvals[$row['CityId']][48]=$row['valnum']."<br>".$row['valnum2'];
        $totalvals[$row['CityId']][47]=$row['val']."<br>".$row['val2'];
        $quin.=" union all select '$row[CityId]' CityId,".$totalvals[$row['CityId']][50]." pr ";
        $strallval.="$row[CityName]_".round($row['pr'],1)."_";
    }
                
}
                        
                    


?>



<!DOCTYPE html>
<html>
<head>
  	<title>گزارش کامل عملکرد</title>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
		
		<link rel="stylesheet" href="../assets/style.css" type="text/css" />
		<link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
		<script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
		<script type="text/javascript" src="../js/persiandatepicker.js"></script>

    <!-- /scripts -->
    
  	<style>
		td.rowtable {
		text-align:left; height:20px; vertical-align:middle;
		border:0px solid blue	;
		}
				td.rowtableR {
		text-align:center; height:20px; vertical-align:middle;
		border:0px solid blue	;
		}

	</style>


  
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
	
           <form action="chart_applicantAggregate.php" method="post">
     <?php print "<td class='data'><input name='strallval' type='hidden' class='textbox' id='strallval'  value='$strallval'  /></td>";?> 
	 <td colspan="2"  > <input style = "text-align:center;font-size:12;line-height:75%;font-weight: bold;font-family:'B Nazanin'; name="submit" type="submit" id="submit" value="نمودار" /></td>
	 </form>
     
            <form action="allapplicantquotaaggregated.php" method="post">
                <table width="95%" align="center">
                 <tbody class='no-print' >
                    <tr>
                        <?php
						 $query="
                         SELECT -1 as _value,'همه' as _key 
                         union all
                         SELECT YearID as _value,Value as _key FROM `year` 
						 where YearID in (select YearID from cityquota)
						 ORDER BY _key DESC";
						 $ID = get_key_value_from_query_into_array($query);
						 
						 
						 print 
							select_option('YearID','سهمیه',',',$ID,0,'','','1','rtl',0,'',$yearid,'','75');
                            
                            
    				   if ($login_designerCO==1)
                       {
                         $sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM tax_tbcity7digit ostan
                         where substring(ostan.id,3,5)='00000'
                         order by _key  COLLATE utf8_persian_ci";
                         $allg1id = get_key_value_from_query_into_array($sqlselect);
    			         print select_option('ostan','استان',',',$allg1id,0,'','','4','rtl',0,'',$selectedCityId);
                       }
                       
                            
                            $query="
                            select 'شهرستان' _key,1 as _value union all
                            select 'سهمیه' _key,2 as _value union all 
                            select 'پیشرفت' _key,3 as _value  ";
                            $IDorder = get_key_value_from_query_into_array($query);
                            
                            if (!$_POST['IDorder'])
                                $IDorderval=3;
                            else $IDorderval=$_POST['IDorder'];

                        print select_option('IDorder','ترتیب',',',$IDorder,0,'','','3','rtl',0,'',$IDorderval,"",'100');
						
						 $query="SELECT creditsourceID as _value,Title as _key FROM `creditsource`";
						 $ID1 = get_key_value_from_query_into_array($query);
                         
						 $query="SELECT credityear as _value,credityear as _key FROM `creditsource` order by credityear desc";
						 $ID2 = get_key_value_from_query_into_array($query);
                         
                        print  
							select_option('creditsourceID','اعتبارات',',',$ID1,0,'','','1','rtl',0,'',$creditsourceID).
							select_option('credityear','سال',',',$ID2,0,'','','1','rtl',0,'',$credityear);
                            
                            
                            if ($login_RolesID==1 || $login_RolesID==13){ 
							print "<td colspan='6' class='label'></td>
                                <td class='data'>بدون در نظر گرفتن نوع سیستم<input name='showb' type='checkbox' id='showb'";
                                if ($showb>0) echo 'checked';
                                 print " /></td>";
                				 }
                                 
  					$checked="";
                    if ($showc>0) $checked="checked";
                    print "<td colspan='1' class='label'>تجمیع</td>
                     <td class='data'><input name='showc' type='checkbox' id='showc' $checked /></td>";
                      $query="select DesignSystemGroupsID _value,Title _key from designsystemgroups 
								order by _key  COLLATE utf8_persian_ci";
								$IDs = get_key_value_from_query_into_array($query);
		
                   print select_option('DesignSystemGroupsID','سیستم',',',$IDs,0,'','','1','rtl',0,'',$DesignSystemGroupsID,'','50');
                                 				 
						 ?>
				      <td colspan="2"></td> 
					 
                
					  
					  
                      <td><input   name="submit" type="submit" class="button" id="submit" size="16" value="جستجو" /></td>
                   </tr>
			     </tbody>
				</table>
                

				<tbody>
                 <table align='center' border='1' id="table2">              
                  <thead>
				   <tr> 
                            <td colspan="23"
                            <span class="f14_fontcb" >  گزارش عملکرد طرح های سامانه های نوین آبیاری  سال  <?php print $yearvalue."(مبالغ به میلیون ریال) <br>".$login_CityName;?></span>  </td>
                   </tr>
                   <tr>
                   <?php 
                       echo "
                        <tr>
                            <th rowspan=\"3\"class=\"f10_fontb\" >ردیف</th>
                            <th rowspan=\"3\"class=\"f13_fontb\">شهرستان</th>
                            <th  rowspan=\"2\"colspan=\"2\"class=\"f13_fontb\" >سهمیه</th>
	    					<th  rowspan=\"2\"colspan=\"2\"class=\"f13_fontb\" > طرح های مطالعاتی</th>
  	                                            
                            <th colspan=\"4\" class=\"f13_fontb\" >طرح های مطالعاتی $darsadm%</th>
                            <th colspan=\"4\" class=\"f13_fontb\" >ارسال به صندوق/بانک $darsads%</th>
							<th colspan=\"8\" class=\"f13_fontb\" > طرح های اجرایی (عقد قرارداد) $dardade%</th>
							<th rowspan=\"2\" class=\"f13_fontb\" >پيشرفت پروژه </th>
		                </tr>
                        <tr>
                            <th  class=\"f13_fontb\" > دردست طراحی</th>
                            <th  class=\"f13_fontb\" >تکمیل پرونده</th>
							<th  colspan=\"2\" class=\"f13_fontb\" > ارسال به صندوق/بانک	</th>
							<th  colspan=\"2\" class=\"f13_fontb\" > تکمیل تضامین	</th>
							<th  colspan=\"2\" class=\"f13_fontb\" >عقد قرارداد	</th>
							<th  colspan=\"2\" class=\"f13_fontb\" >انتخاب مجری	</th>
							<th  colspan=\"2\" class=\"f13_fontb\" >دردست اجرا	</th>
							<th  colspan=\"2\" class=\"f13_fontb\" >اجرا شده	</th>
							<th  colspan=\"2\" class=\"f13_fontb\" >اختلاف	</th>
							
                        </tr>
		  			    <tr>
							<th class=\"f10_fontb\">تعداد</th>
                            <th class=\"f10_fontb\" >سطح (ha)</th>
                            <th class=\"f10_fontb\">تعداد</th>
                            <th class=\"f10_fontb\" >سطح (ha)</th>
                            <th class=\"f10_fontb\" >سطح (ha)</th>
                             <th class=\"f10_fontb\" >سطح (ha)</th>
                            <th class=\"f10_fontb\" >سطح  (ha)</th>
                            <th class=\"f10_fontb\" >بلاعوض (&nbsp;م&nbsp;ر&nbsp;)</th>
                            <th class=\"f10_fontb\" >سطح  (ha)</th>
                            <th class=\"f10_fontb\" >بلاعوض (&nbsp;م&nbsp;ر&nbsp;)</th>
                            <th class=\"f10_fontb\" >سطح  (ha)</th>
                            <th class=\"f10_fontb\" >بلاعوض (&nbsp;م&nbsp;ر&nbsp;)</th>
                            <th class=\"f10_fontb\" >سطح  (ha)</th>
                            <th class=\"f10_fontb\" >بلاعوض (&nbsp;م&nbsp;ر&nbsp;)</th>
                            <th class=\"f10_fontb\" >سطح  (ha)</th>
                            <th class=\"f10_fontb\" >بلاعوض (&nbsp;م&nbsp;ر&nbsp;)</th>
							<th class=\"f10_fontb\" >سطح  (ha)</th>
                            <th class=\"f10_fontb\" >بلاعوض (&nbsp;م&nbsp;ر&nbsp;)</th>
							<th class=\"f10_fontb\" >سطح  (ha)</th>
                            <th class=\"f10_fontb\" >بلاعوض (&nbsp;م&nbsp;ر&nbsp;)</th>
						    <th class=\"f10_fontb\" >درصد</th>
		                 </tr>";
                         ?>
		                </tr>
                      </thead> 
                       
           <?php
                   $rown=0;
                   $sum1=0;
                   $sum2=0;
                   $sum3=0;
                   $sum4=0;
                   $sum5=0;
                   $sum6=0;
                   $sum7=0;
                   $sum8=0;
                   $sum9=0;
                   $sum10=0;
                   $sum11=0;
                   $sum12=0;
                   $sum13=0;
                   $sum14=0;
                   $sum15=0;
				   $sum16=0;
                   $sum17=0;
                   $sum18=0;
                   $sum19=0;
                   $sum20=0;
				   $sum21=0;
                   
                  // print $quin;
                   
                   
                    
                    while($row = mysql_fetch_assoc($result)){     
				        //print  $rown;              
                    //$row['value']
                        //print $row['CityId']."--".substr($login_CityId,0,4);
                        if ($login_RolesID=='17' && $row['CityId']<>substr($selectedCityId,0,4) ) 
						continue;
                        
						if ($login_RolesID=='14' && $row['ClerkIDExcellentSupervisor']<>$login_userid ) 
						continue;
                       
                	          $rown++;
                        if ($rown%2==1) 
                        $b=''; else $b='b';
                       
                       
               ?>                      
                    <tr>    
                         <td <span class="f10_font<?php echo $b; ?>"  >  <?php echo $rown; ?> </span>  </td>
						 <td <span class="f12_font<?php echo $b; ?>">  <?php echo $totalvals[$row['CityId']][49]; ?> </span> </td>
                         <td <span class="f12_font<?php echo $b; ?>">  <?php $sum21+=$totalvals[$row['CityId']][48]; echo $totalvals[$row['CityId']][48];  ?> </span> </td>
                         <td <span class="f12_font<?php echo $b; ?>">  <?php $sum1+=$totalvals[$row['CityId']][47]; echo $totalvals[$row['CityId']][47];  ?> </span> </td>
                         <td <span class="f12_font<?php echo $b; ?>">  <?php $sum2+=$totalvals[$row['CityId']][1]; echo ($totalvals[$row['CityId']][1]-$totalvals[$row['CityId']][51]); echo "<br>".$totalvals[$row['CityId']][51];  ?> </span> </td>
                         <td <span class="f12_font<?php echo $b; ?>">  <?php 
						   $sum3+=$hekvals[$row['CityId']][1]; 
                           echo ($hekvals[$row['CityId']][1]-$hekvals[$row['CityId']][51]);
                           echo "<br>".$hekvals[$row['CityId']][51];
                           echo '</span> </td>'; 
                           ?>                            
                         <td <span class="f12_font<?php echo $b; ?>">  <?php 
                           $sum4+=$hekvals[$row['CityId']][2]; echo $hekvals [$row['CityId']][2];  ?> </span> </td>
                         <td <span class="f12_font<?php echo $b; ?>">  <?php 
                            $sum5+=$hekvals[$row['CityId']][3]; echo $hekvals [$row['CityId']][3];  ?> </span> </td>
                         <td <span class="f12_font<?php echo $b; ?>">  <?php 
                            $sum6+=$hekvals[$row['CityId']][4]; echo $hekvals [$row['CityId']][4]; ?> </span> </td>
                         <td <span class="f12_font<?php echo $b; ?>">  <?php 
                            $sum7+=$belavals[$row['CityId']][4]; echo $belavals [$row['CityId']][4]; ?> </span> </td>
                         <td <span class="f12_font<?php echo $b; ?>">  <?php 
                            $sum8+=$hekvals[$row['CityId']][5]; echo $hekvals [$row['CityId']][5]; ?> </span> </td>
                         <td <span class="f12_font<?php echo $b; ?>">  <?php 
                            $sum9+=$belavals[$row['CityId']][5]; echo $belavals [$row['CityId']][5]; ?> </span> </td>
                         <td <span class="f12_font<?php echo $b; ?>">  <?php 
                            $sum10+=$hekvals[$row['CityId']][6]; echo $hekvals [$row['CityId']][6]; ?> </span> </td>
                         <td <span class="f12_font<?php echo $b; ?>">  <?php 
                            $sum11+=$belavals[$row['CityId']][6]; echo $belavals [$row['CityId']][6]; ?> </span> </td>
                         <td <span class="f12_font<?php echo $b; ?>">  <?php 
                            $sum12+=$hekvals[$row['CityId']][7]; echo $hekvals [$row['CityId']][7];
                            
                            echo '</span> </td>'; 
                             ?>  
                         <td <span class="f12_font<?php echo $b; ?>">  <?php 
                            $sum13+=$belavals[$row['CityId']][7]; echo $belavals [$row['CityId']][7]; ?> </span> </td>
                         <td <span class="f12_font<?php echo $b; ?>">  <?php 
                            $sum14+=round($hekvals[$row['CityId']][8]+$hekvals[$row['CityId']][9],1); echo round($hekvals[$row['CityId']][8]+$hekvals[$row['CityId']][9],1); ?>  </span> </td>
                         <td <span class="f12_font<?php echo $b; ?>">  <?php 
                            $sum15+=round($belavals[$row['CityId']][8]+$belavals[$row['CityId']][9],1); echo round($belavals [$row['CityId']][9]+$belavals[$row['CityId']][9],1); ?> </span> </td>
                         <td <span class="f12_font<?php echo $b; ?>">   
                           <?php 
                            $sum16+=round($hekvals[$row['CityId']][9]-$hekvals[$row['CityId']][10],1); echo round($hekvals[$row['CityId']][9]-$hekvals[$row['CityId']][10],1); ?> </span> </td>
						 <td <span class="f12_font<?php echo $b; ?>">    
                             <?php 
                            $sum17+=round($belavals[$row['CityId']][9]-$belavals[$row['CityId']][10],1); 
                            echo round($belavals[$row['CityId']][9]-$belavals[$row['CityId']][10],1); ?> </span> </td>
                            
                         <td <span class="f12_font<?php echo $b; ?>">  <?php 
                            $sum20+=$hekvals[$row['CityId']][14]; echo $hekvals [$row['CityId']][14]; ?>  </span> </td>
                         <td <span class="f12_font<?php echo $b; ?>">  <?php 
                            $sum19+=round($belavals[$row['CityId']][14],1); echo round($belavals [$row['CityId']][14],1); ?> </span> </td>
				 	     <td <span class="f12_font<?php echo $b; ?>"> <?php 
                         $curval=$totalvals[$row['CityId']][50];
                         $sum18+=$curval; echo $curval;
                         ;
                         
                            echo '</span> </td>'; 
                          ?> 
                    </tr>
					 <?php }  ?>                    
					<tr> 
                            <td colspan="20" <span class="f7_font<?php echo $b; ?>"  >  <?php echo " "; $b='b';?> </span>  </td>
					</tr>    
                    <tr>
                            <td colspan="2" rowspan="2" <span class="f14_font<?php echo $b; ?>"  >  <?php echo "مجموع"; ?> </span>  </td>
							<td	colspan="2"<span class="f131_font<?php echo $b; ?>">  <?php echo round($sum21);  ?> </span> </td>
							<td colspan="2"<span class="f131_font<?php echo $b; ?>">  <?php echo round($sum2); ?> </span> </td>
							<td	colspan="2"<span class="f131_font<?php echo $b; ?>">  <?php echo round($sum4); ?> </span> </td>
							<td	colspan="2"<span class="f131_font<?php echo $b; ?>">  <?php echo round($sum6); ?> </span> </td>
							<td colspan="2"<span class="f131_font<?php echo $b; ?>">  <?php echo round($sum8); ?> </span> </td>
                            <td colspan="2"<span class="f131_font<?php echo $b; ?>">  <?php echo round($sum10); ?> </span> </td>
							<td colspan="2"<span class="f131_font<?php echo $b; ?>">  <?php echo round($sum12); ?> </span> </td>	
							<td colspan="2"<span class="f131_font<?php echo $b; ?>">  <?php echo round($sum14); ?> </span> </td>	
							<td colspan="2"<span class="f131_font<?php echo $b; ?>">  <?php echo round($sum16); ?> </span> </td>
							<td colspan="2"<span class="f131_font<?php echo $b; ?>">  <?php echo round($sum20); ?> </span> </td>
							<td rowspan="2"<span class="f14_font<?php echo $b; ?>">  <?php	echo round($sum18/$rown,1); ?> </span> </td>							
							 
                        </tr>
                    <tr>
                            
							<td	colspan="2"<span class="f132_font<?php echo $b; ?>">  <?php echo round($sum1);  ?> </span> </td>
                           	<td	colspan="2"<span class="f132_font<?php echo $b; ?>">  <?php echo round($sum3); ?> </span> </td>
                            <td	colspan="2"<span class="f132_font<?php echo $b; ?>">  <?php echo round($sum5); ?> </span> </td>
                            <td	colspan="2"<span class="f132_font<?php echo $b; ?>">  <?php echo round($sum7); ?> </span> </td>
                            <td colspan="2"<span class="f132_font<?php echo $b; ?>">  <?php echo round($sum9); ?> </span> </td>
                                                            
                            <td colspan="2"<span class="f132_font<?php echo $b; ?>">  <?php echo round($sum11); ?> </span> </td>
                            <td colspan="2"<span class="f132_font<?php echo $b; ?>">  <?php echo round($sum13); ?> </span> </td>	
                            <td colspan="2"<span class="f132_font<?php echo $b; ?>">  <?php echo round($sum15); ?> </span> </td>      
							
							<td colspan="2"<span class="f132_font<?php echo $b; ?>">  <?php echo round($sum17); ?> </span> </td>
                            <td colspan="2"<span class="f132_font<?php echo $b; ?>">  <?php echo round($sum19); ?> </span> </td>
                             
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

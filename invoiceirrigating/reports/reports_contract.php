<?php 
/*
reorts/reports_contract.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php //include('Chartsql.php'); ?>
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

$currentdate=gregorian_to_jalali(date('Y-m-d'));
$querys = "SELECT YearID from year where value ='".substr($currentdate,0,4)."'";
$results = mysql_query($querys);
$rows = mysql_fetch_assoc($results);
$YearIDcurr=$rows['YearID'];
$MonthIDcurr=substr($currentdate,5,2);
$querys = "SELECT max(YearID) ID from designercocontract_progress 
            union all SELECT max(MonthID) ID from designercocontract_progress where YearID=(SELECT max(YearID) ID from designercocontract_progress)";
$results = mysql_query($querys);
$rows = mysql_fetch_assoc($results);
$YearIDmax=$rows['ID'];
$rows = mysql_fetch_assoc($results);
$MonthIDmax=$rows['ID'];
if ($MonthIDcurr!=$MonthIDmax)
    $insertable=1; else $insertable=0;
//print $insertable;

$where="";		
$str="";
$creditsourceID=0;
$Permissionvals=supervisorcoderrquirement_sql($login_ostanId); 
 
 switch ($_POST['IDorder']) 
  {
    case 1: $orderby=' order by prjtypeTitle COLLATE utf8_persian_ci'; break; 
    case 2: $orderby=' order by contractTitle COLLATE utf8_persian_ci'; break;
    case 3: $orderby=' order by designercoTitle COLLATE utf8_persian_ci';  break;
	case 4: $orderby=' order by designercocontract.No Desc'; break;
    case 5: $orderby=' order by designercocontract.contractDate '; break;
    case 6: $orderby=' order by designercocontract.area'; break;
    case 7: $orderby=" order by round(replace(replace(designercocontract.price,'.',''),',','')/1000000,1)"; break;
    case 8: $orderby=' order by designercocontract.duration'; break;
     default: 
        $orderby=' order by contractTitle COLLATE utf8_persian_ci'; break; 
  }

   		$cond="";		
if ($_POST)
{
    if ($_POST['showc']=='on')
    $showc=1;
    $creditsourceID=$_POST['creditsourceID'];
    $Datefrom=$_POST['Datefrom'];
    $Dateto=$_POST['Dateto'];
    $Datetop=$_POST['Datetop'];
	   
	if (strlen(trim($_POST['prjtypeid']))>0)
			$cond.=" and designercocontract.prjtypeid='$_POST[prjtypeid]'";
	if (strlen(trim($_POST['contracttypeID']))>0)
			$cond.=" and designercocontract.contracttypeID='$_POST[contracttypeID]'";
	if (strlen(trim($_POST['DesignerCoID']))>0)
			$cond.=" and designercocontract.DesignerCoID='$_POST[DesignerCoID]'";
   	
}
else
{
	$Datetop ='1397/02/01';
	$Datefrom ='1393/01/01';
    $Dateto=gregorian_to_jalali(date('Y-m-d'));
}

//print $login_userid.''.$login_DesignerCoID;
if ($login_DesignerCoID)
 $cond.=" and designercocontract.DesignerCoID='$login_DesignerCoID' ";
 
    if (strlen($Datefrom)>0)$cond.=" and 
    concat (
substring(contractDate,1,4),'/',
case length(substring(substring_index(contractDate,'/',2),6))<2 when 1 then concat('0',substring(substring_index(contractDate,'/',2),6)) else substring(substring_index(contractDate,'/',2),6) end,'/',
case length(substring(contractDate,length(substring_index(contractDate,'/',2))+2))<2 when 1 then concat('0',substring(contractDate,length(substring_index(contractDate,'/',2))+2)) else substring(contractDate,length(substring_index(contractDate,'/',2))+2) end)
 >='".$Datefrom."'";
    if (strlen($Dateto)>0)  $cond.=" and 
    concat (
substring(contractDate,1,4),'/',
case length(substring(substring_index(contractDate,'/',2),6))<2 when 1 then concat('0',substring(substring_index(contractDate,'/',2),6)) else substring(substring_index(contractDate,'/',2),6) end,'/',
case length(substring(contractDate,length(substring_index(contractDate,'/',2))+2))<2 when 1 then concat('0',substring(contractDate,length(substring_index(contractDate,'/',2))+2)) else substring(contractDate,length(substring_index(contractDate,'/',2))+2) end)
 <='".$Dateto."'";

$sqlcont= sqlcont($login_CityId);
 
$sql="SELECT designercocontract.designercocontractID,designercocontract_progress.PhysicalProgress,designercocontract_progress.FinancialProgress,
	designercocontract.Title,designercocontract.area,
    (concat (substring(contractDate,1,4),'/',
			case length(substring(substring_index(contractDate,'/',2),6))<2 when 1 then concat('0',substring(substring_index(contractDate,'/',2),6)) else substring(substring_index(contractDate,'/',2),6) end,'/',
			case length(substring(contractDate,length(substring_index(contractDate,'/',2))+2))<2 when 1 then concat('0',substring(contractDate,length(substring_index(contractDate,'/',2))+2)) else substring(contractDate,length(substring_index(contractDate,'/',2))+2) end)
	) contractDate
	,round(replace(replace(designercocontract.price,'.',''),',','')/1000000,1) price,designercocontract.duration,designercocontract.No
	,designerco.Title designercoTitle,designerco.DesignerCoID
	,prjtype.Title prjtypeTitle,prjtype.prjtypeid
	,contracttype.Title contractTitle,contracttype.contracttypeID,contractprogress.cocontractprogress
	,contractprogress.cocontractprogressLastTotal,contractprogress.cocontractprogressDesignArea,contractprogress.cocontractprogresscnt
	,contractprogress.fzkargah,round(replace(replace(contractfree.freedPrice,'.',''),',','')/1000000,1) freedPrice
	
FROM `designercocontract`
	
	left outer join (
					$sqlcont
					) contractprogress on contractprogress.designercocontractID=designercocontract.designercocontractID
	left outer join (select sum(Price) freedPrice,designercocontractID from contractfree
					group by designercocontractID					
					) contractfree on contractfree.designercocontractID=designercocontract.designercocontractID
left outer join  contracttype on contracttype.contracttypeID=designercocontract.contracttypeID 
left outer join  designerco on designerco.DesignerCoID=designercocontract.DesignerCoID 
left outer join  prjtype on prjtype.prjtypeid=designercocontract.prjtypeid 
left outer join designercocontract_progress on designercocontract_progress.designercocontractID=designercocontract.designercocontractID
                and designercocontract_progress.YearID='$YearIDmax' and designercocontract_progress.MonthID='$MonthIDmax'
where 1=1 $cond
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


$ID1[' ']=' ';
$ID2[' ']=' ';
$ID3[' ']=' ';
$ID4[' ']=' ';
$ID5[' ']=' ';
$ID6[' ']=' ';
$ID7[' ']=' ';
$ID8[' ']=' ';
$ID9[' ']=' ';
$dasrow=0;

while($row = mysql_fetch_assoc($result))

{
    $dasrow=1;
    $ID1[trim($row['Title'])]=trim($row['Title']);
    $ID2[trim($row['prjtypeTitle'])]=trim($row['prjtypeid']);
    $ID3[trim($row['contractTitle'])]=trim($row['contracttypeID']);
    $ID4[trim($row['designercoTitle'])]=trim($row['DesignerCoID']);
    $ID5[trim($row['No'])]=trim($row['No']);
    $ID6[trim($row['contractDate'])]=trim($row['contractDate']);
    $ID7[trim($row['area'])]=trim($row['area']);
    $ID8[trim($row['price'])]=trim($row['price']);
    $ID9[trim($row['duration'])]=trim($row['duration']);
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

if ($dasrow)
    mysql_data_seek( $result, 0 );
	

$query="
select 'نوع پروژه' _key,1 as _value union all
select 'نوع قرارداد' _key,2 as _value union all 
select 'مشاور' _key,3 as _value union all
select 'شماره ابلاغ' _key,4 as _value union all
select 'تاریخ قرارداد' _key,5 as _value union all
select 'سطح' _key,6 as _value union all
select 'مبلغ' _key,7 as _value union all
select 'مدت' _key,8 as _value union all
select 'پیشرفت فیزیکی' _key,9 as _value union all
select 'پیشرفت مالی' _key,10 as _value union all
select 'تاریخ' _key,11 as _value ";
$IDorder = get_key_value_from_query_into_array($query);

if (!$_POST['IDorder'])
    $IDorderval=2;
else $IDorderval=$_POST['IDorder'];
	
	

?>
<!DOCTYPE html>
<html>
<head>
  	<title>گزارش وضعیت قراردادها</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
    
    

        <link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
        <script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="../js/persiandatepicker.js"></script>
        


    <script type="text/javascript">
            $(function() {
                $("#Datefrom, #simpleLabel").persiandatepicker();   
                $("#Dateto, #simpleLabel").persiandatepicker();   
				
            });
        
      
    </script>
    
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
                <form action="reports_contract.php" method="post" >
                <table width="95%" align="center" id="table">
                   
                  <thead>
                       
						<input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  />
				  
                      <tr style="background-color:#f3f3f3">
					  
                      <td  class="label">تاریخ</td>
                      <td  class="data">از:<input placeholder="انتخاب تاریخ"  name="Datefrom" type="text" class="textbox" id="Datefrom" 
                      value="<?php if (strlen($Datefrom)>0) { echo $Datefrom;} else {echo '1395/01/01'; } ?>" size="10" maxlength="10" />
					 تا:
                      <input placeholder="انتخاب تاریخ" name="Dateto" type="text" class="textbox" id="Dateto" 
                      value="<?php if (strlen($Dateto)>0) { echo $Dateto;} else {echo gregorian_to_jalali(date('Y-m-d')); } ?>" size="10" maxlength="10" />
					  </td>
                      
                      <?php
                      echo select_option('creditsourceID','اعتبارات',',',$ID10,0,'','','1','rtl',0,'',$creditsourceID,'','100%').
                        	 select_option('IDorder','ترتیب',',',$IDorder,0,'','','3','rtl',0,'',$IDorderval,"",'100');
              
                       ?>
                        <td colspan="2" class="label">پیشبینی&nbsp;پیشرفت</td>
						<td colspan="3"><input placeholder="انتخاب تاریخ" name="Datetop" type="text" class="textbox" id="Datetop" 
                      value="<?php if (strlen($Datetop)>0) {echo $Datetop;} else {echo gregorian_to_jalali(date('Y-m-d')); } ?>" size="10" maxlength="10" />
					  </td>
                 
                       <td colspan="6"></td>
			           <td  colspan=3 class="f7_font">تاریخ چاپ: <?php  echo gregorian_to_jalali(date('Y-m-d'))."</td>"; ?>
					   
					   <td class="data"><input id="showc" name="showc" type="checkbox"  id="showc" 
					<?php 
						if ($showc==1) echo "checked"
					   ."/></td>";
                  
                       
                       
                     
                       
                       
                       echo "</tr>";
                       
                            echo "
		                <tr> 
                            <td colspan=\"23\"
                            <span class=\"f14_fontcb\"  >گزارش وضعیت قراردادها
                            </span>  </td>
							
                        </tr>
                            <tr>
						    <th rowspan=\"2\" class=\"f10_fontb\" >ردیف</th>
                            <th colspan=\"3\" class=\"f10_fontb\" >موضوع</th>
                            <th colspan=\"6\" class=\"f10_fontb\" >مشخصات قرارداد</th>
                            <th colspan=\"3\" class=\"f10_fontb\" >وضعیت پیشرفت پروژه</th>
							<th colspan=\"3\" class=\"f10_fontb\" >وضعیت پیشرفت فیزیکی</th>
							<th rowspan=\"2\" class=\"f10_fontb\" >حق الزحمه (ریال)</th>
							<th colspan=\"3\" class=\"f10_fontb\" >وضعیت پیشرفت مالی</th>
							<th colspan=\"3\" class=\"f10_fontb\" >وضعیت فعلی </th>
                       
							</tr> 
                   		<tr>
                            <th class=\"f10_fontb\" style=\"width: 100px;\" >موضوع قرارداد</th>
                            <th class=\"f10_fontb\" >نوع پروژه</th>
                            <th class=\"f10_fontb\" >نوع قرارداد</th>
							
							<th class=\"f10_fontb\" >مشاور</th>
                            <th class=\"f10_fontb\" >شماره ابلاغ</th>
                            <th class=\"f10_fontb\" ><label >تاریخ قرارداد</label></th>
                            <th class=\"f10_fontb\" ><label >سطح (حجم)</label></th>
                            <th class=\"f10_fontb\" ><label >مبلغ (م ر)</label></th>
                            <th class=\"f10_fontb\" ><label >مدت</label></th>
                           
						   	<th class=\"f10_fontb\" ><label >تعداد</label></th>
							<th class=\"f10_fontb\" ><label >حجم (هکتار)</label></th>
							<th class=\"f10_fontb\" ><label >مبلغ (م ر)</label></th>
                    
                            <th class=\"f10_fontb\" ><label >ماه قبل</label></th>
                            <th class=\"f10_fontb\" ><label >این ماه</label></th>
                            <th class=\"f10_fontb\" ><label >کل</label></th>
						
							<th class=\"f10_fontb\" ><label >ماه قبل</label></th>
                            <th class=\"f10_fontb\" ><label >این ماه</label></th>
                            <th class=\"f10_fontb\" ><label >کل</label></th>
							
							<th class=\"f10_fontb\" ><label >وضعیت</label></th>
							<th class=\"f10_fontb\" ><label >پرداختی</label></th>
                            <th class=\"f10_fontb\" ><label >درصد</label></th>
                          
                 				
                        </tr></thead>
                            ";
                   
               print  "<tr class='no-print'>    
						    <td class=\"f14_font\"></td>".
                            select_option('Title','',',',$ID1,0,'','','1','rtl',0,'',$Title).
                            select_option('prjtypeid','',',',$ID2,0,'','','1','rtl',0,'',$prjtypeid,'','100%').
							select_option('contracttypeID','',',',$ID3,0,'','','1','rtl',0,'',$contracttypeID,'','100%').
					        select_option('DesignerCoID','',',',$ID4,0,'','','1','rtl',0,'',$DesignerCoID,'','100%').
					        select_option('No','',',',$ID5,0,'','','1','rtl',0,'',$No,"",'100%').
					        select_option('contractDate','',',',$ID6,0,'','','1','rtl',0,'',$contractDate,'','100%'). 
							select_option('area','',',',$ID7,0,'','','1','rtl',0,'',$area,"",'100%').
					      
				            select_option('price','',',',$ID8,0,'','','1','rtl',0,'',$price,'','100%').
					        select_option('duration','',',',$ID9,0,'','','1','rtl',0,'',$duration,'','100%');
                            
                            echo "<td style=\"text-align:left;\" colspan=13><input   name=\"submit\" type=\"submit\" class=\"button\" id=\"submit\" size=\"16\"
                           value=\"جستجو\" /></td></tr>";
           
                   $rown=0;
                   $s1=0;
                   $s2=0;
                   $s3=0;
                   $s4=0;
                   $s5=0;
                   $s6=0;
                   $s7=0;
                   $s8=0;
                   $s9=0;
                   $s10=0;
                   $s11=0;
                   $s12=0;
                   $s13=0;
                   $s14=0;
                   $s15=0;
               
			function predict($Datetop,$contractDate,$duration)
			{
				$datepredict=(strtotime($Datetop)-strtotime($contractDate))/86400/30;  //تعداد روز پیشبینی
				if ($datepredict>$duration) $datepredict=$duration; else $datepredict=0;
				$per=$datepredict/$duration;
			 return $per;
			}
			
			while($row = mysql_fetch_assoc($result)){							
                      $ID = 'designercocontract'.'_'.$row['designercoTitle'].'_'.$row['designercocontractID'].'_'.$tblkey.'_'.$tblval;
                  
                       $rown++;
					    if ($rown%2==1) 
                        $b=''; else $b='b';
						
						$s1+=$row['area'];
                   	    $s2+=$row['price'];
                    	//if (strlen($Datetop)>0) 
						$per=predict(jalali_to_gregorian(compelete_date($Datetop)),jalali_to_gregorian(compelete_date($row['contractDate'])),$row['duration']);
						$per1=round($per,1);
						
						
						$fees=$row['fzkargah']*$row['cocontractprogress']/100;
						$feesprogress=$fees/($row['price']*1000000)*100;
                             ?>
							 
				      <tr>    

                            <td
                            <span class="f10_font<?php echo $b; ?>"  >  <?php echo $rown; ?> </span>  </td>
							<td 
							<span class="f10_font<?php echo $b; ?>">  <?php   if ($login_RolesID==1 && $showc==1) echo "($row[designercocontractID])<br>"; echo $row['Title']; ?> </span> </td>
							<td 
							<span class="f10_font<?php echo $b; ?>">  <?php echo $row['prjtypeTitle']; ?> </span> </td>
							<td 
							<span class="f10_font<?php echo $b; ?>">  <?php echo $row['contractTitle']; ?> </span> </td>
							<td 
							<span class="f10_font<?php echo $b; ?>">  <?php if ($login_RolesID==1 && $showc==1) echo "($row[DesignerCoID])<br>"; echo $row['designercoTitle']; ?> </span> </td>
							<td 
							<span class="f10_font<?php echo $b; ?>">  <?php echo $row['No']; ?> </span> </td>
							<td 
							<span class="f10_font<?php echo $b; ?>">  <?php   if ($login_RolesID==1 && $showc==1) 
                            {
                                $date = new DateTime(jalali_to_gregorian($row['contractDate']));
                                $date->modify('+'.$row['duration'].' month');
                            //$date->add(new DateInterval('P2Y'));
                            echo "(".jalali_to_gregorian($row['contractDate']).")<br>
                            (".$date->format('Y-m-d').")<br>";
                            }
                            
                            echo $row['contractDate']; ?> </span> </td>
							<td 
							<span class="f10_font<?php echo $b; ?>">  <?php echo $row['area']; ?> </span> </td>
							<td 
							<span class="f10_font<?php echo $b; ?>">  <?php echo ($row['price']); ?> </span> </td>
							<td 
							<span class="f10_font<?php echo $b; ?>">  <?php echo $row['duration']; ?> </span> </td>
							
					<td <span class="f10_font<?php echo $b; ?>">  <?php echo $row['cocontractprogresscnt']; ?> </span> </td>
                    <td <span class="f10_font<?php echo $b; ?>">  <?php echo round($row['cocontractprogressDesignArea'],1); ?> </span> </td>
                  	<td <span class="f10_font<?php echo $b; ?>">  <?php echo round($row['cocontractprogressLastTotal'],1); ?> </span> </td>
                 		
                    <td <span class="f10_font<?php echo $b; ?>">  <?php echo  round($row['PhysicalProgress'],1); ?> </span> </td>
                    <td <span class="f10_font<?php echo $b; ?>">  <?php echo round($row['cocontractprogress']-$row['PhysicalProgress'],1); ?> </span> </td>
                    <td <span class="f10_font<?php echo $b; ?>">  <?php 
                    echo round($row['cocontractprogress'],1); ?> </span> </td>
                   
				    <td <span class="f10_font<?php echo $b; ?>">  <?php echo round($fees/1000000,1); ?> </span> </td>
				
					<td <span class="f10_font<?php echo $b; ?>">  <?php echo round($row['FinancialProgress'],1); ?> </span> </td>
                    <td <span class="f10_font<?php echo $b; ?>">  <?php echo round($feesprogress-round($row['FinancialProgress'],1),1);
                    
                    
                     ?> </span> </td>
                    <td <span class="f10_font<?php echo $b; ?>">  <?php echo round($feesprogress,1);
                    
                    
                    
                    if ($insertable==1 && (round($fees/1000000,1)>0 || round($feesprogress,1)>0) )
                    {
                        mysql_query("insert into designercocontract_progress (designercocontractID,YearID,MonthID,PhysicalProgress,FinancialProgress,SaveDate,SaveTime,ClerkID) 
                        VALUES ('$row[designercocontractID]','$YearIDcurr','$MonthIDcurr','".round($row['cocontractprogress'],1)."'
                        ,'".round($feesprogress,1)."','" . date('Y-m-d') . "','" . 
                        date('Y-m-d H:i:s') . "','$login_userid')  ");
                    }
                    
                    
                     ?> </span> </td>
				    
					<td <span class="f10_font<?php echo $b; ?>">  <?php echo $per1; ?> </span> </td>
                    <td <span class="f10_font<?php echo $b; ?>">  <?php echo $row['freedPrice']; ?> </span> </td>
					<td <span class="f10_font<?php echo $b; ?>">  <?php echo round(($fees/$row['freedPrice'])*100,1); ?> </span> </td>
					
					<?php if ($row['cocontractprogresscnt']>0) echo 
                 	"<td class='no-print'><a href='../reports/reports_novinsamaneh.php?uid=".rand(10000,99999).rand(10000,99999).
                        rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).'1'.
                        "_".$_POST['YearID']."_".$_POST['showc']."_".$_POST['showa']."_".$_POST['creditsourceID']."_".$_POST['city']
                        ."_".$_POST['ostan']."_".$_POST['credityear']."_".$row['designercocontractID']."_1_".$row['contracttypeID'].
                        rand(10000,99999)."' target='_blank'><img style = 'width: 20px;;' src='../img/search_page.png' title=' ريز '></a></td>";
						else echo "<td class='no-print'></td>";
					 $permitroles = array("1","18","19","5");
					if (in_array($login_RolesID, $permitroles))	{
                    ?>
					<td class='no-print'><a href="<?php print"../codding/codding4table_detail_edit.php?uid=".rand(10000,99999).rand(10000,99999).
						rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>">
                       <img style = 'width: 20px;' src='../img/file-edit-icon.png' title=' ويرايش '></a></td>
                  
				  <?php    
					if ($row['applicantstategroupsID']==6)     
					   $imgtarget="../img/dolar.jpg";
					   else    
					   $imgtarget="../img/dolar2.jpg";
									  
                      print "<td class='no-print'><a target='".$target."' href='../appinvestigation/contractfree_list.php?uid=".rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$row['designercocontractID'].'_5_'.$row['DesignerCoID'].'_'.$row['OperatorCoID'].'_'.$row['ProducersIDw'].rand(10000,99999).
                            "'><img style = 'width: 20px;' src='$imgtarget' title=' آزادسازی '></a></td>"; 
                   ?>
					</tr>
					<?php
						}		
					}					
				     ?>
						 
                         <tr>
                             <td colspan="11" class="f14_fontcb" ><?php echo 'مجموع مساحت (هکتار)';   ?></td>
                            <td colspan="12" 
                            class="f14_fontcb" 
                            ><?php echo number_format($s1);   ?></td>
                        </tr>
    				   
                        <tr>
                             <td colspan="11" class="f14_fontcb" ><?php echo 'مجموع مبلغ (ریال)';   ?></td>
                            <td colspan="12" 
                            class="f14_fontcb" 
                            ><?php echo number_format($s2);   ?></td>
                        </tr>
    				   
                       <tr>
                             <td colspan="11" class="f14_fontcb" ><?php echo 'پیشرفت فیزیکی (درصد)';   ?></td>
                            <td colspan="12" 
                            class="f14_fontcb" 
                            ><?php echo $s3;   ?></td>
                        </tr>
                       <tr>
                             <td colspan="11" class="f14_fontcb" ><?php echo 'پیشرفت مالی (درصد)';   ?></td>
                            <td colspan="12" 
                            class="f14_fontcb" 
                            ><?php echo $s4;   ?></td>
                        </tr>
                   
                   
				   <tr>
				   <td colspan="5">
				   <?php echo"--- ";?>
				   </td>
                   </tr>
                   <tr>
				   <td colspan="5">
				   <?php echo"(---)";?>
				   </td>
                   </tr>
                   
                        
          
               </table>         
                   
                   
                	<script src="../js/jquery-1.9.1.js"></script>
					<script src="../js/jquery.freezeheader.js"></script>

					<script language="javascript" type="text/javascript">
	
						$(document).ready(function () {
						$("#table").freezeHeader();
							})
 					</script>
                   
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

<?php 
/*
reorts/reports_applicantsfree.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php
 require ('../includes/functions.php');

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
    
$str='';
 $str.=" and operatorco.title<>'مجری آبرسانی' ";
if (strlen(trim($_POST['ApplicantFName']))>0)
    $str.=" and applicantmasterop.ApplicantFName like'%$_POST[ApplicantFName]%'";
if (strlen(trim($_POST['ApplicantName']))>0)
    $str.=" and applicantmasterop.ApplicantName like '%$_POST[ApplicantName]%'";
if (strlen(trim($_POST['DesignArea']))>0)
    $str.=" and applicantmasterop.DesignArea='$_POST[DesignArea]'";
if (strlen(trim($_POST['DesignSystemGroupstitle']))>0)
    $str.=" and designsystemgroups.title='$_POST[DesignSystemGroupstitle]'";
if (strlen(trim($_POST['shahrcityname']))>0)
    $str.=" and shahr.cityname='$_POST[shahrcityname]'";	   
if (strlen(trim($_POST['operatorcotitle']))>0)
    $str.=" and operatorco.title like '%$_POST[operatorcotitle]%'";   	   
if (strlen(trim($_POST['applicantstatestitle']))>0)
    $str.=" and applicantstates.title='$_POST[applicantstatestitle]'";  
if (strlen(trim($_POST['lastSaveDate']))>0)
    $str.=" and applicantmasterop.TMDate='$_POST[lastSaveDate]'";   
if ($_POST['LastTotal']>0)
    $str.=" and applicantmasterop.LastTotal='$_POST[LastTotal]'"; 
if ($_POST['belaavaz']>0)
    $str.=" and applicantmasterop.belaavaz='$_POST[belaavaz]'";   
if (strlen(trim($_POST['creditsourcetitle']))>0)
    $str.=" and creditsource.title like '%$_POST[creditsourcetitle]%'";   	   

	       $orderby='order by shahrcityname COLLATE utf8_persian_ci,applicantmasterop.applicantmasterid';
 
$sql = "SELECT applicantmasterall.TMDate,TMSaveDate,applicantmasterop.CityId,
applicantmasterop.ApplicantName,applicantmasterop.ApplicantFName,applicantmasterop.ApplicantMasterID,applicantmasterop.belaavaz,applicantmasterop.LastTotal,(applicantfreedetail1.SaveDate) SaveDate1,(applicantfreedetail2.SaveDate) SaveDate2 ,(applicantfreedetail3.SaveDate) SaveDate3,(applicantfreedetail4.SaveDate) SaveDate4,
applicantmasterop.operatorcoid,applicantmasterop.DesignArea,applicantstategroups.applicantstategroupsID,applicantstategroups.Title applicantstategroupsTitle,
operatorco.title operatorcotitle 
,applicantstates.title applicantstatestitle,applicantstates.applicantstatesID, 
shahr.cityname shahrcityname,shahr.id shahrid 
,designsystemgroups.title DesignSystemGroupstitle
,creditsource.title creditsourcetitle
FROM applicantmaster applicantmasterop


inner join applicantstates on applicantstates.applicantstatesID=applicantmasterop.applicantstatesID

left outer join (select ApplicantMasterID,SaveDate from applicantfreedetail where freestateID=141 group by ApplicantMasterID) applicantfreedetail1 on applicantfreedetail1.applicantmasterid = applicantmasterop.applicantmasterid
left outer join (select ApplicantMasterID,SaveDate from applicantfreedetail where freestateID=142 group by ApplicantMasterID) applicantfreedetail2 on applicantfreedetail2.applicantmasterid = applicantmasterop.applicantmasterid
left outer join (select ApplicantMasterID,SaveDate from applicantfreedetail where freestateID=143 group by ApplicantMasterID) applicantfreedetail3 on applicantfreedetail3.applicantmasterid = applicantmasterop.applicantmasterid
left outer join (select ApplicantMasterID,SaveDate from applicantfreedetail where freestateID=144 group by ApplicantMasterID) applicantfreedetail4 on applicantfreedetail4.applicantmasterid = applicantmasterop.applicantmasterid

left outer join (select ApplicantMasterID,max(SaveDate) TMSaveDate from appchangestate where applicantstatesID in (34,35,38) group by ApplicantMasterID)
 applicantsavedate7 on applicantsavedate7.ApplicantMasterID =applicantmasterop.applicantmasterid


left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmasterop.cityid,1,4) 
and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'

left outer join tax_tbcity7digit tax_tbcity7digitnazer on substring(tax_tbcity7digitnazer.id,1,4)=substring(applicantmasterop.cityid,1,4) 
and substring(tax_tbcity7digitnazer.id,5,3)='000'
left outer join applicantstategroups on applicantstategroups.applicantstategroupsID=applicantstates.applicantstategroupsID
inner join operatorco on operatorco.operatorcoid=applicantmasterop.operatorcoid

left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.DesignSystemGroupsid=applicantmasterop.DesignSystemGroupsid

inner join applicantmasterdetail on applicantmasterdetail.ApplicantMasterIDmaster=applicantmasterop.ApplicantMasterID
inner join applicantmaster applicantmasterall on applicantmasterall.ApplicantMasterID=applicantmasterdetail.ApplicantMasterID

left outer join creditsource on creditsource.creditsourceid=case ifnull(applicantmasterop.creditsourceid,0) when 0 then applicantmasterall.creditsourceid else applicantmasterop.creditsourceid end

where  substring(applicantmasterop.cityid,1,2)=substring('$login_CityId',1,2) and applicantmasterop.applicantstatesID<>34 $str
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
    $ID1[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);
    $ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);
    $ID3[trim($row['DesignArea'])]=trim($row['DesignArea']);
    $ID4[trim($row['DesignSystemGroupstitle'])]=trim($row['DesignSystemGroupstitle']);
    $ID5[trim($row['shahrcityname'])]=trim($row['shahrcityname']);
    $ID6[trim($row['operatorcotitle'])]=trim($row['operatorcotitle']);
    $ID7[trim(floor($row['LastTotal']/100000)/10)]=trim($row['LastTotal']);
    $ID8[trim($row['belaavaz'])]=trim($row['belaavaz']);
    $ID9[trim($row['creditsourcetitle'])]=trim($row['creditsourcetitle']);
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
    
?>



<!DOCTYPE html>
<html>
<head>
  	<title>گزارش پیشرفت طرح های اجرایی</title>
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
            
            <form action="reports_applicantsfree.php" method="post">
             
                <table id="records" width="95%" align="center">
                   
                   
                   <tbody>
                   
                <table align='center' class="page" border='1'  id="table2">              
                  <thead> 
				  <tr> 
                  
                            <td colspan="17"
                            <span class="f14_fontb" >گزارش پیشرفت طرح های اجرایی (اقساط)</span>   <a href="chart_applicantsfree.php" target="_blank">
							<img title="نمودار پيشرفت زماني طرح هاي اجرايي" src="../img/chart.png" style="width: 25px;">
							</a>  </td>
                            
				   </tr>
                        <tr>

                            <th  
                           	<span class="f12_fontb" > رديف  </span> </th>
							<th 
                           	<span class="f12_fontb"> نام  </span> </th>
							<th 
                           	<span class="f12_fontb"> نام خانوادگی </span> </th>
							<th  
                            <span class="f12_fontb"> مساحت </span>
							 (ha)  </th>
                            <th   class="f12_fontb"> نوع سیستم  </th>
						    <th 
                            <span class="f12_fontb">دشت/ شهرستان</span> </th>
							<th  
                            <span class="f12_fontb">شركت مجری</span> </th>
							<th  
                            <span class="f12_fontb"> مبلغ کل </span>
						    <th  <span class="f12_fontb">کمک بلاعوض</span> </th>
						    <th  <span class="f12_fontb">اعتبار</span> </th>
						    
							<th  <span class="f12_fontb">تاریخ انعقاد قرارداد</span> </th>
							<th  <span class="f12_fontb">تاریخ قسط اول</span> </th>
							<th  <span class="f12_fontb">تاریخ قسط دوم</span> </th>
							<th  <span class="f12_fontb">تاریخ قسط سوم</span> </th>
                            <th  <span class="f12_fontb">تاریخ قسط چهارم</span> </th>
						    <th  <span class="f12_fontb">تحویل موقت</span> </th>
						    <th  <span class="f12_fontb">تحویل دائم</span> </th>
							
                        </tr>
                        
                       </thead> 
                        
                   <?php
                  print  "<tr class='no-print'>    
						    <td class=\"f14_font\"></td>".
                            select_option('ApplicantFName','',',',$ID1,0,'','','1','rtl',0,'',$ApplicantFName,'','100%').
                            select_option('ApplicantName','',',',$ID2,0,'','','1','rtl',0,'',$ApplicantName,'','100%').
							select_option('DesignArea','',',',$ID3,0,'','','1','rtl',0,'',$DesignArea,'','100%').
					        select_option('DesignSystemGroupstitle','',',',$ID4,0,'','','1','rtl',0,'',$DesignSystemGroupstitle,'','100%').
					        select_option('shahrcityname','',',',$ID5,0,'','','1','rtl',0,'',$shahrcityname,"",'100%').
					        select_option('operatorcotitle','',',',$ID6,0,'','','1','rtl',0,'',$operatorcotitle,'','100%'). 
				            select_option('LastTotal','',',',$ID7,0,'','','1','rtl',0,'',$LastTotal,'','100%').
					        select_option('belaavaz','',',',$ID8,0,'','','1','rtl',0,'',$belaavaz,'','100%').
                            select_option('creditsourcetitle','',',',$ID9,0,'','','1','rtl',0,'',$creditsourcetitle,'','100%');
                            
                            echo "<td style=\"text-align:left;\" colspan=2><input   name=\"submit\" type=\"submit\" class=\"button\" id=\"submit\" size=\"16\"
                           value=\"جستجو\" /></td></tr>";
                         
                   $rown=0;
                   
				      
						while($row2 = mysql_fetch_assoc($result)){							
                        if ($login_RolesID=='17' && substr($row2['CityId'],0,4)<>substr($login_CityId,0,4) ) 
						continue;
                        
                        $ApplicantName = $row2['ApplicantName'];
                        $ApplicantFName = $row2['ApplicantFName'];
                       
                       
						$savedate1 = $row2['SaveDate1'];
						$savedate2 = $row2['SaveDate2'];
						$savedate3 = $row2['SaveDate3'];
						$savedate4 = $row2['SaveDate4'];
						
						
						
                        $rown++;
                        if ($rown%2==1) 
                        $b='b'; else $b='';
                        
?>                      
                        <tr>    

                            <td
                            <span class="f12_font<?php echo $b; ?>"  >  <?php echo $rown; ?> </span>  </td>
							
                            <td 
							<span class="f12_font<?php echo $b; ?>">  <?php echo $ApplicantFName; ?> </span> </td>
                           
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo $ApplicantName; ?> </span> </td>
                           
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo $row2['DesignArea']; ?> </span> </td>
                            
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo str_replace(' ', '&nbsp;', $row2['DesignSystemGroupstitle']); ?> </span> </td>
                           
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo $row2['shahrcityname']; ?> </span> </td>
                            
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo $row2['operatorcotitle']; ?> </span> </td>
                           
                            <td
							<span class="f12_font<?php echo $b; ?>">  <?php echo floor($row2['LastTotal']/100000)/10; ?> </span> </td>
                           
                                              
                           
                            <td <span class="f12_font<?php echo $b; ?>">  <?php echo $row2['belaavaz']; ?> </span> </td>
                           
                            <td <span class="f12_font<?php echo $b; ?>">  <?php echo $row2['creditsourcetitle']; ?> </span> </td>
                           
                                                     
						   <td
							<span class="f10_font<?php echo $b; ?>"> </span><?php if ( $row2['TMDate']!="") echo gregorian_to_jalali($row2['TMDate']); else echo '0'; ?> </td>
                            
						   <td
							<span class="f10_font<?php echo $b; ?>"> </span><?php if ($savedate1!="") echo gregorian_to_jalali($savedate1); else echo '0'; ?> </td>
                            
						<td
							<span class="f10_font<?php echo $b; ?>"> </span><?php if ($savedate2!="") echo gregorian_to_jalali($savedate2); else echo '0'; ?> </td>
                           	
                            
						<td
							<span class="f10_font<?php echo $b; ?>"> </span><?php if ($savedate3!="") echo gregorian_to_jalali($savedate3); else echo '0'; ?> </td>
                            <td
							<span class="f10_font<?php echo $b; ?>"> </span><?php if ($savedate4!="") echo gregorian_to_jalali($savedate4); else echo '0'; ?> </td>
                            
                            <td <span class="f10_font<?php echo $b; ?>"><?php if ($row2['TMSaveDate']!="") echo gregorian_to_jalali(  $row2['TMSaveDate']); ?></span> </td>  
							<td <span class="f10_font<?php echo $b; ?>"><?php if ($taidpishF!="") echo gregorian_to_jalali( $taidpishF); ?></span> </td>
                               
							
							
                            
                           
							
							
							 
                        </tr><?php

                    }
                    
                    

?>

                      
                   
                </table>
                    </tbody>
					<script src="../js/jquery-1.9.1.js"></script>
				<script src="../js/jquery.freezeheader.js"></script>

			<script language="javascript" type="text/javascript">

        $(document).ready(function () {
         $("#table2").freezeHeader();
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

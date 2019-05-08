<?php 
/*
reorts/reports_projects.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php
require ('../includes/functions.php');
if ($login_ProducersID>0 && $login_isfulloption!=1) header("Location: ../login.php");

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
    
if ($login_RolesID==1)
$where=" "; else
$where=" and applicantmaster.applicantmasterid in (select applicantmaster.applicantmasterid from applicantmaster
inner join invoicemaster on invoicemaster.applicantmasterid=applicantmaster.applicantmasterid
inner join invoicedetail on invoicedetail.invoicemasterid=invoicemaster.invoicemasterid
inner join toolsmarks on toolsmarks.toolsmarksid=invoicedetail.toolsmarksid and 
toolsmarks.gadget3ID in (select toolsmarks.gadget3ID from toolsmarks where toolsmarks.ProducersID='$login_ProducersID')) ";
$sql="select applicantmaster.BankCode,concat(applicantmaster.ApplicantFName,' ',applicantmaster.ApplicantName) Name
,applicantmaster.mobile,applicantmaster.TMDate
,applicantmaster.DesignArea,
designsystemgroups.title DesignSystemGroupstitle,shahr.cityname shahrcityname,operatorco.title operatorcotitle
,applicantmaster.ApplicantMasterID,applicantmaster.applicantstatesID,applicantstates.title applicantstatestitle
,concat(CompanyAddress,' ',BossName,' ',bosslname,' همراه:',bossmobile,' تلفن:',Phone,' فکس:',Fax,' ',Email) Companyinfo
from applicantmaster
inner join applicantmasterdetail on 
case applicantmasterdetail.ApplicantMasterIDmaster>0 when 1 then  applicantmasterdetail.ApplicantMasterIDmaster
else applicantmasterdetail.ApplicantMasterID end=applicantmaster.ApplicantMasterID and ifnull(applicantmasterdetail.ApplicantMasterIDsurat,0)=0 and ifnull(prjtypeid,0)=0
inner join applicantmaster applicantmasterd on applicantmasterd.ApplicantMasterID=applicantmasterdetail.ApplicantMasterID
and applicantmasterd.applicantstatesID in (22,37)
inner join applicantstates on applicantstates.applicantstatesid=applicantmaster.applicantstatesid

left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.DesignSystemGroupsid=applicantmaster.DesignSystemGroupsid
left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) 
and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
left outer join  operatorco on operatorco.operatorcoid=applicantmaster.operatorcoid
where applicantmaster.applicantstatesID not in (34) and substring(applicantmaster.cityid,1,2)=substring('$login_CityId',1,2)
$where

order by applicantstates.title,applicantmaster.applicantmasterid
";                 
try 
    {		
        $result = mysql_query($sql);
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }         


?>



<!DOCTYPE html>
<html>
<head>
  	<title>گزارش پروژه ها</title>
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
            
            <form action="reports_projects.php" method="post">
             
                <table id="records" width="95%" align="center">
                   
                   
                   <tbody>
                   
                <table align='center' class="page" border='1'  id="table2">              
                   <thead>
				  <tr> 
                  
                            <td colspan="17"
                            <span class="f14_fontb" > گزارش لیست کالاها</span>  </td>
	  				        <th colspan="6"  class="f14_fontc">&nbsp;&nbsp; </th>
    	
                            
				   </tr>
                 <?php        if ($login_RolesID==1) {$hide=' ';$strroles='';} else {$hide='style=display:none';} ?>
                 
                        <tr>

                            <th <span class="f9_fontb" > رديف  </span> </th>
							<th <?php echo $hide; ?> <span class="f9_fontb" >کد</span> </th>
							<th <span class="f13_fontb"> متقاضی  </span> </th>
							<th <?php echo $hide; ?> <span class="f9_fontb"> مساحت </span> (ha)  </th>
                            <th  class="f14_fontb"> نوع سیستم  </th>
						    <th <span class="f13_fontb">دشت/ شهرستان</span> </th>
							<th <span class="f13_fontb">شركت </span> </th>
							<th <?php echo $hide; ?> <span class="f13_fontb">وضعیت</span> </th>
  							<th <?php echo $hide; ?> <span class="f13_fontb">تاریخ</span> </th>
  						<th   <span class="f13_fontb">خلاصه پروژه</span> </th>
                        </tr>
                        </thead>
				  
                   <?php
                   $rown=0;
                    while($row = mysql_fetch_assoc($result))
                    {
                        $rown++;
                        if ($rown%2==1) 
                        $b='b'; else $b='';
                        
                        $ID = $row['ApplicantMasterID'].'_4_0_'.$row['operatorcoid'].'_'.$row['applicantstatesID'];
						//if ($login_isfulloption<>1) continue;
			
                         ?>
                        <tr>
                            <td <span class="f12_font<?php echo $b; ?>">  <?php echo $rown; ?> </span> </td>
                            <td <?php echo $hide; ?> <span class="f12_font<?php echo $b; ?>">  <?php echo $row['BankCode']; ?> </span> </td>
                            <td <span class="f12_font<?php echo $b; ?>">  <?php echo "<p title='$row[mobile]'>".$row['Name']; ?>   </p>  </td>
                        
                            <td <?php echo $hide; ?>  <span class="f12_font<?php echo $b; ?>">  <?php echo $row['DesignArea']; ?> </span> </td>
                            <td <span class="f10_font<?php echo $b; ?>">  <?php echo $row['DesignSystemGroupstitle']; ?> </span> </td>
                            <td <span class="f12_font<?php echo $b; ?>">  <?php echo $row['shahrcityname']; ?> </span> </td>
                            <td <span class="f12_font<?php echo $b; ?>">   <?php echo "<p title='$row[Companyinfo]'>".$row['operatorcotitle']; ?>   </p>  </td>
                            <td <?php echo $hide; ?>  <span class="f12_font<?php echo $b; ?>">  <?php echo $row['applicantstatestitle']; ?> </span> </td>
							<td <?php echo $hide; ?>  <span class="f12_font<?php echo $b; ?>">  <?php echo gregorian_to_jalali($row['TMDate']); ?> </span> </td>
							<td></td>        
                        
                        
                        <td class='no-print'><a  target='_blank' href=<?php
                                                            
                            print "../insert/summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = 'width: 25px;' src='../img/search.png' title=' ريز '></a></td>
                            
                        </tr>
                            
                     <?php       
                    }
                    ?>

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

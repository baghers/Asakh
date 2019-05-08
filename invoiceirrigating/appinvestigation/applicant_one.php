<?php 

/*

//appinvestigation/applicant_one.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/invoicemasterfree_list.php
 
 
-
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


if ($login_Permission_granted==0) header("Location: ../login.php");
        //print $_GET["uid"];
            $ApplicantMasterID = substr($_GET["uid"],40,strlen($_GET["uid"])-46);
    	    $freenum = substr($_GET["uid"],strlen($_GET["uid"])-1,1);
            /* 
           applicantmaster جدول مشخصات طرح
           BankCode کدرهگیری طرح
           belaavaz بلاعوض
           criditType تجمیع بودن یا نبودن
           LastTotal جمع کل هزینه های طرح
           private یکی از ویژگی های طرح می باشد که در صورتی که شرکت ها بخواهند طرح تستی و آزمایشی داشته باشند آنرا شخصی می کنند								
           CostPriceListMasterID شناسه سال هزینه های اجرایی طرح 
           creditsourceID شناسه جدول منبع تامین اعتبار
           DesignerCoIDnazer شناسه مشاور بازبین
           ApplicantMasterID شناسه طرح مطالعاتی
           ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
           ApplicantMasterIDsurat شناسه طرح صورت وضعیت
           costpricelistmaster هزینه های اجرایی طرح ها
           year جدول سال
           costpricelistmaster هزینه های اجرایی طرح ها
           creditsource جدول منابع اعتباری
           designerco جدول شرکت های طراح
           designer جدول طراحان
           designsystemgroups سیستم آبیاری
           manuallistprice جدول ثبت هزینه های اجرایی طرح
           manuallistpriceall جدول فهارس بها
           appfoundation جدول سازه های طرح ها
           applicantmasterdetail با توجه به اینکه هر طرح در سه وضعیت مطالعات، پیش فاکتور و صورت وضعیت می باشد و برای هر پروژه تا پایان کار در جدول طرح ها سه طرح مطالعاتی، پیش فاکتور و صورت وضعیت ثبت می شود
           لذا این جدول ارتباط این سه مرحله از طرح را نگهداری می کند
           این جدول دارای ستون های ارتباطی زیر می باشد
           ApplicantMasterID شناسه طرح مطالعاتی
           ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
           ApplicantMasterIDsurat شناسه طرح صورت وضعیت
           clerk جدول کاربران
           operatorapprequest جدول پیشنهاد قیمت های طرح
           applicantmaster جدول مشخصات طرح
           BankCode کد رهگیری طرح
           ApplicantMasterID شناسه طرح
           state=1 انتخاب شدن پیشنهاد توسط کشاورز
           operatorcoID شناسه پیمانکار
           coef1 ضریب اول اجرای طرح
           coef2 ضریب دوم اجرای طرح
           coef3 ضریب سوم اجرای طرح
           coef4 ضریب چهارم اجرای طرح
           coef5 ضریب پنجم اجرای طرح
        */	
    		$query = "SELECT 
			applicantmaster.*,
			shahr.CityName, 
            designsystemgroups.Title designsystemgroupsTitle,
		    operatorco.Title operatorcoTitle,
			applicantmasterd.applicantmasterid,
			round(applicantmasterd.LastTotal/1000000,1) LastTotaldesign,
			round(applicantmasterd.selfcashhelpval/1000000,1) selfcashhelpvaldesign,
			applicantmasterd.belaavaz belaavazdesign,
			applicantmasteri.LastTotal LastTotali,
			designerco.Title designercoTitle,
			creditsource.title creditsourcetitle           
            ,case ifnull(applicantmaster.coef1,0) when 0 then transportcosttable.coef1 else applicantmaster.coef1 end coef11,
        case ifnull(applicantmaster.coef2,0) when 0 then transportcosttable.coef2 else applicantmaster.coef2 end coef22,
        case ifnull(applicantmaster.coef3,0) when 0 then transportcosttable.coef3 else applicantmaster.coef3 end coef33,
        case ifnull(applicantmaster.coef4,0) when 0 then transportcosttable.coef4 else applicantmaster.coef4 end coef44,
        case ifnull(applicantmaster.coef5,0) when 0 then transportcosttable.coef5 else applicantmaster.coef5 end coef55
            
			FROM applicantmaster 
    		left outer join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000'
    		left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' 
            and substring(shahr.id,3,5)<>'00000'
			
            left outer join transportcosttable on transportcosttable.TransportCostTableMasterID=applicantmaster.TransportCostTableMasterID
        and applicantmaster.DesignArea between transportcosttable.MinArea and transportcosttable.MaxArea
        
            inner join operatorco on operatorco.operatorcoid=applicantmaster.operatorcoid
            inner join applicantmaster applicantmasterd on applicantmasterd.BankCode=applicantmaster.BankCode and applicantmasterd.DesignerCoID>0
            inner join applicantmaster applicantmasteri on applicantmasteri.BankCode=applicantmaster.BankCode and applicantmasteri.ApplicantMasterID = '$ApplicantMasterID'
			
			left outer join designerco on designerco.DesignerCoID=applicantmaster.DesignerCoIDnazer        
			left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title)
			designsystemgroups on designsystemgroups.designsystemgroupsid=applicantmaster.designsystemgroupsid
			left outer join creditsource on creditsource.creditsourceid=applicantmaster.creditsourceid    		
			WHERE applicantmaster.ApplicantMasterIDmaster = '$ApplicantMasterID';";

            //print $query;
			$result = mysql_query($query);
								try 
							  {		
								mysql_query($query);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

			$resquery = mysql_fetch_assoc($result);
		
$designercoTitle=$resquery['designercoTitle'];
$operatorcoTitle=$resquery['operatorcoTitle'];
$CityName = $resquery['CityName'];
$LastTotali=$resquery['LastTotali'];


$othercosts= $resquery['othercosts1']+ $resquery['othercosts2']+ $resquery['othercosts3']+ $resquery['othercosts4']+ $resquery['othercosts5'];
$coef= $resquery['coef55']*$resquery['coef44']*$resquery['coef33']*$resquery['coef22']*$resquery['coef11'];

$fehrst10=round(0.1*($resquery['LastFehrestbaha']*$coef+$othercosts),1);

if ($resquery['LastTotal']>=$resquery['LastTotaldesign']) {($resquery['LastTotal']=$resquery['LastTotaldesign']);
                                                          ($resquery['selfcashhelpval']=$resquery['selfcashhelpvaldesign']);
														  ($resquery['belaavaz']=$resquery['belaavazdesign']);}
														  



/*
tax_tbcity7digit جدول شهرها
CityName شهر
ClerkIDExcellentSupervisor ناظر عالی
CPI نام کاربری
DVFS کلمه عبور
DesignerCoIDnazer ناظر
designerco شرکت طراح
clerk کاربران

*/
$sql1 = "select tax_tbcity7digit.CityName,ClerkIDExcellentSupervisor,clerk.CPI first_name,clerk.DVFS last_name,
          DesignerCoIDnazer,designerco.Title designercoTitle
           from tax_tbcity7digit 
		    inner join clerk on clerk.ClerkID=tax_tbcity7digit.ClerkIDExcellentSupervisor
			inner join designerco on designerco.DesignerCoID=tax_tbcity7digit.DesignerCoIDnazer
			
           WHERE tax_tbcity7digit.CityName = '$CityName'";
    $result1 = mysql_query($sql1);
							try 
							  {		
								mysql_query($sql1);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

    $resquery1 = mysql_fetch_assoc($result1);
	$ClerkIDExcellentSupervisor = decrypt($resquery1["first_name"]).' '.decrypt($resquery1["last_name"]); 
//print $sql1;
if ($designercoTitle=='') $designercoTitle=$resquery1['designercoTitle'];
//print $designercoTitle;



/*
freestate جدول مراحل آزادسازی
Title عنوان
producers تولیدکنندگان
applicantfreedetail ریز آزادسازی
producersID شناسه تولیدکننده
Price مبلغ
CheckNo شماره چک
CheckBank بانک
Description شرح
*/
		  
$sql2="SELECT freestate.Title freestateTitle,freestate.Code freestatecode,
case applicantfreedetail.producersID when -1 then 'شرکت مجری $operatorcoTitle'  when -2 then 'کشاورز:$ApplicantFullName' else concat('شرکت ',producers.Title) end producersTitle

,Price,CheckNo,CheckDate,CheckBank,Description
,applicantfreedetail.freestateID,applicantfreedetail.AccountNo,applicantfreedetail.AccountBank,applicantfreedetail.applicantfreedetailID 
            FROM applicantfreedetail
            left outer join freestate on freestate.freestateID=applicantfreedetail.freestateID
            left outer join producers on producers.producersID=applicantfreedetail.producersID
            where applicantfreedetail.ApplicantMasterID='$ApplicantMasterID'
            order by freestate.Code,producersTitle
        "; 
 
$result2 = mysql_query($sql2);
$result3 = mysql_query($sql2);
						try 
							  {		
								mysql_query($sql2);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }


     
                    $cnt=0;$prefreestatecode1='قسط اول';$sum1=0;$prefreestatecode2='قسط دوم';$sum2=0;$prefreestatecode3='قسط سوم';$sum3=0;
                    while($row = mysql_fetch_assoc($result3))
					    {
						if ($prefreestatecode1==$row['freestateTitle']){ $cnt++;$sum1+=$row['Price'];}
						if ($prefreestatecode2==$row['freestateTitle']){ $cnt++;$sum2+=$row['Price'];}
						if ($prefreestatecode3==$row['freestateTitle']){ $cnt++;$sum3+=$row['Price'];}
						} 
						 $darsadfree1=round(100*($sum1/$LastTotali),1);
                         $darsadfree2=round(100*($sum2/$LastTotali),1);
						 $darsadfree12=$darsadfree1+$darsadfree2;
                         $darsadfree3=round(100*($sum3/$LastTotali),1);
                      //print $darsadfree1.'='.$darsadfree2;
			?>


<!DOCTYPE html>
<html>
<head>


	<title> آزادسازي قسط  پروژه</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
  
  
  <style>
p {
    display: block;
    margin-top: 0em;
    margin-bottom: 0em;
    margin-left: 30;
    margin-right: 35;
}

f13_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:16pt;line-height:200%;font-weight: bold;font-family:'B lotus';                        
}
.f16_font{
	border:0px solid black;border-color:#000000 #000000;text-align:center;font-size:16pt;line-height:100%;font-weight: bold;font-family:'B Titr';                        
}
.f14_font{
	border:0px solid black;border-color:#000000 #000000;text-align:center;font-size:14pt;line-height:150%;font-family:'B zar'; font-weight: normal
}
.f10_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:10pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:9pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

</style>
   
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
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
            
            
    		<div id="content">
			<div id="content-tarh">
		<br/>
	
      	<table width="600" align="center" class="form" >
            <tbody>
	
	
	
	
	<?php  if ($freenum==1) {  ?>	

			   <tr>
					 <p><b><span class="f16_font"> مديريت محترم امور سرمایه گذاری سازمان </span></b></p>
					 <p><b><?php echo 'موضوع : آزادسازي قسط اول پروژه آبياري ' .'&nbsp;'. $resquery["ApplicantFName"].'&nbsp;'.$resquery["ApplicantName"] .'&nbsp;'. ' '; ?></b></p>
					 <p>سلام علیکم</p>
					 <p align="justify">
					 <span class="f14_font">
					 <?php echo 'بااحترام، با عنايت به نامه ثبت شده متقاضي به شماره
					 ..........
					 مورخ
					 ..........	
					 در دفتر اين سازمان به پيوست 
					 ....
					 برگ پيش فاكتورهاي صادره شامل لوله‌هاي پلي‌اتيلن و سايرلوازم و هزينه‌هاي اجرايي و قرارداد اجرايي در ارتباط با پروژه آبياري'.'&nbsp;<b>'. $resquery["designsystemgroupsTitle"] . '</b>&nbsp;'.'به مساحت'
					 . '&nbsp;<b>' . $resquery["DesignArea"] .'</b>&nbsp; هکتار متعلق به &nbsp;<b>' . $resquery["ApplicantFName"].'&nbsp;'.$resquery["ApplicantName"] . '</b>&nbsp; واقع در شهرستان &nbsp;'.
					 '<b>'.$resquery["CityName"] .'</b>&nbsp;ايفاد مي‌گردد. خواهشمند است دستور فرماييد قسط اول تسهيلات معادل 
					'.'&nbsp; '.$darsadfree1.'% &nbsp;'.'
					 مبلغ كل هزينه هاي طرح به شرح ذيل پرداخت گردد: '; ?>
                      </span></p> 
	               </p>				 
                </tr>
	           
			   <tr>
                   <?php	                    
                    $cnt=0;
                    $prefreestatecode='قسط اول';
                    $sum=0;
                    while($row = mysql_fetch_assoc($result2)){
                        
                        if ($prefreestatecode==$row['freestateTitle'])
                        { $cnt++;
		           ?>      
				   <p align="justify">
				   <span class="f14_font">
                    <?php echo $cnt.'-مبلغ'.'    &nbsp;<b>'.number_format($row['Price']).'</b>    &nbsp'
							.'ریال به حساب شماره'.'    &nbsp'.$row['AccountNo'].'    &nbsp'.'بانک'.'    &nbsp'.$row['AccountBank'].'    &nbsp'
							.'به نام'.'    &nbsp'.$row['producersTitle'].'    &nbsp'.$row['Description']
                             ; 
							 $sum+=$row['Price'];
                    }
                    }
	                 ?></span>
              </p>				 
     		</tr>	
                <tr>
					 <p align="justify"><span class="f14_font">
					 <?php echo 'توضيحاً اينكه قسط دوم پروژه پس از حمل كليه لوازم پيش فاكتورها به محل اجراي طرح و تاييد دستگاه نظارت و ارائه صورتجلسه تحويل كالا و 15 درصد قسط سوم نيز پس از تست و راه اندازي و تنظيم صورتجلسه تحويل و تحول موقت با تاييد اين  مديريت و مديريت شهرستان و دستگاه نظارت  پس از كسر 10% حسن انجام اجراي پروژه در وجه مجري پروژه شركت'.'&nbsp;<b>'.$operatorcoTitle. '</b>&nbsp;'.'طرف قرارداد متقاضي قابل پرداخت خواهد بود.'; ?>
					  </span>
					  <p align="justify"><span class="f14_font">
					 <?php echo 'بديهي است 10% حسن انجام اجراي پروژه پس از يكسال بهره‌برداري كامل و تنظيم فرم تحويل و تحول قطعي پروژه بعد از تاييد اين مديريت و مديريت شهرستان و دستگاه نظارت در وجه شركت مجري آزادسازي خواهد گرديد.';?>
				      </span>
					 <p align="justify"><span class="f14_font">
					 <?php echo 'لازم به ذكر است كه نظارت دقيق بر حسن اجراي پروژه و لوازم ارسالي مطابق پيش فاكتورهاي پيوست توسط دستگاه نظارت (مشاور ذيصلاح طرف قرارداد) شرکت  '.'<b>'.$designercoTitle .'</b>&nbsp;'.' ضمن هماهنگي با كارشناسان ناظر استان و شهرستان انجام و گزارش پيشرفت فيزيكي توسط مشاور در مقاطع ماهانه به اين مديريت ارسال نمايند.';?>
					  </span><br/><br/>
	
				</tr>

         <?php } else if ($freenum==2) { ?>
		   <tr>
					 <p><b><span class="f16_font">مدیریت محترم امور سرمایه گذاری سازمان</span></b></p>
					 <p><b><?php echo 'موضوع : آزادسازي قسط دوم پروژه آبياري ' .'&nbsp;'. $resquery["ApplicantFName"].'&nbsp;'.$resquery["ApplicantName"] .'&nbsp;'. ' '; ?></b></p>
					 <p>سلام علیکم</p>
					 <p align="justify"> <span class="f14_font">
					 <?php echo 'بااحترام، پیرو نامه شماره 
					 ..........
					 مورخ
					 ..........	
					 در دفتر اين سازمان 
					 و با توجه به نامه شماره 
					 ..........
					 مورخ
					 ..........
					 مدیریت محترم جهاد کشاورزی شهرستان '.'<b>'.$resquery["CityName"] .'</b>&nbsp;'.'
					 و نامه شماره
					 ..........
					 مورخ
					 ..........
					 دستگاه نظارت شركت محترم مهندسين مشاور '.'&nbsp;<b>'. $designercoTitle. '</b>&nbsp;'.'
					 مبنی بر اعلام حمل لوازم طرح آبیاری '.'&nbsp;<b>'. $resquery["designsystemgroupsTitle"] . '</b>&nbsp;'.'به مساحت'
					 . '&nbsp;<b>' . $resquery["DesignArea"] .'</b>&nbsp; هکتار متعلق به &nbsp;<b>' . $resquery["ApplicantFName"].'&nbsp;'
					 .$resquery["ApplicantName"] . '</b>&nbsp; واقع در شهرستان &nbsp;'.
					 '<b>'.$resquery["CityName"] .'</b>&nbsp; به محل پروژه، خواهشمند است نسبت به آزادسازی  قسط دوم تا 
					'.'&nbsp; '.$darsadfree12.'% &nbsp;'.'
					  مبلغ پیش فاکتورها به شرح ذیل  اقدام و نتیجه را به این مدیریت اعلام نمایید:'; ?>
					 </span>
                      </p> 
	               </p>				 
					</tr>
	           
			   <tr>
                   <?php	                    
                    $cnt=0;
                    $prefreestatecode='قسط دوم';
                    $sum=0;
                    while($row = mysql_fetch_assoc($result2)){
                        
                        if ($prefreestatecode==$row['freestateTitle'])
                        { $cnt++;
		           ?>      
				   <p align="justify"> <span class="f14_font">
                    <?php echo $cnt.'-مبلغ'.'    &nbsp'.number_format($row['Price']).'    &nbsp'
							.'ریال به حساب شماره'.'    &nbsp'.$row['AccountNo'].'    &nbsp'.'بانک'.'    &nbsp'.$row['AccountBank'].'    &nbsp'
							.'به نام'.'    &nbsp'.$row['producersTitle'].'    &nbsp'.$row['Description']
                             ; 
                    }
                    }
                   ?>
				   </span>
              </p>				 
     		</tr>	
                <tr>
					 <p align="justify"><span class="f14_font">
					 <?php echo '   بدیهی است قسط سوم  نيز پس از تست و راه اندازي و تنظيم صورتجلسه تحويل و تحول موقت با تاييد اين  مديريت و مديريت شهرستان و دستگاه نظارت  پس از كسر 10% حسن انجام اجراي پروژه در وجه مجري پروژه شركت'
					 .'&nbsp;<b>'.$operatorcoTitle. '</b>&nbsp;'.
					 'طرف قرارداد متقاضي قابل پرداخت خواهد بود.'; ?>
					   </span><br/><br/>
		
				</tr>
	
	
   
         <?php } else if ($freenum==3) { ?>
		   <tr>
					 
					 <p><b><span class="f16_font">مدیریت محترم امور سرمایه گذاری سازمان</span></b></p>
					 <p><b><?php echo 'موضوع : آزادسازي قسط سوم پروژه آبياري ' .'&nbsp;'. $resquery["ApplicantFName"].'&nbsp;'.$resquery["ApplicantName"] .'&nbsp;'. ' '; ?></b></p>
					 <p>سلام علیکم</p>
					 <p align="justify"> <span class="f14_font">
					 <?php echo 'بااحترام، پیرو نامه شماره 
					 ..........
					 مورخ
					  ..........	
					 در دفتر اين سازمان   
					 
					در ارتباط با پروژه آبياري '.'&nbsp;<b>'. $resquery["designsystemgroupsTitle"] . '</b>&nbsp;'.'به مساحت'
					 . '&nbsp;<b>' . $resquery["DesignArea"] .'</b>&nbsp; هکتار متعلق به &nbsp;<b>' . $resquery["ApplicantFName"].'&nbsp;'.$resquery["ApplicantName"] . '</b>&nbsp; واقع در شهرستان &nbsp;'.
					 '<b>'.$resquery["CityName"] .'</b>&nbsp;
					منضم به صورت وضعيت قطعي پروژه به مبلغ كل
					'.'&nbsp;<b>'. $resquery['LastTotal']. '</b>&nbsp;'.'
					 میلیون ریال ايفاد و با عنايت به مبلغ قطعي تاييد شده توسط دستگاه نظارت پروژه نامه شماره
					 ..........
					 مورخ
					  ..........	
					 شرکت
					 
					'.'&nbsp;<b>'. $designercoTitle. '</b>&nbsp;'.'
					خواهشمند است ضمن بررسي، مبلغ بلاعوض پروژه را به
					'.'&nbsp;<b>'. $resquery['belaavaz']. '</b>&nbsp;'.'
					ميليون ريال و مبلغ خودياري
					'.'&nbsp;<b>' . $resquery['selfcashhelpval']. '</b>&nbsp;'.'
					میلیون ریال تصحیح و قسط آخر پروژه را آزادسازی نمایید:
					 
					 '; ?>
					 
                  </span>
                      </p> 
	               </p>				 
					</tr>
	                                

			   <tr>
                   <?php	                    
                    $cnt=0;
                    $prefreestatecode='قسط سوم';
                    $sum=0;
                    while($row = mysql_fetch_assoc($result2)){
                        
                        if ($prefreestatecode==$row['freestateTitle'])
                        { $cnt++;
		           ?>      
				  <p align="justify"> <span class="f14_font">
                   <?php echo $cnt.'-مبلغ'.'    &nbsp'.number_format($row['Price']).'    &nbsp'
							.'ریال به حساب شماره'.'    &nbsp'.$row['AccountNo'].'    &nbsp'.'بانک'.'    &nbsp'.$row['AccountBank'].'    &nbsp'
							.'به نام'.'    &nbsp'.$row['producersTitle'].$row['Description']
                             ; 
                    }
                    }
                   ?>
              </p>				 
     		</tr>	
                <tr>
				 <p align="justify"><span class="f14_font">
					 <?php echo '
			 		بديهي است 10% حسن انجام اجراي پروژه به مبلغ
					'.'&nbsp;' . number_format($fehrst10). '&nbsp;'.'
					
					  ريال پس از يكسال بهره‌برداري كامل و تنظيم فرم تحويل و تحول قطعي پروژه بعد از تاييد اين مديريت و مديريت شهرستان و دستگاه نظارت در وجه شركت مجري آزادسازي خواهد گرديد.
						';?>
					   </span><br/><br/>
	
				</tr>
	
	
	
	
         <?php } else if ($freenum==4) { ?>
		   <tr>
					 
					 <p><b><span class="f16_font">مدیریت محترم امور سرمایه گذاری سازمان</span></b></p>
					 <p><b><?php echo 'موضوع : آزادسازي قسط آخر پروژه آبياري ' .'&nbsp;'. $resquery["ApplicantFName"].'&nbsp;'.$resquery["ApplicantName"] .'&nbsp;'. ' '; ?></b></p>
					 <p>سلام علیکم</p>
					 <p align="justify"> <span class="f14_font">
					 <?php echo 'بااحترام، پیرو نامه شماره 
					 ..........
					 مورخ
					  ..........	
					 در دفتر اين سازمان   
					 
					در ارتباط با پروژه آبياري '.'&nbsp;<b>'. $resquery["designsystemgroupsTitle"] . '</b>&nbsp;'.'به مساحت'
					 . '&nbsp;<b>' . $resquery["DesignArea"] .'</b>&nbsp; هکتار متعلق به &nbsp;<b>' . $resquery["ApplicantFName"].'&nbsp;'.$resquery["ApplicantName"] . '</b>&nbsp; واقع در شهرستان &nbsp;'.
					 '<b>'.$resquery["CityName"] .'</b>&nbsp;
					 به مبلغ كل
					'.'&nbsp;<b>'. $resquery['LastTotal']. '</b>&nbsp;'.'
					 میلیون ریال ايفاد و با عنايت به مبلغ قطعي تاييد شده توسط دستگاه نظارت پروژه نامه شماره
					 ..........
					 مورخ
					  ..........	
					 شرکت
					 
					'.'&nbsp;<b>'. $designercoTitle. '</b>&nbsp;'.'
					خواهشمند است ضمن بررسي، مبلغ    
					   قسط آخر پروژه را آزادسازی نمایید:
					 
					 '; ?>
					 
                  </span>
                      </p> 
	               </p>				 
					</tr>
	                                

			   <tr>
                   <?php	                    
                    $cnt=0;
                    $prefreestatecode='حسن انجام کار';
                    $sum=0;
                    while($row = mysql_fetch_assoc($result2)){
                        
                        if ($prefreestatecode==$row['freestateTitle'])
                        { $cnt++;
		           ?>      
				  	 <p align="justify"><span class="f14_font">
			  <?php echo $cnt.'-مبلغ'.'    &nbsp'.number_format($row['Price']).'    &nbsp'
							.'ریال به حساب شماره'.'    &nbsp'.$row['AccountNo'].'    &nbsp'.'بانک'.'    &nbsp'.$row['AccountBank'].'    &nbsp'
							.'به نام'.'    &nbsp'.$row['producersTitle'].$row['Description']
                             ; 
                    }
                    }
                   ?>
              </p>				 
     		</tr>	
                     <?php } ?>
			   
			   
               <tr>  
					 <p><b>رونوشت:</b></p>
					 <p align="justify"><?php echo '-مدیریت محترم جهاد کشاورزی شهرستان '.'<b>'.$resquery["CityName"] .'</b>&nbsp;'.' براي استحضار و دستور مقتضي به منظور اعلام به متقاضي و هماهنگي با دستگاه نظارت جهت كنترل پروژه به انضمام ضمائم';?></p>
					 <p align="justify"><b>-معاون</b>محترم مدیریت برای اطلاع</p>
					 <p align="justify">-رئیس اداره محترم اداره توسعه سیستم های نوین آبیاری برای اطلاع و پیگیری</p>
					 <p align="justify"><?php echo '- شركت محترم مهندسين مشاور '.'&nbsp;<b>'. $designercoTitle. '</b>&nbsp;'.'براي اطلاع و اقدام لازم به منظور نظارت بر اجراي دقيق پروژه و ارسال به موقع گزارش پيشرفت فيزيكي ماهانه به انضمام ضمائم';?></p>
					 <p align="justify"><?php echo '- شركت محترم '.'&nbsp;<b>'.$operatorcoTitle. '</b>&nbsp;'. 'براي اطلاع و اقدام لازم به منظور انجام به موقع تعهدات به انضمام ضمائم';?></p>
					  <p align="justify"><?php echo '- جناب  '.'&nbsp;<b>'.$ClerkIDExcellentSupervisor. '</b>&nbsp;'. 'براي اطلاع و اقدام لازم به انضمام ضمائم';?></p>
				</tr>
			   
	


	
			   
			</tbody>
		</table>
 </div>
			<!-- /content -->
</div>



            <!-- footer -->
			<?php  include('../includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->
	
</body>	
</html>	

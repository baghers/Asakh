<?php

/*

//appinvestigation/applicant_tosandogh.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/applicant_manageredit.php
 
 
-
*/

 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


if ($login_Permission_granted==0) header("Location: ../login.php");
        
            $ApplicantMasterID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);//شناسه طرح
            /*
            applicantmaster مشخصات طرح
            tax_tbcity7digit شهرها
            CityName نام شهر
            designsystemgroups سیستم آبیاری
            creditsource منبع اعتباری
            ApplicantMasterIDشناسه طرح
            id شناسه شهر
            */
    		$query = "SELECT applicantmaster.*,shahr.CityName, 
            designsystemgroups.Title designsystemgroupsTitle,creditsource.title creditsourcetitle
            FROM applicantmaster 
    		left outer join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000'
    		left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) and substring(shahr.id,5,3)='000' 
            and substring(shahr.id,3,5)<>'00000'
            left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.designsystemgroupsid=applicantmaster.designsystemgroupsid
            left outer join creditsource on creditsource.creditsourceid=applicantmaster.creditsourceid
    		WHERE applicantmaster.ApplicantMasterID = '$ApplicantMasterID';";
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
            
			$LastTotal=floor($resquery['LastTotal']/100000)/10;
            
			
            
            $linearray = explode('_',splithektar($ApplicantMasterID));//دریافت تفکیک سطح پروژه
            $ghatre=$linearray[0];//قطره ای
            $sathi=$linearray[1];//سطحی
            $barani=$linearray[2];//بارانی
        
        		
			
			?>

<!DOCTYPE html>
<html>
<head>


	<title>نامه ارسال پرونده جهت تامین اعتبار</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
  
  
  <style>
p {
    display: block;
    margin-top: 0em;
    margin-bottom: 0em;
    margin-left: 0;
    margin-right: 0;
}

f14_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:200%;font-weight: bold;font-family:'B Nazanin';                        
}
.f13_font{
	border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
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
		<table width="600" align="center" class="form">
            <tbody>
                <tr>
					<td>
					 <p><b>جناب آقای مهندس </b></p>
					 <p><b>مدیریت محترم امور سرمایه گذاری سازمان</b></p>
					 <p><b><?php echo 'موضوع : ارسال پرونده طرح آبیاری آقای' .'&nbsp;'. $resquery["ApplicantFName"].'&nbsp;'.$resquery["ApplicantName"] .'&nbsp;'. 'جهت تامین اعتبار'; ?></b></p>
					 <p>سلام علیکم</p>
					 <p align="justify"><?php echo 'با احترام ، به پیوست جدول و یک جلد دفترچه مطالعاتی پروژه سیستم های نوین آبیاری '.'&nbsp;<b>'. $resquery["designsystemgroupsTitle"] . '</b>&nbsp;'.'به مساحت'
					 . '&nbsp;<b>' . $resquery["DesignArea"] .'</b>&nbsp; هکتار متعلق به &nbsp;<b>' . $resquery["ApplicantFName"].'&nbsp;'.$resquery["ApplicantName"] . '</b>&nbsp; واقع در شهرستان &nbsp;'.
					 '<b>'.$resquery["CityName"] .'</b>&nbsp;جهت استفاده از محل کمک های <b>'.$resquery["creditsourcetitle"].'</b>&nbsp;به مبلغ <b>&nbsp;'. $LastTotal . '</b>&nbsp; میلیون ریال جهت اقدام نزد صندوق حمایت از بخش کشاورزی ارسال میگردد.'. 
					 'خواهشمند است ضمن بررسی از نتیجه این مدیریت را مطلع نمایید.'; ?>
					 <p align="justify"><?php if ($resquery['belaavaz']>0) echo 'شایان ذکر است از اعتبار فوق الاشاره مبلغ' .'&nbsp;<b>'. $resquery["belaavaz"]. '</b>&nbsp;میلیون ریال سهم مشارکت دولتی در نظر گرفته شده است ';
					 
                     if ($ghatre>0 || $barani>0 || $sathi>0) 
					 echo "ضمنا";
                     if ($ghatre>0)
                        echo "آبیاری قطره ای <b>&nbsp;$ghatre&nbsp;</b> هکتار ";
                     
                     if ($sathi>0)
                        echo " آبیاری سطحی<b>&nbsp;$sathi</b> هکتار ";
                     
                     if ($barani>0)
                        echo " آبیاری بارانی<b> $barani</b> هکتار ";
                        
                     if ($ghatre>0 || $barani>0 || $sathi>0) 
					  echo 'می باشد.';
				        
                    echo '</p>';	?>
					 <br/><br/>
					 <p><b>رونوشت:</b></p>
					 <p align="justify"><?php echo '-مدیریت محترم جهاد کشاورزی شهرستان '.'<b>'.$resquery["CityName"] .'</b>&nbsp;'.'به انضمام یک نسخه از لیست پیوست و دو جلد گزارش مطالعاتی و ابلاغ به متقاضی به منظور پیگیری و تکمیل پرونده در مدیریت محترم سرمایه گذاری سازمان.';?></p>
					 <p align="justify">ضمناً در صورتی که طی 10 روز از تاریخ صدور معرفینامه متقاضی از لیست خارج خواهد شد.</p>
					 <p align="justify"><b>-معاون</b>محترم مدیریت برای اطلاع</p>					 
					 <p align="justify">-رئیس اداره محترم اداره توسعه سیستم های نوین آبیاری برای اطلاع و پیگیری</p>
					 <p align="justify">-جناب آقای مهندس .......... به منظور ثبت اطلاعات متقاضی در سایت سامانه های نوین آبیاری جهت اطلاعات و اقدام </p>
					 <p align="justify"><?php echo '-جناب آقای <b>'. $resquery["ApplicantFName"].'&nbsp;'.$resquery["ApplicantName"] .'</b>&nbsp;' . 'برای اطلاع و پیگیری لازم. ضمنا در صورت عدم مراجعه طی 10 روز از تاریخ صدور معرفینامه از لیست خارج خواهد شد.';?></p>
					</td>
				</tr>
			</tbody>
		</table>
 </div>
			<!-- /content -->
</div>
            <!-- footer -->
			<?php include('../includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->
	
</body>	
</html>	

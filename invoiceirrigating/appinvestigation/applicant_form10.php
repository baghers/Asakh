<?php 
//اتصال به دیتا بیس
include('../includes/connect.php'); 
// بررسی لاگین شده یا نه 
//از روی سیشن به متغیرها انتقال می دهد
//مثل 
//$login_RolesID
include('../includes/check_user.php'); 
// توابع مرتبط با المنت های اچ تی امال صفحات
include('../includes/elements.php'); 
//نمایش نقاط جغرافیای طرح روی نقشه
require ('../includes/gPoint.php'); 

//شناسه طرح
$ApplicantMasterID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);

//پرس و جوی استخراج کاربر بازبینی که طرح را بازبینی کرده است  	
// در صورتی که بازبین دکتر افشار باشد محل جلسه کمیته فنی مرکز تحقیقات می باشد و در غیر اینصورت شرکت مهندسین مشاور می باشد	
//بدین منظور از جدول تغییر وضعیت ها تغییر وضعیت های 4 و 8 که توسط بازبین انجام می شود استخراج شده و نام کاربری که این تغییر وضعیت ها را داده است بدست می آید
$query = "SELECT max(clerkwin.CPI) CPI,max(clerkwin.DVFS) DVFS
            FROM appchangestate 
            left outer join clerk clerkwin on clerkwin.ClerkID=appchangestate.ClerkID
    		WHERE appchangestate.ApplicantMasterID = '$ApplicantMasterID' and appchangestate.applicantstatesID in (4,8);";
            //print $query;
try 
{		
    $result = mysql_query($query); 
}
//catch exception
catch(Exception $e) 
{
    echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
}
    
			
$resquery = mysql_fetch_assoc($result);
//نام کاربر کد شده
$encrypted_string=$resquery['CPI'];
//فرآیند دیکود شدن نام کاربر
$encryption_key="!@#$8^&*";
$decrypted_string="";
for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
    $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
//نام خانوادگی کد شده
$encrypted_string=$resquery['DVFS'];
//فرآیند دیکود شدن نام خانوادگی کاربر
$encryption_key="!@#$8^&*";
$decrypted_string.=" ";
for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
    $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
    
if ($decrypted_string=='دکتر افشار')
    $spname='مرکز تحقیقات کشاورزی و منابع طبیعی استان';
else 
    $spname=" شرکت ".$decrypted_string;
    
/*
    		$query = "SELECT 
            
            
            -- مشخصات طرح
            applicantmaster.*,
            
			-- عنون شرکت مشاور طراح
            designerco.Title TitleDesign, 
            
			-- عنوان سیستم آبیاری
            designsystemgroups.Title designsystemgroupsTitle,
            
            -- نام شهر طرح
            shahr.cityname shahrcityname,
            
            -- نام استان طرح
            ostan.cityname ostancityname,
            
            -- نوع پروژه 0 آبیاری تحت فشار 1 آبرسانی 2 قنوات
            applicantmasterdetail.prjtypeid,
            
            
            -- منبع اعتباری سطحی
            creditsource.sathival
			
            -- جدول مشخصات طرح
            FROM applicantmaster 
            
            
			-- جدول ارتباطی طرح ها
            -- applicantmasterdetail با توجه به اینکه هر طرح در سه وضعیت مطالعات، پیش فاکتور و صورت وضعیت می باشد و برای هر پروژه تا پایان کار در جدول طرح ها سه طرح مطالعاتی، پیش فاکتور و صورت وضعیت ثبت می شود
            -- لذا این جدول ارتباط این سه مرحله از طرح را نگهداری می کند
            -- این جدول دارای ستون های ارتباطی زیر می باشد
            -- ApplicantMasterID شناسه طرح مطالعاتی
            -- ApplicantMasterIDmaster شناسه طرح اجرایی یا پیش فاکتور
            -- ApplicantMasterIDsurat شناسه طرح صورت وضعیت
			left outer join applicantmasterdetail on 
			(applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID'
			or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID')
	
            
			-- جدول سیستم آبیاری طرح ها
    		left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.designsystemgroupsid=applicantmaster.designsystemgroupsid
            left outer join designerco on applicantmaster.DesignerCoID=designerco.DesignerCoID 
            
            
			-- جدول شهرها
            left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) 
            and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
            
            
			-- جدول استان ها
            inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' 
            
            
			-- جدول منابع اعتباری
			left outer join creditsource on creditsource.creditsourceID = applicantmaster.creditsourceID

    		WHERE applicantmaster.ApplicantMasterID = '$ApplicantMasterID';";

*/
    			
    		$query = "SELECT applicantmaster.*,designerco.Title TitleDesign, 
            designsystemgroups.Title designsystemgroupsTitle,shahr.cityname shahrcityname,ostan.cityname ostancityname
			,applicantmasterdetail.prjtypeid,creditsource.sathival
			
            FROM applicantmaster 
			
			left outer join applicantmasterdetail on 
			(applicantmasterdetail.ApplicantMasterID='$ApplicantMasterID' or applicantmasterdetail.ApplicantMasterIDmaster='$ApplicantMasterID'
			or applicantmasterdetail.ApplicantMasterIDsurat='$ApplicantMasterID')
	
    		left outer join (SELECT DesignSystemGroupsID, title FROM designsystemgroups WHERE DesignSystemGroupsID <>4 UNION ALL SELECT -1 DesignSystemGroupsID, 'قطره اي/ باراني' title) designsystemgroups on designsystemgroups.designsystemgroupsid=applicantmaster.designsystemgroupsid
            left outer join designerco on applicantmaster.DesignerCoID=designerco.DesignerCoID 
            
            left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) 
            and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'
            
            inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(applicantmaster.cityid,1,2) and substring(ostan.id,3,5)='00000' 
    
			left outer join creditsource on creditsource.creditsourceID = applicantmaster.creditsourceID

    		WHERE applicantmaster.ApplicantMasterID = '$ApplicantMasterID';";
            //print $query;
            try 
            {		
                $result = mysql_query($query); 
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            }

			
			$resquery = mysql_fetch_assoc($result);
            
            // نوع پروژه 0 آبیاری تحت فشار 1 آبرسانی 2 قنوات
            $prjtypeid=$resquery["prjtypeid"];
            
            // مبلغ کل طرح
			$LastTotal=$resquery["LastTotal"];
            
            //مساحت طرح
			$DesignArea=$resquery["DesignArea"];
            if($prjtypeid==1)// آبرسانی 
				{
				    //تبدیل متراژ به مساحت
					$DesignArea=round($resquery["TotlainvoiceValues"]/$resquery["sathival"],1);
					$spname="";
                    
                    //متغیر چاپ مساحت
					$prjarea=$DesignArea."&nbsp; هکتار &nbsp; (".$resquery['DesignArea']." متر)";
					$designsystemgroupsTitle="کم فشار آبرسانی";
					
                    //$LastTotal مبلغ کل طرح
                    //$resquery['TotlainvoiceValues'] مبلغ کل پیش فاکتورها
					$TotlValues=number_format($LastTotal)."&nbsp (".number_format($resquery['TotlainvoiceValues']).")";
                    
                    //هزینه لوازم در واحد هکتار
					$TotlValuesper=number_format($resquery['TotlainvoiceValues']/$DesignArea);
				}	
			else //آبیاری تحت فشار و قنوات
				{
				    //$LastTotal مبلغ کل طرح
					$TotlValues=number_format($LastTotal);
                    
                    // مبلغ کل طرح در واحد هکتار
					$TotlValuesper=number_format($LastTotal/$DesignArea);
					
					$prjarea=$DesignArea."&nbsp; هکتار";
                    
                    //$designsystemgroupsTitle سیستم آبیاری
					$designsystemgroupsTitle=$resquery["designsystemgroupsTitle"];
		
				}
				
			//تابع تفکیک سطح طرح 
            //  قطره ای چند هکتار
            //کم فشار چند هکتار
            //بارانی چند هکتار
            $linearray = explode('_',splithektar($ApplicantMasterID));
            
            //تابع تفکیک بر اساس الگوی کشت
            //  قطره ای چه محصولی
            //کم فشار چه محصولی
            //بارانی چه محصولی
            $linearray2 = explode('_',splitimplant($ApplicantMasterID));
            
            
            //رشته چاپ نوع مصول و سطح هریک
            $implantpattern=""; 
            if ($linearray[0]>0 || $linearray2[0]>0)
                $implantpattern.="<br> قطره ای ".$linearray[0]." هکتار (".substr($linearray2[0],3).")";
                
            if ($linearray[1]>0 || $linearray2[1]>0)
            $implantpattern.="<br> کم فشار ".$linearray[1]." هکتار (".substr($linearray2[1],3).")";
            
            if ($linearray[2]>0 || $linearray2[2]>0)
            $implantpattern.="<br> بارانی ".$linearray[2]." هکتار (".substr($linearray2[2],3).")"; 

            //پرس و جوی محاسبه دبی و حق آبه کل بر اساس منابع مختلف آبی
            //applicantwsource جدول منابع آبی طرح ها
            //watersource جدول منابع مختلف آبی
            $strapplicantwsource="";
            $query="select concat(watersource.title,' با دبی ',Wdebi,' لیتر در ثانیه با حقابه ',Whour,' ساعت از مدار ',Wcircle,' روز =',
            (round((Whour/(Wcircle*24))*Wdebi,1))) Title
             
            from applicantwsource 
            inner join watersource on watersource.WaterSourceID=applicantwsource.WaterSourceID
            
            where applicantwsource.ApplicantMasterID ='$ApplicantMasterID'";
            try 
            {		
                $result = mysql_query($query); 
            }
            //catch exception
            catch(Exception $e) 
            {
                echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
            }
            
            //استخراج منابع مختلف آبی 
            while($row = mysql_fetch_assoc($result))
            {
                //$row['Title'] عنوان منبع آبی
                $strapplicantwsource.="<br>(".$row['Title'].")";
            }	
		//در فیلد CountyName مشخصات زیر با فاصله  کاراکتر زیر خط جدا شده اند اولین آیتم روستا می باشد
		$linearray = explode('_',$resquery['CountyName']);
	    $CountyName=$linearray[0];
		$strCountyName='روستا: '.$CountyName.'&nbsp;&nbsp;&nbsp;&nbsp;Xutm=  '.number_format($resquery["XUTM1"],0,'','').'&nbsp;&nbsp;&nbsp;Yutm=   '.
        number_format($resquery['YUTM1'],0,'',''); 
		$strName='کد/شناسه ملی: '.$resquery['melicode'].'&nbsp;&nbsp;&nbsp;&nbsp;همراه:  '.$resquery['mobile']; 
      //تشکیل لینک اطلاعات تكميلي سیستم و محصولات 
					$ID = 'applicantsystemtype_t_0_ApplicantMasterID_'.$ApplicantMasterID;
			         $syslnk= "<a class='no-print' href='../codding/codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                       rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.
                                       rand(10000,99999)."' target=\"_blank\" >
                                     <img style = 'width: 25px;' src='../img/giah.jpg' title=' اطلاعات تكميلي سیستم و محصولات'></a>";
        //تشکیل لینک  اطلاعات تكميلي منبع آبی
                      $ID = 'applicantwsource_t_0_ApplicantMasterID_'.$ApplicantMasterID;
			         $wlnk= "<a class='no-print' href='../codding/codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                       rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.
                                       rand(10000,99999)."' target=\"_blank\" >
                                     <img style = 'width: 15px;' src='../img/ab.jpg' title=' اطلاعات تكميلي منبع آبی'></a>";
                  
        //لینک ویرایش مشخصات طرح
				  		$n1lnk= "<a class='no-print' target='".$target."' href='../insert/applicant_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
										rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterID.rand(10000,99999)."'>
										<img style = 'width: 20px;' src='../img/file-edit-icon.png' title=' ويرايش $ApplicantMasterID'></a>";
		//لینک ویرایش مشخصات مدیریتی طرح
						$n2lnk= "<a class='no-print' target='".$target."' href='applicant_manageredit.php?uid=".rand(10000,99999).rand(10000,99999)
                            .rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                            .$ApplicantMasterID.'_1_'.$row['DesignerCoID'].'_'.$row['operatorcoid'].rand(10000,99999).
                            "'><img style = 'width: 20px;' src='../img/file-edit-icon.png' title=' ويرايش '></a>"; 
                    	if ($login_RolesID==9) 	$nlnk=$n1lnk; else $nlnk=$n2lnk;
 
        //لینک نمایش مختصات جغرافیایی در نقشه
								$myHome =& new gPoint();
                                $myHome->setUTM( $resquery['XUTM1'], $resquery['YUTM1'], "40V");
                                $myHome->convertTMtoLL(); 
                                $gps="
                                   <a class='no-print' onclick=\"lookupGeoData(".$myHome->Lat().",".$myHome->Long().")\" href=\"#\">
                                <img style = 'width: 15px;' 
								src='../img/gmap.png' title=' موقعیت '></a>
                            "; 
	        
 
        //لینک ریز مشخصات پروژه و هزینه های طرح
					$ID = $ApplicantMasterID.'_3_'.$row['DesignerCoID'].'_'.$row['operatorcoid'].'_'.$row['applicantstatesID']
                        .'_'.$login_RolesID;
                     
					$search= "<a  class='no-print' target='".$target."' 
                            href='../insert/summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).
                            "'><img style = 'width: 20px;' src='../img/search.png' title=' ريز '></a>";
                     						
        //اینکه طرح تجمیعی از خرده مالکین است یا نه
        //طرح های تجمیعی فارغ از نوع سیستم آبیاری حداکثر مبلغ بلاعوض یعنی 85 درصد را دریافت می کنند  
                  $criditType="";
                  if ($resquery['criditType']==1)
                  $criditType='(تجمیع)';
 //نقش های مجاز برای مشاهده لینک ها
$permitstateid = array("2","3","4","23","46","50");
if (in_array($resquery['applicantstatesID'], $permitstateid))				             
$login_state=0; else $login_state=1;
	
                                      
	   ?>	
								

<!DOCTYPE html>
<html>
<head>


	<title>فرم شماره (6) تائیدیه کمیته فنی</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
  <script src="http://api.mygeoposition.com/api/geopicker/api.js" type="text/javascript"></script>
    <script type="text/javascript">
        function lookupGeoData(Lat,Lng) {
            myGeoPositionGeoPicker({
                returnFieldMap            : {'geoposition5' : '<LAT>,<LNG>'},
                startPositionLat        : Lat,
                startPositionLng        : Lng
            });
        }
    </script> 
  
 <?php if ($login_state==0) { ?>
   <style>
.tbl {
    background-image: url("../img/bak.gif") !important;
    background-repeat: repeat-y;
} 

</style>
<?php } ?>
 
  <style>


.center {
    margin: auto;
    width: 50%;
    border: 3px solid green;
    padding: 10px;
}
.my_font1{font-size:17pt;font-weight: bold;font-family:'B Nazanin';border:0px solid black;line-height: 100%;}
.my_font2{font-size:21pt;font-weight: bold;font-family:'B Nazanin';}
</style>






<body>

	
				<!-- container 
	<div id="container" >-->

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
			
	
                  	
				
                		<table  align="center"  class="tbl">
            <tbody class="my_font1">
            <?php if ($login_state==1) {?>
                  <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td colspan="2">  <span class="f8_font" >تاریخ چاپ:<?php echo gregorian_to_jalali(date('Y-m-d'));?></span>  </td>
                </tr>
				  
                <tr><td></td>
					<td colspan="2" class="my_font2">
					 <p><b><?php echo "";?> مدیریت محترم آب و خاک و امور فنی و مهندسی</b></p>
					 <p><b>سازمان جهاد کشاورزی <?php echo $resquery["ostancityname"];?></b></p>
					 <p><b>موضوع :فرم شماره (6) تائیدیه کمیته فنی</b></p>
					 </td>
                     </tr>
                     <tr><td></td><td class="my_font1" colspan='2'><p > جلسه کمیته فنی مورخ <input class="my_font1"  value='<?php echo gregorian_to_jalali(date('Y-m-d')); ?>' size='9'  /> در محل <input class="my_font1"  value='<?php echo $spname; ?>' size='44'  /><br>
                      تشکیل و طرح سامانه های نوین آبیاری با مشخصات زیر به تائید رسید.</p></td></tr>
                     
				<?php } ?>	 
                     <tr><td></td><td class="my_font1" width="40%"><p ><?php echo '<b>1- نام و نام خانوادگی متقاضی : '.'&nbsp;<b>'.$resquery["ApplicantFName"].'&nbsp;'.$resquery["ApplicantName"] . '</b></td><td  width=\'60%\' class=\'my_font1\'>'.$resquery["BankCode"] .'</b><br>('.$strName.')</br></p>';?></td></tr>
                      
					 <tr><td></td><td class="my_font1"><p><b><?php 
							echo "2- مساحت طرح: $nlnk</td><td class='my_font1'>".$prjarea."
							 
							</p>";?></td></tr>
					 <tr><td></td><td class="my_font1"><p><b><?php 
							echo "3- دبی منطبق با پروانه بهره برداری:$wlnk</td><td class='my_font1'>". 
							$resquery["Debi"]."&nbsp; لیتر در ثانیه $strapplicantwsource</p>";?></td></tr>
					 
                     <tr><td ></td><td class="my_font1"><p><b><?php echo "4- الگوی کشت تایید:$syslnk</td><td class='my_font1'>". $implantpattern.'</b>&nbsp; <b></p>';?></td></tr>
                     <tr><td></td><td class="my_font1"><p><b><?php echo '5- نوع روش آبیاری طراحی شده:</td><td class=\'my_font1\'>'. $designsystemgroupsTitle.' '.$criditType.'</b>&nbsp; <b></p>';?></td></tr>
					 <tr><td></td><td class="my_font1"><p><b><?php echo "6- هزینه کل خرید و اجرای طرح بر حسب ریال:$search</td><td class='my_font1'>". 
							$TotlValues."&nbsp;  </p>";?></td></tr>
					
					<tr><td></td><td class="my_font1"><p><b><?php echo "7-هزینه واحد سطح برحسب ریال:</td><td class='my_font1'>". 
							$TotlValuesper."&nbsp;  </p>";?></td></tr>
			                    
				        <tr><td></td><td class="my_font1"><p><b><?php echo '8- نام شرکت طراح:</td><td class=\'my_font1\'>'. $resquery["TitleDesign"].'</b>&nbsp;  </p>';?></td></tr>
				     <tr><td></td><td class="my_font1"><p><b><?php echo '9- نام شهرستان:</td><td class=\'my_font1\'>'. $resquery["shahrcityname"].'</b><br>('.$strCountyName.')'.$gps.'</br>&nbsp;  </p></td></tr>';?>
						 
                     
  <?php if ($login_state==1) 
	{?>
             		   
	                <tr><td></td><td colspan="1" align="center" class="my_font2"><p >تاییدیه اعضای کمیته فنی</p></td></tr>
					<tr><td></td><td colspan="1" align="center" class="my_font1"><p >الف- مدیر آب و خاک و امور فنی مهندسی</p></td>
	            
	                <td colspan="1" align="center" class="my_font1"><p >ب- کارشناس مسئول سامانه  های نوین استان</p></td></tr>
					    	<tr><td></td><td colspan="1" align="center" class="my_font1"><p ></p></td></tr>
	        
	                <tr><td></td><td colspan="1" align="center" class="my_font1"><p >ج- کارشناس آب و خاک شهرستان</p></td></tr>
					    	<tr><td></td><td colspan="1" align="center" class="my_font1"><p ></p></td></tr>
	        
					<tr><td></td><td colspan="1" align="center" class="my_font1"><p >د- مهندسین مشاور طراح</p></td>
			
	        
	               <td colspan="1" align="center" class="my_font1"><p >ه- مهندسین مشاور بازبین</p></td></tr>
					    	<tr><td></td><td colspan="1" align="center" class="my_font1"><p ></p></td></tr>
	        
					   
					   
					   
	<?php }
                        //ایجاد لینک های فایل های پروژه شامل فایل اتوکد دفترچه طراحی  دفترچه محاسبات
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
								//$ApplicantMasterID شناسه طرح
                                //$No:
                                //1=فایل اتوکد
                                //2=دفترچه طراحی
                                //3=دفترچه محاسبات
                                 if (($ID==$ApplicantMasterID) && ($No==1) )
                                    $fstr1="<a href='../../upfolder/$file' ><img style = 'width: 1%;' src='../img/accept.png' title='فایل اتوکد' ></a>
                                    ";
                                
                                 if (($ID==$ApplicantMasterID) && ($No==2) )
                                    $fstr2="<a href='../../upfolder/$file' ><img style = 'width: 1%;' src='../img/accept.png' title='دفترچه طراحی' ></a>
                                    ";
                                
                                if (($ID==$ApplicantMasterID) && ($No==3) )
                                    $fstr3="
                                    <a href='../../upfolder/$file' ><img style = 'width: 1%;' src='../img/accept.png' title='دفترچه محاسبات' ></a>
                                    ";        
                            }
                        }
                        
							
    					// print "</tr><tr><td colspan='3'>--------------------------------------------------------------------------------------------------------------------------------------------</td>";
                         
                            echo " 
                      
                            <tr>
                            <td></td><td colspan='2' class='label'>□ فایل&nbspنقشه&nbsp(با&nbspفرمت&nbspAutoCAD)
                             $fstr1
                             </tr>
                             
                             <tr>
                            <td></td> <td colspan='2' class='label'>□ فایل&nbspدفترچه&nbsp(با&nbspفرمت&nbspOffice)
                             $fstr2
                            </tr>
                             
                             <tr>
                             <td></td><td colspan='2' class='label'>□ فایل&nbspمحاسبات&nbsp(با&nbspفرمت&nbspOffice)
                             $fstr3
                             </tr>";
                       
						//print "</tr><tr><td colspan='2'>--------------------------------------------------------------------------------------------------------------------------------------------</td>";
                      









				?>





				
            		 
			</tbody>
		</table>
               
 </div>
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

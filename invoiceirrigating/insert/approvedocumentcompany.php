<?php

/*

insert/approvedocumentcompany.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
members_operatorcos.php
*/
 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


$display=" " ;
$labl=" ";
$producType="شرکت";			
if ($login_Permission_granted==0) header("Location: ../login.php");
if (!$_POST) 
{ 
    $ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $linearray = explode('_',$ID);
    $TBLNAME=$linearray[0];//نام جدول
    $TBLTITLE=$linearray[1];//عنوان
    $TBLID=$linearray[2];//شناسه
    $TYPECON = $linearray[3];//نوع

	if ( $login_ProducersID>0 || $TBLNAME=="producers")
    { 
        /*
        producers جدول تولیدکنندگان
        PipeProducer تولیدکننده لوله بودن
        ProducersID شناسه تولیدکننده
        */
	  $sqlpr = "SELECT producers.PipeProducer 
	  ,case producers.PipeProducer when 1 then 'شرکت' when 2 then 'شرکت' when 3 then 'شرکت' when 4 then 'شرکت' when 5 then 'شرکت' when 6 then 'شرکت'
								  when 101 then 'فروشگاه' when 102 then 'فروشگاه' when 103 then 'فروشگاه' when 104 then 'فروشگاه' 
								  when 105 then 'فروشگاه' when 106 then 'فروشگاه' when 107 then 'فروشگاه' end producType 
	  FROM producers where producers.ProducersID=$login_ProducersID;";
	  
	  					   		try 
								  {		
									        $resultpr = mysql_query($sqlpr);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

      $resquerypr = mysql_fetch_assoc($resultpr);
	  $producType=$resquerypr["producType"];
	   if ($producType=='فروشگاه'){$display="display:none" ;	$labl=" و یا  پروانه کسب ";}
		
		if ($TBLID>0) 
            $IDUser = $TBLID; else  $IDUser = $login_ProducersID;
        $POD = 1; 
        $path = "../../upfolder/producerapproval/producers/";


	}
	
	
    else if ($login_OperatorCoID>0 || $TBLNAME=="operatorco")//مجری
    { 
        if ($TBLID>0) 
            $IDUser = $TBLID; else $IDUser = $login_OperatorCoID;
        $POD = 2;	
        $path = "../../upfolder/producerapproval/operatoco/";
    }	
    else if ($login_DesignerCoID>0 || $TBLNAME=="designerco" ) //طراح 
    { 
        if ($TBLID>0) 
            $IDUser = $TBLID; else  $IDUser = $login_DesignerCoID;
        $POD = 3;
        $path = "../../upfolder/producerapproval/designerco/";
    }

	
} 
	
	
if ($_POST)
{     
 $TBLID=$_POST['TBLID'];
    $IDUser = $_POST['IDUser'];
	$showb=0;
    if ($_POST['showb']=='on')   {$showb=1;}
	
    $POD = $_POST['POD'];
    $path = $_POST['path'];
    $qmode = $_POST['qmode'];
    if ($POD == 3)
        $TBLNAME="designerco";
    else if ($POD == 2)
        $TBLNAME="operatorco";
    else if ($POD == 1)
        $TBLNAME="producers";
		
	if ($POD == 1) $rank="rank";	 else $rank="corank";
    if ($login_RolesID==10)
    {

        $engineersystemdate = $_POST['engineersystemdate'];
        $engineersystemvalidate = $_POST['engineersystemvalidate'];
        $engineersystemno = $_POST['engineersystemno'];
        $engineersystemIssuer = $_POST['engineersystemIssuer'];       
	   $query = "UPDATE $TBLNAME SET 
        engineersystemdate = '$engineersystemdate',engineersystemvalidate = '$engineersystemvalidate',engineersystemno ='$engineersystemno',
        engineersystemIssuer = '$engineersystemIssuer',
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "' 
        WHERE $TBLNAME"."ID='$IDUser'";
          
  					   		try 
								  {		
									        $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
		
    }
    if ((in_array($login_RolesID,  array("1", "4", "18","20"))) && $showb==0)
    
	{
    	   /*
        operatorco جدول شرکت های پیمانکار
        designer جدول شرکت های طراح
        members جدول اعضای هیئت مدیره
        operatorapprequest جدول پیشنهاد قیمت های طرح
        clerk جدول کاربران
        fundationYear تاریخ تاسیس شرکت پیمانکار
        fundationno شماره مدرک تاسیس پیمانکار
        fundationIssuer مرجع صادر کننده صلاحیت پیمانکار
        boardchangeno شماره نامه آخرین تغییرات
        boardchangedate تاریخ آخرین تغییرات هیئت مدیره
        boardvalidationdate تاریخ اعتبار مدرک رئیس هیئت مدیره
        boardIssuer مرجع صادرکننده مدرک هیئت مدیره
        copermisionno تعداد پروژه های قابل انجام
        StarCo تعداد ستاره های شرکت
        ent_Num تعداد انتظامی بودن شرکت
        ent_DateTo پایان انتظامی بودن شرکت
        copermisiondate تاریخ مجوز شرکت
        copermisionvalidate تاریخ اعتبار مجوز شرکت
        copermisionIssuer مرجع صادر کننده مجوز شرکت
        contractordate تاریخ قرارداد شرکت
        contractorvalidate تاریخ اعتبار قرارداد شرکت
        contractorno شماره نامه قرارداد شرکت
        contractorIssuer مرجع صادرکننده قرارداد شرکت
        contractorRank1 رتبه شرکت نفر 1
        contractorField1 شرح رتبه شرکت نفر 1
        contractorRank2 رتبه شرکت نفر 2
        contractorField2 شرح رتبه شرکت نفر 2
        engineersystemdate تاریخ مدرک مهندس شرکت
        engineersystemvalidate تاریخ اعتبار مدرک مهندس شرکت
        engineersystemno شماره مدرک مهندس شرکت
        engineersystemIssuer مرجع صادر کننده مدرک مهندس شرکت
        engineersystemRank رتبه  مهندس شرکت
        engineersystemField شرح مهندس شرکت
        valueaddeddate تاریخ گواهی ارزش افزوده
        valueaddedvalidate تاریخ اعتبار گواهی ارزش افزوده
        valueaddedno شماره گواهی ارزش افزوده
        valueaddedIssuer مرجع گواهی ارزش افزوده
        operatorcoID شناسه شرکت مجری
        membersinfo.FName نام
        membersinfo.LName نام خانوادگی
        projectcount92 تعداد پروژه های اول دوره پیمانکار
        projecthektar92 مساحت پروژه های انجام داده شده پیمانکار
        Title عنوان شرکت
    	CompanyAddress آدرس شرکت
        Phone2 تلفن دوم شرکت
        bossmobile موبایل مدیر عامل شرکت 
        corank رتبه شرکت
        firstperiodcoprojectarea مجموع مساحت پروژه های انجام داده اول دوره شرکت
        firstperiodcoprojectnumber تعداد  پروژه های انجام داده اول دوره شرکت
        coprojectsum مجموع تعدادی پروژه های شرکت
        projecthektardone پروژه های انجام داده شرکت
        simultaneouscnt تعداد پروژه های همزمان
        thisyearprgarea مساحت پرژه های امسال
        above20cnt تعداد پروژه های بالای 20 هکتار
        above55cnt تعداد پروژه های بالای 55 هکتار
        currentprgarea مساحت پروژه های جاری
        projectcountdone تعداد پروژه های انجام داده شرکت
        clerk.clerkid شناسه کاربر
        designerinfo.designercnt تعداد کارشناسان طراح شرکت
        designerinfo.dname نام کارشناس طراح
        designerinfo.duplicatedesigner داشتن کارشناسی که در دو شرکت فعالیت نماید
        membersinfo.duplicatemembers عضو هیئت مدیره که در دو شرکت فعالیت نماید
        allreq.cnt reqcnt تعداد پیشنهادات ارسال شده
        allwinreq.wincnt تعداد پیشنهادات انتخاب شده
        avgpmreq.avg میانگین ظرایب پیشنهاد قیمت های شرکت
        avgpmreqa.avga میانگین ظرایب پیشنهاد قیمت های انتخابی
        BossName نام مدیر عامل
        bosslname نام خانوادگی مدیر عامل
        */
        
        $fundationYear = $_POST['fundationYear'];
        $fundationno = $_POST['fundationno'];
        $fundationIssuer = $_POST['fundationIssuer'];
        $boardchangeno = $_POST['boardchangeno'];
        $boardchangedate = $_POST['boardchangedate'];
        $boardvalidationdate = $_POST['boardvalidationdate'];
        $boardIssuer = $_POST['boardIssuer'];
        $copermisionno = $_POST['copermisionno'];
        $copermisiondate = $_POST['copermisiondate'];
        $corank = $_POST['corank'];
        $copermisionvalidate = $_POST['copermisionvalidate'];
        $copermisionIssuer = $_POST['copermisionIssuer'];
        $contractordate = $_POST['contractordate'];
        $contractorvalidate = $_POST['contractorvalidate'];
        $contractorno = $_POST['contractorno'];
        $contractorIssuer = $_POST['contractorIssuer'];
        $engineersystemdate = $_POST['engineersystemdate'];
        $engineersystemvalidate = $_POST['engineersystemvalidate'];
        $engineersystemno = $_POST['engineersystemno'];
        $engineersystemIssuer = $_POST['engineersystemIssuer'];
        $valueaddeddate = $_POST['valueaddeddate'];
        $valueaddedvalidate = $_POST['valueaddedvalidate'];
        $valueaddedno = $_POST['valueaddedno'];
        $valueaddedIssuer = $_POST['valueaddedIssuer'];        
	   $query = "UPDATE $TBLNAME SET fundationYear = '$fundationYear',fundationno = '$fundationno',fundationIssuer = '$fundationIssuer', 
        boardchangeno = '$boardchangeno',boardchangedate = '$boardchangedate',boardvalidationdate = '$boardvalidationdate',
        boardIssuer = '$boardIssuer',copermisionno = '$copermisionno',copermisiondate = '$copermisiondate',$rank = '$corank',
        copermisionvalidate = '$copermisionvalidate',copermisionIssuer = '$copermisionIssuer',contractordate = '$contractordate',
        contractorvalidate = '$contractorvalidate',contractorno = '$contractorno',contractorIssuer = '$contractorIssuer',
        engineersystemdate = '$engineersystemdate',engineersystemvalidate = '$engineersystemvalidate',engineersystemno ='$engineersystemno',
        engineersystemIssuer = '$engineersystemIssuer',valueaddeddate = '$valueaddeddate',valueaddedvalidate = '$valueaddedvalidate',
        valueaddedno = '$valueaddedno',valueaddedIssuer = '$valueaddedIssuer',
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "' 
        WHERE $TBLNAME"."ID='$IDUser'";
       
						   		try 
								  {		
									        $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

		//print $query;exit;
    }
    else
    {
        $fundationYear = $_POST['fundationYeartmp'];
        $fundationno = $_POST['fundationnotmp'];
        $fundationIssuer = $_POST['fundationIssuertmp'];
        $boardchangeno = $_POST['boardchangenotmp'];
        $boardchangedate = $_POST['boardchangedatetmp'];
        $boardvalidationdate = $_POST['boardvalidationdatetmp'];
        $boardIssuer = $_POST['boardIssuertmp'];
        $copermisionno = $_POST['copermisionnotmp'];
        $copermisiondate = $_POST['copermisiondatetmp'];
        $corank = $_POST['coranktmp'];
        $copermisionvalidate = $_POST['copermisionvalidatetmp'];
        $copermisionIssuer = $_POST['copermisionIssuertmp'];
        $contractordate = $_POST['contractordatetmp'];
        $contractorvalidate = $_POST['contractorvalidatetmp'];
        $contractorno = $_POST['contractornotmp'];
        $contractorIssuer = $_POST['contractorIssuertmp'];
        $engineersystemdate = $_POST['engineersystemdatetmp'];
        $engineersystemvalidate = $_POST['engineersystemvalidatetmp'];
        $engineersystemno = $_POST['engineersystemnotmp'];
        $engineersystemIssuer = $_POST['engineersystemIssuertmp'];
        $valueaddeddate = $_POST['valueaddeddatetmp'];
        $valueaddedvalidate = $_POST['valueaddedvalidatetmp'];
        $valueaddedno = $_POST['valueaddednotmp'];
        $valueaddedIssuer = $_POST['valueaddedIssuertmp'];
           
        $query = "SELECT count(*) cnt from tmpco where  UID='$IDUser' and type='$POD' " ;
    
       				   		try 
								  {		
									        $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

        $resquery = mysql_fetch_assoc($result);
    	
        $cnt = $resquery['cnt'];
        
        if ($cnt>=1)
              
            $query = "UPDATE tmpco SET fundationYear = '$fundationYear',fundationno = '$fundationno',fundationIssuer = '$fundationIssuer', 
            boardchangeno = '$boardchangeno',boardchangedate = '$boardchangedate',boardvalidationdate = '$boardvalidationdate',
            boardIssuer = '$boardIssuer',copermisionno = '$copermisionno',copermisiondate = '$copermisiondate',corank = '$corank',
            copermisionvalidate = '$copermisionvalidate',copermisionIssuer = '$copermisionIssuer',contractordate = '$contractordate',
            contractorvalidate = '$contractorvalidate',contractorno = '$contractorno',contractorIssuer = '$contractorIssuer',
            engineersystemdate = '$engineersystemdate',engineersystemvalidate = '$engineersystemvalidate',engineersystemno ='$engineersystemno',
            engineersystemIssuer = '$engineersystemIssuer',valueaddeddate = '$valueaddeddate',valueaddedvalidate = '$valueaddedvalidate',
            valueaddedno = '$valueaddedno',valueaddedIssuer = '$valueaddedIssuer',
    		SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', 
    		ClerkID = '" . $login_userid . "' 
            WHERE UID='$IDUser' and type='$POD'";
    	else	
            $query = "insert into tmpco (fundationYear,fundationno,fundationIssuer, 
            boardchangeno,boardchangedate,boardvalidationdate,
            boardIssuer,copermisionno,copermisiondate,
            copermisionvalidate,corank,copermisionIssuer,contractordate,
            contractorvalidate,contractorno,contractorIssuer,
            engineersystemdate,engineersystemvalidate,engineersystemno,
            engineersystemIssuer,valueaddeddate,valueaddedvalidate,
            valueaddedno,valueaddedIssuer,UID,type,SaveTime,SaveDate,ClerkID) values 
            ( '$fundationYear','$fundationno','$fundationIssuer', 
            '$boardchangeno','$boardchangedate','$boardvalidationdate',
            '$boardIssuer','$copermisionno','$copermisiondate',
            '$copermisionvalidate','$corank','$copermisionIssuer','$contractordate',
            '$contractorvalidate','$contractorno','$contractorIssuer',
            '$engineersystemdate','$engineersystemvalidate','$engineersystemno',
            '$engineersystemIssuer','$valueaddeddate','$valueaddedvalidate',
            '$valueaddedno','$valueaddedIssuer','$IDUser','$POD', '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$login_userid."')";
         
            			   		try 
								  {		
									        $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    		//print $query;



    		
    		if ($_FILES["file1"]["error"] > 0) 
            {
                //echo "Error: " . $_FILES["file1"]["error"] . "<br>";
                //exit;
            } 
            else 
            {
    		 if (($_FILES["file1"]["size"] / 1024)>200)
            {
                print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
                exit;
            }
                $ext = end((explode(".", $_FILES["file1"]["name"])));
                $attachedfile=$IDUser.'_1_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
                //print $path.$attachedfile;
                foreach (glob($path. $IDUser.'_1*') as $filename) 
                {
                    unlink($filename);
                }move_uploaded_file($_FILES["file1"]["tmp_name"],$path.$attachedfile);   
            }
     		
            if ($_FILES["file2"]["error"] > 0) 
            {
                //echo "Error: " . $_FILES["file2"]["error"] . "<br>";
                //exit;
            } 
            else 
            {
    		 if (($_FILES["file2"]["size"] / 1024)>200)
            {
                print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
                exit;
            }
                $ext = end((explode(".", $_FILES["file2"]["name"])));
                $attachedfile=$IDUser.'_2_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
                foreach (glob($path. $IDUser.'_2*') as $filename) 
                {
                    unlink($filename);
                }
                move_uploaded_file($_FILES["file2"]["tmp_name"],$path.$attachedfile);   
            }
            		
            if ($_FILES["file3"]["error"] > 0) 
            {
                //echo "Error: " . $_FILES["file3"]["error"] . "<br>";
                //exit;
            } 
            else 
            {
    		 if (($_FILES["file3"]["size"] / 1024)>200)
            {
                print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
                exit;
            }
                $ext = end((explode(".", $_FILES["file3"]["name"])));
                $attachedfile=$IDUser.'_3_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
                foreach (glob($path. $IDUser.'_3*') as $filename) 
                {
                    unlink($filename);
                }
                move_uploaded_file($_FILES["file3"]["tmp_name"],$path.$attachedfile);   
            }
            		
            if ($_FILES["file4"]["error"] > 0) 
            {
                //echo "Error: " . $_FILES["file4"]["error"] . "<br>";
                //exit;
            } 
            else 
            {
    		 if (($_FILES["file4"]["size"] / 1024)>200)
            {
                print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
                exit;
            }
                $ext = end((explode(".", $_FILES["file4"]["name"])));
                $attachedfile=$IDUser.'_4_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
                foreach (glob($path. $IDUser.'_4*') as $filename) 
                {
                    unlink($filename);
                }
                move_uploaded_file($_FILES["file4"]["tmp_name"],$path.$attachedfile);   
            }
            		
            if ($_FILES["file5"]["error"] > 0) 
            {
                //echo "Error: " . $_FILES["file5"]["error"] . "<br>";
                //exit;
            } 
            else 
            {
    		 if (($_FILES["file5"]["size"] / 1024)>200)
            {
                print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
                exit;
            }
                $ext = end((explode(".", $_FILES["file5"]["name"])));
                $attachedfile=$IDUser.'_5_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
                foreach (glob($path. $IDUser.'_5*') as $filename) 
                {
                    unlink($filename);
                }
                move_uploaded_file($_FILES["file5"]["tmp_name"],$path.$attachedfile);   
            }
            		
            if ($_FILES["file6"]["error"] > 0) 
            {
                //echo "Error: " . $_FILES["file6"]["error"] . "<br>";
                //exit;
            } 
            else 
            {
    		 if (($_FILES["file6"]["size"] / 1024)>200)
            {
                print "حداکثر اندازه مجاز فایل اسکن 200 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
                exit;
            }
                $ext = end((explode(".", $_FILES["file6"]["name"])));
                $attachedfile=$IDUser.'_6_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
                foreach (glob($path. $IDUser.'_6*') as $filename) 
                {
                    unlink($filename);
                }
                move_uploaded_file($_FILES["file6"]["tmp_name"],$path.$attachedfile);   
            }        
        
    }
  

}

?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست تاییدیه ها</title>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
		
		<link rel="stylesheet" href="../assets/style.css" type="text/css" />
		<link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
		<script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
		<script type="text/javascript" src="../js/persiandatepicker.js"></script>

    <script>



                
    </script>
	
	 <script type="text/javascript">
            $(function() {
				           
                $("#fundationYear, #simpleLabel").persiandatepicker();   
                $("#valueaddeddate, #simpleLabel").persiandatepicker();   
                $("#valueaddedvalidate, #simpleLabel").persiandatepicker();   
				$("#boardchangedate, #simpleLabel").persiandatepicker();   
                $("#boardvalidationdate, #simpleLabel").persiandatepicker();   
			    $("#copermisiondate, #simpleLabel").persiandatepicker();   
				$("#copermisionvalidate, #simpleLabel").persiandatepicker();   
                $("#contractordate, #simpleLabel").persiandatepicker();   
				$("#contractorvalidate, #simpleLabel").persiandatepicker();   
                $("#engineersystemdate, #simpleLabel").persiandatepicker();   
				$("#engineersystemvalidate, #simpleLabel").persiandatepicker();   
                
                $("#fundationYeartmp, #simpleLabel").persiandatepicker();   
                $("#valueaddeddatetmp, #simpleLabel").persiandatepicker();   
                $("#valueaddedvalidatetmp, #simpleLabel").persiandatepicker();   
				$("#boardchangedatetmp, #simpleLabel").persiandatepicker();   
                $("#boardvalidationdatetmp, #simpleLabel").persiandatepicker();   
			    $("#copermisiondatetmp, #simpleLabel").persiandatepicker();   
				$("#copermisionvalidatetmp, #simpleLabel").persiandatepicker();   
                $("#contractordatetmp, #simpleLabel").persiandatepicker();   
				$("#contractorvalidatetmp, #simpleLabel").persiandatepicker();   
                $("#engineersystemdatetmp, #simpleLabel").persiandatepicker();   
				$("#engineersystemvalidatetmp, #simpleLabel").persiandatepicker(); 
                
	
				
            });
                
    </script>
	<style>
		td.rowtable {
		text-align:center; height:30px; vertical-align:middle;
		}
	</style>
	
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
            <!--	 /main navigation -->
			<!-- main navigation -->
            <?php include('../includes/subnavigation.php'); ?>
            <!-- /main navigation -->

			<!-- header -->
            <?php //include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
			  <div style = "text-align:left;">
				<?php 
				
				if ($result==1) print $secerror= '<p class="note">اطلاعات با موفقيت ذخيره شد.'; 
				?>
				<a  href=<?php 
					$permitrolsid = array("1", "4","18","20","21");
					if (in_array($login_RolesID, $permitrolsid) && $TBLID>0 ) 
					{
						if ($TBLNAME=="producers")
						print "../members_" . $TBLNAME.".php"."><img style = \"width: 2%;\" src=\"../img/Return.png\" title='بازگشت' ></a>";
						else 
						print "../members_" . $TBLNAME."s.php"."><img style = \"width: 2%;\" src=\"../img/Return.png\" title='بازگشت' ></a>";
					}
					else					
					print "../home.php"."><img style = \"width: 2%;\" src=\"../img/Return.png\" title='بازگشت' ></a>";
			
			if ($result==1) print $secerror= '</p>'; 
					
							?>
				
				</div>
				
				<form action="approvedocumentcompany.php" method="post" enctype="multipart/form-data">
			    <div id="loading-div-background">
					<div id="loading-div" class="ui-corner-all" >
						 <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
						 <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
					</div>
			    </div>
				<table id="recordtable" width="99%" align="center">
                   <tbody>           
               

                   <?php         
                   $fstr1="";$fstr2="";$fstr3="";$fstr4="";$fstr5="";$fstr6="";
					if ($POD==1)
                    $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/producerapproval/producers/';
					else if ($POD==3)
					$directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/producerapproval/designerco/';
					else if ($POD==2)
					$directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/producerapproval/operatoco/';

					
                if ($POD>0) 
				{
					$handler = opendir($directory);
                    while ($file = readdir($handler)) 
                    {
                        // if file isn't this directory or its parent, add it to the results
                        if ($file != "." && $file != "..") 
                        {
                            
                            $linearray = explode('_',$file);
                            $ID=$linearray[0];
                            $No=$linearray[1];
							$path1 = $path.$file;
                            if (($ID==$IDUser) && ($No==1) )
                                $fstr1="<a target='blank' href='$path1' ><img style = 'width: 20px;' src='../img/accept.png'  ></a>";
                            if (($ID==$IDUser) && ($No==2) )
                                $fstr2="<a target='blank'  href='$path1' ><img style = 'width: 20px;' src='../img/accept.png' ></a>";
                            if (($ID==$IDUser) && ($No==3) )
                                $fstr3="<a target='blank'  href='$path1' ><img style = 'width: 20px;' src='../img/accept.png' ></a>";        
                            if (($ID==$IDUser) && ($No==4) )
                                $fstr4="<a target='blank'  href='$path1' ><img style = 'width: 20px;' src='../img/accept.png' ></a>";        
                            if (($ID==$IDUser) && ($No==5) )
                                $fstr5="<a target='blank'  href='$path1' ><img style = 'width: 20px;' src='../img/accept.png' ></a>";        
                            if (($ID==$IDUser) && ($No==6) )
                                $fstr6="<a target='blank'  href='$path1' ><img style = 'width: 20px;' src='../img/accept.png' ></a>";        
                        }
                    }
				}
	
				if (in_array($login_RolesID,  array("1", "4", "18","20","10")))
                    $disabled_string='';
                else 
                    $disabled_string='disabled';
                
				if ($POD==1) {
                $sql = "
                SELECT ( exists (SELECT * FROM tmpco where UID='$IDUser' and type='$POD')) qmode,
                tmpco.fundationYear fundationYeartmp,tmpco.fundationno fundationnotmp,tmpco.fundationIssuer fundationIssuertmp,tmpco.boardchangeno boardchangenotmp,
                tmpco.boardchangedate boardchangedatetmp,tmpco.boardvalidationdate boardvalidationdatetmp,tmpco.boardIssuer boardIssuertmp,
                tmpco.copermisionno copermisionnotmp,tmpco.copermisiondate copermisiondatetmp,tmpco.copermisionvalidate copermisionvalidatetmp,
                tmpco.copermisionIssuer copermisionIssuertmp,tmpco.contractordate contractordatetmp,tmpco.contractorvalidate contractorvalidatetmp,
                tmpco.contractorno contractornotmp,tmpco.contractorIssuer contractorIssuertmp,tmpco.engineersystemdate engineersystemdatetmp,
                tmpco.engineersystemvalidate engineersystemvalidatetmp,tmpco.engineersystemno engineersystemnotmp,tmpco.engineersystemIssuer engineersystemIssuertmp,
                tmpco.valueaddeddate valueaddeddatetmp,tmpco.valueaddedvalidate valueaddedvalidatetmp,tmpco.valueaddedno valueaddednotmp,tmpco.valueaddedIssuer valueaddedIssuertmp
				,tmpco.corank coranktmp,producers.rank corank
                ,producers.Title,producers.fundationYear,producers.fundationno,producers.fundationIssuer,producers.boardchangeno,producers.boardchangedate,producers.boardvalidationdate,
                producers.boardIssuer,producers.copermisionno,producers.copermisiondate,producers.copermisionvalidate,producers.copermisionIssuer,producers.contractordate,
                producers.contractorvalidate,producers.contractorno,producers.contractorIssuer,producers.engineersystemdate,producers.engineersystemvalidate,producers.engineersystemno,
                producers.engineersystemIssuer,producers.valueaddeddate,producers.valueaddedvalidate,producers.valueaddedno,producers.valueaddedIssuer
                FROM producers 
                left outer join tmpco on UID='$IDUser' and type='$POD' 
                where producers.producersID='$IDUser' ";
				$resultPOD = mysql_fetch_assoc(mysql_query($sql));}
				else if ($POD==2) {
                $sql = "SELECT ( exists (SELECT * FROM tmpco where UID='$IDUser' and type='$POD')) qmode,
                tmpco.fundationYear fundationYeartmp,tmpco.fundationno fundationnotmp,tmpco.fundationIssuer fundationIssuertmp,tmpco.boardchangeno boardchangenotmp,
                tmpco.boardchangedate boardchangedatetmp,tmpco.boardvalidationdate boardvalidationdatetmp,tmpco.boardIssuer boardIssuertmp,
                tmpco.copermisionno copermisionnotmp,tmpco.copermisiondate copermisiondatetmp,tmpco.copermisionvalidate copermisionvalidatetmp,
                tmpco.copermisionIssuer copermisionIssuertmp,tmpco.contractordate contractordatetmp,tmpco.contractorvalidate contractorvalidatetmp,
                tmpco.contractorno contractornotmp,tmpco.contractorIssuer contractorIssuertmp, tmpco.engineersystemdate engineersystemdatetmp,tmpco.contractorRank1 contractorRank1tmp,
                tmpco.engineersystemvalidate engineersystemvalidatetmp,tmpco.engineersystemno engineersystemnotmp,tmpco.engineersystemIssuer engineersystemIssuertmp,
                tmpco.valueaddeddate valueaddeddatetmp,tmpco.valueaddedvalidate valueaddedvalidatetmp,tmpco.valueaddedno valueaddednotmp,tmpco.valueaddedIssuer valueaddedIssuertmp
               ,tmpco.corank coranktmp ,operatorco.Title,operatorco.fundationYear,operatorco.fundationno,operatorco.fundationIssuer,operatorco.boardchangeno,operatorco.boardchangedate,operatorco.boardvalidationdate,
                operatorco.boardIssuer,operatorco.copermisionno,operatorco.copermisiondate,operatorco.copermisionvalidate,operatorco.copermisionIssuer,operatorco.contractordate,
                operatorco.contractorvalidate,operatorco.contractorno,operatorco.contractorIssuer,
				operatorco.contractorRank1,
				operatorco.engineersystemdate,operatorco.engineersystemvalidate,operatorco.engineersystemno,
                operatorco.engineersystemIssuer,operatorco.valueaddeddate,operatorco.valueaddedvalidate,operatorco.valueaddedno,operatorco.valueaddedIssuer,operatorco.corank
				
                FROM operatorco 
                left outer join tmpco on UID='$IDUser' and type='$POD' 
                where operatorco.OperatorCoID='$IDUser' ";
                //print $sql;
				$resultPOD = mysql_fetch_assoc(mysql_query($sql));}
				else if ($POD==3) {
                $sql = "
                SELECT ( exists (SELECT * FROM tmpco where UID='$IDUser' and type='$POD')) qmode,
                tmpco.fundationYear fundationYeartmp,tmpco.fundationno fundationnotmp,tmpco.fundationIssuer fundationIssuertmp,tmpco.boardchangeno boardchangenotmp,
                tmpco.boardchangedate boardchangedatetmp,tmpco.boardvalidationdate boardvalidationdatetmp,tmpco.boardIssuer boardIssuertmp,
                tmpco.copermisionno copermisionnotmp,tmpco.copermisiondate copermisiondatetmp,tmpco.copermisionvalidate copermisionvalidatetmp,
                tmpco.copermisionIssuer copermisionIssuertmp,tmpco.contractordate contractordatetmp,tmpco.contractorvalidate contractorvalidatetmp,
                tmpco.contractorno contractornotmp,tmpco.contractorIssuer contractorIssuertmp,tmpco.engineersystemdate engineersystemdatetmp,
                tmpco.engineersystemvalidate engineersystemvalidatetmp,tmpco.engineersystemno engineersystemnotmp,tmpco.engineersystemIssuer engineersystemIssuertmp,
                tmpco.valueaddeddate valueaddeddatetmp,tmpco.valueaddedvalidate valueaddedvalidatetmp,tmpco.valueaddedno valueaddednotmp,tmpco.valueaddedIssuer valueaddedIssuertmp
                ,designerco.Title,designerco.fundationYear,designerco.fundationno,designerco.fundationIssuer,designerco.boardchangeno,designerco.boardchangedate,designerco.boardvalidationdate,
                designerco.boardIssuer,designerco.copermisionno,designerco.copermisiondate,designerco.copermisionvalidate,designerco.copermisionIssuer,designerco.contractordate,
                designerco.contractorvalidate,designerco.contractorno,designerco.contractorIssuer,designerco.engineersystemdate,designerco.engineersystemvalidate,designerco.engineersystemno,
                designerco.engineersystemIssuer,designerco.valueaddeddate,designerco.valueaddedvalidate,designerco.valueaddedno,designerco.valueaddedIssuer
                FROM designerco 
                left outer join tmpco on UID='$IDUser' and type='$POD' 
                where designerco.designercoID='$IDUser' ";
				$resultPOD = mysql_fetch_assoc(mysql_query($sql));}
					if ($login_RolesID<>1)
                    if ((compelete_date($resultPOD["copermisionvalidate"])<gregorian_to_jalali(date('Y-m-d')))
							|| (compelete_date($resultPOD["boardvalidationdate"])<gregorian_to_jalali(date('Y-m-d'))))
							{
						   echo ("<SCRIPT LANGUAGE='JavaScript'>  
						   alert('لطفا نسبت به تکمیل مدارک وبررسی تاریخهای اعتبار در سربرگ  تاییدیه اقدام نمایید.\\n تاریخ گواهی آخرین تغییرات و یا تاریخ اعتبار مجوز بررسی شود.'); </SCRIPT>");
	
							echo ("<SCRIPT LANGUAGE='JavaScript'>  
						   alert('لطفا بخش های خالی را تکمیل نمایید.'); </SCRIPT>");
							}
				print "<tr><td colspan='9' style='text-align:center; height:50px;'><br/><b>تاييديه مدارك  ".$producType.' <b><b> '.$resultPOD['Title']."</b></td>"; 
			    if ($login_RolesID==1){ 
				         print "<td style = 'width: 10%;' class='data'><input name='showb' type='checkbox' id='showb'";
                                if ($showb>0) echo 'checked';
								print " />شرکت</td></tr>";}
                                
                                
                                //print $sql;
    			 
				
				
					?>
				<tr>
				<td  style="text-align:center; height:25px;">رديف</td>
				<td  style="text-align:center;">عنوان</td>
				<td  style="text-align:center;"></td>
				<td  style="text-align:center;">تاريخ</td>
				<td  style="text-align:center;">شماره</td>
				<td  style="text-align:center;">تاريخ اعتبار</td>
				<td  style="text-align:center;">مرجع صادر كننده</td>
				<td  style="text-align:center;">اسكن</td>
				<td></td>
				<td class="no"></td>
				
				
				
				</tr>
                <?php if ($login_RolesID!=10){ ?>
				    <tr >
						<td class='label'>1</td>
						<td>گواهي نامه ثبت نام ارزش افزوده </td>
						<td></td>
						
			<td><input style="color:#<?php if ($resultPOD['valueaddeddate']!=$resultPOD['valueaddeddatetmp']) echo 'ff0000'; ?>;" 
			placeholder="انتخاب تاریخ"  name="valueaddeddatetmp" type="text" class="textbox" id="valueaddeddatetmp" value="<?php if (strlen($resultPOD['valueaddeddatetmp'])>0) echo $resultPOD['valueaddeddatetmp'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['valueaddedno']!=$resultPOD['valueaddednotmp']) echo 'ff0000'; ?>;" 
						name="valueaddednotmp" type="text" class="textbox" id="valueaddednotmp" 
			  value="<?php echo $resultPOD['valueaddednotmp']; ?>" size="10" maxlength="20" /></td>
						<td><input style="color:#<?php if ($resultPOD['valueaddedvalidate']!=$resultPOD['valueaddedvalidatetmp']) echo 'ff0000'; ?>;" placeholder="انتخاب تاریخ"  name="valueaddedvalidatetmp" type="text" class="textbox" id="valueaddedvalidatetmp" value="<?php if (strlen($resultPOD['valueaddedvalidatetmp'])>0) echo $resultPOD['valueaddedvalidatetmp'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['valueaddedIssuer']!=$resultPOD['valueaddedIssuertmp']) echo 'ff0000'; ?>;" name="valueaddedIssuertmp" type="text" class="textbox" id="valueaddedIssuertmp" 
			  value="<?php echo $resultPOD['valueaddedIssuertmp'] ?>" size="20" maxlength="50" /></td>
						<td class='data'><input type='file' name='file1' id='file1' accept='image/*'></td>
						<td><?php print $fstr1;?></td>
						
                    </tr>
                    <tr>
						<td class='label'></td>
						<td>(تایید شده)</td>
						<td></td>
			
						<td><input style="color:#<?php if ($resultPOD['valueaddeddate']!=$resultPOD['valueaddeddatetmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>  placeholder="انتخاب تاریخ"  name="valueaddeddate" type="text" class="textbox" id="valueaddeddate" value="<?php if (strlen($resultPOD['valueaddeddate'])>0) echo $resultPOD['valueaddeddate'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['valueaddedno']!=$resultPOD['valueaddednotmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>   name="valueaddedno" type="text" class="textbox" id="valueaddedno" 
			  value="<?php echo $resultPOD['valueaddedno']; ?>" size="10" maxlength="20" /></td>
						<td><input style="color:#<?php if ($resultPOD['valueaddedvalidate']!=$resultPOD['valueaddedvalidatetmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>  placeholder="انتخاب تاریخ"  name="valueaddedvalidate" type="text" class="textbox" id="valueaddedvalidate" value="<?php if (strlen($resultPOD['valueaddedvalidate'])>0) echo $resultPOD['valueaddedvalidate'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['valueaddedIssuer']!=$resultPOD['valueaddedIssuertmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>   name="valueaddedIssuer" type="text" class="textbox" id="valueaddedIssuer" 
			  value="<?php echo $resultPOD['valueaddedIssuer'] ?>" size="20" maxlength="50" /></td>
						<td class='data'></td>
						<td></td>
			        </tr>
                    
					<tr>
						<td class='label'>2</td>
						<td >آخرين تغييرات  <?php echo $labl; ?></td>
						<td></td>
			
						<td ><input style="color:#<?php if ($resultPOD['boardchangedate']!=$resultPOD['boardchangedatetmp']) echo 'ff0000'; ?>;" 
						placeholder="انتخاب تاریخ"  name="boardchangedatetmp" type="text" class="textbox" id="boardchangedatetmp" value="<?php if (strlen($resultPOD['boardchangedatetmp'])>0) echo $resultPOD['boardchangedatetmp'];?>" size="10" maxlength="10" /></td>
						<td ><input style="color:#<?php if ($resultPOD['boardchangeno']!=$resultPOD['boardchangenotmp']) echo 'ff0000'; ?>;"  name="boardchangenotmp" type="text" class="textbox" id="boardchangenotmp" 
			  value="<?php echo $resultPOD['boardchangenotmp']; ?>" size="10" maxlength="20" /></td>
						<td ><input style="color:#<?php if ($resultPOD['boardvalidationdate']!=$resultPOD['boardvalidationdatetmp']) echo 'ff0000'; ?>;" placeholder="انتخاب تاریخ"  name="boardvalidationdatetmp" type="text" class="textbox" id="boardvalidationdatetmp" value="<?php if (strlen($resultPOD['boardvalidationdatetmp'])>0) echo $resultPOD['boardvalidationdatetmp'];?>" size="10" maxlength="10" /></td>
						<td ><input style="color:#<?php if ($resultPOD['boardIssuer']!=$resultPOD['boardIssuertmp']) echo 'ff0000'; ?>;"  name="boardIssuertmp" type="text" class="textbox" id="boardIssuertmp" 
			  value="<?php echo $resultPOD['boardIssuertmp'] ?>" size="20" maxlength="50" /></td>
						<td class='data' ><input type='file' name='file2' id='file2' accept='image/*'></td>
                        <td><?php print $fstr2;?></td>  
									
						
                    </tr>
				
                 <tr>
						<td class='label'></td>
						<td>(تایید شده)</td>
						<td></td>
			
						<td><input style="color:#<?php if ($resultPOD['boardchangedate']!=$resultPOD['boardchangedatetmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>  placeholder="انتخاب تاریخ"  name="boardchangedate" type="text" class="textbox" id="boardchangedate" value="<?php if (strlen($resultPOD['boardchangedate'])>0) echo $resultPOD['boardchangedate'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['boardchangeno']!=$resultPOD['boardchangenotmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>   name="boardchangeno" type="text" class="textbox" id="boardchangeno" 
			  value="<?php echo $resultPOD['boardchangeno']; ?>" size="10" maxlength="20" /></td>
						<td><input style="color:#<?php if ($resultPOD['boardvalidationdate']!=$resultPOD['boardvalidationdatetmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>  placeholder="انتخاب تاریخ"  name="boardvalidationdate" type="text" class="textbox" id="boardvalidationdate" value="<?php if (strlen($resultPOD['boardvalidationdate'])>0) echo $resultPOD['boardvalidationdate'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['boardIssuer']!=$resultPOD['boardIssuertmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>   name="boardIssuer" type="text" class="textbox" id="boardIssuer" 
			  value="<?php echo $resultPOD['boardIssuer'] ?>" size="20" maxlength="50" /></td>
						<td class='data'></td>
						<td></td>
						
                    </tr>
                    
				<tr style="<?php echo $display;?>;">
	                    <td class='label'>3</td>
						<td>آگهي تاسيس </td>
						<td></td>
			
						<td ><input style="color:#<?php if ($resultPOD['fundationYear']!=$resultPOD['fundationYeartmp']) echo 'ff0000'; ?>;" placeholder="انتخاب تاریخ"  name="fundationYeartmp" type="text" class="textbox" id="fundationYeartmp" value="<?php if (strlen($resultPOD['fundationYeartmp'])>0) echo $resultPOD['fundationYeartmp'];?>" size="10" maxlength="10" /></td>
						
						<td><input style="color:#<?php if ($resultPOD['fundationno']!=$resultPOD['fundationnotmp']) echo 'ff0000'; ?>;" name="fundationnotmp" type="text" class="textbox" id="fundationnotmp" 
			  value="<?php echo $resultPOD['fundationnotmp'] ?>" size="10" maxlength="20" /></td>
						<td></td>
						<td><input style="color:#<?php if ($resultPOD['fundationIssuer']!=$resultPOD['fundationIssuertmp']) echo 'ff0000'; ?>;" name="fundationIssuertmp" type="text" class="textbox" id="fundationIssuertmp" 
			  value="<?php echo $resultPOD['fundationIssuertmp'] ?>" size="20" maxlength="50" /></td>
						<td class='data'><input type='file' name='file3' id='file3' accept='image/*'></td>
                       <td><?php print $fstr3;?></td>     
					   									
                    </tr>
					
                     	<tr style="<?php echo $display;?>;">
	     	            <td class='label'></td>
						<td>(تایید شده)</td>
						<td>پایه</td>

			
						<td><input style="color:#<?php if ($resultPOD['fundationYear']!=$resultPOD['fundationYeartmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>  placeholder="انتخاب تاریخ"  name="fundationYear" type="text" class="textbox" id="fundationYear" value="<?php if (strlen($resultPOD['fundationYear'])>0) echo $resultPOD['fundationYear'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['fundationno']!=$resultPOD['fundationnotmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>   name="fundationno" type="text" class="textbox" id="fundationno" 
			  value="<?php echo $resultPOD['fundationno']; ?>" size="10" maxlength="20" /></td>
						<td></td>
						<td><input style="color:#<?php if ($resultPOD['fundationIssuer']!=$resultPOD['fundationIssuertmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>   name="fundationIssuer" type="text" class="textbox" id="fundationIssuer" 
			  value="<?php echo $resultPOD['fundationIssuer'] ?>" size="20" maxlength="50" /></td>
						<td class='data'></td>
						<td></td>
						
                    </tr>
                    
                    <tr>
						<td class='label'>4</td>
						<td>گواهینامه صلاحيت <?php echo $labl; ?></td>
						<td><input style="color:#<?php if ($resultPOD['corank']!=$resultPOD['coranktmp']) echo 'ff0000'; ?>;" placeholder="پایه"  name="coranktmp" type="text" class="textbox" id="coranktmp" value="<?php if (strlen($resultPOD['coranktmp'])>0) echo $resultPOD['coranktmp'];?>" size="1" maxlength="1" /></td>
						
						
						<td><input style="color:#<?php if ($resultPOD['copermisiondate']!=$resultPOD['copermisiondatetmp']) echo 'ff0000'; ?>;" placeholder="انتخاب تاریخ"  name="copermisiondatetmp" type="text" class="textbox" id="copermisiondatetmp" value="<?php if (strlen($resultPOD['copermisiondatetmp'])>0) echo $resultPOD['copermisiondatetmp'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['copermisionno']!=$resultPOD['copermisionnotmp']) echo 'ff0000'; ?>;"  name="copermisionnotmp" type="text" class="textbox" id="copermisionnotmp" 
			  value="<?php echo $resultPOD['copermisionnotmp'] ?>" size="10" maxlength="20" /></td>
						<td><input style="color:#<?php if ($resultPOD['copermisionvalidate']!=$resultPOD['copermisionvalidatetmp']) echo 'ff0000'; ?>;" placeholder="انتخاب تاریخ"  name="copermisionvalidatetmp" type="text" class="textbox" id="copermisionvalidatetmp" value="<?php if (strlen($resultPOD['copermisionvalidatetmp'])>0) echo $resultPOD['copermisionvalidatetmp'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['copermisionIssuer']!=$resultPOD['copermisionIssuertmp']) echo 'ff0000'; ?>;"  name="copermisionIssuertmp" type="text" class="textbox" id="copermisionIssuertmp" 
			  value="<?php echo $resultPOD['copermisionIssuertmp'] ?>" size="20" maxlength="50" /></td>
						<td class='data'><input type='file' name='file4' id='file4' accept='image/*'></td>
                        <td><?php print $fstr4;?></td>       
						
												
                    </tr>
                     <tr>
						<td class='label'></td>
						<td>(تایید شده)</td>
						
						<td><input style="color:#<?php if ($resultPOD['corank']!=$resultPOD['coranktmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>  placeholder="پایه"  name="corank" type="text" class="textbox" id="corank" value="<?php if (strlen($resultPOD['corank'])>0) echo $resultPOD['corank'];?>" size="1" maxlength="1" /></td>
					
						<td><input style="color:#<?php if ($resultPOD['copermisiondate']!=$resultPOD['copermisiondatetmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>  placeholder="انتخاب تاریخ"  name="copermisiondate" type="text" class="textbox" id="copermisiondate" value="<?php if (strlen($resultPOD['copermisiondate'])>0) echo $resultPOD['copermisiondate'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['copermisionno']!=$resultPOD['copermisionnotmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>   name="copermisionno" type="text" class="textbox" id="copermisionno" 
			  value="<?php echo $resultPOD['copermisionno']; ?>" size="10" maxlength="20" /></td>
						<td><input style="color:#<?php if ($resultPOD['copermisionvalidate']!=$resultPOD['copermisionvalidatetmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>  placeholder="انتخاب تاریخ"  name="copermisionvalidate" type="text" class="textbox" id="copermisionvalidate" value="<?php if (strlen($resultPOD['copermisionvalidate'])>0) echo $resultPOD['copermisionvalidate'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['copermisionIssuer']!=$resultPOD['copermisionIssuertmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>   name="copermisionIssuer" type="text" class="textbox" id="copermisionIssuer" 
			  value="<?php echo $resultPOD['copermisionIssuer'] ?>" size="20" maxlength="50" /></td>
						<td class='data'></td>
						<td></td>
						
                    </tr>
 <?php } ?>

   
                	<tr style="<?php echo $display;?>;">
						<td class='label'>5</td>
						<td>آخرین لیست بیمه   </td>
						<td></td>
			
						<td><input style="color:#<?php if ($resultPOD['engineersystemdate']!=$resultPOD['engineersystemdatetmp']) echo 'ff0000'; ?>;" placeholder="انتخاب تاریخ"  name="engineersystemdatetmp" type="text" class="textbox" id="engineersystemdatetmp" value="<?php if (strlen($resultPOD['engineersystemdatetmp'])>0) echo $resultPOD['engineersystemdatetmp'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['engineersystemno']!=$resultPOD['engineersystemnotmp']) echo 'ff0000'; ?>;"  name="engineersystemnotmp" type="text" class="textbox" id="engineersystemnotmp" 
			  value="<?php echo $resultPOD['engineersystemnotmp'] ?>" size="10" maxlength="20" /></td>
						<td><input style="color:#<?php if ($resultPOD['engineersystemvalidate']!=$resultPOD['engineersystemvalidatetmp']) echo 'ff0000'; ?>;" placeholder="انتخاب تاریخ"  name="engineersystemvalidatetmp" type="text" class="textbox" id="engineersystemvalidatetmp" value="<?php if (strlen($resultPOD['engineersystemvalidatetmp'])>0) echo $resultPOD['engineersystemvalidatetmp'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['engineersystemIssuer']!=$resultPOD['engineersystemIssuertmp']) echo 'ff0000'; ?>;"  name="engineersystemIssuertmp" type="text" class="textbox" id="engineersystemIssuertmp" 
			  value="<?php echo $resultPOD['engineersystemIssuertmp'] ?>" size="20" maxlength="50" /><?php 
              
              if ($login_RolesID==1 || $login_RolesID==10)
              {
                $sql="
                select CPI,DVFS from clerk 
                inner join (select max(SaveTime) SaveTime,max(ClerkID) ClerkID from  tbl_log  
                where tName='operatorco' and tID='$IDUser' and colname in ('engineersystemdate'
                ,'engineersystemIssuer','engineersystemno','engineersystemIssuer')) maxtbl on maxtbl.ClerkID=clerk.ClerkID
                 ";
                    //print $sql;        
                $results1 = mysql_query($sql);
                $rows1 = mysql_fetch_assoc($results1);
                
                if ($rows1['CPI']>0)
                {
              		$encrypted_string=$rows1['CPI'];
            		$encryption_key="!@#$8^&*";
            		$decrypted_string="";
            		for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
            				$decrypted_string.=chr(substr($encrypted_string,3*$i+3,3));
                    $encrypted_string=$rows1['DVFS'];
                    $encryption_key="!@#$8^&*";
                    $decrypted_string.=" ";
                    for ($i=0;$i<(substr($encrypted_string,0,3)-5);$i++)
                        $decrypted_string.=chr(substr($encrypted_string,3*$i+3,3)); 
                        echo $decrypted_string;
                }
                

            
              }
               ?></td>
						<td class='data'><input type='file' name='file6' id='file6' accept='image/*'></td>
                        <td><?php print $fstr6;?></td>    
							
          </tr>
                     <tr style="<?php echo $display;?>;">
						<td class='label'></td>
						<td>(تایید شده)</td>
						<td></td>
			
						<td><input style="color:#<?php if ($resultPOD['engineersystemdate']!=$resultPOD['engineersystemdatetmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>  placeholder="انتخاب تاریخ"  name="engineersystemdate" type="text" class="textbox" id="engineersystemdate" value="<?php if (strlen($resultPOD['engineersystemdate'])>0) echo $resultPOD['engineersystemdate'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['engineersystemno']!=$resultPOD['engineersystemnotmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>   name="engineersystemno" type="text" class="textbox" id="engineersystemno" 
			  value="<?php echo $resultPOD['engineersystemno']; ?>" size="10" maxlength="20" /></td>
						<td><input style="color:#<?php if ($resultPOD['engineersystemvalidate']!=$resultPOD['engineersystemvalidatetmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>  placeholder="انتخاب تاریخ"  name="engineersystemvalidate" type="text" class="textbox" id="engineersystemvalidate" value="<?php if (strlen($resultPOD['engineersystemvalidate'])>0) echo $resultPOD['engineersystemvalidate'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['engineersystemIssuer']!=$resultPOD['engineersystemIssuertmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>   name="engineersystemIssuer" type="text" class="textbox" id="engineersystemIssuer" 
			  value="<?php echo $resultPOD['engineersystemIssuer'] ?>" size="20" maxlength="50" /></td>
						<td class='data'></td>
						<td></td>
						
					</tr>


 <?php if ($login_RolesID==1){ ?>
                    
					<tr style="<?php echo $display;?>;">
						<td class='label'>6</td>
						<td>گواهي صلاحيت پيمانكاري مدیریت و برنامه ریزی</td>
						
						<td><input style="color:#<?php if ($resultPOD['contractorRank1']!=$resultPOD['contractorRank1tmp']) echo 'ff0000'; ?>;" placeholder="پایه"  name="contractorRank1tmp" type="text" class="textbox" id="contractorRank1tmp" value="<?php if (strlen($resultPOD['contractorRank1tmp'])>0) echo $resultPOD['contractorRank1tmp'];?>" size="1" maxlength="1" /> آب</td>
						
						<td><input style="color:#<?php if ($resultPOD['contractordate']!=$resultPOD['contractordatetmp']) echo 'ff0000'; ?>;" placeholder="انتخاب تاریخ"  name="contractordatetmp" type="text" class="textbox" id="contractordatetmp" value="<?php if (strlen($resultPOD['contractordatetmp'])>0) echo $resultPOD['contractordatetmp'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['contractorno']!=$resultPOD['contractornotmp']) echo 'ff0000'; ?>;"  name="contractornotmp" type="text" class="textbox" id="contractornotmp" 
			  value="<?php echo $resultPOD['contractornotmp'] ?>" size="10" maxlength="20" /></td>
						<td><input style="color:#<?php if ($resultPOD['contractorvalidate']!=$resultPOD['contractorvalidatetmp']) echo 'ff0000'; ?>;" placeholder="انتخاب تاریخ"  name="contractorvalidatetmp" type="text" class="textbox" id="contractorvalidatetmp" value="<?php if (strlen($resultPOD['contractorvalidatetmp'])>0) echo $resultPOD['contractorvalidatetmp'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['contractorIssuer']!=$resultPOD['contractorIssuertmp']) echo 'ff0000'; ?>;"  name="contractorIssuertmp" type="text" class="textbox" id="contractorIssuertmp" 
			  value="<?php echo $resultPOD['contractorIssuertmp'] ?>" size="20" maxlength="50" /></td>
						<td class='data'><input type='file' name='file5' id='file5' accept='image/*'></td>
                        <td><?php print $fstr5;?></td>  
												
                    </tr>
					<tr style="<?php echo $display;?>;">
						<td class='label'></td>
						<td>(تایید شده)</td>
						
						<td><input style="color:#<?php if ($resultPOD['contractorRank1']!=$resultPOD['contractorRank1tmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>  placeholder="پایه"  name="contractorRank1" type="text" class="textbox" id="contractordate" value="<?php if (strlen($resultPOD['contractorRank1'])>0) echo $resultPOD['contractorRank1'];?>" size="1" maxlength="1" /></td>
						
						<td><input style="color:#<?php if ($resultPOD['contractordate']!=$resultPOD['contractordatetmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>  placeholder="انتخاب تاریخ"  name="contractordate" type="text" class="textbox" id="contractordate" value="<?php if (strlen($resultPOD['contractordate'])>0) echo $resultPOD['contractordate'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['contractorno']!=$resultPOD['contractornotmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>   name="contractorno" type="text" class="textbox" id="contractorno" 
			  value="<?php echo $resultPOD['contractorno']; ?>" size="10" maxlength="20" /></td>
						<td><input style="color:#<?php if ($resultPOD['contractorvalidate']!=$resultPOD['contractorvalidatetmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>  placeholder="انتخاب تاریخ"  name="contractorvalidate" type="text" class="textbox" id="contractorvalidate" value="<?php if (strlen($resultPOD['contractorvalidate'])>0) echo $resultPOD['contractorvalidate'];?>" size="10" maxlength="10" /></td>
						<td><input style="color:#<?php if ($resultPOD['contractorIssuer']!=$resultPOD['contractorIssuertmp']) echo 'ff0000'; ?>;" <?php echo $disabled_string  ?>   name="contractorIssuer" type="text" class="textbox" id="contractorIssuer" 
			  value="<?php echo $resultPOD['contractorIssuer'] ?>" size="20" maxlength="50" /></td>
						<td class='data'></td>
						<td></td>
						
                    </tr>
				
	 <?php } ?>
  
                    
                			<tr>                     
                            <td colspan="9"><input type="hidden" name="IDUser" value ="<?php echo $IDUser; ?>">
							<input type="hidden" name="POD" value ="<?php echo $POD; ?>">
							<input type="hidden" name="path" value ="<?php echo $path; ?>">
							<input type="hidden" name="qmode" value ="<?php echo $resultPOD['qmode']; ?>">
							<input type="hidden" name="TBLID" value ="<?php echo $TBLID; ?>">
				
				
                        <?php 
			
                      if ($login_RolesID!='22')
                      echo 
                      
                      "<input   name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>"; ?>
                      
                        	
							</tr>
           
                    </tbody>
                   
                </table>
                      
                 <tr >
                        <span colspan="1" id="fooBar">  &nbsp;</span>
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

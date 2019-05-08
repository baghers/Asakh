<?php 

/*

insert/producerapprovement.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
 
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php
if ($login_Permission_granted==0) header("Location: ../login.php");
if ( $login_ProducersID>0)//کاربر لاگین تولید کننده
    { $IDUser = $login_ProducersID;
	  $POD = 1; 
	  $path = "../../upfolder/producerapproval/producers/";
	  $table = "producers";
	  $field = "producers.ProducersID";
	  }
	else if ($login_OperatorCoID>0)//کاربر لاگین مجری 
	{ $IDUser = $login_OperatorCoID;
	  $POD = 2;	
	  $path = "../../upfolder/producerapproval/operatoco/";
	  $table = "operatorco";
	  $field = "operatorco.OperatorCoID";}	
	else if ($login_DesignerCoID>0) //کاربر لاگین طراح  
	{ $IDUser = $login_DesignerCoID;
	  $POD = 3;
	  $path = "../../upfolder/producerapproval/designerco/";
	  $table = "designerco";
	  $field = "designerco.DesignerCoID";
	  }	
if ($_POST)
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
  
        $query = "UPDATE $table SET fundationYear = '$fundationYear',fundationno = '$fundationno',fundationIssuer = '$fundationIssuer'
        ,boardchangeno = '$boardchangeno',boardchangedate = '$boardchangedate',boardvalidationdate = '$boardvalidationdate'
        ,boardIssuer = '$boardIssuer',copermisionno = '$copermisionno',copermisiondate = '$copermisiondate'
        ,copermisionvalidate = '$copermisionvalidate',copermisionIssuer = '$copermisionIssuer',contractordate = '$contractordate'
        ,contractorvalidate = '$contractorvalidate',contractorno = '$contractorno',contractorIssuer = '$contractorIssuer'
        ,engineersystemdate = '$engineersystemdate',engineersystemvalidate = '$engineersystemvalidate',engineersystemno ='$engineersystemno'
        ,engineersystemIssuer = '$engineersystemIssuer',valueaddeddate = '$valueaddeddate',valueaddedvalidate = '$valueaddedvalidate'
        ,valueaddedno = '$valueaddedno',valueaddedIssuer = '$valueaddedIssuer' 
        ,SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
        WHERE $field='$IDUser'";
		
		  	   				  	try 
								  {		
									$result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

		if ($result=="true") $secerror= '<p class="error">کد امنیتی نادرست می باشد</p>';
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
            foreach (glob($path. $IDUser.'_1*') as $filename) 
            {
                unlink($filename);
            }
            move_uploaded_file($_FILES["file1"]["tmp_name"],$path.$attachedfile);   
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


?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست تاییدیه ها</title>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	
		<script type="text/javascript" language='javascript' src='../assets/jquery2.js'></script>
		<script type="text/javascript" src="../lib/jquery2.js"></script>
		<script type='text/javascript' src='../lib/jquery.bgiframe.min.js'></script>
		<script type='text/javascript' src='../lib/jquery.ajaxQueue.js'></script>
		<script type='text/javascript' src='../lib/thickbox-compressed.js'></script>
		<script type='text/javascript' src='../jquery.autocomplete.js'></script>
		<script type='text/javascript' src='localdata.js'></script>
		<link rel="stylesheet" type="text/css" href="main.css" />
		<link rel="stylesheet" type="text/css" href="../jquery.autocomplete.css" />
		<link rel="stylesheet" type="text/css" href="../lib/thickbox.css" />
		<link rel="stylesheet" href="../assets/style.css" type="text/css" />
		<link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
        <script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="../js/persiandatepicker.js"></script>
        


    <script type="text/javascript">
            $(function() {
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
				
            });
                
    </script>
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
			<p></p>
            <?php if ($result==1) print $secerror= '<p class="note">اطلاعات با موفقيت ذخيره شد.</p>'; ?>
            <form action="approvedocumentcompany.php" method="post" enctype="multipart/form-data">
			    <div id="loading-div-background">
					<div id="loading-div" class="ui-corner-all" >
						 <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
						 <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
					</div>
			    </div>
				<br/>
				<table id="records" width="99%" align="center">
                   <tbody>           
               

                   <?php         
                   $fstr1="";
                    $fstr2="";
                    $fstr3="";
                    $fstr4="";
                    $fstr5="";
                    $fstr6="";
					if ($POD==1)
                    $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/producerapproval/producers/';
					else if ($POD==3)
					$directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/producerapproval/designerco/';
					else if ($POD==2)
					$directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/producerapproval/operatorco/';
					
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
                                $fstr1="<a target='blank' href='$path1' ><img style = 'width: 100%;' src='../img/accept.png'  ></a>";
                            if (($ID==$IDUser) && ($No==2) )
                                $fstr2="<a target='blank'  href='$path1' ><img style = 'width: 100%;' src='../img/accept.png' ></a>";
                            if (($ID==$IDUser) && ($No==3) )
                                $fstr3="<a target='blank'  href='$path1' ><img style = 'width: 100%;' src='../img/accept.png' ></a>";        
                            if (($ID==$IDUser) && ($No==4) )
                                $fstr4="<a target='blank'  href='$path1' ><img style = 'width: 100%;' src='../img/accept.png' ></a>";        
                            if (($ID==$IDUser) && ($No==5) )
                                $fstr5="<a target='blank'  href='$path1' ><img style = 'width: 100%;' src='../img/accept.png' ></a>";        
                            if (($ID==$IDUser) && ($No==6) )
                                $fstr6="<a target='blank'  href='$path1' ><img style = 'width: 100%;' src='../img/accept.png' ></a>";        
                        }
                    } ?>
               
				<?php 
				if ($POD==1) {
                $sql = "SELECT * FROM producers where ProducersID=$IDUser";
				$resultPOD = mysql_fetch_assoc(mysql_query($sql));}
				else if ($POD==2) {
                $sql = "SELECT * FROM operatorco where OperatorCoID=$IDUser";
				$resultPOD = mysql_fetch_assoc(mysql_query($sql));}
				else if ($POD==3) {
                $sql = "SELECT * FROM designerco where DesignerCoID=$IDUser";
				$resultPOD = mysql_fetch_assoc(mysql_query($sql));}
				print "<tr><td colspan='8' style='text-align:center; height:50px;'><br/><b>تاييديه مدارك شركت ".$resultPOD['Title']."</b></td></tr>"; ?>
				<tr>
				<td  style="text-align:center; height:25px;">رديف</td>
				<td  style="text-align:center;">عنوان</td>
				<td  style="text-align:center;">تاريخ</td>
				<td  style="text-align:center;">شماره</td>
				<td  style="text-align:center;">تاريخ اعتبار</td>
				<td  style="text-align:center;">مرجع صادر كننده</td>
				<td  style="text-align:center;">اسكن</td>
				<td></td>
				</tr>
				    <tr>
						<td class='label'>1</td>
						<td>گواهي نامه ثبت نام ارزش افزوده </td>
						<td><input placeholder="انتخاب تاریخ"  name="valueaddeddate" type="text" class="textbox" id="valueaddeddate" value="<?php if (strlen($resultPOD['valueaddeddate'])>0) echo $resultPOD['valueaddeddate']; else echo gregorian_to_jalali(date('Y-m-d')); ?>" size="8" maxlength="10" /></td>
						<td><input  name="valueaddedno" type="text" class="textbox" id="valueaddedno" 
			  value="<?php echo $resultPOD['valueaddedno']; ?>" size="5" maxlength="10" /></td>
						<td><input placeholder="انتخاب تاریخ"  name="valueaddedvalidate" type="text" class="textbox" id="valueaddedvalidate" value="<?php if (strlen($resultPOD['valueaddedvalidate'])>0) echo $resultPOD['valueaddedvalidate']; else echo gregorian_to_jalali(date('Y-m-d')); ?>" size="8" maxlength="10" /></td>
						<td><input  name="valueaddedIssuer" type="text" class="textbox" id="valueaddedIssuer" 
			  value="<?php echo $resultPOD['valueaddedIssuer'] ?>" size="25" maxlength="5" /></td>
						<td class='data'><input type='file' name='file1' id='file1' accept='application/zip'></td>
						<td><?php print $fstr1;?></td>
                    </tr>
					<tr>
						<td class='label'>2</td>
						<td style="width:30%">آخرين تغييرات </td>
						<td style="width:9%"><input placeholder="انتخاب تاریخ"  name="boardchangedate" type="text" class="textbox" id="boardchangedate" value="<?php if (strlen($resultPOD['boardchangedate'])>0) echo $resultPOD['boardchangedate']; else echo gregorian_to_jalali(date('Y-m-d')); ?>" size="8" maxlength="10" /></td>
						<td style="width:7%"><input  name="boardchangeno" type="text" class="textbox" id="boardchangeno" 
			  value="<?php echo $resultPOD['boardchangeno']; ?>" size="5" maxlength="10" /></td>
						<td style="width:9%"><input placeholder="انتخاب تاریخ"  name="boardvalidationdate" type="text" class="textbox" id="boardvalidationdate" value="<?php if (strlen($resultPOD['boardvalidationdate'])>0) echo $resultPOD['boardvalidationdate']; else echo gregorian_to_jalali(date('Y-m-d')); ?>" size="8" maxlength="10" /></td>
						<td style="width:18%"><input  name="boardIssuer" type="text" class="textbox" id="boardIssuer" 
			  value="<?php echo $resultPOD['boardIssuer'] ?>" size="25" maxlength="50" /></td>
						<td class='data' ><input type='file' name='file2' id='file2' accept='application/zip'></td>
                        <td><?php print $fstr2;?></td>       
                    </tr>
					<tr>
						<td class='label'>3</td>
						<td>آگهي تاسيس</td>
						<td><input  name="fundationYear" type="text" class="textbox" id="fundationYear" 
			  value="<?php echo $resultPOD['fundationYear'] ?>" size="8" maxlength="10" /></td>
						<td><input  name="fundationno" type="text" class="textbox" id="fundationno" 
			  value="<?php echo $resultPOD['fundationno'] ?>" size="5" maxlength="10" /></td>
						<td></td>
						<td><input name="fundationIssuer" type="text" class="textbox" id="fundationIssuer" 
			  value="<?php echo $resultPOD['fundationIssuer'] ?>" size="25" maxlength="50" /></td>
						<td class='data'><input type='file' name='file3' id='file3' accept='application/zip'></td>
                       <td><?php print $fstr3;?></td>        
                    </tr>
					<tr>
						<td class='label'>4</td>
						<td>گواهي مجوز دفتر بهبود سامانه هاي نوين آبياري </td>
						<td><input placeholder="انتخاب تاریخ"  name="copermisiondate" type="text" class="textbox" id="copermisiondate" value="<?php if (strlen($resultPOD['copermisiondate'])>0) echo $resultPOD['copermisiondate']; else echo gregorian_to_jalali(date('Y-m-d')); ?>" size="8" maxlength="10" /></td>
						<td><input  name="copermisionno" type="text" class="textbox" id="copermisionno" 
			  value="<?php echo $resultPOD['copermisionno'] ?>" size="5" maxlength="10" /></td>
						<td><input placeholder="انتخاب تاریخ"  name="copermisionvalidate" type="text" class="textbox" id="copermisionvalidate" value="<?php if (strlen($resultPOD['copermisionvalidate'])>0) echo $resultPOD['copermisionvalidate']; else echo gregorian_to_jalali(date('Y-m-d')); ?>" size="8" maxlength="10" /></td>
						<td><input  name="copermisionIssuer" type="text" class="textbox" id="copermisionIssuer" 
			  value="<?php echo $resultPOD['copermisionIssuer'] ?>" size="25" maxlength="50" /></td>
						<td class='data'><input type='file' name='file4' id='file4' accept='application/zip'></td>
                        <td><?php print $fstr4;?></td>       
                    </tr>
					<tr>
						<td class='label'>5</td>
						<td>گواهي صلاحيت پيمانكاري</td>
						<td><input placeholder="انتخاب تاریخ"  name="contractordate" type="text" class="textbox" id="contractordate" value="<?php if (strlen($resultPOD['contractordate'])>0) echo $resultPOD['contractordate']; else echo gregorian_to_jalali(date('Y-m-d')); ?>" size="8" maxlength="10" /></td>
						<td><input  name="contractorno" type="text" class="textbox" id="contractorno" 
			  value="<?php echo $resultPOD['contractorno'] ?>" size="5" maxlength="10" /></td>
						<td><input placeholder="انتخاب تاریخ"  name="contractorvalidate" type="text" class="textbox" id="contractorvalidate" value="<?php if (strlen($resultPOD['contractorvalidate'])>0) echo $resultPOD['contractorvalidate']; else echo gregorian_to_jalali(date('Y-m-d')); ?>" size="8" maxlength="10" /></td>
						<td><input  name="contractorIssuer" type="text" class="textbox" id="contractorIssuer" 
			  value="<?php echo $resultPOD['contractorIssuer'] ?>" size="25" maxlength="50" /></td>
						<td class='data'><input type='file' name='file5' id='file5' accept='application/zip'></td>
                        <td><?php print $fstr5;?></td>      
                    </tr>
					<tr>
						<td class='label'>6</td>
						<td>گواهي صلاحيت نظام مهندسي كشاورزي </td>
						<td><input placeholder="انتخاب تاریخ"  name="engineersystemdate" type="text" class="textbox" id="engineersystemdate" value="<?php if (strlen($resultPOD['engineersystemdate'])>0) echo $resultPOD['engineersystemdate']; else echo gregorian_to_jalali(date('Y-m-d')); ?>" size="8" maxlength="10" /></td>
						<td><input  name="engineersystemno" type="text" class="textbox" id="engineersystemno" 
			  value="<?php echo $resultPOD['engineersystemno'] ?>" size="5" maxlength="10" /></td>
						<td><input placeholder="انتخاب تاریخ"  name="engineersystemvalidate" type="text" class="textbox" id="engineersystemvalidate" value="<?php if (strlen($resultPOD['engineersystemvalidate'])>0) echo $resultPOD['engineersystemvalidate']; else echo gregorian_to_jalali(date('Y-m-d')); ?>" size="8" maxlength="10" /></td>
						<td><input  name="engineersystemIssuer" type="text" class="textbox" id="engineersystemIssuer" 
			  value="<?php echo $resultPOD['engineersystemIssuer'] ?>" size="25" maxlength="50" /></td>
						<td class='data'><input type='file' name='file6' id='file6' accept='application/zip'></td>
                        <td><?php print $fstr6;?></td>       
                    </tr>
					
							<tr>                     
                            <td colspan="8"><input   name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
							</tr>
                 </tbody>
                   
                </table>
                      
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

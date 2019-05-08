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
	
        if ($TBLID>0) 
            $IDUser = $TBLID; else  $IDUser = $login_ProducersID;
        $POD = 1; 

    }
    else if ($login_OperatorCoID>0 || $TBLNAME=="operatorco")
    { 
        if ($TBLID>0) 
            $IDUser = $TBLID; else $IDUser = $login_OperatorCoID;
        $POD = 2;	
    }	
    else if ($login_DesignerCoID>0 || $TBLNAME=="designerco" )  
    { 
        if ($TBLID>0) 
            $IDUser = $TBLID; else  $IDUser = $login_DesignerCoID;
        $POD = 3;
    }

	
} 
  

$register = false;
	
if ($_POST)
{     
 
 

$IDUser=$_POST['IDUser'];
$POD=$_POST['POD'];
$TBLNAME=$_POST['TBLNAME'];

    if ($POD == 3)        $TBLNAME="designerco";
    else if ($POD == 2)   $TBLNAME="operatorco";
    else if ($POD == 1)   $TBLNAME="producers";

//PRINT $_POST['IDUser'].'sa'.$_POST['POD'].'--'.$TBLNAME;exit;


	//  if (in_array($login_RolesID,  array("1", "4", "18","20")))
   // {
 
 
        $Title = $_POST['Title'];
        $BossName = $_POST['BossName'];
        $CompanyAddress = $_POST['CompanyAddress'];
        $talkerName = $_POST['talkerName'];
        $Wbsite = $_POST['Wbsite'];
        $Phone = $_POST['Phone'];
        $Fax = $_POST['Fax'];
        $Email = $_POST['Email'];
        $Phone2 = $_POST['Phone2'];
        $bosslname = $_POST['bosslname'];
        $bosslicence = $_POST['bosslicence'];
        $bosslicencedate = $_POST['bosslicencedate'];
        $bossmobile = $_POST['bossmobile'];
        $talkerlname = $_POST['talkerlname'];
        $talkerlicence = $_POST['talkerlicence'];
        $talkerlicencedate = $_POST['talkerlicencedate'];
        $signitureowner1 = $_POST['signitureowner1'];
        $signitureop1 = $_POST['signitureop1'];
        $signitureowner2 = $_POST['signitureowner2'];
        $signitureop2 = $_POST['signitureop2'];
        $signitureowner3 = $_POST['signitureowner3'];
        $otherboardmember = $_POST['otherboardmember'];

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
		
        $query = "UPDATE $TBLNAME SET 
		Title = '$Title',BossName = '$BossName',CompanyAddress = '$CompanyAddress', 
        talkerName = '$talkerName',Wbsite = '$Wbsite',Phone = '$Phone',Fax = '$Fax',Email = '$Email',Phone2 = '$Phone2',
        bosslname = '$bosslname',bosslicence = '$bosslicence',bosslicencedate = '$bosslicencedate',
        bossmobile = '$bossmobile',talkerlname = '$talkerlname',talkerlicence = '$talkerlicence',
        talkerlicencedate = '$talkerlicencedate',signitureowner1 = '$signitureowner1',signitureop1 ='$signitureop1',
        signitureowner2 = '$signitureowner2',signitureop2 = '$signitureop2',signitureowner3 = '$signitureowner3',
        AccountNo='$_POST[AccountNo]',AccountBank='$_POST[AccountBank]',
        otherboardmember = '$otherboardmember',
		
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
 
 $register = true;



		

}
		
?>
<!DOCTYPE html>
<html>
<head>
  	<title>مشخصات شرکت</title>

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
                
                
				
                $("#Title, #simpleLabel");   
                $("#BossName, #simpleLabel");   
                $("#CompanyAddress, #simpleLabel");   
				$("#talkerName, #simpleLabel");   
                $("#Wbsite, #simpleLabel");   
			    $("#Phone, #simpleLabel");   
				$("#Fax, #simpleLabel");   
                $("#Email, #simpleLabel");   
				$("#Phone2, #simpleLabel");   
                $("#bosslname, #simpleLabel");   
				$("#bosslicence, #simpleLabel");   
                
                $("#bosslicencedate, #simpleLabel").persiandatepicker();   
                $("#bossmobile, #simpleLabel");   
                $("#talkerlname, #simpleLabel");   
				$("#talkerlicence, #simpleLabel");   
                $("#talkerlicencedate, #simpleLabel").persiandatepicker();   
			    $("#signitureowner1, #simpleLabel");   
				$("#signitureop1, #simpleLabel");   
                $("#signitureowner2, #simpleLabel");   
				$("#signitureop2, #simpleLabel");   
                $("#signitureowner3, #simpleLabel");   
				$("#otherboardmember, #simpleLabel"); 
                $("#AccountNo, #simpleLabel");   
				$("#AccountBank, #simpleLabel"); 
                
				
            });
                
    </script>
    <!-- /scripts -->
</head>
<body >

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">

		<?php

				if ($_POST){
					if ($register){
						echo '<p class="note">ثبت با موفقيت انجام شد</p>';
	                    
					}else{
						echo '<p class="error">خطا در ثبت...</p>';
					}
				}

?>

		
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
			  <div style = "text-align:left;">
			             <?php $permitrolsid = array("1", "4","18","20","21");
                         if (in_array($login_RolesID, $permitrolsid) && $TBLID>0) { ?>
			          	  <a  href=<?php if ($TBLNAME=="producers")
                           print "../members_" . $TBLNAME.".php"."><img style = \"width: 4%;\" src=\"../img/Return.png\" title='بازگشت' ></a></p>";
						else 
						    print "../members_" . $TBLNAME."s.php"."><img style = \"width: 4%;\" src=\"../img/Return.png\" title='بازگشت' ></a></div>";
					     	}	?>
						
            
			
			<form action="approvedocumentcompany1.php" method="post" enctype="multipart/form-data">
			    <div id="loading-div-background">
					<div id="loading-div" class="ui-corner-all" >
						 <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
						 <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
					</div>
			    </div>
				
				<br/>
				
				<table id="recordtable" width="99%" align="center">
                   <tbody>           
               

				<?php 
				
				
              //  if (in_array($login_RolesID,  array("1", "4", "18","20")))
			  
			  
                //    $disabled_string='';
              //  else 
                //    $disabled_string='disabled';
                $readonly="";
				if ($POD==1) {
                $sql = "SELECT 
				
				producers.Title,producers.BossName,producers.CompanyAddress,producers.talkerName,producers.Wbsite,producers.Phone,producers.Fax
				,producers.Email,producers.Phone2,producers.bosslname,producers.bosslicence	,producers.bosslicencedate,producers.bossmobile
				,producers.talkerlname,producers.talkerlicence,producers.talkerlicencedate,producers.signitureowner1,producers.signitureop1
				,producers.signitureowner2,producers.signitureop2,producers.signitureowner3,producers.otherboardmember
				,producers.AccountNo,producers.AccountBank
				
                FROM producers 
                where producers.producersID='$IDUser' ";
				$resultPOD = mysql_fetch_assoc(mysql_query($sql));}
				else if ($POD==2) {
				$readonly="readonly";
                $sql = "SELECT 
				
				 operatorco.Title,operatorco.CompanyAddress ,operatorco.Wbsite,operatorco.Phone
				,operatorco.Fax,operatorco.Email,operatorco.Phone2,operatorco.bosslicence,operatorco.bosslicencedate
	            ,operatorco.talkerlicence,operatorco.talkerlicencedate,operatorco.signitureowner1
				,operatorco.signitureop1,operatorco.signitureowner2,operatorco.signitureop2,operatorco.signitureowner3,operatorco.otherboardmember
				,operatorco.AccountNo,operatorco.AccountBank
				,membersinfo.FName BossName,membersinfo.LName bosslname,membersinfo.Phone bossmobile
           		,membersinfo2.FName talkerName,membersinfo2.LName talkerlname
                FROM operatorco 
				
				 left outer join (
					select count(*) memberscnt,members.*
					,case members2.NationalCode>0 when 1 then 1 else 0 end duplicatemembers
					from members 
					left outer join members members2 on members2.NationalCode=members.NationalCode and members2.operatorcoid<>members.operatorcoid
					where members.operatorcoid>0 and members.Position=1
					group by members.operatorcoid) membersinfo on membersinfo.operatorcoid=operatorco.operatorcoID

				
				
				
				 left outer join (
					select count(*) memberscnt,members.*
					,case members2.NationalCode>0 when 1 then 1 else 0 end duplicatemembers
					
					from members 
					left outer join members members2 on members2.NationalCode=members.NationalCode and members2.operatorcoid<>members.operatorcoid
					where members.operatorcoid>0 and members.Position=2
					group by members.operatorcoid) membersinfo2 on membersinfo2.operatorcoid=operatorco.operatorcoID
		
				
				
                where operatorco.OperatorCoID='$IDUser' ";
                $resultPOD = mysql_fetch_assoc(mysql_query($sql));}
				
				else if ($POD==3) {
                $sql = "SELECT 
				
				 designerco.Title,designerco.BossName,designerco.CompanyAddress,designerco.talkerName,designerco.Wbsite,designerco.Phone
				,designerco.Fax,designerco.Email,designerco.Phone2,designerco.bosslname,designerco.bosslicence,designerco.bosslicencedate
	            ,designerco.bossmobile,designerco.talkerlname,designerco.talkerlicence,designerco.talkerlicencedate,designerco.signitureowner1
				,designerco.signitureop1,designerco.signitureowner2,designerco.signitureop2,designerco.signitureowner3,designerco.otherboardmember
				,designerco.AccountNo,designerco.AccountBank
				FROM designerco 
                where designerco.designercoID='$IDUser' ";
				
				$resultPOD = mysql_fetch_assoc(mysql_query($sql));}
				
			if ($resultPOD["CompanyAddress"]=='' || $resultPOD["BossName"]=='')	
			   echo ("<SCRIPT LANGUAGE='JavaScript'>  alert('لطفا نسبت به تکمیل مدارک در سربرگ  تاییدیه اقدام نمایید.'); </SCRIPT>");
				
	
				echo ("<SCRIPT LANGUAGE='JavaScript'> alert('لطفا بخش های خالی را تکمیل نمایید.'); </SCRIPT>");
				
				
				print "<tr><td colspan='9' style='text-align:center; height:50px;'><br/><b>تاييديه مشخصات  ".$producType.' <b><b> '.$resultPOD['Title']."</b></td></tr>"; ?>
				<tr>
				<!--
						<td style="width:30%">آدرس شرکت </td>
						<td style="width:18%"><input  name="boardIssuer" type="text" class="textbox" id="boardIssuer" 
			        
				-->
				<td  style="text-align:center; height:25px;">رديف</td>
				<td colspan='1' style="text-align:center;">شرح</td><td></td>
				<td colspan='1' style="text-align:center;">مشخصات</td>
				
				<td  style="text-align:center; height:25px;">رديف</td>
				<td colspan='1' style="text-align:center;">شرح</td><td></td>
				<td colspan='1' style="text-align:center;">مشخصات</td>
				
				</tr>
				    <tr>
						<td style="text-align:center;"class='label'>1</td>
						<td>نام <?php echo $producType ?> </td><td></td>
						<td><input placeholder="<?php echo $resultPOD['Title'] ?>" name="Title" type="text" class="textbox" id="Title" 
			  value="<?php echo $resultPOD['Title'] ?>" size="30" maxlength="50" /></td>
                    	<td style="text-align:center;"class='label'>2</td>
						<td >آدرس <?php echo $producType ?> </td>
						<td colspan='2'><input  placeholder="<?php echo 'آدرس '; ?>"name="CompanyAddress" type="text" class="textbox" id="CompanyAddress" 
			  value="<?php echo $resultPOD['CompanyAddress'] ?>" size="55" maxlength="80" /></td>
                    </tr>
					
					<tr>
						<td style="text-align:center;"class='label'>3</td>
						<td>وب سایت </td><td></td>
						<td><input placeholder="<?php echo 'وب'; ?>" name="Wbsite" type="text" class="textbox" id="Wbsite" 
			  value="<?php echo $resultPOD['Wbsite'] ?>" size="30" maxlength="50" /></td>
                    	<td style="text-align:center;"class='label'>4</td>
						<td >ایمیل</td>
						<td colspan='2'><input  placeholder="<?php echo 'ایمیل'; ?>"name="Email" type="text" class="textbox" id="Email" 
			  value="<?php echo $resultPOD['Email'] ?>" size="40" maxlength="50" /></td>
                    </tr>
					
					
					<tr>
						<td style="text-align:center;"class='label'>5</td>
						<td>تلفن</td><td></td>
						<td><input placeholder="<?php echo 'تلفن'; ?>" name="Phone" type="text" class="textbox" id="Phone" 
			  value="<?php echo $resultPOD['Phone'] ?>" size="30" maxlength="12" /></td>
                    	<td style="text-align:center;"class='label'>6</td>
						<td >تلفن</td>
						<td colspan='2'><input  placeholder="<?php echo 'تلفن'; ?>"name="Phone2" type="text" class="textbox" id="Phone2" 
			  value="<?php echo $resultPOD['Phone2'] ?>" size="40" maxlength="12" /></td>
                    </tr>
					
							<tr>
						<td style="text-align:center;"class='label'>7</td>
						<td>فکس</td><td></td>
						<td><input placeholder="<?php echo ' فکس'; ?>" name="Fax" type="text" class="textbox" id="Fax" 
			  value="<?php echo $resultPOD['Fax'] ?>" size="30" maxlength="50" /></td>
                    	<td style="text-align:center;"class='label'>8</td>
						<td >همراه مدیرعامل</td>
						<td colspan='2'><input  placeholder="<?php echo 'همراه'; ?>"name="bossmobile" type="text" class="textbox" id="bossmobile" 
			  value="<?php echo $resultPOD['bossmobile'] ?>" size="40" maxlength="12" <?php echo $readonly; ?> /></td>
                    </tr>
			
					<tr>
						<td style="text-align:center;"class='label'>9</td>
						<td>نام مدیرعامل </td><td></td>
						<td><input placeholder="<?php echo 'نام '; ?>" name="BossName" type="text" class="textbox" id="BossName" 
			  value="<?php echo $resultPOD['BossName'] ?>" size="30" maxlength="50" <?php echo $readonly; ?> /></td>
                    	<td style="text-align:center;"class='label'>10</td>
						<td >نام رئیس هیئت مدیره </td>
						<td colspan='2'><input  placeholder="<?php echo 'نام '; ?>"name="talkerName" type="text" class="textbox" id="talkerName" 
			  value="<?php echo $resultPOD['talkerName'] ?>" size="40" maxlength="50" <?php echo $readonly; ?> /></td>
                    </tr>
					<tr>
						<td style="text-align:center;"class='label'>11</td>
						<td>نام خانوادگی مدیر عامل</td><td></td>
						<td><input placeholder="<?php echo 'نام '; ?>" name="bosslname" type="text" class="textbox" id="bosslname" 
			  value="<?php echo $resultPOD['bosslname'] ?>" size="30" maxlength="50" <?php echo $readonly; ?> /></td>
                    	<td style="text-align:center;"class='label'>12</td>
						<td >نام خانوادگی رئیس هیئت مدیره</td>
						<td colspan='2'><input  placeholder="<?php echo 'نام '; ?>"name="talkerlname" type="text" class="textbox" id="talkerlname" 
			  value="<?php echo $resultPOD['talkerlname'] ?>" size="40" maxlength="50" <?php echo $readonly; ?> /></td>
                    </tr>
					
					
					<tr>
						<td style="text-align:center;"class='label'>13</td>
						<td>مدرک تحصیلی مدیرعامل</td><td></td>
						<td><input placeholder="<?php echo 'مدرک تحصیلی'; ?>" name="bosslicence" type="text" class="textbox" id="bosslicence" 
			  value="<?php echo $resultPOD['bosslicence'] ?>" size="30" maxlength="50" /></td>
                    	<td style="text-align:center;"class='label'>14</td>
						<td >مدرک تحصیلی رئیس هیئت مدیره</td>
						<td colspan='2'><input  placeholder="<?php echo 'مدرک تحصیلی'; ?>"name="talkerlicence" type="text" class="textbox" id="talkerlicence" 
			  value="<?php echo $resultPOD['talkerlicence'] ?>" size="40" maxlength="50" /></td>
                    </tr>
					<tr>
						<td style="text-align:center;"class='label'>15</td>
						<td>تاریخ مدرک تحصیلی مدیرعامل</td><td></td>
						<td><input placeholder="<?php echo 'تاریخ'; ?>" name="bosslicencedate" type="text" class="textbox" id="bosslicencedate" 
			  value="<?php echo $resultPOD['bosslicencedate'] ?>" size="30" maxlength="50" /></td>
                    	<td style="text-align:center;"class='label'>16</td>
						<td >تاریخ مدرک رئیس هیئت مدیره</td>
						<td colspan='2'><input  placeholder="<?php echo 'تاریخ'; ?>"name="talkerlicencedate" type="text" class="textbox" id="talkerlicencedate" 
			  value="<?php echo $resultPOD['talkerlicencedate'] ?>" size="40" maxlength="50" /></td>
                    </tr>
					
					<tr>
						<td style="text-align:center;"class='label'>17</td>
						<td>شماره حساب <?php echo $producType ?></td><td></td>
						<td><input placeholder="<?php echo 'شماره حساب '; ?>" name="AccountNo" type="text" class="textbox" id="AccountNo" 
			  value="<?php echo $resultPOD['AccountNo'] ?>" size="30" maxlength="50" /></td>
                    	<td style="text-align:center;"class='label'>18</td>
						<td >نام بانک</td>
						<td colspan='2'><input  placeholder="<?php echo 'نام بانک'; ?> "name="AccountBank" type="text" class="textbox" id="AccountBank" 
			  value="<?php echo $resultPOD['AccountBank'] ?>" size="40" maxlength="50" /></td>
                    </tr>
					<tr>
						<td style="text-align:center;"class='label'>19</td>
						<td>حق امضا </td><td></td>
						<td><input placeholder="<?php echo 'نام مدیرعامل'; ?>" name="signitureowner1" type="text" class="textbox" id="signitureowner1" 
			  value="<?php echo $resultPOD['signitureowner1'] ?>" size="30" maxlength="50" /></td>
            			<td ><input  placeholder="<?php echo 'و/یا/.'; ?>"name="signitureop1" type="text" class="textbox" id="signitureop1" 
			  value="<?php echo $resultPOD['signitureop1'] ?>" size="3" maxlength="3" /></td>
			            
                    	<td><input placeholder="<?php echo 'نام رئیس هیئت مدیره'; ?>" name="signitureowner2" type="text" class="textbox" id="signitureowner2" 
			  value="<?php echo $resultPOD['signitureowner2'] ?>" size="28" maxlength="50" /></td>
			
                    	<td ><input  placeholder="<?php echo 'و/یا/.'; ?>"name="signitureop2" type="text" class="textbox" id="signitureop2" 
			  value="<?php echo $resultPOD['signitureop2'] ?>" size="3" maxlength="3" /></td>
			  
			        	<td><input placeholder="<?php echo 'مهر شرکت'; ?>" name="signitureowner3" type="text" class="textbox" id="signitureowner3" 
			  value="<?php echo $resultPOD['signitureowner3'] ?>" size="28" maxlength="50" /></td>
              
                    </tr>
					
					
					<tr>
						<td style="text-align:center;"class='label'>20</td>
						<td>دیگر اعضای هیئت مدیره</td><td></td>
						<td colspan='5'><input placeholder="<?php echo 'نام شرکت'; ?>" name="otherboardmember" type="text" class="textbox" id="otherboardmember" 
			  value="<?php echo $resultPOD['otherboardmember'] ?>" size="60" maxlength="50" /></td>
                    </tr>
					
					
					<tr>
						<td style="text-align:center;"class='label'></td>
						<td></td><td></td>
						<td><input  name="IDUser" type="hidden" class="textbox" id="IDUser" 
			  value="<?php echo $IDUser; ?>" size="30" maxlength="50" /></td>
                    	<td style="text-align:center;"class='label'></td>
						<td ></td>
						<td colspan='2'><input name="POD" type="hidden" class="textbox" id="POD" 
			  value="<?php echo $POD; ?>" size="40" maxlength="50" /></td>
                    </tr>
					
							<tr>                     
                            <td colspan="8"><input   name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
							</tr>
							
							
                 </tbody>
                   
                </table>

				<div>
				<p style="text-align:right; color:red;">*در صورت اعمال تغييرات جهت تائيد ضمن ارسال مستندات با مدیریت آب و خاک تماس حاصل فرمائيد. </p>
				</div>                     
                 <tr>
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
                </form> 
				
            </div>
			<!-- /content -->

		</div>
		

            <!-- footer -->
			<?php include('../includes/footer.php'); ?>
            <!-- /footer -->

        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>

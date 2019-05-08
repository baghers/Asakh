<?php 
/*

//admin/defineuseraccess.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود

-

*/

include('../includes/connect.php'); 
include('../includes/check_user.php');
include('../includes/elements.php');

if ($login_Permission_granted==0) header("Location: ../login.php");

$g1id=$_GET["g1id"];//شناسه استان
        
if ($_POST)//دکمه ثبت کلیک شده بود 
{
    if ($login_designerCO==1)//نقش مدیر پیگیری
    {
        $login_ostanId=substr($_POST['g1id'],0,2);//شناسه استان
        $g1id=$_POST['g1id'];
    }	
	/*
    supervisorcoderrquirement جدول تنظیمات پیکربندی سیستم
    KeyStr عنوان تنظیم مربوطه
    ValueInt مقدار صحیح تنظیم مربوطه
    SaveTime زمان
    SaveDate تاریخ
    ClerkID کاربر
    ostan استان
    */	
    try 
    {	
        $sql= "UPDATE supervisorcoderrquirement SET  ValueInt = '$_POST[proposedaycnt]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "',	ClerkID = '" . $login_userid . "' WHERE KeyStr ='proposedaycnt' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET  ValueInt = '$_POST[propose30daypermissionless]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='propose30daypermissionless' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[proposepermissionless]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "',ClerkID = '" . $login_userid . "' WHERE KeyStr ='proposepermissionless' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[proposedesignerless]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='proposedesignerless' and ostan='$login_ostanId'";
        mysql_query($sql);
       $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[startpay]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='startpay' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[AdminRolesID]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='AdminRolesID' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[permitNotInvoice]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='permitNotInvoice' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[permitPerposeCity]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='permitPerposeCity' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[permitErrTiminig]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='permitErrTiminig' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[permitTiminig]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='permitTiminig' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[permitEmtiaz]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='permitEmtiaz' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[smallapplicantsize]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='smallapplicantsize' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET  ValueInt = '$_POST[elapseddayforautomatictransfer]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='elapseddayforautomatictransfer' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET  ValueInt = '$_POST[proosecntforautomatictransfer]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='proosecntforautomatictransfer' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET  ValueInt = '$_POST[proposenumcnt]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='proposenumcnt' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[proposeautomat]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='proposeautomat' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[percentapplicantsize]',SaveTime = '" . date('Y-m-d H:i:s') . "',
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='percentapplicantsize' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[percentapplicantsize4]',SaveTime = '" . date('Y-m-d H:i:s') . "',
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='percentapplicantsize4' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[percentapplicantsize3]',SaveTime = '" . date('Y-m-d H:i:s') . "',
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='percentapplicantsize3' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[percentapplicantsize2]',SaveTime = '" . date('Y-m-d H:i:s') . "',
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='percentapplicantsize2' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[percentapplicantsize1]',SaveTime = '" . date('Y-m-d H:i:s') . "',
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='percentapplicantsize1' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[hmmp5zarib]',SaveTime = '" . date('Y-m-d H:i:s') . "',
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='hmmp5zarib' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[hmmp4zarib]',SaveTime = '" . date('Y-m-d H:i:s') . "',
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='hmmp4zarib' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[hmmp3zarib]',SaveTime = '" . date('Y-m-d H:i:s') . "',
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='hmmp3zarib' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[hmmp2zarib]',SaveTime = '" . date('Y-m-d H:i:s') . "',
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='hmmp2zarib' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[hmmp1zarib]',SaveTime = '" . date('Y-m-d H:i:s') . "',
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='hmmp1zarib' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[proposeprojectlessCnt]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='proposeprojectlessCnt' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[proposeprojectlessHa]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='proposeprojectlessHa' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[proposeprojectless]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='proposeprojectless' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[proposecoless]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='proposecoless' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[proposedamane]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='proposedamane' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[hidecredit]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='hidecredit' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[sentoanjomanws]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='sentoanjomanws' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[p1Zpishhamzaman]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='p1Zpishhamzaman' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[p2Zpishhamzaman]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='p2Zpishhamzaman' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[p3Zpishhamzaman]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='p3Zpishhamzaman' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[p4Zpishhamzaman]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='p4Zpishhamzaman' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[p5Zpishhamzaman]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='p5Zpishhamzaman' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[p1Zpishhamzamanvol]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='p1Zpishhamzamanvol' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[p2Zpishhamzamanvol]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='p2Zpishhamzamanvol' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[p3Zpishhamzamanvol]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='p3Zpishhamzamanvol' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[p4Zpishhamzamanvol]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='p4Zpishhamzamanvol' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[p5Zpishhamzamanvol]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='p5Zpishhamzamanvol' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[validday]', SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='validday' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[pipeproposerror]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='pipeproposerror' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[pipeproposetonaj]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='pipeproposetonaj' and ostan='$login_ostanId'";
        mysql_query($sql);   
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[hourcntforproposepselection]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='hourcntforproposepselection' and ostan='$login_ostanId'";
        mysql_query($sql);   
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[deadlineerj]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='deadlineerj' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[deadlineselectop]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='deadlineselectop' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[deadlinefirstsave]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='deadlinefirstsave' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[deadlineapprove]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='deadlineapprove' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[deadlinetempdel]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='deadlinetempdel' and ostan='$login_ostanId'";
        mysql_query($sql);
        $sql= "UPDATE supervisorcoderrquirement SET ValueInt = '$_POST[deadlinepermanentdel]',SaveTime = '" . date('Y-m-d H:i:s') . "', 
    		SaveDate = '" . date('Y-m-d') . "', ClerkID = '" . $login_userid . "' WHERE KeyStr ='deadlinepermanentdel' and ostan='$login_ostanId'";
        mysql_query($sql); 
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
    } 
  
}


if ($g1id>0) $ostanID=substr($g1id,0,2); else $ostanID=$login_ostanId;
//تابع خواندن تنظیمات پیکربندی سیستم و قراردادن نتایج در آرایه کلید و مقدار
$Permissionvals=supervisorcoderrquirement_sql($ostanID);    				

?>
<!DOCTYPE html>
<html>
<head>
  	<title>مدیریت امکان پیشنهاد قیمت</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
<script>
	function selectpage()
    {
        window.location.href ='?g1id=' +document.getElementById('g1id').value;
        
	}
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
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
                <br />
              <form action="defineuseraccess.php" method="post">
            <table id="records" width="90%" align="center">
                   
<?php	 
//print $g1id;

 if ($login_designerCO==1)
            {
			$sqlselect="select distinct ostan.CityName _key,ostan.id _value  FROM clerk
			inner join tax_tbcity7digit ostan on substring(ostan.id,1,2)=substring(clerk.cityid,1,2) and substring(ostan.id,3,5)='00000'
			$ost
			order by _key  COLLATE utf8_persian_ci";
			$allg1id = get_key_value_from_query_into_array($sqlselect);
            print select_option('g1id','استان',',',$allg1id,0,'','','4','rtl',0,'',$g1id,"onChange=\"selectpage();\"",'213');
?>    <td colspan="2"> <input value='<?php echo $g1id; ?>' hidden name='g1id' type='text' class='textbox' id='g1id'    size='8' maxlength='8' /></td>
  <?php			
	        }
     ?>                
            
            <thead>
              <!--tr><th colspan="8"><div id="mydiv" > امکان شرکت در پیشنهاد قیمت </div></th></tr-->
            </thead>    
            <tbody>
					 <tr >       
				    <td colspan="3" align="center" > <b> پیشنهاد قیمت </b></td>
					  <td></td><td></td><td></td><td></td>
                </tr >
		
                 <tr >        
                    <td ><b>شرح</b></td>
                    <td colspan="6"><b>اجازه پیشنهاد قیمت</b></td>
                 </tr >
                 
				 
				 <tr><td></td>
                    <td><b>دارد</b></td>
                    <td><b>ندارد</b></td>
                    <td colspan="4"></td>
                 </tr>
				 
                 <tr><td>تعداد روز مورد نیاز جهت ارجاع خودکار(روز)</td>
                 <td>
                  <input value='<?php echo $Permissionvals['elapseddayforautomatictransfer']; ?>'
                       name='elapseddayforautomatictransfer' type='text'  id='elapseddayforautomatictransfer'    size='2' maxlength='5' /></td>
                  <td></td><td></td><td></td><td></td><td></td>
                  </tr>
                 <tr><td>تعداد پیشنهاد مورد نیاز جهت ارجاع خودکار(عدد)</td>
                 <td>
                  <input value='<?php echo $Permissionvals['proosecntforautomatictransfer']; ?>'
                       name='proosecntforautomatictransfer' type='text'  id='proosecntforautomatictransfer'    size='2' maxlength='5' /></td>
                  <td></td><td></td><td></td><td></td><td></td>
                  </tr> 
                  
                 <tr><td>تعداد روز مجاز پیشنهاد قیمت (روز)</td>
                 <td>
                  <input value='<?php echo $Permissionvals['proposedaycnt']; ?>'
                       name='proposedaycnt' type='text'  id='proposedaycnt'    size='2' maxlength='5' /></td>
                  <td></td><td></td><td></td><td></td><td></td>
                  </tr>
                  
				  
                 <tr><td>حداقل تعداد مجاز پیشنهاد قیمت (تعداد)</td>
                 <td>
                  <input value='<?php echo $Permissionvals['proposenumcnt']; ?>'
                       name='proposenumcnt' type='text'  id='proposenumcnt'    size='2' maxlength='5' /></td>
                  <td></td><td></td><td></td><td></td><td></td>
                  </tr>
				  
				 <tr><td>امکان ارجاع خودکار پیشنهاد قیمت</td>
                 <td>
                    <input type="radio" name="proposeautomat" value="1" <?php if ($Permissionvals['proposeautomat']==1) echo "checked"; ?>>
                  </td>
                  <td>
                   <input type="radio" name="proposeautomat" value="0" <?php if ($Permissionvals['proposeautomat']==0) echo "checked"; ?>>
                   </td>
				  <td></td><td></td><td></td><td></td>
                  </tr> 
                  
                  
                  <tr><td>حداکثر مساحت طرح های کوچک (هکتار)</td>
                 <td>
                  <input value='<?php echo $Permissionvals['smallapplicantsize']; ?>'
                       name='smallapplicantsize' type='text' id='smallapplicantsize'    size='2' maxlength='5' /></td>
                  <td></td>  <td></td><td></td><td></td><td></td>
                
                  </tr>
                 
				 <tr><td>درصد مجاز افزایش مساحت هر پایه(%)</td>
                 <td>
                  <input value='<?php echo $Permissionvals['percentapplicantsize']; ?>'
                       name='percentapplicantsize' type='text'  id='percentapplicantsize'    size='2' maxlength='5' />:5</td>
                 <td>
                  <input value='<?php echo $Permissionvals['percentapplicantsize4']; ?>'
                       name='percentapplicantsize4' type='text'  id='percentapplicantsize4'    size='2' maxlength='5' />:4</td>
                 <td>
                  <input value='<?php echo $Permissionvals['percentapplicantsize3']; ?>'
                       name='percentapplicantsize3' type='text'  id='percentapplicantsize3'    size='2' maxlength='5' />:3</td>
                 <td>
                  <input value='<?php echo $Permissionvals['percentapplicantsize2']; ?>'
                       name='percentapplicantsize2' type='text'  id='percentapplicantsize2'    size='2' maxlength='5' />:2</td>
                 <td>
                  <input value='<?php echo $Permissionvals['percentapplicantsize1']; ?>'
                       name='percentapplicantsize1' type='text'  id='percentapplicantsize1'    size='2' maxlength='5' />:1</td>
                       <td></td>         
                  </tr>
                 		 <tr><td>ضریب مجاز افزایش مساحت هر پایه(%)</td>
                 <td>
                  <input value='<?php echo $Permissionvals['hmmp5zarib']; ?>'
                       name='hmmp5zarib' type='text'  id='hmmp5zarib'    size='2' maxlength='5' />:5</td>
                 <td>
                  <input value='<?php echo $Permissionvals['hmmp4zarib']; ?>'
                       name='hmmp4zarib' type='text'  id='hmmp4zarib'    size='2' maxlength='5' />:4</td>
                 <td>
                  <input value='<?php echo $Permissionvals['hmmp3zarib']; ?>'
                       name='hmmp3zarib' type='text'  id='hmmp3zarib'    size='2' maxlength='5' />:3</td>
                 <td>
                  <input value='<?php echo $Permissionvals['hmmp2zarib']; ?>'
                       name='hmmp2zarib' type='text'  id='hmmp2zarib'    size='2' maxlength='5' />:2</td>
                 <td>
                  <input value='<?php echo $Permissionvals['hmmp1zarib']; ?>'
                       name='hmmp1zarib' type='text'  id='hmmp1zarib'    size='2' maxlength='5' />:1</td>
                       <td></td>         
                  </tr>
     
				 <tr><td>امکان پیشنهاد قیمت یک ماه قبل از اتمام مجوز</td>
                 <td>
                    <input type="radio" name="propose30daypermissionless" value="1" <?php if ($Permissionvals['propose30daypermissionless']==1) echo "checked"; ?>>
                  </td>
                  <td>
                   <input type="radio" name="propose30daypermissionless" value="0" <?php if ($Permissionvals['propose30daypermissionless']==0) echo "checked"; ?>>
                   </td>
				    <td></td><td></td><td></td><td></td>
                 
                  </tr>
                  
                 <tr><td>امکان پیشنهاد قیمت شرکتهای فاقد مجوز</td>
                 <td>
                    <input type="radio" name="proposepermissionless" value="1" <?php if ($Permissionvals['proposepermissionless']==1) echo "checked"; ?>>
                  </td>
                  <td>
                   <input type="radio" name="proposepermissionless" value="0" <?php if ($Permissionvals['proposepermissionless']==0) echo "checked"; ?>>
                   </td>
				     <td></td><td></td><td></td><td></td>
                
                  </tr> 
                  
                  
                 <tr><td>امکان پیشنهاد قیمت شرکتهای فاقد کارشناس فنی</td>
                 <td>
                    <input type="radio" name="proposedesignerless" value="1" <?php if ($Permissionvals['proposedesignerless']==1) echo "checked"; ?>>
                  </td>
                  <td>
                   <input type="radio" name="proposedesignerless" value="0" <?php if ($Permissionvals['proposedesignerless']==0) echo "checked"; ?>>
                   </td>
				     <td></td><td></td><td></td><td></td>
                
                  </tr>  
                 
				  
                 <tr><td>امکان پیشنهاد قیمت شرکتهای فاقد اعتبار هیئت مدیره</td>
                 <td>
                    <input type="radio" name="proposecoless" value="1" <?php if ($Permissionvals['proposecoless']==1) echo "checked"; ?>>
                  </td>
                  <td>
                   <input type="radio" name="proposecoless" value="0" <?php if ($Permissionvals['proposecoless']==0) echo "checked"; ?>>
                   </td>
				     <td></td><td></td><td></td><td></td>
                
                  </tr>  

				 <tr><td>امکان پیشنهاد قیمت خارج از ظرفیت تعداد</td>
                 <td>
                    <input type="radio" name="proposeprojectlessCnt" value="1" <?php if ($Permissionvals['proposeprojectlessCnt']==1) echo "checked"; ?>>
                  </td>
                  <td>
                   <input type="radio" name="proposeprojectlessCnt" value="0" <?php if ($Permissionvals['proposeprojectlessCnt']==0) echo "checked"; ?>>
                   </td>
				     <td></td><td></td><td></td><td></td>
                
                  </tr>  
                    
			 <tr><td>امکان پیشنهاد قیمت خارج از ظرفیت هکتار</td>
                 <td>
                    <input type="radio" name="proposeprojectlessHa" value="1" <?php if ($Permissionvals['proposeprojectlessHa']==1) echo "checked"; ?>>
                  </td>
                  <td>
                   <input type="radio" name="proposeprojectlessHa" value="0" <?php if ($Permissionvals['proposeprojectlessHa']==0) echo "checked"; ?>>
                   </td>
				     <td></td><td></td><td></td><td></td>
                
                  </tr>  
                    
		 <tr><td>امکان پیشنهاد قیمت با داشتن سایر خطاها</td>
                 <td>
                    <input type="radio" name="proposeprojectless" value="1" <?php if ($Permissionvals['proposeprojectless']==1) echo "checked"; ?>>
                  </td>
                  <td>
                   <input type="radio" name="proposeprojectless" value="0" <?php if ($Permissionvals['proposeprojectless']==0) echo "checked"; ?>>
                   </td>
				     <td></td><td></td><td></td><td></td>
                
                  </tr>  
                    
		
					
				 <tr><td>خارج از دامنه متناسب قیمتهای پیشنهادی</td>
                 <td>
                    <input type="radio" name="proposedamane" value="1" <?php if ($Permissionvals['proposedamane']==1) echo "checked"; ?>>
                  </td>
                  <td>
                   <input type="radio" name="proposedamane" value="0" <?php if ($Permissionvals['proposedamane']==0) echo "checked"; ?>>
                   </td>
				     <td></td><td></td><td></td><td></td>
                
                  </tr> 
                  
 				 <tr><td>امکان نمایش ستون اعتبارات در پیشنهاد قیمت</td>
                 <td>
                    <input type="radio" name="hidecredit" value="0" <?php if ($Permissionvals['hidecredit']==0) echo "checked"; ?>>
                  </td>
                  <td>
                   <input type="radio" name="hidecredit" value="1" <?php if ($Permissionvals['hidecredit']==1) echo "checked"; ?>>
                   </td>
				     <td></td><td></td><td></td><td></td>
                
                  </tr> 
       		                  
			     <tr><td>حداکثر تعداد طرح ثبت نشده جهت پیشنهاد قیمت</td>
                 <td>
                  <input value='<?php echo $Permissionvals['permitNotInvoice']; ?>'
                       name='permitNotInvoice' type='text'  id='permitNotInvoice'    size='3' maxlength='5' /></td>
                  <td></td>  <td></td><td></td><td></td><td></td>
                  </tr>
           
         		                  
			     <tr><td>حداکثر مساحت تفویضی به شهرستان هکتار</td>
                 <td>
                  <input value='<?php echo $Permissionvals['permitPerposeCity']; ?>'
                       name='permitPerposeCity' type='text'  id='permitPerposeCity'    size='3' maxlength='5' /></td>
                  <td></td>  <td></td><td></td><td></td><td></td>
                  </tr>
           
             
 				 <tr><td>امکان ثبت منتخب پیشنهاد خطادار توسط شهرستان</td>
                 <td>
                    <input type="radio" name="permitErrNazer" value="0" <?php if ($Permissionvals['permitErrNazer']==0) echo "checked"; ?>>
                  </td>
                  <td>
                   <input type="radio" name="permitErrNazer" value="1" <?php if ($Permissionvals['permitErrNazer']==1) echo "checked"; ?>>
                   </td>
				     <td></td><td></td><td></td><td></td>
                
                  </tr> 
             
                  
                   
				 <tr > <td colspan="7"> &nbsp </td></tr >  
				 <tr >       
				    <td colspan="3" align="center" > <b>آزادسازی ظرفیت</b></td>
					  <td></td><td></td><td></td><td></td>
                </tr >
        		  
                    
			     <tr><td>حداکثر خطادر عدم ثبت وضعیت طرحها (0-5)</td>
                 <td>
                  <input value='<?php echo $Permissionvals['permitErrTiminig']; ?>'
                       name='permitErrTiminig' type='text'  id='permitErrTiminig'    size='2' maxlength='5' /></td>
                  <td></td>  <td></td><td></td><td></td><td></td>
                  </tr>

		
   			     <tr><td>حداقل ردیفهای تکمیل شده جدول زمانبندی (1-14)</td>
                 <td>
                  <input value='<?php echo $Permissionvals['permitTiminig']; ?>'
                       name='permitTiminig' type='text'  id='permitTiminig'    size='2' maxlength='5' /></td>
                  <td></td>  <td></td><td></td><td></td><td></td>
                  </tr>

  			     <tr><td>حداقل امتیاز ارزشیابی (1-100)</td>
                 <td>
                  <input value='<?php echo $Permissionvals['permitEmtiaz']; ?>'
                       name='permitEmtiaz' type='text'  id='permitEmtiaz'    size='2' maxlength='5' /></td>
                  <td> </td>  <td></td><td></td><td></td><td></td>
                  </tr>

            	 <tr><td>مهلت  انجام کار</td>
                <td>ارجاع پیشنهاد</td>
                 <td>انتخاب مجری</td>
                 <td>ثبت اولیه</td>
                 <td>تایید پیش فاکتور</td>
                 <td>تحویل موقت</td>
				<td>تحویل دائم</td>
                  </tr>
        
	    <tr>
    <td></td>
    <td><input value='<?php echo $Permissionvals['deadlineerj']; ?>' name='deadlineerj' type='text'  id='deadlineerj'    size='2' maxlength='5' /></td>
    <td><input value='<?php echo $Permissionvals['deadlineselectop']; ?>' name='deadlineselectop' type='text'  id='deadlineselectop'    size='2' maxlength='5' /></td>
    <td><input value='<?php echo $Permissionvals['deadlinefirstsave']; ?>' name='deadlinefirstsave' type='text'  id='deadlinefirstsave'    size='2' maxlength='5' /></td>
    <td><input value='<?php echo $Permissionvals['deadlineapprove']; ?>' name='deadlineapprove' type='text'  id='deadlineapprove'    size='2' maxlength='5' /></td>
    <td><input value='<?php echo $Permissionvals['deadlinetempdel']; ?>' name='deadlinetempdel' type='text'  id='deadlinetempdel'    size='2' maxlength='5' /></td>
	<td><input value='<?php echo $Permissionvals['deadlinepermanentdel']; ?>' name='deadlinepermanentdel' type='text'  id='deadlinepermanentdel'    size='2' maxlength='5' /></td>
    </tr>
	
		 	     <tr><td>امکان ارجاعات خارج از ظرفیت</td>
                <td>
                    <input type="radio" name="sentoanjomanws" value="1" <?php if ($Permissionvals['sentoanjomanws']==1) echo "checked"; ?>>
                  </td>
                  <td>
                   <input type="radio" name="sentoanjomanws" value="0" <?php if ($Permissionvals['sentoanjomanws']==0) echo "checked"; ?>>
                   </td>
				     <td></td><td></td><td></td><td></td>
                
                  </tr>  
              

                  
				<?php  if ($ostanID==19) { ?>
				
				 <tr > <td colspan="7"> &nbsp </td></tr >  
				 <tr >       
				    <td colspan="3" align="center" > <b> پیشنهاد قیمت  لوله ها</b></td>
					  <td></td><td></td><td></td><td></td>
                </tr >
                
				<tr><td>رتبه</td><td>A </td><td>A </td><td>B </td><td>B </td><td>C </td><td></td></tr>
				
				
		 <tr><td>امتیاز</td>
                 <td>94-100</td>
                 <td>86-93</td>
                 <td>78-85</td>
                 <td>71-78</td>
                 <td>64-70</td>
				<td></td>
			</tr>

			
		   <tr><td>ضمانت نامه (میلیون تومان)</td>
                 <td>
                  <input value='<?php echo $Permissionvals['p1Zemanat']; ?>'
                       name='p1Zemanat' type='text'  id='p1Zemanat'    size='2' maxlength='5' /></td>
                 <td>
                  <input value='<?php echo $Permissionvals['p2Zemanat']; ?>'
                       name='p2Zemanat' type='text'  id='p2Zemanat'    size='2' maxlength='5' /></td>
                 <td>
                  <input value='<?php echo $Permissionvals['p3Zemanat']; ?>'
                       name='p3Zemanat' type='text'  id='p3Zemanat'    size='2' maxlength='5' /></td>
                 <td>
                  <input value='<?php echo $Permissionvals['p4Zemanat']; ?>'
                       name='p4Zemanat' type='text'  id='p4Zemanat'    size='2' maxlength='5' /></td>
                 <td>
                  <input value='<?php echo $Permissionvals['p5Zemanat']; ?>'
                       name='p5Zemanat' type='text'  id='p5Zemanat'    size='2' maxlength='5' /></td>
				<td></td>
				</tr>
                
			
		   <tr><td>تعداد پیش فاکتور همزمان</td>
                 <td>
                  <input value='<?php echo $Permissionvals['p1Zpishhamzaman']; ?>'
                       name='p1Zpishhamzaman' type='text'  id='p1Zpishhamzaman'    size='2' maxlength='5' /></td>
                 <td>
                  <input value='<?php echo $Permissionvals['p2Zpishhamzaman']; ?>'
                       name='p2Zpishhamzaman' type='text'  id='p2Zpishhamzaman'    size='2' maxlength='5' /></td>
                 <td>
                  <input value='<?php echo $Permissionvals['p3Zpishhamzaman']; ?>'
                       name='p3Zpishhamzaman' type='text'  id='p3Zpishhamzaman'    size='2' maxlength='5' /></td>
                 <td>
                  <input value='<?php echo $Permissionvals['p4Zpishhamzaman']; ?>'
                       name='p4Zpishhamzaman' type='text'  id='p4Zpishhamzaman'    size='2' maxlength='5' /></td>
                 <td>
                  <input value='<?php echo $Permissionvals['p5Zpishhamzaman']; ?>'
                       name='p5Zpishhamzaman' type='text'  id='p5Zpishhamzaman'    size='2' maxlength='5' /></td>
				<td></td>
 				</tr>
 
	
		   <tr><td>حجم تناژ  (تن)</td>
                 <td>
                  <input value='<?php echo $Permissionvals['p1Zpishhamzamanvol']; ?>'
                       name='p1Zpishhamzamanvol' type='text'  id='p1Zpishhamzamanvol'    size='2' maxlength='5' /></td>
                 <td>
                  <input value='<?php echo $Permissionvals['p2Zpishhamzamanvol']; ?>'
                       name='p2Zpishhamzamanvol' type='text'  id='p2Zpishhamzamanvol'    size='2' maxlength='5' /></td>
                 <td>
                  <input value='<?php echo $Permissionvals['p3Zpishhamzamanvol']; ?>'
                       name='p3Zpishhamzamanvol' type='text'  id='p3Zpishhamzamanvol'    size='2' maxlength='5' /></td>
                 <td>
                  <input value='<?php echo $Permissionvals['p4Zpishhamzamanvol']; ?>'
                       name='p4Zpishhamzamanvol' type='text'  id='p4Zpishhamzamanvol'    size='2' maxlength='5' /></td>
                 <td>
                  <input value='<?php echo $Permissionvals['p5Zpishhamzamanvol']; ?>'
                       name='p5Zpishhamzamanvol' type='text'  id='p5Zpishhamzamanvol'    size='2' maxlength='5' /></td>
				<td></td>
 				</tr>
 
	              <tr><td>تعداد روز اعتبار پیش فاکتورها(روز)</td>
                 <td>
                  <input value='<?php echo $Permissionvals['validday']; ?>'
                       name='validday' type='text'  id='validday'    size='2' maxlength='5' /></td>
                  <td></td><td></td><td></td><td></td><td></td>
                  </tr>
    
	
				 <tr><td>اخطار اعتبار پیش فاکتور، ضمانتنامه و...</td>
                 <td>
                    <input type="radio" name="pipeproposerror" value="1" <?php if ($Permissionvals['pipeproposerror']==1) echo "checked"; ?>>
                  </td>
                  <td>
                   <input type="radio" name="pipeproposerror" value="0" <?php if ($Permissionvals['pipeproposerror']==0) echo "checked"; ?>>
                   </td>
				     <td></td><td></td><td></td><td></td>
                
                  </tr>  
                    
	           <tr><td>امکان پیشنهاد قیمت با لاتر از سقف تناژ</td>
                 <td>
                    <input type="radio" name="pipeproposetonaj" value="1" <?php if ($Permissionvals['pipeproposetonaj']==1) echo "checked"; ?>>
                  </td>
                  <td>
                   <input type="radio" name="pipeproposetonaj" value="0" <?php if ($Permissionvals['pipeproposetonaj']==0) echo "checked"; ?>>
                   </td>
				     <td></td><td></td><td></td><td></td>
                
                  </tr> 
                  
                  
                 <tr><td>مهلت انتخاب پیشنهاد قیمت لوله برای ناظر عالی (ساعت)</td>
                 <td>
                  <input value='<?php echo $Permissionvals['hourcntforproposepselection']; ?>'
                       name='hourcntforproposepselection' type='text'  id='hourcntforproposepselection'    size='2' maxlength='5' /></td>
                  <td></td><td></td><td></td><td></td><td></td>
                  </tr>
				
		  <?php } ?>
                
				<?php  if ($login_RolesID==1) { ?>
                 <tr><td>حداکثر تراکنش مصرفی مهمان</td>
                 <td>
                  <input value='<?php echo $Permissionvals['startpay']; ?>'
                       name='startpay' type='text' class='textbox' id='startpay'    size='2' maxlength='5' /></td>
                  <td>
				     <td></td><td></td><td></td><td></td>
                  </tr>
               <tr><td>وب سرویس</td>
                 <td>
                  <input value='<?php echo $Permissionvals['AdminRolesID']; ?>'
                       name='AdminRolesID' type='text' class='textbox' id='AdminRolesID'    size='2' maxlength='5' /></td>
                  <td>
				     <td></td><td></td><td></td><td></td>
                  </tr>
                
			  			  
                 <?php } ?>
                   
                   
                  <tr>
                    <td style="text-align: left;" colspan="6"><input   name="save" type="submit" class="button" id="save" size="16" value="ثبت" />&nbsp;</td>
					<td></td>
		        			
                   </tr>   
                               
            </tbody>
            </table>
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

<?php 
/*

//appinvestigation/appuploads.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/allapplicantstates.php
/appinvestigation/allapplicantstatesop.php
 
 
-
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
$linearray = explode('_',$ids);
$ID=$linearray[0];
/*
applicantmasterdetail جدول ارتباطی طرح ها
    ApplicantMasterID شناسه طرح
    ApplicantMasterIDmaster شناسه طرح اجرایی
    ApplicantMasterIDsurat شناسه صورت وضعیت
*/
$sql="select * from applicantmasterdetail where ApplicantMasterID='$ID' or ApplicantMasterIDmaster='$ID' or ApplicantMasterIDsurat='$ID'";
$result = mysql_query($sql);
							try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

$row = mysql_fetch_assoc($result);
$ApplicantMasterIDd=$row['ApplicantMasterID'];
$ApplicantMasterIDop=$row['ApplicantMasterIDmaster'];
$ApplicantMasterIDoplist=$row['ApplicantMasterIDsurat'];
/*
applicantmaster جدول مشخصات طرح
ApplicantMasterID شناسه طرح
*/
$sql="select * from applicantmaster where ApplicantMasterID='$ApplicantMasterIDd'";
//print $sql;
$result = mysql_query($sql);
							try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

$row = mysql_fetch_assoc($result);
$AppName=$row['ApplicantFName']." ".$row['ApplicantName'];//عنوان پروژه

$applicantreportsidd="";
//applicantreports جدول گزارشات پروژه
$sql="select * from applicantreports where ApplicantMasterID='$ApplicantMasterIDd'";
$result = mysql_query($sql);
							try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

while ($row = mysql_fetch_assoc($result))
    $applicantreportsidd.=$row['applicantreportsID']."_";
$applicantreportsidd=substr($applicantreportsidd,0,strlen($applicantreportsidd)-1);

$applicantreportsidop="";
//applicantreports جدول گزارشات پروژه اجرایی
$sql="select * from applicantreports where ApplicantMasterID='$ApplicantMasterIDop'";
$result = mysql_query($sql);
							try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

while ($row = mysql_fetch_assoc($result))
    $applicantreportsidop.=$row['applicantreportsID']."_";
$applicantreportsidop=substr($applicantreportsidop,0,strlen($applicantreportsidop)-1);

$applicantreportsidoplist="";
//applicantreports جدول گزارشات پروژه صورت وضعیت
$sql="select * from applicantreports where ApplicantMasterID='$ApplicantMasterIDoplist'";
$result = mysql_query($sql);
							try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

while ($row = mysql_fetch_assoc($result))
    $applicantreportsidoplist.=$row['applicantreportsID']."_";
$applicantreportsidoplist=substr($applicantreportsidoplist,0,strlen($applicantreportsidoplist)-1);


$applicantfreedetailop="";
//applicantfreedetail جدول آزادسازی ها
$sql="select * from applicantfreedetail where ApplicantMasterID='$ApplicantMasterIDop'";
$result = mysql_query($sql);
							try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

while ($row = mysql_fetch_assoc($result))
    $applicantfreedetailop.=$row['applicantfreedetailID']."_";
$applicantfreedetailop=substr($applicantfreedetailop,0,strlen($applicantfreedetailop)-1);


$invoiced="";
//invoicemaster جدول عناوین پیش فاکتور های پروژه
$sql="select * from invoicemaster where ApplicantMasterID='$ApplicantMasterIDd'";
$result = mysql_query($sql);
					try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

while ($row = mysql_fetch_assoc($result))
    $invoiced.=$row['InvoiceMasterID']."_".$row['Title']."_";
$invoiced=substr($invoiced,0,strlen($invoiced)-1);

$invoiceop="";
//invoicemaster جدول عناوین پیش فاکتور های پروژه اجرایی
$sql="select * from invoicemaster where ApplicantMasterID='$ApplicantMasterIDop' and ifnull(proposable,0)=0";
$result = mysql_query($sql);
					try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

while ($row = mysql_fetch_assoc($result))
    $invoiceop.=$row['InvoiceMasterID']."_".$row['Title']."_";
$invoiceop=substr($invoiceop,0,strlen($invoiceop)-1);

$invoiceoplist="";
//invoicemaster جدول عناوین پیش فاکتور های پروژه صورت وضعیت
$sql="select * from invoicemaster where ApplicantMasterID='$ApplicantMasterIDoplist'  and ifnull(proposable,0)=0";
$result = mysql_query($sql);
					try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

while ($row = mysql_fetch_assoc($result))
    $invoiceoplist.=$row['InvoiceMasterID']."_".$row['Title']."_";
$invoiceoplist=substr($invoiceoplist,0,strlen($invoiceoplist)-1);

$applicantwsourced="";
//applicantwsource منابع آبی پروژه
$sql="select * from applicantwsource where ApplicantMasterID='$ApplicantMasterIDd'";
$result = mysql_query($sql);
					try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

while ($row = mysql_fetch_assoc($result))
    $applicantwsourced.=$row['ApplicantWSourceID']."_".$row['Title']."_";
$applicantwsourced=substr($applicantwsourced,0,strlen($applicantwsourced)-1);

$operatorapprequest="";
/*
operatorco جدول پیمانکاران
ApplicantMasterID شناسه طرح
operatorapprequest پیشنهادات طرح
state وضعیت
*/
$sql="select operatorapprequestID,ApplicantMasterID,case state when 1 then concat(operatorco.title,' (منتخب)') else operatorco.title end Title from operatorapprequest 
inner join operatorco on operatorco.operatorcoID=operatorapprequest.operatorcoID
where ApplicantMasterID='$ApplicantMasterIDd' and ApplicantMasterID>0 and state=1
order by price";
$result = mysql_query($sql);
					try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

while ($row = mysql_fetch_assoc($result))
    $operatorapprequest.=$row['operatorapprequestID']."_".$row['Title']."_";
$operatorapprequest=substr($operatorapprequest,0,strlen($operatorapprequest)-1);    
            
$producerapprequest="";
/*
operatorco جدول پیمانکاران
ApplicantMasterID شناسه طرح
operatorapprequest پیشنهادات طرح
state وضعیت
*/
$sql="select producerapprequestID,ApplicantMasterID,case state when 1 then concat(producers.title,' (منتخب)') else producers.title end Title from producerapprequest 
inner join producers on producers.ProducersID=producerapprequest.ProducersID
where ApplicantMasterID='$ApplicantMasterIDop' and ApplicantMasterID>0 and state=1
order by price";
$result = mysql_query($sql);
					try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

while ($row = mysql_fetch_assoc($result))
    $producerapprequest.=$row['producerapprequestID']."_".$row['Title']."_";
$producerapprequest=substr($producerapprequest,0,strlen($producerapprequest)-1);    
    
    
    
    




?>
<!DOCTYPE html>
<html>
<head>
  	<title>   فایل ها</title>

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





    <script>





    function fillform(Url)
    {     
     
        //alert(1);
        ///$("#loading-div-background").show();
        var ApplicantMasterIDd='<?php print $ApplicantMasterIDd ?>';
        var ApplicantMasterIDop='<?php print $ApplicantMasterIDop ?>';
        var ApplicantMasterIDoplist='<?php print $ApplicantMasterIDoplist ?>';
        var applicantreportsidd='<?php print $applicantreportsidd ?>';
        var applicantreportsidop='<?php print $applicantreportsidop ?>';
        var applicantreportsidoplist='<?php print $applicantreportsidoplist ?>';
        var applicantfreedetailop='<?php print $applicantfreedetailop ?>';
        var invoiced='<?php print $invoiced ?>';
        var invoiceop='<?php print $invoiceop ?>';
        var invoiceoplist='<?php print $invoiceoplist ?>';
        var applicantwsourced='<?php print $applicantwsourced ?>';
        var operatorapprequest='<?php print $operatorapprequest ?>';
        var producerapprequest='<?php print $producerapprequest ?>';
        
        
        //alert(Url);
    
         $.post(Url, {ApplicantMasterIDd:ApplicantMasterIDd,ApplicantMasterIDop:ApplicantMasterIDop,ApplicantMasterIDoplist:ApplicantMasterIDoplist
         ,applicantreportsidd:applicantreportsidd,applicantreportsidop:applicantreportsidop,applicantreportsidoplist:applicantreportsidoplist
         ,applicantfreedetailop:applicantfreedetailop,invoiced:invoiced,invoiceop:invoiceop,invoiceoplist:invoiceoplist
         ,applicantwsourced:applicantwsourced,operatorapprequest:operatorapprequest,producerapprequest:producerapprequest
         
         }, function(data){
            var outstr="<tr><td style='text-align: center;width: 1500px;' ><span  class='f14_fontcb' >فایل های بارگزاری شده طرح <?php print $AppName; ?></span></td></tr>";
          //alert(1);
           
        if (data.appfilemapd!='')  
           outstr+="<tr><td ><span class='f14_fontcb' >اسناد طرح </td></tr><tr><td ><span class='f14_fontcb' >"+data.appfilemapd+"</td></tr>";
        
        if (data.strapplicantreportsidd!='')  
           outstr+="<tr><td ><span class='f14_fontcb' >گزارشات مطالعات </td></tr><tr><td ><span class='f14_fontcb' >"+data.strapplicantreportsidd+"</td></tr>";
        if (data.contractd!='')  
             outstr+="<tr><td ><span class='f14_fontcb' >قرارداد </td></tr><tr><td ><span class='f14_fontcb' >"+data.contractd+"</td></tr>";
        if (data.strinvoiced!='')  
           outstr+="<tr><td ><span class='f14_fontcb' >لیست لوازم </td></tr><tr><td ><span class='f14_fontcb' >"+data.strinvoiced+"</td></tr>";
        
        if (data.strapplicantwsourced!='')  
           outstr+="<tr><td ><span class='f14_fontcb' >پروانه منبع آبی </td></tr><tr><td ><span class='f14_fontcb' >"+data.strapplicantwsourced+"</td></tr>";
        if (data.stroperatorapprequest!='')  
           outstr+="<tr><td ><span class='f14_fontcb' >پیشنهاد قیمت اجرا </td></tr><tr><td ><span class='f14_fontcb' >"+data.stroperatorapprequest+"</td></tr>";
        if (data.sandughd!='')  
           outstr+="<tr><td ><span class='f14_fontcb' >نامه ارسال به صندوق جهت تامین اعتبار </td></tr><tr><td ><span class='f14_fontcb' >"+data.sandughd+"</td></tr>";
           
           
        
        if (data.appfilemapop!='')  
           outstr+="<tr><td ><span class='f14_fontcb' >اسناد طرح </td></tr><tr><td ><span class='f14_fontcb' >"+data.appfilemapop+"</td></tr>";   
        if (data.strapplicantreportsidop!='')  
            outstr+="<tr><td ><span class='f14_fontcb' >گزارشات پیش فاکتور </td></tr><tr><td ><span class='f14_fontcb' >"+data.strapplicantreportsidop+"</td></tr>";
        if (data.contractop!='')  
             outstr+="<tr><td ><span class='f14_fontcb' >تحویل موقت </td></tr><tr><td ><span class='f14_fontcb' >"+data.contractop+"</td></tr>";
        if (data.strapplicantfreedetailop!='')  
           outstr+="<tr><td ><span class='f14_fontcb' >آزادسازی </td></tr><tr><td ><span class='f14_fontcb' >"+data.strapplicantfreedetailop+"</td></tr>";
        if (data.strinvoiceop!='')  
           outstr+="<tr><td ><span class='f14_fontcb' >پیشفاکتورها </td></tr><tr><td ><span class='f14_fontcb' >"+data.strinvoiceop+"</td></tr>";
        if (data.strproducerapprequest!='')  
           outstr+="<tr><td ><span class='f14_fontcb' >پیشنهاد قیمت لوله </td></tr><tr><td ><span class='f14_fontcb' >"+data.strproducerapprequest+"</td></tr>";
           
           
           
        if (data.strapplicantreportsidoplist!='')  
             outstr+="<tr><td ><span class='f14_fontcb' >گزارشات صورت وضعیت </td></tr><tr><td ><span class='f14_fontcb' >"+data.strapplicantreportsidoplist+"</td></tr>";
        if (data.contractoplist!='')  
             outstr+="<tr><td ><span class='f14_fontcb' >تحویل دائم </td></tr><tr><td ><span class='f14_fontcb' >"+data.contractoplist+"</td></tr>";
        if (data.strinvoiceoplist!='')  
           outstr+="<tr><td ><span class='f14_fontcb' >پیشفاکتورها </td></tr><tr><td ><span class='f14_fontcb' >"+data.strinvoiceoplist+"</td></tr>";
             
             outstr+="</span></td></tr>";
           $('#maindiv').html(outstr);
           
           
       }, 'json'); 
    
    
    
    
    
       
                       
                    
    }
                
    </script>
    <!-- /scripts -->
</head>
<body <?php if ($done!=1) echo "onload= \"document.getElementById('submit').click();\""; ?>>

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
            
                        
            <form id="theForm" name="theForm" action="appuploads.php" method="post"  enctype="multipart/form-data">
             
                
                <table id="records" width="95%" align="center">
                       
                   <tbody >
                  
    
 <script type='text/javascript'>
         
         /*
         $(document).ready(function () {
            $("#loading-div-background").css({ opacity: 0.8 });
           
        });
*/

</script>                  
                   
                    <?php
                    print "
                    <td colspan='2' style='visibility: hidden;'><input name='submit'  class='button' id='submit'
                    onclick = \"fillform('$_server_fileserver/invoiceirrigating/appinvestigation/appuploads_jr.php')\"
                     value='دریافت اطلاعات' /></td>";

                    /*
                    print "
                    <td colspan='2'><input name='submit2'  class='button' id='submit2'
                     value='بارگذاری اطلاعات' disabled='true' type='submit'/></td>";
                     */

                    ?>
                    <div id="maindiv"><label>لیست فایل ها</label></div>
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

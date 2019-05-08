<?php 

/*

//appinvestigation/invoicemasterfree_list.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/aaapplicantfree.php
 -
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");
/*
نقش های مجاز به ثبت
16 صندوق
19 مدیریت پرونده ها
7 بانک
13 مدیر آبیاری
14 ناظر عالی
1 مدیر پیگیری
31 مدیر آبرسانی
*/
$permitrolsid = array("16", "19","7","13","14","1","31");
if ($_POST && (in_array($login_RolesID, $permitrolsid))&& $_POST['ApplicantMasterID']>0)
{
    
	
    
    

    
    
    $ApplicantMasterID=$_POST['ApplicantMasterID'];//شناسه طرح   
    $freestateID=$_POST['freestateID'];//شناسه شماره قسط
    $ProducersID=$_POST['ProducersID'];//شناسه تولید کننده
    $paytype=$_POST['paytype'];//paytype درصورتی که صفر باشد واریز و در صورتی که یک باشد دریافت می باشد
    $OperatorCoID=$_POST['OperatorCoID'];//شناسه پیمانکار
    $Price = str_replace(',', '', $_POST['Price']);//مبلغ
    $CheckNo=$_POST['CheckNo'];//شماره چک
    $CheckDate=$_POST['CheckDate'];//تاریخ چک
    $CheckBank=$_POST['CheckBank'];//بانک
    $letterdate=$_POST['letterdate'];//تاریخ نامه آزادسازی
    $letterno=$_POST['letterno'];//شماره نامه آزادسازی
    $Description=$_POST['Description'];//توضیحات
    $AccountBank=$_POST['AccountBank'];//بانک حساب
    $AccountNo=$_POST['AccountNo'];//شماره حساب
            
        $SaveTime=date('Y-m-d H:i:s');
        $SaveDate=date('Y-m-d');
        $ClerkID=$login_userid;
		    /*
    applicantfreedetail جدول ریز آزادسازی
    applicantmaster جدول مشخصات طرح
    applicantmasterid شناسه طرح
    applicantfreedetailID شناسه ریز قسط آزادسازی
    */		
        $query1 = "SELECT applicantfreedetailID FROM applicantfreedetail 
        where freestateID='$freestateID' and ProducersID='$ProducersID' and Price='$Price' and paytype='$paytype' and ApplicantMasterID='$ApplicantMasterID'";
       
							try 
							  {		
								 $result1 = mysql_query($query1);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

  		$row1 = mysql_fetch_assoc($result1);
        if ($row1['applicantfreedetailID']>0)
            $isdup =1;
        else $isdup=0;
                        
                
                
                
    
        if ($isdup==0)      
        if ($_POST['true']>0)    
        if ($ApplicantMasterID>0)
        {
            /*
            applicantfreedetail جدول ریز آزادسازی
            applicantmaster جدول مشخصات طرح
            applicantmasterid شناسه طرح
            applicantfreedetailID شناسه ریز قسط آزادسازی
            $ApplicantMasterID=$_POST['ApplicantMasterID'];//شناسه طرح   
            $freestateID=$_POST['freestateID'];//شناسه شماره قسط
            $ProducersID=$_POST['ProducersID'];//شناسه تولید کننده
            $paytype=$_POST['paytype'];//paytype درصورتی که صفر باشد واریز و در صورتی که یک باشد دریافت می باشد
            $OperatorCoID=$_POST['OperatorCoID'];//شناسه پیمانکار
            $Price = str_replace(',', '', $_POST['Price']);//مبلغ
            $CheckNo=$_POST['CheckNo'];//شماره چک
            $CheckDate=$_POST['CheckDate'];//تاریخ چک
            $CheckBank=$_POST['CheckBank'];//بانک
            $letterdate=$_POST['letterdate'];//تاریخ نامه آزادسازی
            $letterno=$_POST['letterno'];//شماره نامه آزادسازی
            $Description=$_POST['Description'];//توضیحات
            $AccountBank=$_POST['AccountBank'];//بانک حساب
            $AccountNo=$_POST['AccountNo'];//شماره حساب
            */
            $query ="INSERT INTO applicantfreedetail
            (AccountBank,AccountNo,ApplicantMasterID,freestateID,ProducersID,Price,CheckNo,paytype,letterdate,letterno,CheckDate,CheckBank,Description,SaveTime,SaveDate,ClerkID)
             values('$AccountBank','$AccountNo','$ApplicantMasterID','$freestateID','$ProducersID','$Price','$CheckNo','$paytype','$letterdate','$letterno','$CheckDate'
             ,'$CheckBank','$Description','$SaveTime','$SaveDate','$ClerkID')";
                
          
							try 
							  {		
								  $result = mysql_query($query);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

            print "<p class='note'>ثبت با موفقیت انجام شد<p>";
            //exit;
			   
				
				  if ($_FILES["filep"]["error"] > 0) 
				{
					//echo "Error: " . $_FILES["file2"]["error"] . "<br>";
				} 
				else 
				{
				     $query = "SELECT applicantfreedetailID FROM applicantfreedetail where applicantfreedetailID = last_insert_id() and SaveTime='$SaveTime' 
                            and ClerkID='$ClerkID'";
                        
                        
							try 
							  {		
								$result = mysql_query($query);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

                  		$row = mysql_fetch_assoc($result);
                        
						$IDUser =$row['applicantfreedetailID'];
						$path = "../../upfolder/free/";
			
					 if (($_FILES["filep"]["size"] / 1024)>100)
					{
						print "حداکثر اندازه مجاز فایل اسکن 100 کیلوبایت می باشد. لطفا اندازه اسکن فایل را کاهش دهید";
					}
						$ext = end((explode(".", $_FILES["filep"]["name"])));
						$attachedfile=$IDUser.'_'.rand(100000000,999999999).rand(100000000,999999999).'.'.$ext;
						//print $path.$attachedfile;
						foreach (glob($path. $IDUser.'_1*') as $filename) 
						{
							unlink($filename);
						}move_uploaded_file($_FILES["filep"]["tmp_name"],$path.$attachedfile);
					
				}
		$_POST['true']=0; 
		}
		
		
		
}
else 
{
    $uid=$_GET["uid"];

$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
$linearray = explode('_',$ids);


$ApplicantMasterID=$linearray[0];
$type=$linearray[1];
$DesignerCoID=$linearray[2];
$OperatorCoID=$linearray[3];
$ProducersID=$linearray[4];
    //if (!($OperatorCoID>0)) header("Location: ../login.php");   
    $g2id=is_numeric($_GET["g2id"]) ? intval($_GET["g2id"]) : 0;
}

/*

    ApplicantName عنوان طرح
    ApplicantFName نام متقاضی
    CPI نام کاربر
    DVFS نام خانوادگی کاربر
    ClerkID شناسه کاربر
    clerk جدول کاربران
    tax_tbcity7digit شهرها
    id شناسه شهر
    CountyName روستای طرح
    applicantmaster جدول مشخصات طرح
    ApplicantMasterID شناسه طرح
    operatorcoid شناسه پیمانکار
    DesignArea مساحت طرح
    Code سریال طرح

    */
$sql = "SELECT applicantmaster.ApplicantName,applicantmaster.DesignArea,applicantmaster.ApplicantFName,operatorco.Title operatorcoTitle,
applicantmasterd.applicantmasterid,shahr.cityname shahrcityname  
FROM applicantmaster
left outer join operatorco on operatorco.operatorcoid=applicantmaster.operatorcoid
inner join applicantmaster applicantmasterd on applicantmasterd.BankCode=applicantmaster.BankCode
left outer join tax_tbcity7digit shahr on substring(shahr.id,1,4)=substring(applicantmaster.cityid,1,4) 
and substring(shahr.id,5,3)='000' and substring(shahr.id,3,5)<>'00000'

where applicantmaster.ApplicantMasterID='$ApplicantMasterID'";

//print $sql;


							try 
							  {		
								$result = mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

$row = mysql_fetch_assoc($result);
if ($type==5) $tit='/متر';
$ApplicantName =$row['ApplicantFName']." ".$row['ApplicantName']." ".$row['DesignArea']." هکتار ".$tit." شهرستان ".$row['shahrcityname'];
$ApplicantFullName=$row['ApplicantName']." ".$row['ApplicantFName'];
$operatorcoTitle=$row['operatorcoTitle'];
$applicantmasteridd=$row['applicantmasterid'];

$cond="";
if ($g2id>0) $cond.=" and freestateID='$g2id' ";
              /*
            applicantfreedetail جدول ریز آزادسازی
            freestate وضعی آزادسازی
            applicantmasterid شناسه طرح
            applicantfreedetailID شناسه ریز قسط آزادسازی 
            freestateID شناسه شماره قسط
            $ProducersID شناسه تولید کننده
            paytype درصورتی که صفر باشد واریز و در صورتی که یک باشد دریافت می باشد
            OperatorCoID شناسه پیمانکار
            Price  مبلغ
            CheckNo شماره چک
            CheckDate تاریخ چک
            CheckBank بانک
            letterdate تاریخ نامه آزادسازی
            letterno شماره نامه آزادسازی
            Description توضیحات
            AccountBank بانک حساب
            AccountNo شماره حساب
            
            */
$sql="SELECT freestate.Title freestateTitle,freestate.Code freestatecode,
case applicantfreedetail.producersID when -1 then 'مجری:<br>$operatorcoTitle'  when -2 then 'کشاورز (عودت خودیاری): <br> $ApplicantFullName'
  when -3 then 'کشاورز (انجام عملیات):<br> $ApplicantFullName' else concat('فروشنده:<br>',producers.Title) end producersTitle

,Price,CheckNo,paytype,letterdate,letterno,CheckDate,CheckBank,Description
,applicantfreedetail.freestateID,applicantfreedetail.AccountNo,applicantfreedetail.AccountBank,applicantfreedetail.applicantfreedetailID 
            FROM applicantfreedetail
            left outer join freestate on freestate.freestateID=applicantfreedetail.freestateID
            left outer join producers on producers.producersID=applicantfreedetail.producersID
            where applicantfreedetail.ApplicantMasterID='$ApplicantMasterID'
            order by freestate.Code,producersTitle

        "; 
 //print $sql;

						try 
							  {		
								$result = mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }


$sqlselect="select distinct freestateTitle _key,freestateID _value from ($sql)as view1 order by _key  COLLATE utf8_persian_ci";
$allg2id = get_key_value_from_query_into_array($sqlselect);

?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست پرداختی ها</title>

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
                $("#CheckDate, #simpleLabel").persiandatepicker();  
                $("#letterdate, #simpleLabel").persiandatepicker();   
				
            });
        
        
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

    function convert(aa) {
        //alert(1);
        var number = document.getElementById(aa).value.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        
        number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        //alert(numberWithCommas(number));
        document.getElementById(aa).value=numberWithCommas(number);
        
    }
    function selectpage(){
       
        window.location.href ='?uid=' +document.getElementById('uid').value
        + '&g2id=' + document.getElementById('g2id').value;
        
	}
    
 function fillform(Url)
    {      
        var type=0,ID=0,Price;
        if (document.getElementById('ProducersID').value==-3)
        {
            type=3;//کشاورز
            ID=document.getElementById('ApplicantMasterIDd').value;
        }
        if (document.getElementById('ProducersID').value==-2)
        {
            type=3;//کشاورز
            ID=document.getElementById('ApplicantMasterIDd').value;
        }
        if (document.getElementById('ProducersID').value==-1)
        {
            type=2;//مجری
            ID=document.getElementById('OperatorCoID').value;
        }
        if (document.getElementById('ProducersID').value>0)
        {
            type=1;//فروشنده
            ID=document.getElementById('ProducersID').value;
        }
        Price=document.getElementById('Price').value;
            //alert(type);
            //alert(ID);
        if (ID>0)
        {
            
            $("#loading-div-background").show();
            $.post(Url, {type:type,ID:ID,Price:Price}, function(data){
            $("#loading-div-background").hide();  
            if (data.errors==1) alert("تاریخ انقضاء ضمانت ثبت نشده است");   
            if (data.errors==2) alert("ضمانت به انقضا رسیده است");  
            if (data.errors==3) alert("مبلغ وارد شده بیشتر از مبلغ  ضمانت پرداختی "+data.guaranteepayval+" می باشد");  
            if (data.errors==4) alert("کمتر از ده روز به انقضاء ضمانت مانده است");
                       
            $('#AccountNo').val(data.AccountNo);
            $('#AccountBank').val(data.AccountBank);
            }, 'json');                           
        }
    }
                
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
            <?php //include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
            
            <form action="invoicemasterfree_list.php" method="post" enctype="multipart/form-data">
             <div id="loading-div-background">
                <div id="loading-div" class="ui-corner-all" >
                  <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
                  <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
                 </div>
                </div>
                
                <table width="95%" align="center">
                    <tbody>
                    <h1 align="center">  لیست آزادسازی های انجام شده  طرح <?php print $ApplicantName; ?> </h1>
                        
                        
                        <tr>
                        
                        <div style = "text-align:left;">

				       <?php 
                //    $permitrolsid = array("1","5","19","13","14");
                  //  if (in_array($login_RolesID, $permitrolsid))
                  //  {
                        
                        echo "<a  target='_blank' href='applicant_one.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$ApplicantMasterID.rand(10000,99999).'1'.
                                    "'><img style = 'width: 3%;' 
                                    src='../img/mail_send1.png' title=' نامه آزادسازی قسط اول '></a>";
                        
						echo "<a  target='_blank' href='applicant_one.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$ApplicantMasterID.rand(10000,99999).'2'.
                                    "'><img style = 'width: 3%;' 
                                    src='../img/mail_send2.png' title=' نامه آزادسازی قسط دوم '></a>";
                                    
						echo "<a  target='_blank' href='applicant_one.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$ApplicantMasterID.rand(10000,99999).'3'.
                                    "'><img style = 'width: 3%;' 
                                    src='../img/mail_send3.png' title=' نامه آزادسازی قسط سوم '></a>";
                                    
						echo "<a  target='_blank' href='applicant_one.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$ApplicantMasterID.rand(10000,99999).'4'.
                                    "'><img style = 'width: 3%;' 
                                    src='../img/mail_send4.png' title=' نامه آزادسازی قسط چهارم '></a>";
                                    
                   // }         
                   
				   $href=$_SERVER['HTTP_REFERER'];
				   if ($type==1) $href='../appinvestigation/allapplicantstatesop.php';
				   if ($type==5) $href='../appinvestigation/allapplicantrequestws.php';
				   
                   print "<a  href='$href'><img style = \"width: 3%;\" src=\"../img/Return.png\" title='بازگشت'></a>";
                    
                     ?>
         
						
               
               </div>
               
                          <INPUT type="hidden" id="OperatorCoID" name="OperatorCoID" value="<?php print $OperatorCoID; ?>"/>
                          <INPUT type="hidden" id="ApplicantMasterIDd" name="ApplicantMasterIDd" value="<?php print $applicantmasteridd; ?>"/>
                          <INPUT type="hidden" id="type" name="type" value="<?php print $type; ?>"/>
                          <INPUT type="hidden" id="type" name="type" value="<?php print $type; ?>"/>
                          <INPUT type="hidden" id="true" name="true" value="1"/>
                          
                          
                          <td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
                          <INPUT type="hidden" id="txturl" value="<?php print "$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php"; ?>"/>
                           <!-- div style = "text-align:left;">
                            <button title='افزودن طرح جدید' style="cursor:pointer;width:70px;height:70px;background-color:transparent; border-color:transparent;" type="button" onclick="add()">
                           <img style = 'width: 60%;' src='../img/Actions-document-new-icon.png' ></button > 
                          </div -->
                          
                          
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr style='color:0000ff; background-color: #B2FFB7'>
                        
                            <th ></th>
                            <th width="5%">مرحله آزادسازی</th>
                        	<th width="10%"  colspan="2">دریافت کننده</th>
                            <th width="15%">مبلغ(ریال)</th>
                            <th width="15%">ش حساب دریافت کننده</th>
                            <th width="20%">بانک دریافت کننده</th>
                            <th width="10%">ش چک صادره</th>
                            <th width="5%">تاریخ</th>
                            <th width="5%">بانک</th>
                            <th width="35%">توضیحات</th>
                            <th width="35%">تاریخ</th>
                            <th width="35%">شماره</th>
                            <th width="35%">نامه آزادسازی</th>
                            <th ></th>
                        </tr>
                   
                        
                    
                                
   <?php

     $permitrolsid2 = array("16","7","1");
     if (in_array($login_RolesID, $permitrolsid2))  $readonly=''; else $readonly='readonly';
   
   $permitrolsid = array("16","19","7","13","14","1","31");
     if ($type==1 && (in_array($login_RolesID, $permitrolsid)))
     {          
       $query=" 
       select producers.ProducersID as _value,concat('فروشنده: ',producers.Title) as _key 
       from producers 
       inner join invoicemaster on invoicemaster.ProducersID=producers.ProducersID and invoicemaster.ApplicantMasterID='$ApplicantMasterID'
       where producers.ProducersID<>135
       union all select -1 as _value, 'مجری:$operatorcoTitle' _key
       union all select -2 as _value, 'کشاورز (عودت خودیاری):$ApplicantFullName' _key
       union all select -3 as _value, 'کشاورز (انجام عملیات):$ApplicantFullName' _key
                         order by _value desc";
	$allProducersID = get_key_value_from_query_into_array($query);
							try 
							  {		
								mysql_query($query);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

	}
	
  if ($type==5 && (in_array($login_RolesID, $permitrolsid)))
     {          
       $query=" 
       select producers.ProducersID as _value,concat('فروشنده: ',producers.Title) as _key 
       from producers 
       where producers.ProducersID='$ProducersID'
       union all select -1 as _value, 'مجری:$operatorcoTitle' _key
       union all select -2 as _value, 'کشاورز (عودت خودیاری):$ApplicantFullName' _key
       union all select -3 as _value, 'کشاورز (انجام عملیات):$ApplicantFullName' _key
                         order by _value desc";
	$allProducersID = get_key_value_from_query_into_array($query);
							try 
							  {		
								mysql_query($query);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

	}
	
       $query=" 
       select 0 _value, '+' _key
       union all select 1 _value, '-' _key";
	   $allpaytype = get_key_value_from_query_into_array($query);
        			

    if (($type==1 || $type==5) && (in_array($login_RolesID, $permitrolsid)))
     {          
  
       $query='select freestateID as _value,Title as _key from freestate order by Code';
       $allfreestateID = get_key_value_from_query_into_array($query);
         			   
        print "<tr><td/>".select_option('freestateID','',',',$allfreestateID,141,'','','1','rtl',0,'','','','60').
        select_option('ProducersID','',',',$allProducersID,0,'','','1','rtl',0,'',0,"","120").
        select_option('paytype','',',',$allpaytype,0,'','','1','rtl',0,'',$paytype,"","20")."
        <td class='data'><input onblur=\"fillform('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/appinvestigation/guarantee_level1_jr.php');\" 
        
        name='Price' type='text' class='textbox' id='Price'   size='15' maxlength='50' onKeyUp=\"convert('Price')\"/></td>
        <td class='data'><input name='AccountNo' type='text' class='textbox' id='AccountNo' size='10'   maxlength='50' /></td>
        <td class='data'><input name='AccountBank' type='text' class='textbox' id='AccountBank'  size='15'  maxlength='60' /></td>
        <td class='data' ><input name='CheckNo' type='text' class='textbox' id='CheckNo' size='8'   maxlength='50' $readonly /></td>
        <td class='data'><input   name='CheckDate' type='text' class='textbox' id='CheckDate'  size='6' maxlength='10' /></td>
        <td class='data'><input name='CheckBank' type='text' class='textbox' id='CheckBank'  value='کشاورزی' size='8' maxlength='50' /></td>
        <td class='data'><input name='Description' type='text' class='textbox' id='Description' size='16'   maxlength='90' /></td>                  
		
        <td class='data' ><input name='letterdate' type='text' class='textbox' id='letterdate' size='6'   maxlength='50'  /></td>
        <td class='data' ><input name='letterno' type='text' class='textbox' id='letterno' size='6'   maxlength='50'  /></td>
		<td class='data'><input name='filep' type='file' class='textbox' id='filep'   style='width:150px' /></td>                  
		
		
        <td><input   name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
		
        <td class='data'><input name='ApplicantMasterID' type='hidden' class='textbox' id='ApplicantMasterID' value='$ApplicantMasterID' /></td>  
		
        </tr>
        <tr>";
       // print "type=$type RolesID=$login_RolesID";
    //print_r($permitrolsid);
     }    
                    
    //print select_option('g2id','',',',$allg2id,0,'','','2','rtl',0,'',$g2id,"onChange=\"selectpage();\"",'213');
    print
                    '</tr>
                    
                    </thead>
                    <thead>
                    </thead>     
                   <tbody>';
         
                     
                    
                    $cnt=0;
                    $prefreestatecode=1;
                    $sum=0;
					$sumt=0;
                    while($row = mysql_fetch_assoc($result)){
                        if ($row['paytype']==1)
					    $sumt-=$row['Price'];
                        else
					    $sumt+=$row['Price'];
                        $cnt++;
                        if ($prefreestatecode<>$row['freestatecode'])
                        {
							print "
                            <tr>
							<td colspan=3 style='color:0000ff; background-color: #B2FFB7'> مجموع ".  $freestateTitle. "</td>
                            <td colspan=11 style='color:0000ff; background-color: #B2FFB7'>".number_format($sum)."</td>
                            </tr>";
                            $sum=0;
                            $prefreestatecode=$row['freestatecode'];
							
                        }
                        if ($row['paytype']==1)
                        $sum-=$row['Price'];
                        else
                        $sum+=$row['Price'];
                        $freestateTitle=$row['freestateTitle'];
						
						
						   
					$fstr1='';
					$IDUser =$row['applicantfreedetailID'];
                    $directory = $_SERVER['DOCUMENT_ROOT'].'/upfolder/free/';
		         	$handler = opendir($directory);
                    while ($file = readdir($handler)) 
                     {
                        if ($file != "." && $file != "..") 
                        {
                            $linearray = explode('_',$file);
                            $IDU=$linearray[0];
                            $No=$linearray[1];
							$num=$linearray[2];
				            if (($IDU==$IDUser)  )
                                $fstr1="<a href='../../upfolder/free/$file' target='_blank' >
                                        <img name='file1img' id='file1img' style = 'width: 25px;' src='../img/accept.png' title='اسکن' ></a>";
                                    
                            
                            
			            }
				     }

   
   
   
   
						
						
						
?>                     
                        <tr>
                            <td><?php echo $cnt; ?></td>
                            <td><?php echo $row['freestateTitle']; ?></td>
                            <td><?php 
                            if ($row['paytype']==1) $pt='(-)'; else $pt='';
                            
                            echo  $row['producersTitle'];//str_replace(' ', '&nbsp;', $row['producersTitle']); ?></td>
                            <td><?php echo "$pt"; ?></td>
                            <td><?php echo number_format($row['Price']); ?></td>
                            <td><?php echo $row['AccountNo']; ?></td>
                            <td><?php echo $row['AccountBank']; ?></td>
                            <td><?php echo $row['CheckNo']; ?></td>
                            <td><?php echo $row['CheckDate']; ?></td>
                            <td><?php echo $row['CheckBank']; ?></td>
                            <td><?php echo $row['Description']; ?></td>
                            <td><?php echo $row['letterdate']; ?></td>
                            <td><?php echo $row['letterno']; ?></td>
                            <?php 
							print "<td class='no-print'>";	
                           $permitrolsid = array("16", "19","7","13","14","1","31");
	                 
		 if (!$row['CheckNo'] || (in_array($login_RolesID, $permitrolsid2))) 
                    {	
                             if (($type==1 || $type==5) && (in_array($login_RolesID, $permitrolsid)))
					        print "<a target='_blank'
                            href='invoicemasterfree_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['applicantfreedetailID'].'_'.$ApplicantMasterID.'_'.$OperatorCoID.rand(10000,99999).
                            "'
                            onClick=\"return confirm('مطمئن هستید که حذف شود ؟');\"
                            > <img style = 'width: 20px;' src='../img/delete.png' title='حذف'> </a>"; ?>
                            <?php 
                             
                            if (in_array($login_RolesID, $permitrolsid))
                             {
                                print "<a target='_blank' href='invoicemasterfree_edit.php?uid=".rand(10000,99999).
                                rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                rand(10000,99999).$row['applicantfreedetailID'].'_'.$ApplicantMasterID.'_'.
                                $OperatorCoID.'_'.$operatorcoTitle.rand(10000,99999).
                                "'><img style = 'width: 25px;' src='../img/file-edit-icon.png' title=' ويرايش '></a>";
                            
                             }
                     }
                        print "$fstr1</td><td></td>";           
                             ?>
                            
                            
                        </tr><?php

                    }
					    
                    if ($prefreestatecode<>$row['freestatecode'])
                    {
					    print "
                            <tr  >
                            <td colspan=3 style='color:0000ff; background-color: #B2FFB7' >مجموع ".$freestateTitle."</td>
                            <td colspan=11 style='color:0000ff; background-color: #B2FFB7'>".number_format($sum)."</td>
                            </tr>";
							
                    }
                        

?>

                        
                   
                    </tbody>
					<?php   print "
                            <tr >
                            <td colspan=3 style=color:009900 >مجموع آزادسازی</td>
                            <td colspan=11 style=color:009900>".number_format($sumt)."</td>
                            </tr>";
							
					if (in_array($login_RolesID, $permitrolsid2))		
				    print "   
                <tr></tr><tr><td colspan=7></td><td colspan=7 style=color:CC6666>
				درصورت تکمیل اطلاعات و ثبت چک صادره، امکان تغییرات توسط مدیریت آب و خاک وجود نخواهد داشت.</td></tr>";
				   
				   ?>
                </table>
				
				
				<tr><td > <?php echo '<font color=\"aa0000\">درصورت امکان آزادسازیهاو آزادسازیهای چند مرحله ای به شکل زیر انجام پذیرد:</font>';   ?></td></tr>
				</br>
                <tr><td > <?php echo '<font color=\"000000\">قسط اول و دوم بابت لوازم و قسمتی از اجرا </font>';   ?></td></tr>
				</br>
                <tr><td > <?php echo '<font color=\"000000\">قسط سوم بابت هزینه اجرا</font>';   ?></td></tr>
				</br>
                <tr><td > <?php echo '<font color=\"000000\">قسط چهارم بابت حسن انجام کار</font>';   ?></td></tr>
				</br>
                
				
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

<?php 
/*

//appinvestigation/aaapplicantfreep.php


فرم هایی که این صفحه داخل آنها فراخوانی می شود
/insert/summaryinvoice.php
-
*/
include('../includes/connect.php'); 
include('../includes/check_user.php'); 
include('../includes/elements.php'); 


if ($login_Permission_granted==0) header("Location: ../login.php");
if ($_POST)//دکمه ثبت کلیک شود
{
    $uidmain=$_POST["uidmain"];//شناسه آدرس ارسالی
    //$_POST["rowbelaavaz"] مبلغ بلاعوض
    //$_POST["applicantmasteridd"] شناسه طرح
    //applicantmaster جدول مشخصات طرح
    if ($_POST["rowbelaavaz"]>0 && $_POST["applicantmasteridd"]>0)
    {
        $query=" update applicantmaster set belaavaz='$_POST[rowbelaavaz]' where applicantmasterid='$_POST[applicantmasteridd]'";
        mysql_query($query);
    }
}
else
{
    $uidmain=$_GET["uid"];//شناسه آدرس ارسالی    
}
    

$ids = substr($uidmain,40,strlen($uidmain)-45);
$linearray = explode('_',$ids);
$ApplicantMasterID=$linearray[0];//شناسه طرح
$applicantstatesIDsurat=$linearray[2];//شناسه وضعیت صورت وضعیت
$ApplicantMasterIDsurat=$linearray[4];//شناسه طرح صورت وضعیت
$showbel=$linearray[1];//نمایش بلاعوض
$showkejra=$linearray[1];// عمیات متقاضی است
$app=$ApplicantMasterID;//شناسه طرح صورت وضعیت
$kejra1=0;$kejra2=0;$kejra3=0;$kejra4=0;$sqlPricekejra=0;$Pricek=0;//متغیرهای متقاضی
/*
Price مبلغ
freestateID شناسه وضعیت طرح
applicantfreedetail جدول ریز آزادسازی ها
ApplicantMasterID شناسه طرح
producersID شناسه تولید کننده
*/
$sqlself = "SELECT Price,freestateID from applicantfreedetail where applicantfreedetail.ApplicantMasterID='$ApplicantMasterID' and producersID=-2";
try 
    {		
        $results = mysql_query($sqlself); 
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }
          
 
$self1=0;$self2=0;$self3=0;$self4=0;
while($rows = mysql_fetch_assoc($results))
{
    if ($rows['freestateID']==141)//قسط اول 
        {$self1=$rows['Price']+$self1;$stext.='ق1';}
    if ($rows['freestateID']==142)//قسط دوم 
        {$self2=$rows['Price']+$self2;$stext.='ق2';}
    if ($rows['freestateID']==143)//قسط سوم 
        {$self3=$rows['Price']+$self3;$stext.='ق3';}
    if ($rows['freestateID']==144)//قسط چهارم 
        {$self4=$rows['Price']+$self4;$stext.='ق4';}
    $Pricek=$self1+$self2+$self3+$self4;
}	
/*
Price مبلغ
freestateID شناسه وضعیت طرح
applicantfreedetail جدول ریز آزادسازی ها
ApplicantMasterID شناسه طرح
producersID شناسه تولید کننده
*/
$sqlkejra = "SELECT Price,freestateID from applicantfreedetail where applicantfreedetail.ApplicantMasterID='$ApplicantMasterID' and producersID=-3";
try 
    {		
        $results = mysql_query($sqlkejra); 
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }
 
        	
        while($rows = mysql_fetch_assoc($results))
        {
            if ($rows['freestateID']==141)//قسط اول  
                {$kejra1=$rows['Price']+$kejra1;$stextkejra.='ق1';}
            if ($rows['freestateID']==142)//قسط دوم  
                {$kejra2=$rows['Price']+$kejra2;$stextkejra.='ق2';}
            if ($rows['freestateID']==143)//قسط سوم  
                {$kejra3=$rows['Price']+$kejra3;$stextkejra.='ق3';}
            if ($rows['freestateID']==144) //قسط چهارم 
                {$kejra4=$rows['Price']+$kejra4;$stextkejra.='ق4';}
            $Pricekejra=$kejra1+$kejra2+$kejra3+$kejra4;
        }
		if ($showkejra==3) 	$sqlPricekejra=$Pricekejra;else $sqlPricekejra=0;  
		 
    $showa=0;//نمایش همه طرح ها
    $yearid=13;//سال
    //تابع ایجاد پرس و جوی آزادسازی
    /*
    $ApplicantMasterID شناسه طرح
    $login_CityId شناسه شهر کاربر لاگین کرده
    $kejra1 کسر از قسط 1
    $kejra2 کسر از قسط 2
    $kejra3 کسر از قسط 3
    $kejra4 کسر از قسط 4
    $sqlPricekejra مبلغ اضافه به صورت وضعیت
    $Pricek حسن انجام کار
    $Pricekejra سن انجام تعهدات
    $str شرط های محدودیت ها
    $orderby رشته ترتیب پرس و جود
    */
    $sql=freequery($ApplicantMasterID,$login_CityId,$kejra1,$kejra2,$kejra3,$kejra4,$sqlPricekejra,$Pricek,$Pricekejra,$orderby);
    try 
    {		
        $result = mysql_query($sql); 
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }     
    $ID1[' ']=' ';
    $ID2[' ']=' ';
    $ID3[' ']=' ';
    $ID4[' ']=' ';
    $ID5[' ']=' ';
    $dasrow=0;
    //حلقه پر کردن آرایه های کلید و مقدار مربوط به کومبوباکس های فیلتر
    $row = mysql_fetch_assoc($result);
    $dasrow=1;    
    $ID1[trim($row['shahrcityname'])]=trim($row['shahrid']);//شهر
    $ID2[trim($row['ApplicantName'])]=trim($row['ApplicantName']);//عنوان پروژه
    $ID3[trim($row['operatorcotitle'])]=trim($row['operatorcoid']);//شناسه پیمانکار
    $ID4[trim($row['ApplicantFName'])]=trim($row['ApplicantFName']);//نام متقاضی   
    $ID5[trim($row['creditsourcetitle'])]=trim($row['creditsourceid']);//شناسه منبع تامین اعتبار
	$errType=$row['errType']; //نوع خطا
	$sumsurat=$row['LastTotals'];//مبلغ کل هزینه های طرح 
    $criditType=$row['criditType'];//اعتبار بانک یا صندوق 
    $rowbelaavaz=round ($row["belaavaz"],2);//بلاعوض
    $applicantmasteridd=$row['applicantmasteridd'];//شناسه طرح 
    //مرتب سازی آرایه های کلید و مقدار مربوط به کومبوباکس ها 
    $ID1=mykeyvalsort($ID1);
    $ID2=mykeyvalsort($ID2);
    $ID3=mykeyvalsort($ID3);
    $ID4=mykeyvalsort($ID4);
    $ID5=mykeyvalsort($ID5);
    if ($dasrow)
        mysql_data_seek( $result, 0 );
    if ($criditType>0) //اعتبار بانک/صندوق
            {$creditTypetitle='طرح تجمیع';}
    if ($row["creditsourceid"]>0)//شناسه منبع تامین اعتبار
				$selectedcreditsourceID=$row["creditsourceid"];
			else $selectedcreditsourceID=4;    
//نمایش بلاعوض
if ($showbel==2) $sysbelaavaz=$row["belaavaz"];
else 
//تابع محاسبه بلاعوض سیستمی
//$selectedcreditsourceID شناسه منبع تامین اعتبار
//$ApplicantMasterID شناسه طرح
//$sumsurat مجموع مبلغ صورت وضعیت
//$criditTypes تجمیع بودن طرح
//if ($row["belaavazsurat"]>0) 
//    $sysbelaavaz=$row["belaavazsurat"];
//else 
$sysbelaavaz=calculatebelavaz($selectedcreditsourceID,$ApplicantMasterIDsurat,$sumsurat,$criditType);			
?>
<!DOCTYPE html>
<html>
<head>
  	<title>پیشنهاد  آزادسازی</title>

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

    </script>
	              
                <script src="../js/jquery-1.9.1.js"></script>
				<script src="../js/jquery.freezeheader.js"></script>

			<script language="javascript" type="text/javascript">

        $(document).ready(function () {
         $("#table2").freezeHeader();
		})
 
    </script>
    <script>
  function checkchange(){
		   if (document.getElementById('showbel').checked || document.getElementById('showkejra').checked)
		   {  
				   if (document.getElementById('showbel').checked)
					{
						//var sysbelaavaz2=document.getElementById('sysbelaavaz').value;
						var uid=document.getElementById('uid').value;
						//alert(uid);
						//<?php $sys ?> = sysbelaavaz2;
							window.location.href =document.getElementById('uid').value;
					}
					
				   
				   if (document.getElementById('showkejra').checked)
					{
						//var sysbelaavaz2=document.getElementById('sysbelaavaz').value;
						var uid3=document.getElementById('uid3').value;
						//alert(uid3);
						//<?php $sys ?> = sysbelaavaz2;
							window.location.href =document.getElementById('uid3').value;
					}
		
			}		
			else 
			{		
				var uid=document.getElementById('uid1').value;
		        window.location.href =document.getElementById('uid1').value;
    		
			}	
		
        }
		
  
	/*
    function calculate() {
        //alert(document.getElementById('Price3p').value);
        //alert(document.getElementById('tPrice').value);
	var y = numberWithoutCommas(document.getElementById('Price3p').value)-numberWithoutCommas(document.getElementById('tPrice').value);
	
	document.getElementById('Price3').value=numberWithCommas(Math.round(y));
	}*/	
		
		
	function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
	}

    function numberWithoutCommas(x) {
    var number = x.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
        number = number.replace(",", "");
    return number;    
 }

   
    </script>
  <?php echo $sys;?>
    <style>
.CSSTable table{ border-collapse: collapse;width:90%;height:auto;margin:10px;font-family:'B Nazanin'; }

.CSSTable tr:nth-child(odd){ background-color:#f5f5f5; }

.CSSTable tr:nth-child(even){ background-color:#ffffff; }

.CSSTable td { vertical-align:middle; border-width:0px 1px 1px 0px;text-align:right;padding:7px;font-size:18px;font-weight:bold;color:#000000;}
.CSSTable td.t2 { vertical-align:middle; border-width:0px 1px 1px 0px;text-align:center;padding:4px;font-size:12px;font-weight:bold;color:#000000;}
.CSSTable td.t3 { vertical-align:middle; border-width:0px 1px 1px 0px;text-align:right;padding:4px;font-size:12px;font-weight:bold;color:#000000;}
.CSSTable tr:first-child td { background-color:#d3e5e5;border:0px solid #c1c1c1;text-align:center;border-width:0px 0px 1px 1px;font-size:16px;
	font-weight:bold;color:#000;}
.CSSTable tr:first-child td.t2 { background-color:#d3e5e5;border:0px solid #c1c1c1;text-align:center;border-width:0px 0px 1px 1px;font-size:22px;
	font-weight:bold;color:#000;}
.CSSTable tr:first-child:hover td{background-color:#2cb7b7;}
.CSSTable tr:first-child td:first-child{border-width:0px 0px 1px 0px;}
.CSSTable tr:first-child td:last-child{border-width:0px 0px 1px 1px;}



.f55_font{ text-align:right;font-size:12.0pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';}

.f9_font{ text-align:right;font-size:9.0pt;line-height:100%;font-family:'B Nazanin';}
  
</style>

<style>
.f16_font{
	background-color:#f5f5f5;border:0px solid black;text-align:right;font-size:13pt;font-weight: bold;font-family:'B Nazanin';                        
}

.f15_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:200%;font-weight: bold;font-family:'B Nazanin';                        
}

.f14_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:12pt;line-height:200%;font-weight: bold;font-family:'B Nazanin';                        
}
.f13_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}
.f11_font{
	background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:11pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}


.f10_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:10pt;line-height:150%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_font{
		background-color:#ffffff;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:9pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f8_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:8pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }
.f7_font{
		border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }
.f13_fontb{
	background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:13pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}
.f11_fontb{
	background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:11pt;line-height:100%;font-weight: bold;font-family:'B lotus';                        
}

.f10_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:10pt;line-height:150%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f9_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:9pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

.f8_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:8pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }
.f7_fontb{
		background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:center;font-size:7pt;line-height:100%;font-weight: bold;font-family:'B Nazanin';                           
  }

   .f131_font{
	border:1px solid black;border-color:#000000 #000000;text-align:right;font-size:13pt;line-height:150%;font-weight: bold;font-family:'B lotus';                        
}
.f131_fontb{
	background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:right;font-size:13pt;line-height:150%;font-weight: bold;font-family:'B Nazanin';                        
}
.f132_font{
	border:1px solid black;border-color:#000000 #000000;text-align:left;font-size:13pt;line-height:150%;font-weight: bold;font-family:'B lotus';                        
}
.f132_fontb{
	background-color:#cfe7e7;border:1px solid black;border-color:#000000 #000000;text-align:left;font-size:13pt;line-height:150%;font-weight: bold;font-family:'B Nazanin';                        
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
            <!-- /main navigation -->
			<!-- main navigation -->
            <?php include('../includes/subnavigation.php'); ?>
            <!-- /main navigation -->

			<!-- header -->
            <?php include('../includes/header.php');  ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
            
            <form action="aaapplicantfreep.php" method="post" onSubmit="return CheckForm()" enctype="multipart/form-data">
                 <div id="loading-div-background">
                <div id="loading-div" class="ui-corner-all" >
                  <img style="height:80px;margin:30px;" src="../img/loading7.gif" alt="در حال بارگذاری..."/>
                  <h2 style="color:gray;font-weight:normal;">از صبر و شکیبایی تان سپاسگزاریم</h2>
                 </div>
                </div>
                
                
                <table id="records" width="95%" align="center">
                
                  <tr> 
                         <?php  
                            $query="SELECT YearID as _value,Value as _key FROM `year` 
                             where YearID in (select YearID from cityquota)
                             
                             ORDER BY year.Value DESC";
            				 $ID = get_key_value_from_query_into_array($query);
                             print 
                             select_option('YearID','سهمیه',',',$ID,0,'','','1','rtl',0,'',$yearid,'','75');
                          
						    print select_option('IDorder','ترتیب',',',$IDorder,0,'','','3','rtl',0,'',$IDorderval,"",'100');
                       
                            print select_option('creditcsourceID','اعتبار',',',$ID5,0,'','','1','rtl',0,'',$creditcsourceID,'','95');
              
						$uid3="aaapplicantfreep.php?uid="
						.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
						.rand(10000,99999).rand(10000,99999).$ApplicantMasterID."_3_".$applicantstatesIDsurat
						."_".$operatorcoid.rand(10000,99999)."";
						
			            $uid1="aaapplicantfreep.php?uid="
						.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
						.rand(10000,99999).rand(10000,99999).$ApplicantMasterID."_1_".$applicantstatesIDsurat
						."_".$operatorcoid.rand(10000,99999)."";
						
						$uid="aaapplicantfreep.php?uid="
						.rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
						.rand(10000,99999).rand(10000,99999).$ApplicantMasterID."_2_".$applicantstatesIDsurat
						."_".$operatorcoid.rand(10000,99999)."";
						
						?><td class="data"><INPUT type="hidden" id="sysbelaavaz" value="<?php print $rowbelaavaz; ?>"/></td> 
						<td class="data"><input name="uid" type="hidden" class="textbox" id="uid"  value="<?php echo $uid; ?>"  /></td>
                      <td class="data"><input name="uid1" type="hidden" class="textbox" id="uid1"  value="<?php echo $uid1; ?>"  /></td>
                      <td class="data"><input name="uid3" type="hidden" class="textbox" id="uid3"  value="<?php echo $uid3; ?>"  /></td>
                      <td class="data"><input name="uidmain" type="hidden" class="textbox" id="uidmain"  value="<?php echo $uidmain; ?>"  /></td>
                      <td class="data"><input name="applicantmasteridd" type="hidden" class="textbox" id="applicantmasteridd"  value="<?php echo $applicantmasteridd; ?>"  /></td>
                      
                      
                      
                      
                      
                      
						<?php
							print "<td colspan='2' class='label'>
							<a target='".$target."' href='appinvestigation/allapplicantstates.php?uid='>بلاعوض دستی</a>
							<input  placeholder='".$rowbelaavaz."' type='text' class='f51_font' class='textbox'  name='rowbelaavaz'  
							id='rowbelaavaz'     value='".$rowbelaavaz."'  />
	 
							<input name='showbel' type='checkbox' id='showbel' onChange='checkchange()'";
                             if ($showbel==2) echo 'checked';
                             print " /></td>
                             <td><input name='submit' type='submit' class='button' id='submit' value='تصحیح بلاعوض' /></td>";
                            
					
							print "<td colspan='1' class='label'>همه</td>
                         <td class='data'><input name='showa' type='checkbox' id='showa' ";
                             if ($showa>0) echo 'checked';
                             print " /></td>";
                             
                          ?>
  					      
                
				   </tr>
				   
				   
		        </table>
                 <table align='center' border='1' id="table2">              
                   <thead>           
				   <tr>
                              <td colspan="22" <span class="f15_font" >
							  پیشنهاد آزاد سازی (مبالغ به میلیون ریال)  
					<?php
					           print  "<a target='".$target."' href='invoicemasterfree_list.php?uid=".rand(10000,99999).
                                rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                rand(10000,99999).$row['applicantmasterid'].'_1_0_'.$row['operatorcoid'].'_'.$row['operatorcoTitle'].rand(10000,99999).
                                "'><img style = 'width: 22px;' src='../img/Actions-document-export-icon.png' title='آزادسازی'></a>";
                      ?>   
					  </span>
					  </td>
                    <tr>        
					 <tr>
                            <th class="f9_font" > رديف   </th>
                            <th class="f9_font" >کد   </th>
							<th class="f11_font"> نام   </th>
							<th class="f11_font"> نام خانوادگی  </th>
							<th class="f9_font"> مساحت </span>(ha)  </th>
						    <th class="f9_font">دشت/ شهرستان </th>
							<th class="f9_font">شركت مجری </th>
							<th class="f9_font"> مبلغ طراحی</th>
							<th class="f9_font">پیش فاکتور</th>
							<th class="f9_font">کمک بلاعوض</th>
							<th class="f9_font"> جمع خودیاری</th>
							<th class="f11_font">صورت وضعیت</th>
							<th class="f11_font">هزینه اجرا</th>
							<th class="f11_font"> بلاعوض اصلاحی</th>
							<th class="f11_font"> خودیاری اصلاحی</th>
					
							<th class="f11_font">قسط اول </th>
							<th class="f11_font">قسط دوم </th>
							<th class="f9_font">قسط خودیاری</th>
							<th class="f11_font">قسط سوم </th>
							
							<th class="f11_font">قسط چهارم </th>
							<th class="f13_font">آزادسازی شده </th>
							<th class="f13_font">آزادسازی نشده </th>
					
                        </tr>
                        
                        </thead> 
						
	<?php if ($login_RolesID=='100') { 
	   ?>					
						
                        <tr class='no-print'>    
							<td class="f14_font"></td>
                            <td class="f14_font"></td>
                            <?php print select_option('ApplicantFname','',',',$ID4,0,'','','1','rtl',0,'',$ApplicantFname,'','40'); ?>
							 <?php print select_option('ApplicantName','',',',$ID2,0,'','','1','rtl',0,'',$ApplicantName,'','50'); ?>
							<?php print select_option('IDArea','',',',$IDArea,0,'','','1','rtl',0,'',$IDArea,'','30'); ?>
					       <?php print select_option('sos','',',',$ID1,0,'','','1','rtl',0,'',$sos,"",'50'); ?>  
					       <?php print select_option('operatorcoid','',',',$ID3,0,'','','1','rtl',0,'',$operatorcoid,'','35') ?>
							<td></td> 
					       <?php print select_option('IDprice1','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice1,'','35'); ?>  
					        <?php print select_option('IDprice2','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice2,'','35'); ?> 
                      	<td class="f14_font"></td>
                      	<td class="f14_font"></td>
                      	<td class="f14_font"></td>
                      	<td class="f14_font"></td>
                      	<td class="f14_font"></td>
                           <?php print select_option('IDprice3','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice3,'','35'); ?> 
					       <?php print select_option('IDprice4','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice4,'','35'); ?> 
							<td class="f14_font"></td>
                         
					       <?php print select_option('IDprice5','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice5,'','35'); ?> 
					       <?php print select_option('IDprice6','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice6,'','35'); ?> 
					       <?php print select_option('IDprice7','',',',$IDprice,0,'','','1','rtl',0,'',$IDprice7,'','35'); ?> 
                      	<td class="f14_font"></td>
                       
					 
					 </tr> 
                     
<?php }
                    $Total=0;
                    $rown=0;
                    $Description="";
					$sumarea=0;
                    $sum1=0;
                    $sum2=0;
                    $sum3=0;
                    $sum4=0;
                    $sumall=0;
                    $LastTotal=0;
                    $LastTotald=0;
                    $LastTotaldif=0;
                    $selfnotcashhelpval=0;
					$selfcashhelpval=0;
					$selfhelp=0;
                    $belaavaz=0;
                    $remain=0;
					$LastTotalsum=0;
					$LastFehrestbahasum=0;
					
    while($resquery = mysql_fetch_assoc($result))
    { 
					  
   	    $hatarray = explode('_',$resquery["letterno"]);
        $hatarraysurat = explode('_',$resquery["letternosurat"]);
        if (str_replace(',', '', $hatarraysurat[0])>0)
            $hat=str_replace(',', '', $hatarraysurat[0]);//حسن انجام تعهدات
        else
            $hat=str_replace(',', '', $hatarray[0]);//حسن انجام تعهدات
            
        if (str_replace(',', '', $hatarraysurat[2])>0)
   	        $hak = str_replace(',', '', $hatarraysurat[2]);//حسن انجام کار
        else
   	        $hak = str_replace(',', '', $hatarray[2]);//حسن انجام کار
        
        
                      
                        $ApplicantFName=$resquery['ApplicantFName'];
                        $ApplicantName=$resquery['ApplicantName'];
						$othercosts5=$resquery["othercosts5"];
                        $Price1=$resquery["Price1"]-$self1;
                        $Price2=$resquery["Price2"]-$self2;
                        $Price3=$resquery["Price3"]-$self3;
                        $Price4=$resquery["Price4"]-$self4;
	 				    $Pricek=$resquery["Pricek"];
	   				    $Pricekejra=$resquery["Pricekejra"];
		
        			   
					//	print $Price1.'*'.$Price2.'*'.$Price3.'*'.$Price4.'*'.$Pricek.'*'.$Priceall.'*'.($Price1+$Price2);exit;
	                  // print '***'.$Pricek;				  
					
       					$LastFehrestbaha=$resquery["LastFehrestbaha"];
						$LastFehrestbaha25=$resquery["LastFehrestbaha"]*0.25;
						//$LastFehrestbaha=$resquery["LastFehrestbaha"]+$resquery["othercosts"];
                        // print $LastFehrestbaha;exit;    
                		if ($sysbelaavaz>$resquery['belaavaz'] || $sysbelaavaz==0)  $sysbelaavaz=$resquery['belaavaz'];
					
                
					///	if ($resquery["othercosts5"]) $LastTotals=$resquery["othercosts5"]+$resquery["LastTotals"];	
						if ($resquery["LastTotals"]>$resquery["LastTotald"]) 
							{$LastTotals=$resquery["LastTotald"];}
       					else {
									
									if ($resquery["LastTotals"]>0) $LastTotals=$resquery["LastTotals"];
                						else if ($resquery["LastTotal"]>0) $LastTotals=$resquery["LastTotal"];
                						else $LastTotals=$resquery["LastTotald"];
                              }
         	       
		//$total=round(($resquery["belaavaz"]*1000000+$resquery["selfhelp"])/10000)*10000+$othercosts5;
		$total=round(($resquery["belaavaz"]*1000000+$resquery["selfhelp"])/10000)*10000;
		$totalcredit=$total;
		
		if ($total>$resquery["LastTotald"]) $total=$resquery["LastTotald"];
		  if ($LastTotals>$total) $LastTotals=$total; 
				
			//print $LastTotals;exit;
			  //print ($sysbelaavaz*1000000+$resquery['selfhelp']);exit;
		  /// if (($sysbelaavaz*1000000+$resquery['selfhelp']+$othercosts5)< $LastTotals) 
          /// {$LastTotals=$sysbelaavaz*1000000+$resquery['selfhelp']+$othercosts5;$errblavaz='بلاعوض محاسباتی وسهم خودیاری کنترل شود!';}

             if (($sysbelaavaz*1000000+$resquery['selfhelp'])< $LastTotals) 
           {$LastTotals=$sysbelaavaz*1000000+$resquery['selfhelp'];$errblavaz='بلاعوض محاسباتی وسهم خودیاری کنترل شود!';}

		   
		 //  print $LastTotals;exit;
		   		   	if ($showbel==2 && $sysbelaavaz*1000000>$LastTotals)  $sysbelaavaz=$LastTotals/1000000;
			
		            $selfhelps=$LastTotals-$sysbelaavaz*1000000;
			
				
			        if ($Pricek>0) $selfremain=$Pricek ;
					else
          			$selfremain=$resquery["selfhelp"]-$selfhelps;
	              //  print $selfremain;
					if ($selfremain<0) $selfremain=0;
		//			print $sysbelaavaz.'*'.$resquery['belaavaz'].'*'.$selfhelps.'*'.$resquery['selfhelp'];exit;
		//if ($sysbelaavaz>$resquery['belaavaz'] || $selfhelps<$resquery['selfhelp']) $Priceall=$resquery["Priceall"]-$selfremain;
		//else 
		$Priceall=$resquery["Priceall"]-$resquery["Pricek"];

				//print $Price1.'*'.$Price2.'*'.$Price3.'*'.$Price4.'*'.$Pricek.'*'.$Priceall.'*'.($Price1+$Price2).'*'.$resquery["Priceall"];exit;
					
		////محاسبه اقصاط
		$LastTotals25=$LastTotals-$LastFehrestbaha+0.25*$LastFehrestbaha;
		
		if (!(strlen($resquery["Price1"])>0)) {$Price1=$LastTotals*0.6;$cl1='0000ff';
												if ($Price1>$LastTotals25)	$Price1=$LastTotals25;} else {$Price1=$resquery["Price1"]-$self1;}
		
		
		if ( !(strlen($resquery["Price2"])>0) || ($resquery["Price2"]==$self2 && $resquery["Price2"]>0)) {
			
			$Price2=$LastTotals*0.85-$Price1; $cl2='0000ff';
			if ($Price2>$LastTotals25)	$Price2=$LastTotals25;
			if (($Price2+$Price1)>$LastTotals25) $Price2=$LastTotals25-$Price1;
			if ($Price2<0) $Price2=0;
			if ($resquery["Price2"]==$self2 && $self2>0) {$Price2=$Price2;$cl2='';}
			
			
			
			}else{ $Price2=$resquery["Price2"]-$self2;}

			
    
	 if (!(strlen($resquery["Price3"])>0) || ($resquery["Price3"]==$self3 && $resquery["Price3"]>0)) 
     {
        //print "sa".$resquery["Price3"];
		    $Price3=$LastTotals-$LastFehrestbaha*0.1-($Price2+$Price1)+$selfremain; $cl3='0000ff';$cl6='0000ff';
			
     		if (($selfhelps-$resquery["selfhelp"])<0) {$Price3=$Price3-$selfremain;}
			//print $Price3;
	        if ($Price3<0) $Price3=0;
			if ($Price3==$self3 && $self3>0) 
            {
                //print $Price3;
                $Price3=$Price3;$cl3='';
            }
    } else 
    { 
			 
             //print "sa3";
             $Price3=$resquery["Price3"]-$self3;
    }

			//if ($selfhelps<$resquery['selfhelp'] && ($resquery['selfhelp']-$selfhelps)<$resquery["Price3"]) {$Price3=$Price3-$selfremain;}
		
    	//print $selfremain;
		//print $total;
		//$remaintotal=$total-$resquery["Priceall"];
			$remaintotal=$totalcredit-$resquery["Priceall"];
		
					if ($remaintotal<($LastTotals-$resquery["Priceall"])) $remains=$remaintotal/100000/10;else  $remains=($LastTotals-$resquery["Priceall"]+$resquery["Pricek"])/100000/10;
				
		if ($resquery["Pricek"]) $cl6='';else $cl6='0000ff';
		
	
		if (!$resquery["Price4"]>0) 
        {
			//print $hak."sa";
            if ($hak>0) $Price4=$hak; 
            else
            {
                $Price4=$LastFehrestbaha*0.1;$cl4='0000ff';
                if ($LastFehrestbaha<=0)
                {
                    
                    header("Location: ../insert/summaryinvoice.php?uid='".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999)
                    .rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ApplicantMasterIDsurat."_4_0_$operatorcoid_".
                    $applicantstatesIDsurat.rand(10000,99999)."'");
                    echo "لطفا صفحه صورت وضعیت طرح را باز و بررسی نموده و دوباره وارد این صفحه شوید";
                    exit;
                }
                
                
                
                //print $LastFehrestbaha;exit;
                
            }
				
			 
			 if ($remaintotal<$Price4) $Price4=$remaintotal;
			
			
		
			if ($Price4<0)
			if (!$resquery["Price3"]>0)
			  {
			//	if ($Price3<($LastFehrestbaha*0.1)) $price3=0;
		   //else  $Price3=($Price3-$LastFehrestbaha*0.1);
				
				if ($Pricekejra>0) $Price3=$Price3-$Pricekejra;
				
				$Price4=$LastTotals-($Price1+$Price2+$Price3);
				
				if ($Pricekejra>0) $Price4=$Price4-$Pricekejra;
				
			  }				
	   
	   
	   
		
	   
	        if ($remaintotal<$remains*1000000) {$Price4=$Price4-($remains*1000000-$remaintotal);$errh='مانده اعتبار از مبلغ حسن انجام کار کمتر می باشد.';}

				
			  
        }else {$Price4=$resquery["Price4"]-$self4;}
		     
			    if (($remaintotal/100000/10)<$remains) $remains=$remaintotal/100000/10;
		


//print ($sqlPricekejra/1000000)	 ;
			
//print ($LastTotals-($Price1+$Price2+$Price3+$Price4));
if ($Price1<0) $cl1='red'; if ($Price2<0) $cl2='red';if ($Price3<0) $cl3='red';if ($Price4<0) $cl4='red';

				
					    $sumarea+=$resquery["DesignArea"];
                        $sum1+=floor($resquery["Price1"]);
                        $sum2+=floor($resquery["Price2"]);
                        $sum3+=floor($resquery["Price3"]);
                        $sum4+=floor($resquery["Price4"]);
                        $sumall+=$resquery["Priceall"];
                        $LastTotal+=$resquery["LastTotal"];
                        $LastTotald+=$resquery["LastTotald"];
						$LastTotalsum+=floor($resquery["LastTotals"]);
						$LastFehrestbahasum+=floor($LastFehrestbaha);
						
						
						
        if ($resquery["LastTotal"]<$resquery["LastTotald"])	$LastTotaldifr=$resquery["LastTotal"]-$resquery["LastTotald"]; else $LastTotaldifr=0;
                        $LastTotaldif+=$LastTotaldifr;
						$selfnotcashhelpval+=$resquery["selfnotcashhelpval"];
                        $selfcashhelpval+=$resquery["selfcashhelpval"];
                    	$selfhelp+=$resquery["selfhelp"];
                        
				
			         if ($remains<0) {$cl5='ff0000';$err='مجموع آزادسازی بیشتر از منابع مالی یا مبلغ صورت وضعیت نهایی است';}
			
                        $remain+=$resquery["LastTotals"]-$resquery["Priceall"]+$selfremain;

						$belaavaz+=round($resquery['belaavaz'],1);
                            $rown++;
                            if ($rown%2==1) 
                            $b='b'; else $b='';

						$remainself=($resquery["selfhelp"])-($selfremain+$selfhelps);
		
						if ($remainself<0)
						{
							$remainselftext='خودیاری اضافه عودت شده: ';
							$cl7='ff0000';
							if ($remainself<100000*(-1))					
							if ($errType<>2){$query = "UPDATE applicantmaster SET 
                            SaveTime = '" . date('Y-m-d H:i:s') . "', 
                SaveDate = '" . date('Y-m-d') . "', 
                ClerkID = '" . $login_userid . "',
                            errType = '2'	WHERE ApplicantMasterID = ".$ApplicantMasterID.";";
							$resul = mysql_query($query);}
						}
						else if ($remainself>0)
						{
							$remainselftext='مانده خودیاری قابل پرداخت: ';
							$cl7='0000ff';
						}	
					
                             print "<tr '>";    
                              
    if ($hat>0)    
        $tPrice=$hat;
    else              
	    $tPrice=$LastTotals-($Price1+$Price2+$Price3+$Price4+$Pricekejra);	
     
     if ($tPrice<0)
     {
        $Price3=$Price3+$tPrice;
        $tPrice=0;
     }
        //echo $tPrice."sa";
        		 
if ($resquery["Price3"]<=0 || $Price4>0 &&  $tPrice>0) $Price3p=$Price3;	
	
if ($hat>0) 
{
    $Price3=$Price3p-$hat;
}

	
//print $LastTotals-($Price1+$Price2+$Price3+$Price4);
?>                      
      </tr>

<tr>



		<td class="f10_font"  colspan="15" style="text-align: center;font-size:12.0pt;font-family:'B Nazanin';"><?php echo $app.'&nbsp;&nbsp;&nbsp;'.$resquery['creditsourcetitle'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$resquery['designsystemgroupsTitle'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$creditTypetitle;
					$permitrolsid = array("1","5","19","13","14");		
				    if (in_array($login_RolesID, $permitrolsid))
                    {
                         echo "<a  target='".$target."' href='../insert/summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ApplicantMasterID.'_5_0_0_'.$applicantstatesID.rand(10000,99999)."'>
                            <img style = 'width: 3%;' src='../img/search_page.png' title=' جدول تفکیک مالی پیشفاکتور '></a> تفکیک سیستم آبیاری"; 
						
						
					       echo "<a  target='".$target."' href='../insert/summaryinvoice.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$ApplicantMasterIDsurat.'_5_0_0_'.$applicantstatesID.rand(10000,99999)."'>
                            <img style = 'width: 3%;' src='../img/search.png' title='جدول تفکیک مالی صورت وضعیت'></a>"; 
               
                    }  
                   

		; ?></td>
                    
							<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo round($Price1/$LastTotals *100,2 ); ?>%</td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo round($Price2/$LastTotals *100,2); ?>%</td>
                            <td></td>		 
							 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo round($Price3/$LastTotals *100,2); ?>%</td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo round($Price4/$LastTotals *100,2); ?>%</td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:12.0pt;font-family:'B Nazanin';"><?php echo round($Priceall/$LastTotals *100,2); ?>%</td>
							<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:12.0pt;font-family:'B Nazanin';"><?php echo round($remains*1000000/$LastTotals *100,2); ?>%</td>
		
							
      </tr>
                 
<tr>




				 <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $rown; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';" ><?php echo "($resquery[sandoghcode])" ;?></td>
														
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery['ApplicantFName']; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["ApplicantName"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["DesignArea"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo  $resquery["shahrcityname"]; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo $resquery["operatorcotitle"] ; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["LastTotald"]/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($resquery["LastTotal"]/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo round($resquery['belaavaz'],1); ?></td>
                        	  <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo (floor($resquery["selfhelp"]/100000)/10); echo '<br>'.(floor($resquery["othercosts5"]/100000)/10) ?></td>
							
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor(($resquery["LastTotals"])/100000)/10; ?></td>
							<td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($LastFehrestbaha/100000)/10; ?></td>
							
        <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo round($sysbelaavaz,1); ?></td>
                        	  <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($selfhelps/100000)/10; ?></td>

							
						  <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl1; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($Price1/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl2; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($Price2/100000)/10; ?></td>
							 
					          <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl6; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($selfremain/100000)/10; ?></td>
							
							
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl3; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($Price3/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl4; ?>;text-align: center;font-size:10.0pt;font-family:'B Nazanin';"><?php echo floor($Price4/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl; ?>;text-align: center;font-size:11.0pt;font-family:'B Nazanin';"><?php echo floor($Priceall/100000)/10; ?></td>
                            <td class="f10_font<?php echo $b; ?>"  style="color:#<?php echo $cl5; ?>;text-align: center;font-size:11.0pt;font-family:'B Nazanin';"><?php echo floor($remains*10)/10; ?></td>
														
 							
</tr>

                        
                
                   <tr>
	
        					<td colspan="20" class="f8_font" align="left"><?php echo '<br>' ;   ?></td>
            				<td colspan="2" class="f8_font" align="left"><?php echo '*بدون احتساب قسط خودیاری' ;   ?></td>
                   </tr>
	           
               <?php 
               
               if ($ApplicantMasterID>0)
               {
                print "
 	            </table>"; 
                ?>
				<div class="CSSTable" >
			
			
                <table >
				<tr></tr>
			        <tr>
					
					<td style='color:green'>اعتبار کل طرح: <?php if($resquery["othercosts5"]) echo "<br><font size=1>بدون احتساب خودیاری به صورت کالا و خدمات</font> </td>"; ?></td>
					<td><?php echo number_format($totalcredit);  ?></td>
					<td style='color:green'>ریال </td> <td></td>
					<td style='color:green'>مبلغ آزادسازی شده:<br><font size=1> با احتساب خودیاری عودتی</font> </td>
					<td><?php echo number_format($resquery["Priceall"]); ?></td>
					<td style='color:green'>ریال </td> 
					
					<td></td>
					<td style='color:green'>اعتبار مانده:<br><font size=1>حسن انجام کار و تعهدات</font> </td>
					<td style='color:purple'><?php echo number_format($remaintotal);?></td>
					<td style='color:green'>ریال </td> <td></td>
					</tr>
			<?php if ($Pricekejraold) {?>		
				<tr>
					<td style='color:green' colspan='3'>انجام عملیات اجرایی  توسط متقاضی:</td>
					<td colspan='2'><?php echo number_format($Pricekejra); ?></td>
					<td style='color:green'>ریال </td> <td></td>
			<?php }?>		
				
				</tr>	
               </table >
			   <table >
				
                    <tr>
					<td colspan="22"> وضعيت آزادسازیهای طرح :&nbsp;&nbsp;&nbsp; <?php echo  $resquery['ApplicantFName'] .' '. $resquery['ApplicantName'] ; ?>  
					</td>
					
					
					</tr>
                    <tr>
					
					<td>مبلغ نهایی طرح:<br><font size=1 color=green><?php if($resquery["othercosts5"]) echo "بدون احتساب خودیاری به صورت کالا و خدمات"; ?></font></td>
					<td ><?php echo number_format($LastTotals); ?></td>
					<td>ریال </td> <td></td><td></td><td></td>
					<td>مبلغ نهایی بلاعوض:</td>
					<td><?php echo number_format($sysbelaavaz*1000000);?></td>
					<td>ریال </td> <td></td><td></td><td></td>
					</tr>
                   
				    <tr>
					<td>قسط اول:</td>
					<td style='color: <?php echo $cl1 ?>;'> <?php echo number_format($Price1); ?></td>
					<td>ریال </td> <td></td><td></td><td></td>
					<td> خودیاری  به صورت کالا و خدمات:</td>
					<td style='color: <?php echo $cl1 ?>;'> <?php echo number_format($resquery["othercosts5"]); ?></td>
					<td>ریال </td> <td></td><td></td><td></td>
					</tr>
                   
                   <td>قسط دوم:</td>
					<td style='color: <?php echo $cl2 ?>;'> <?php echo number_format($Price2); ?></td>
					<td>ریال </td> <td></td><td></td><td></td>
					<td> خودیاری عودتی: <?php echo $stext; ?></td>
					<td style='color: <?php echo $cl6 ?>;'> <?php echo number_format($selfremain); ?></td>
					<td>ریال </td> <td></td><td></td><td></td>
					</tr>
                   
                   
                    <tr>
						<td class='label' id='labeltPrice3' name='labeltPrice3' >قسط سوم:</td>
						<td class="data" ><input  name="Price3" type="text" 
						  id="Price3" value="<?php echo number_format($Price3);?>"  size="10" maxlength="20"
							class="f16_font" style='color: <?php echo $cl3 ?>;' /></td>
						
						<td>ریال </td> <td></td><td></td><td></td>
						
						<?php if ($remainselftext){ ?>
						<td> <?php echo $remainselftext; ?></td>
						<td style='color: <?php echo $cl7 ?>;'> <?php echo number_format($remainself); ?></td>
						<td>ریال </td> <td></td><td></td><td></td>
						<?php } ?>
						
					</tr>
                 <?php if ($Pricekejra>0) {  ?>
				   <tr>
				   
					
						<td class='label' id='labelPricekejra' name='labelPricekejra'><?php echo $stextkejra;?> عملیات متقاضی: </td>
						<td style='color: <?php echo $cl8 ?>;'> <?php echo number_format($Pricekejra); ?></td>
						<td>ریال 
						<input name='showkejra' type='checkbox' id='showkejra' onChange='checkchange()' <?php if ($showkejra==3) echo "checked";?>>
						</td> <td></td><td></td><td></td>
								   
				   </tr>
				<?php } ?>
				
 <?php if ($Price3p>0) { ?>
				   <tr>
						<td class='label' id='labeltPrice' name='labeltPrice'>حسن انجام تعهدات:</td>
						<td class="data"><input   name="tPrice" type="text" style='color: green'
						  id="tPrice" value="<?php echo number_format(round($tPrice));?>"  size="10" maxlength="20"	class="f16_font" /></td>
					   <td>ریال </td> <td></td><td></td><td></td>
						 <td class="data"><input name="Price3p"  type="hidden" class="textbox" id="Price3p"  value="<?php echo number_format($Price3p); ?>"  /></td>
				   <td></td><td></td><td></td><td></td><td></td>
				   </tr>
				<?php } ?>
		
				    <tr>
					<td>حسن انجام کار:</td>
					<td style='color: <?php echo $cl4 ?>;'> <?php echo number_format($Price4); ?></td>
					<td>ریال </td> <td></td><td></td><td></td>
					
					</tr>
                   

						
                </table>
		</div>
		
                 <?php } ?>  




					 
                          <?php
    }
                    print "
 	            </table>"; 
/*					
if ($login_RolesID=='1') {
?>
                <table >

                   <tr>
                            <td rowspan="2" colspan="4" class="f14_font" ><?php echo 'مجموع (ریال)';   ?></td>
                            <td rowspan="2" colspan="3" class="f14_font" ><?php echo (floor($sumarea*10)/10).' هکتار';   ?></td>
                            <td colspan="2" class="f131_font" ><?php echo floor($LastTotald/100000)/10;   ?></td>
						    <td colspan="2" class="f131_font" ><?php echo $belaavaz;   ?></td>
                    
	                <td colspan="2" class="f131_font" ><?php echo number_format($LastTotalsum);   ?>&nbsp;&nbsp;&nbsp;</td>
					<td colspan="2" class="f131_font" ></td>

                   <td colspan="2" class="f131_font" style="color:#<?php echo $cl1; ?>" ><?php echo number_format($sum1);  ?>&nbsp;&nbsp;&nbsp;</td>
                   <td colspan="3" class="f131_font" style="color:#<?php echo $cl3; ?>"><?php echo number_format($sum3);  ?>&nbsp;&nbsp;&nbsp;</td>
                   <td colspan="2" class="f131_font" ><?php echo number_format($sumall); ?></td>
    	
				   </tr>
  
                   <tr>
        					<td colspan="2" class="f132_font" ><?php echo floor($LastTotal/1000000);   ?></td>
	                        <td colspan="2" class="f132_font" ><?php echo floor($selfhelp/100000)/10;   ?></td>
    					    
							<td colspan="2" class="f132_font" >&nbsp;&nbsp;&nbsp;<?php echo number_format($LastFehrestbahasum);   ?></td>
														<td colspan="2" class="f132_font" ></td>

	                        <td colspan="2" class="f132_font" style="color:#<?php echo $cl2; ?>">&nbsp;&nbsp;&nbsp;<?php echo number_format($sum2);  ?></td>
                         	<td colspan="3" class="f132_font" style="color:#<?php echo $cl4; ?>">&nbsp;&nbsp;&nbsp;<?php echo number_format($sum4);  ?></td>
                         	<td colspan="2" class="f132_font" style="color:#<?php echo $cl5; ?>">&nbsp;&nbsp;&nbsp;<?php echo number_format($remain);   ?></td>
        
                   </tr>
				                   </table>

  <?php
    
                }               
*/				
?>
    
	
	
	
	
              <?php
					echo '<br>' ;  
					echo '<br>*مبالغ پیشنهادی جهت آزادسازی با رنگ آبی مشخص شده اند <br>' ; 
					echo '*مبالغ آزادسازی شده با رنگ مشکی مشخص شده اند <br>' ; 
					echo '*مبلغ نهایی طرح بدون احتساب خودیاری کالا و خدمات آورده شده است<br>'; 
					//echo '*در محاسبه10% حسن انجام کار اجرایی، ضرایب در نظر گرفته نشده است<br>'; 
					if ($applicantstatesIDsurat<>45) echo '<font color=\"cc0000\">* صورت وضعیت طرح نهایی نشده است!</font><br>' ;
					if ($errblavaz) echo '<font color=\"cc0000\">*'.$errblavaz.'</font><br>' ;
					if ($err) echo '<font color=\"ff0000\">*'.$err.'</font><br>' ;
					if ($errh) echo '<font color=\"ff0000\">*'.$errh.'</font><br>' ;
					
				?>
					
			  <tr ><span colsapn="1" id="fooBar">  &nbsp;</span></tr>
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

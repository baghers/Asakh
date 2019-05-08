<?php 
/*

//appinvestigation/allapplicantquotasandugh.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود

*/


include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/functions.php');

if ($login_Permission_granted==0) header("Location: ../login.php");

//شناسه وضعیت هایی که نشان می دهد پروژه در وضعیت مطالعات و طراحی می باشد
/*
2 مدیر مشاور به کاربرطراح
3 کاربر به مدیر مشاور طراح
4 بازبین به مدیر مشاور طراح
5 مدیر مشاور طراح به بازبین
6 کاربر بازبین به بازبین
7 بازبین به کاربر بازبین
11 مدیریت آب و خاک به بازبین
24 دریافت پیشنهاد قیمت
25 مدیر مشاور طراح به م ج شهرستان

*/
$indesignstates=array("2","3","4","5","6","7","11","24","25");
$showc=0;//1 نمایش طرح های تجمیع
$cond=" and substring(applicantmaster.CityId,1,2)=substring('$login_CityId',1,2) ";//شرط محدودیت شهر
$showm=0;//1 بلاعوض تلفیقی
$showb=0;//1 منبع اعتبار بانک 
$shows=0;//1 منبع اعتباری صندوق


		
if ($_POST)
{
    if ($_POST['showm']=='on')   {$showm=1;}
	if ($_POST['showb']=='on')   {$showb=1;}
	if ($_POST['shows']=='on')   {$shows=1;}
    if ($_POST['showc']=='on')
    $showc=1;
}
 
if ($showc==1) $cond.=" and ifnull(applicantmaster.criditType,0)=1 ";//افزودن شرط تجمیع بودن

    $selftitle1="خویاری نقدی";//عنوان خودیاری در صندوق
    $selftitle2="خودیاری تعهدی";//عنوان خودیاری غیر نقدی در صندوق
    $selftitle3="خودیاری پرداخت شده";//عنوان مجموع خودیاری پرداخت شده در صندوق

//$login_RolesID 16 کارشناس صندوق    
//$shows 1 منبع اعتباری صندوق
 if ($login_RolesID==16 || $shows==1) //در صورتی که کاربر صندوق و خواهان مشاهده منابع اعتباری صندوق باشد
 { 
    
    $cond.=" and applicantmaster.applicantstatesID in (12,22) "; 
    $stTitle='صندوق حمایت از توسعه بخش کشاورزی';//عنوان محل
    $stSendTaiid='12,22';//شماره وضعیت های کارتابل صندوق شامل دوازده مدیریت آب و خاک به صندوق و بیست و دو که انعقاد قرارداد با صندوق می باشد
    $stSend='12';//وضعیت مدیریت آب و خاک به صندوق
    $stTaiid='22';//وضعیت انعقاد قرارداد با صندوق
    $stTaiidpish='30';//وضعیت تایید پیش فاکتور
    $stTahvil='45';//وضعیت تایید صورت وضعیت
 }
 if ($login_RolesID==7 || $showb==1)                      
 {
    $cond.=" and applicantmaster.applicantstatesID in (36,37) ";//شرط محدود کردن مشاهده طرح های موجود در کارتابل
    $stTitle='بانک کشاورزی';//عنوان محل
    $stSendTaiid='36,37';//شماره وضعیت های کارتابل بانک
    $stSend='36';//وضعیت مدیریت آب و خاک به بانک
    $stTaiid='37';//وضعیت انعقاد قرارداد با بانک
    $stTaiidpish='30';//وضعیت تایید پیش فاکتور
    $stTahvil='45';//وضعیت تایید صورت وضعیت
    $selftitle1="سهم شریک";//عنوان خودیاری در بانک
    $selftitle2="تسهیلات";//عنوان خودیاری غیر نقدی در بانک
    $selftitle3="تسهیلات پرداخت شده";//عنوان مجموع خودیاری پرداخت شده در بانک
 }

 if ($login_RolesID!=7 && $login_RolesID!=16 )//درصورتی که کاربر صندوق یا بانک نباشد و مدیر باشد منابع اعتباری بانک و صندوق را
 //همزمان می بیند 
    {$stTitle='بانک/صندوق';$stSendTaiid='12,22,36,37';$stSend='12,36';$stTaiid='22,37';$stTaiidpish='30';$stTahvil='45';}

    try 
      {		
        $result = mysql_query(retqueryaggregated($cond));//پرس و جوی مشخصات کلیه طرح ها
      }
      //catch exception
      catch(Exception $e) 
      {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
      }
      



while($row = mysql_fetch_assoc($result))//در این حلقه می خواهیم تعداد و سطح و مبالغ پروژه های هر منبع اعتباری تفکیک شود و در لیست پایین نمایش داده شود
{   
    
    $totalvals[$row['creditsourceID']][1]++;//تعداد طرح های منبع اعتباری
    $hekvals[$row['creditsourceID']][1]+=$row['DesignAread'];//مجموع مساحت طرح های منبع اعتباری
    $belavals[$row['creditsourceID']][1]+=$row['belaavazd'];//مجموع بلاعوض طرح های منبع اعتباری
    $selfcashhelpval[$row['creditsourceID']][1]+=$row['selfcashhelpval'];//مجموع خودیاری نقدی طرح های منبع اعتباری
    $selfnotcashhelpval[$row['creditsourceID']][1]+=$row['selfnotcashhelpval'];//مجموع خودیاری غیر نقدی طرح های منبع اعتباری
    if (in_array($row['applicantstatesIDd'],$indesignstates))//طرح های طراحی
    {
        $totalvals[$row['creditsourceID']][2]++;//تعداد طرح های منبع اعتباری
        $hekvals[$row['creditsourceID']][2]+=$row['DesignAread'];//مجموع مساحت طرح های منبع اعتباری
        $belavals[$row['creditsourceID']][2]+=$row['belaavazd'];//مجموع بلاعوض طرح های منبع اعتباری
        $selfcashhelpval[$row['creditsourceID']][2]+=$row['selfcashhelpval'];//مجموع خودیاری نقدی طرح های منبع اعتباری
        $selfnotcashhelpval[$row['creditsourceID']][2]+=$row['selfnotcashhelpval'];//مجموع خودیاری غیر نقدی طرح های منبع اعتباری
    }
    else  if (in_array($row['applicantstatesIDd'],array("12","36","22","37")))//ارسال به صندوق یا بانک
    {
        $totalvals[$row['creditsourceID']][4]++;//تعداد طرح های منبع اعتباری
        $hekvals[$row['creditsourceID']][4]+=$row['DesignAread'];//مجموع مساحت طرح های منبع اعتباری
        $belavals[$row['creditsourceID']][4]+=$row['belaavazd'];//مجموع بلاعوض طرح های منبع اعتباری
        $selfcashhelpval[$row['creditsourceID']][4]+=$row['selfcashhelpval'];//مجموع خودیاری نقدی طرح های منبع اعتباری
        $selfnotcashhelpval[$row['creditsourceID']][4]+=$row['selfnotcashhelpval'];//مجموع خودیاری غیر نقدی طرح های منبع اعتباری
        if (in_array($row['applicantstatesIDd'],array("12","36")))//تکمیل تضامین
        {
            $totalvals[$row['creditsourceID']][5]++;//تعداد طرح های منبع اعتباری
            $hekvals[$row['creditsourceID']][5]+=$row['DesignAread'];//مجموع مساحت طرح های منبع اعتباری
            $belavals[$row['creditsourceID']][5]+=$row['belaavazd']; //مجموع بلاعوض طرح های منبع اعتباری
            $selfcashhelpval[$row['creditsourceID']][5]+=$row['selfcashhelpval']; //مجموع خودیاری نقدی طرح های منبع اعتباری
            $selfnotcashhelpval[$row['creditsourceID']][5]+=$row['selfnotcashhelpval']; //مجموع خودیاری غیر نقدی طرح های منبع اعتباری
        }
        else //انعقاد قرارداد
        {
            $totalvals[$row['creditsourceID']][6]++;//تعداد طرح های منبع اعتباری
            $hekvals[$row['creditsourceID']][6]+=$row['DesignAread'];//مجموع مساحت طرح های منبع اعتباری
            $belavals[$row['creditsourceID']][6]+=$row['belaavazd']; //مجموع بلاعوض طرح های منبع اعتباری
            $selfcashhelpval[$row['creditsourceID']][6]+=$row['selfcashhelpval'];//مجموع خودیاری نقدی طرح های منبع اعتباری  
            $selfnotcashhelpval[$row['creditsourceID']][6]+=$row['selfnotcashhelpval']; //مجموع خودیاری غیر نقدی طرح های منبع اعتباری
            if(!($row['applicantstatesIDop']>0))//درحال پیشنهاد قیمت
            {
                $totalvals[$row['creditsourceID']][7]++;//تعداد طرح های منبع اعتباری
                $hekvals[$row['creditsourceID']][7]+=$row['DesignAread'];//مجموع مساحت طرح های منبع اعتباری
                $belavals[$row['creditsourceID']][7]+=$row['belaavazd'];//مجموع بلاعوض طرح های منبع اعتباری
                $selfcashhelpval[$row['creditsourceID']][7]+=$row['selfcashhelpval'];//مجموع خودیاری نقدی طرح های منبع اعتباری
                $selfnotcashhelpval[$row['creditsourceID']][7]+=$row['selfnotcashhelpval'];//مجموع خودیاری غیر نقدی طرح های منبع اعتباری
            }       
            else if (in_array($row['applicantstatesIDop'],array("30","35","38")))//تایید نهایی پیشفاکتور و آزادسازی 
            {
                $totalvals[$row['creditsourceID']][9]++;//تعداد طرح های منبع اعتباری
                $hekvals[$row['creditsourceID']][9]+=$row['DesignAreaop'];//مجموع مساحت طرح های منبع اعتباری
                $belavals[$row['creditsourceID']][9]+=$row['belaavazop'];  //مجموع بلاعوض طرح های منبع اعتباری
                $selfcashhelpval[$row['creditsourceID']][9]+=$row['selfcashhelpval'];//مجموع خودیاری نقدی طرح های منبع اعتباری
                $selfnotcashhelpval[$row['creditsourceID']][9]+=$row['selfnotcashhelpval'];//مجموع خودیاری غیر نقدی طرح های منبع اعتباری 
                
                if ($row['permanentfree']==1 && $row['applicantstatesIDoplist']==45)//ـحویل دائم
                {
                    $totalvals[$row['creditsourceID']][13]++;//تعداد طرح های منبع اعتباری
                    $hekvals[$row['creditsourceID']][13]+=$row['DesignAreaoplist'];//مجموع مساحت طرح های منبع اعتباری
                    $belavals[$row['creditsourceID']][13]+=$row['belaavazoplist'];   //مجموع بلاعوض طرح های منبع اعتباری 
                    $selfcashhelpval[$row['creditsourceID']][13]+=$row['selfcashhelpval'];//مجموع خودیاری نقدی طرح های منبع اعتباری
                    $selfnotcashhelpval[$row['creditsourceID']][13]+=$row['selfnotcashhelpval'];//مجموع خودیاری غیر نقدی طرح های منبع اعتباری
                }
                else if ($row['applicantstatesIDoplist']==45)//ـحویل موقت
                {
                    $totalvals[$row['creditsourceID']][12]++;//تعداد طرح های منبع اعتباری
                    $hekvals[$row['creditsourceID']][12]+=$row['DesignAreaoplist'];//مجموع مساحت طرح های منبع اعتباری
                    $belavals[$row['creditsourceID']][12]+=$row['belaavazoplist'];   //مجموع بلاعوض طرح های منبع اعتباری
                    $selfcashhelpval[$row['creditsourceID']][12]+=$row['selfcashhelpval'];//مجموع خودیاری نقدی طرح های منبع اعتباری 
                    $selfnotcashhelpval[$row['creditsourceID']][12]+=$row['selfnotcashhelpval'];//مجموع خودیاری غیر نقدی طرح های منبع اعتباری 
                }
                else if ($row['applicantstatesIDop']==35)//آزادسازی ظرفیت
                {
                    $totalvals[$row['creditsourceID']][11]++;//تعداد طرح های منبع اعتباری
                    $hekvals[$row['creditsourceID']][11]+=$row['DesignAreaoplist'];//مجموع مساحت طرح های منبع اعتباری
                    $belavals[$row['creditsourceID']][11]+=$row['belaavazoplist']; //مجموع بلاعوض طرح های منبع اعتباری  
                    $selfcashhelpval[$row['creditsourceID']][11]+=$row['selfcashhelpval'];//مجموع خودیاری نقدی طرح های منبع اعتباری 
                    $selfnotcashhelpval[$row['creditsourceID']][11]+=$row['selfnotcashhelpval'];//مجموع خودیاری غیر نقدی طرح های منبع اعتباری 
                }
                else //درحال اجرا
                {
                    $totalvals[$row['creditsourceID']][10]++;//تعداد طرح های منبع اعتباری
                    $hekvals[$row['creditsourceID']][10]+=$row['DesignAreaop'];//مجموع مساحت طرح های منبع اعتباری
                    $belavals[$row['creditsourceID']][10]+=$row['belaavazop']; //مجموع بلاعوض طرح های منبع اعتباری  
                    $selfcashhelpval[$row['creditsourceID']][10]+=$row['selfcashhelpval'];//مجموع خودیاری نقدی طرح های منبع اعتباری 
                    $selfnotcashhelpval[$row['creditsourceID']][10]+=$row['selfnotcashhelpval'];//مجموع خودیاری غیر نقدی طرح های منبع اعتباری 
                }
                
            }
            else if ($row['applicantstatesIDop']==34)//انصراف از اجرا
            {
                $totalvals[$row['creditsourceID']][14]++;//تعداد طرح های منبع اعتباری
                $hekvals[$row['creditsourceID']][14]+=$row['DesignAreaop'];//مجموع مساحت طرح های منبع اعتباری
                $belavals[$row['creditsourceID']][14]+=$row['belaavazop']; //مجموع بلاعوض طرح های منبع اعتباری
                $selfcashhelpval[$row['creditsourceID']][14]+=$row['selfcashhelpval'];//مجموع خودیاری نقدی طرح های منبع اعتباری
                $selfnotcashhelpval[$row['creditsourceID']][14]+=$row['selfnotcashhelpval'];//مجموع خودیاری غیر نقدی طرح های منبع اعتباری
                
            }
                
            else//تهیه پیش فاکتور
            {
                $totalvals[$row['creditsourceID']][8]++;//تعداد طرح های منبع اعتباری
                $hekvals[$row['creditsourceID']][8]+=$row['DesignAread'];//مجموع مساحت طرح های منبع اعتباری
                $belavals[$row['creditsourceID']][8]+=$row['belaavazd']; //مجموع بلاعوض طرح های منبع اعتباری
                $selfcashhelpval[$row['creditsourceID']][8]+=$row['selfcashhelpval'];//مجموع خودیاری نقدی طرح های منبع اعتباری
                $selfnotcashhelpval[$row['creditsourceID']][8]+=$row['selfnotcashhelpval'];//مجموع خودیاری غیر نقدی طرح های منبع اعتباری
            }  
        }
    }
    else//تکمیل پرونده
    {
        $totalvals[$row['creditsourceID']][3]++;//تعداد طرح های منبع اعتباری
        $hekvals[$row['creditsourceID']][3]+=$row['DesignAread'];//مجموع مساحت طرح های منبع اعتباری
        $belavals[$row['creditsourceID']][3]+=$row['belaavazd']; //مجموع بلاعوض طرح های منبع اعتباری
        $selfcashhelpval[$row['creditsourceID']][3]+=$row['selfcashhelpval'];//مجموع خودیاری نقدی طرح های منبع اعتباری
        $selfnotcashhelpval[$row['creditsourceID']][3]+=$row['selfnotcashhelpval'];//مجموع خودیاری غیر نقدی طرح های منبع اعتباری
    }
    
    
    
}

$sql = "SELECT * from creditsource where ostan=substring('$login_CityId',1,2) 
order by credityear,creditsourceID";
    try 
      {		
        $result = mysql_query($sql);
      }
      //catch exception
      catch(Exception $e) 
      {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
      }
                              



?>


<!DOCTYPE html>
<html>
<head>
  	<title>گزارش عملکرد مالی و فیزیکی طرحهای  سیستم های نوین آبیاری</title>
	
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>


    <!-- /scripts -->
    
  <script type="text/javascript" src="../js/tablescroller.js"></script>
<script type="text/javascript">
var ts = new tablescroller('maintbl',true);
//alert(1);
</script>




 <script>
    function checkchange()//تابعی که بررسی می کند آیا تغییر صورت گرفته یا خیر
    {
        
        document.getElementById("sum1").innerHTML=0;
        document.getElementById("sum2").innerHTML=0;
        document.getElementById("sum3").innerHTML=0;
        document.getElementById("sum4").innerHTML=0;
        document.getElementById("sum5").innerHTML=0;
        document.getElementById("sum6").innerHTML=0;
        document.getElementById("sum7").innerHTML=0;
        document.getElementById("sum8").innerHTML=0;
        document.getElementById("sum9").innerHTML=0;
        document.getElementById("sum10").innerHTML=0;
        document.getElementById("sum11").innerHTML=0;
        document.getElementById("sum12").innerHTML=0;
        document.getElementById("sum13").innerHTML=0;
        document.getElementById("sum14").innerHTML=0;
        document.getElementById("sum15").innerHTML=0;
        document.getElementById("sum16").innerHTML=0;
        document.getElementById("sum17").innerHTML=0;
        document.getElementById("sum18").innerHTML=0;
        document.getElementById("sum19").innerHTML=0;
        document.getElementById("sum20").innerHTML=0;
        document.getElementById("sum21").innerHTML=0;
        document.getElementById("sum22").innerHTML=0;
        document.getElementById("sum23").innerHTML=0;
        document.getElementById("sum24").innerHTML=0;
        document.getElementById("sum25").innerHTML=0;
        for (var j=1;j<=(document.getElementById('rowcnt').value);j++)
            if (document.getElementById('chkr'+j).checked)
            {
                document.getElementById("sum1").innerHTML=document.getElementById("sum1").innerHTML*1+document.getElementById("val01"+j).innerHTML*1;
                document.getElementById("sum2").innerHTML=document.getElementById("sum2").innerHTML*1+document.getElementById("val02"+j).innerHTML*1;
                document.getElementById("sum3").innerHTML=document.getElementById("sum3").innerHTML*1+document.getElementById("val03"+j).innerHTML*1;
                document.getElementById("sum4").innerHTML=document.getElementById("sum4").innerHTML*1+document.getElementById("val04"+j).innerHTML*1;
                document.getElementById("sum5").innerHTML=document.getElementById("sum5").innerHTML*1+document.getElementById("val05"+j).innerHTML*1;
                document.getElementById("sum6").innerHTML=document.getElementById("sum6").innerHTML*1+document.getElementById("val06"+j).innerHTML*1;
                document.getElementById("sum7").innerHTML=document.getElementById("sum7").innerHTML*1+document.getElementById("val07"+j).innerHTML*1;
                document.getElementById("sum8").innerHTML=document.getElementById("sum8").innerHTML*1+document.getElementById("val08"+j).innerHTML*1;
                document.getElementById("sum9").innerHTML=document.getElementById("sum9").innerHTML*1+document.getElementById("val09"+j).innerHTML*1;
                document.getElementById("sum10").innerHTML=document.getElementById("sum10").innerHTML*1+document.getElementById("val10"+j).innerHTML*1;
                document.getElementById("sum11").innerHTML=document.getElementById("sum11").innerHTML*1+document.getElementById("val11"+j).innerHTML*1;
                document.getElementById("sum12").innerHTML=document.getElementById("sum12").innerHTML*1+document.getElementById("val12"+j).innerHTML*1;
                document.getElementById("sum13").innerHTML=document.getElementById("sum13").innerHTML*1+document.getElementById("val13"+j).innerHTML*1;
                document.getElementById("sum14").innerHTML=document.getElementById("sum14").innerHTML*1+document.getElementById("val14"+j).innerHTML*1;
                document.getElementById("sum15").innerHTML=document.getElementById("sum15").innerHTML*1+document.getElementById("val15"+j).innerHTML*1;
                document.getElementById("sum16").innerHTML=document.getElementById("sum16").innerHTML*1+document.getElementById("val16"+j).innerHTML*1;
                document.getElementById("sum17").innerHTML=document.getElementById("sum17").innerHTML*1+document.getElementById("val17"+j).innerHTML*1;
                document.getElementById("sum18").innerHTML=document.getElementById("sum18").innerHTML*1+document.getElementById("val18"+j).innerHTML*1;
                document.getElementById("sum19").innerHTML=document.getElementById("sum19").innerHTML*1+document.getElementById("val19"+j).innerHTML*1;
                document.getElementById("sum20").innerHTML=document.getElementById("sum20").innerHTML*1+document.getElementById("val20"+j).innerHTML*1;
                document.getElementById("sum21").innerHTML=document.getElementById("sum21").innerHTML*1+document.getElementById("val21"+j).innerHTML*1;
                document.getElementById("sum22").innerHTML=document.getElementById("sum22").innerHTML*1+document.getElementById("val22"+j).innerHTML*1;
                document.getElementById("sum23").innerHTML=document.getElementById("sum23").innerHTML*1+document.getElementById("val23"+j).innerHTML*1;
                document.getElementById("sum24").innerHTML=document.getElementById("sum24").innerHTML*1+document.getElementById("val24"+j).innerHTML*1;
                document.getElementById("sum25").innerHTML=document.getElementById("sum25").innerHTML*1+document.getElementById("val25"+j).innerHTML*1;
                
            }
        document.getElementById("sum1").innerHTML=Math.round(document.getElementById("sum1").innerHTML*10)/10;
        document.getElementById("sum2").innerHTML=Math.round(document.getElementById("sum2").innerHTML*10)/10;
        document.getElementById("sum3").innerHTML=Math.round(document.getElementById("sum3").innerHTML*10)/10;
        document.getElementById("sum4").innerHTML=Math.round(document.getElementById("sum4").innerHTML*10)/10;
        document.getElementById("sum5").innerHTML=Math.round(document.getElementById("sum5").innerHTML*10)/10;
        document.getElementById("sum6").innerHTML=Math.round(document.getElementById("sum6").innerHTML*10)/10;
        document.getElementById("sum7").innerHTML=Math.round(document.getElementById("sum7").innerHTML*10)/10;
        document.getElementById("sum8").innerHTML=Math.round(document.getElementById("sum8").innerHTML*10)/10;
        document.getElementById("sum9").innerHTML=Math.round(document.getElementById("sum9").innerHTML*10)/10;
        document.getElementById("sum10").innerHTML=Math.round(document.getElementById("sum10").innerHTML*10)/10;
        document.getElementById("sum11").innerHTML=Math.round(document.getElementById("sum11").innerHTML*10)/10;
        document.getElementById("sum12").innerHTML=Math.round(document.getElementById("sum12").innerHTML*10)/10;
        document.getElementById("sum13").innerHTML=Math.round(document.getElementById("sum13").innerHTML*10)/10;
        document.getElementById("sum14").innerHTML=Math.round(document.getElementById("sum14").innerHTML*10)/10;
        document.getElementById("sum15").innerHTML=Math.round(document.getElementById("sum15").innerHTML*10)/10;
        document.getElementById("sum16").innerHTML=Math.round(document.getElementById("sum16").innerHTML*10)/10;
        document.getElementById("sum17").innerHTML=Math.round(document.getElementById("sum17").innerHTML*10)/10;
        document.getElementById("sum18").innerHTML=Math.round(document.getElementById("sum18").innerHTML*10)/10;
        document.getElementById("sum19").innerHTML=Math.round(document.getElementById("sum19").innerHTML*10)/10;
        document.getElementById("sum20").innerHTML=Math.round(document.getElementById("sum20").innerHTML*10)/10;
        document.getElementById("sum21").innerHTML=Math.round(document.getElementById("sum21").innerHTML*10)/10;
        document.getElementById("sum22").innerHTML=Math.round(document.getElementById("sum22").innerHTML*10)/10;
        document.getElementById("sum23").innerHTML=Math.round(document.getElementById("sum23").innerHTML*10)/10;
        document.getElementById("sum24").innerHTML=Math.round(document.getElementById("sum24").innerHTML*10)/10;
        document.getElementById("sum25").innerHTML=Math.round(document.getElementById("sum25").innerHTML*10)/10;
                

	}
    
    
    </script>
  
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
           
            <form action="allapplicantquotasandugh.php" method="post">
                   
           <table align='center' id='maintbl' name='maintbl' class="page" border='1'>              
            <tbody>
                      <?php  
					  $titr='بلاعوض';
                         print "<td colspan='5' class='label'>جمع مبلغ بلاعوض و خودیاری(تلفیقی)</td>
                                <td class='data'><input name='showm' type='checkbox' id='showm'";
                                if ($showm>0) {echo 'checked';$titr='تلفیقی';}
                                 print " /></td>";
					   ?>
                   	  <?php if ($login_RolesID!=16 && $login_RolesID!=7){ 
								 
                         print "<td colspan='2' class='label'>بانک</td>
                                <td class='data'><input name='showb' type='checkbox' id='showb'";
                                if ($showb>0) echo 'checked';
                                 print " /></td>";
                         print "<td colspan='2' class='label'>صندوق</td>
                                <td class='data'><input name='shows' type='checkbox' id='shows'";
                                if ($shows>0) echo 'checked';
                                 print " /></td>";
								 }
                                 
   					$checked="";
                    if ($showc>0) $checked="checked";
                    print "<td colspan='1' class='label'>تجمیع</td>
                     <td class='data'><input name='showc' type='checkbox' id='showc' $checked /></td>";
                      ?>
                   
				   
				   
                      <td colspan="3"><input    name="submit" type="submit" class="button" id="submit" size="16" value="جستجو" /></td>   
              <tr> 
			  
			  
                <td colspan="28"
                <span class="f14_fontcb" > گزارش عملكرد فيزيكي و مالي طرح توسعه آبياري تحت فشار <?php  print $stTitle.' تا '.gregorian_to_jalali(date('Y-m-d'))."(مبالغ به میلیون ریال) <br>";?></span>  </td>
 			  </tr>
       
              <tr>
                 <?php 
                          
                          
                            echo "
                            <tr>
                            <th rowspan=\"3\"class=\"f10_fontb\" >ردیف</th>
                            <th rowspan=\"3\"class=\"f13_fontb\">اعتبارات</th>
                            <th rowspan=\"1\"colspan=\"4\"class=\"f13_fontb\" >اعتبار</th>
	    					<th rowspan=\"1\"colspan=\"12\"class=\"f13_fontb\" >عملکرد فیزیکی </th>
  	                        <th rowspan=\"1\"colspan=\"10\" class=\"f13_fontb\" >عملکرد مالی</th>
             				</tr>
                        
                            <tr>
                            <th  rowspan=\"2\"class=\"f13_fontb\" > شماره قرارداد</th>
                            <th  rowspan=\"2\"class=\"f13_fontb\" >مبلغ قرارداد</th>
							<th  rowspan=\"2\"class=\"f13_fontb\" >مبلغ واریزی</th>
							<th  rowspan=\"2\"class=\"f13_fontb\" >خالص واریز با کسر%1</th>
														
							<th  colspan=\"3\" class=\"f13_fontb\" >طرحهای معرفی شده</th>
							<th  colspan=\"5\" class=\"f13_fontb\" > قرارداد منعقد شده با متقاضی</th>
							<th  colspan=\"3\" class=\"f13_fontb\" > در حال اجرا</th>
							
							<th  rowspan=\"2\" class=\"f13_fontb\" >سطح در حال اجرا</th>
							<th  rowspan=\"2\" class=\"f13_fontb\" >سطح اجرا شده</th>
							
							<th  colspan=\"3\" class=\"f13_fontb\" >طرحهای فی الحال شده</th>
							
							<th  rowspan=\"2\"class=\"f13_fontb\" >$titr پرداخت شده</th>
                            <th  rowspan=\"2\"class=\"f13_fontb\" >$selftitle3</th>
                            <th  rowspan=\"2\"class=\"f13_fontb\" >$titr در تعهد</th>
                            <th  rowspan=\"2\"class=\"f13_fontb\" >سپرده حسن انجام کار</th>
                            <th  rowspan=\"2\"class=\"f13_fontb\" >مبلغ برگشتی به خزانه</th>
                            <th  rowspan=\"2\"class=\"f13_fontb\" >مانده عقد قراداد نشده</th>
                            
							

                        </tr>
		
					 <tr>
							<th class=\"f10_fontb\">تعداد</th>
                            <th class=\"f10_fontb\" >سطح (ha)</th>
                            <th class=\"f10_fontb\">مبلغ $titr</th>
                            
							<th class=\"f10_fontb\">تعداد</th>
                            <th class=\"f10_fontb\" >سطح (ha)</th>
                            <th class=\"f10_fontb\">مبلغ $titr</th>
                            <th class=\"f10_fontb\">$selftitle1</th>
                            <th class=\"f10_fontb\">$selftitle2</th>
                            
							<th class=\"f10_fontb\">تعداد</th>
                            <th class=\"f10_fontb\" >سطح (ha)</th>
                            <th class=\"f10_fontb\">مبلغ $titr</th>
                            
                            <th class=\"f10_fontb\" >سطح (ha)</th>
							<th class=\"f10_fontb\">$titr قابل وصول</th>
                            <th class=\"f10_fontb\">$titr مانده</th>
                            

                        </tr>";
                        
                          ?>
                            
                            
                        </tr>
                        
                     
                        
                   <?php
                   $rown=0;
                   $sum1=0;
                   $sum2=0;
                   $sum3=0;
                   $sum4=0;
                   $sum5=0;
                   $sum6=0;
                   $sum7=0;
                   $sum8=0;
                   $sum9=0;
                   $sum10=0;
                   $sum11=0;
                   $sum12=0;
                   $sum13=0;
                   $sum14=0;
                   $sum15=0;
				   $sum16=0;
                   $sum17=0;
                   $sum18=0;
                   $sum19=0;
                   $sum20=0;
				   $sum21=0;
                   $i=0;
				   
				   
                    while($row = mysql_fetch_assoc($result)){     
                    //$login_RolesID 17 ناظر مقیم  
                        if ($login_RolesID=='17' && $row['CityId']<>substr($login_CityId,0,4) ) 
						continue;
                    //$login_RolesID 14 ناظر عالی
						if ($login_RolesID=='14' && $row['ClerkIDExcellentSupervisor']<>$login_userid ) 
						continue;
							
     			 if ($showm==1){
     			    //بلاعوض
					$belaavaz=$belavals[$row['creditsourceID']][4]+$selfcashhelpval[$row['creditsourceID']][4]+$selfnotcashhelpval[$row['creditsourceID']][4];
					$belaavaz2=$belavals[$row['creditsourceID']][6];
					$belaavaz3=$belavals[$row['creditsourceID']][9]+$selfcashhelpval[$row['creditsourceID']][9]+$selfnotcashhelpval[$row['creditsourceID']][9];
					//$resum=$row['selfhelpval2'];
					
				  } else {
				    //بلاعوض
					$belaavaz=$belavals[$row['creditsourceID']][4];
					$belaavaz2=$belavals[$row['creditsourceID']][6];
					$belaavaz3=$belavals[$row['creditsourceID']][9];
					$resum=0;
				  }					
 
                if ($login_RolesID=='16' || $shows==1 && $showb==0)//صندوق
                { 
				   $contractNo=$row['contractNo'];//شماره قرارداد
				   $contractFee=$row['contractFee'];//کل هزینه قرارداد
				   $contractFree=$row['contractFree'];//هزینه  قرارداد
				   $contractFreenet=$row['contractFreenet'];//هزینه خالص قرارداد
				   $contractFeereturn=$row['contractFeereturn'];//بازگشتی مبلغ قراداد
					}					
				 else if ($login_RolesID=='7' || $showb==1 && $shows==0)//بانک
                 { 
				   $contractNo=$row['contractNoB'];//شماره قرارداد
				   $contractFee=$row['contractFeeB'];//کل هزینه قرارداد
				   $contractFree=$row['contractFreeB'];//هزینه  قرارداد
				   $contractFreenet=$row['contractFreenetB'];//هزینه خالص قرارداد
				   $contractFeereturn=$row['contractFeereturnB'];//بازگشتی مبلغ قراداد
			    	}
				 else  if ($showb=='1' && $shows=='1'||$showb==0 && $shows==0  )//سایرین  
                 {  
				   $contractNo=' ('.$row['contractNoB'].') ('.$row['contractNo'].')';//شماره قرارداد
				   $contractFee=$row['contractFeeB']+$row['contractFee'];//کل هزینه قرارداد
				   $contractFree=$row['contractFreeB']+$row['contractFree'];//هزینه  قرارداد
				   $contractFreenet=$row['contractFreenetB']+$row['contractFreenet'];//هزینه خالص قرارداد
			 	   $contractFeereturn=$row['contractFeereturnB']+$row['contractFeereturn'];//بازگشتی مبلغ قراداد
			       }
						
					
						
                        $rown++;
                        if ($rown%2==1) 
                        $b=''; else $b='b';
						
						$Comment[$i]=$row["contractComment"];//توضیحات قرارداد
						$i++;
								
						
						
						?>

                        <tr>    
                
                            <td <span class="f10_font<?php echo $b; ?>"  >  <?php echo $rown; ?>
                            
                            <input type='checkbox' id='chkr<?php echo $rown; ?>' name='chkr<?php echo $rown; ?>' checked onChange="checkchange();"></input>
                             </span>  
                            
                            </td>
							<td <span class="f10_font<?php echo $b; ?>">  <?php echo $row['Title'];//عنوان قرارداد ?> </span> </td>
                            <td	<span class="f10_font<?php echo $b; ?>">  <?php  echo $contractNo;//شماره قرارداد  ?> </span> </td>
                            <td class="f10_font<?php echo $b; ?>">
                                <div id='val01<?php echo $rown; ?>' name='val01<?php echo $rown; ?>'>
                                    <?php $sum1=$sum1+$contractFee/1000000 ; echo $contractFee/1000000; ?>
                                </div>
                            </td>	
                            
                            
                            
                            
                           	<td	<span id='val02<?php echo $rown; ?>' name='val02<?php echo $rown; ?>'
                               class="f10_font<?php $sum2=$sum2+$contractFree/1000000;echo $b; ?>">  <?php  echo $contractFree/1000000;  ?> </span> </td>
                           	<td <span id='val03<?php echo $rown; ?>' name='val03<?php echo $rown; ?>' class="f10_font<?php $sum3=$sum3+$contractFreenet/1000000;echo $b; ?>">  <?php echo $contractFreenet/1000000; ?> </span> </td>
                        	
                            
						    <td	<span id='val04<?php echo $rown; ?>' name='val04<?php echo $rown; ?>' class="f10_font<?php $sum4=$sum4+$totalvals[$row['creditsourceID']][4];echo $b ?>">  <?php  echo $totalvals[$row['creditsourceID']][4];   ?> </span> </td>
                            <td	<span id='val05<?php echo $rown; ?>' name='val05<?php echo $rown; ?>' class="f10_font<?php $sum5=$sum5+($hekvals[$row['creditsourceID']][4]);echo $b ?>"><?php  echo round($hekvals[$row['creditsourceID']][4]); ?> </span> </td>
                            <td	<span id='val06<?php echo $rown; ?>' name='val06<?php echo $rown; ?>' class="f10_font<?php $sum6=$sum6+round($belaavaz,1);echo $b ?>"> <?php echo   round($belaavaz,1);  ?> </span> </td>
                        
						    <td	<span id='val07<?php echo $rown; ?>' name='val07<?php echo $rown; ?>' class="f10_font<?php $sum7=$sum7+$totalvals[$row['creditsourceID']][6];echo $b ?>">  <?php  echo $totalvals[$row['creditsourceID']][6];   ?> </span> </td>
                            <td	<span id='val08<?php echo $rown; ?>' name='val08<?php echo $rown; ?>' class="f10_font<?php $sum8=$sum8+($hekvals[$row['creditsourceID']][6]);echo $b ?>"><?php echo round($hekvals[$row['creditsourceID']][6]);  ?> </span> </td>
                            <td	<span id='val09<?php echo $rown; ?>' name='val09<?php echo $rown; ?>' class="f10_font<?php $sum9=$sum9+round($belaavaz2,1);echo $b ?>"> <?php echo  round($belaavaz2,1);?> </span> </td>
                            <td	<span id='val10<?php echo $rown; ?>' name='val10<?php echo $rown; ?>' class="f10_font<?php $sum10=$sum10+$selfcashhelpval[$row['creditsourceID']][6];echo $b ?>"> <?php echo  $selfcashhelpval[$row['creditsourceID']][6];?> </span> </td>
                            <td	<span id='val11<?php echo $rown; ?>' name='val11<?php echo $rown; ?>' class="f10_font<?php $sum11=$sum11+$selfnotcashhelpval[$row['creditsourceID']][6];echo $b ?>"> <?php echo  $selfnotcashhelpval[$row['creditsourceID']][6];?> </span> </td>
            				
							
						    <td	<span id='val12<?php echo $rown; ?>' name='val12<?php echo $rown; ?>' class="f10_font<?php $sum12=$sum12+$totalvals[$row['creditsourceID']][9];echo $b ?>">  <?php  echo $totalvals[$row['creditsourceID']][9];   ?> </span> </td>
                            <td	<span id='val13<?php echo $rown; ?>' name='val13<?php echo $rown; ?>'  class="f10_font<?php $sum13=$sum13+($hekvals[$row['creditsourceID']][9]);echo $b ?>"><?php  echo round($hekvals[$row['creditsourceID']][9]);  ?> </span> </td>
                            <td	<span id='val14<?php echo $rown; ?>' name='val14<?php echo $rown; ?>'  class="f10_font<?php $sum14=$sum14+round($belaavaz3,1);echo $b ?>"> <?php echo round($belaavaz3,1);?> </span> </td>
            				
							
							<td	<span id='val15<?php echo $rown; ?>' name='val15<?php echo $rown; ?>'  class="f10_font<?php $sum15=$sum15+($hekvals[$row['creditsourceID']][10]);echo $b ?>"><?php  echo ($hekvals[$row['creditsourceID']][10]);  ?> </span> </td>
                            <td	<span id='val16<?php echo $rown; ?>' name='val16<?php echo $rown; ?>'  class="f10_font<?php $sum16=$sum16+($hekvals[$row['creditsourceID']][11]+$hekvals[$row['creditsourceID']][12]+$hekvals[$row['creditsourceID']][13]);echo $b ?>"><?php  echo ($hekvals[$row['creditsourceID']][11]+$hekvals[$row['creditsourceID']][12]+$hekvals[$row['creditsourceID']][13]);  ?> </span> </td>
                            
					        <td	<span id='val17<?php echo $rown; ?>' name='val17<?php echo $rown; ?>'  class="f10_font<?php $sum17;echo $b ?>"><?php  echo '';  ?> </span> </td>
                            <td	<span id='val18<?php echo $rown; ?>' name='val18<?php echo $rown; ?>'  class="f10_font<?php $sum18;echo $b ?>"> <?php echo '';    ?> </span> </td>
            		        <td	<span id='val19<?php echo $rown; ?>' name='val19<?php echo $rown; ?>'  class="f10_font<?php $sum19;echo $b ?>"> <?php echo '';    ?> </span> </td>
            				
			                <td	<span id='val20<?php echo $rown; ?>' name='val20<?php echo $rown; ?>'  class="f10_font<?php $sum20=$sum20+floor($belaavaz3);echo $b ?>"> <?php echo   floor($belaavaz3);    ?> </span> </td>
			                <td	<span id='val21<?php echo $rown; ?>' name='val21<?php echo $rown; ?>'  class="f10_font<?php $sum21=0;echo $b ?>"> <?php echo   ""    ?> </span> </td>
			                <td	<span id='val22<?php echo $rown; ?>' name='val22<?php echo $rown; ?>'  class="f10_font<?php $sum22=$sum22+floor($belaavaz2)-floor($belaavaz3)+floor($row['selfhelpval3']);echo $b ?>"> <?php echo   floor($belaavaz2)-floor($belaavaz3)+floor($row['selfhelpval3']);    ?> </span> </td>
            								
			                <td	<span id='val23<?php echo $rown; ?>' name='val23<?php echo $rown; ?>'  class="f10_font<?php $sum23=$sum23+floor($row['Fehrestbaha']);;echo $b ?>"> <?php echo  floor($row['Fehrestbaha']);    ?> </span> </td>
							
							<td	<span id='val24<?php echo $rown; ?>' name='val24<?php echo $rown; ?>'  class="f10_font<?php $sum24=$sum24+floor(($contractFeereturn/100000)/10);echo $b ?>"> <?php echo floor(($contractFeereturn/100000)/10);    ?> </span> </td>
							<td	<span id='val25<?php echo $rown; ?>' name='val25<?php echo $rown; ?>'  class="f10_font<?php $sum25=$sum25+floor(($contractFreenet/100000)/10)-floor(($contractFeereturn/100000)/10)-floor($belaavaz2)+floor($resum); echo $b ?>"> <?php echo   floor(($contractFreenet/100000)/10)-floor(($contractFeereturn/100000)/10)-floor($belaavaz2)+floor($resum);    ?> </span> </td>
							
                        </tr>
				<?php }  ?>                    
                       
					<tr> 
                      <td colspan="20" <span class="f14_font<?php echo $b; ?>"  >  <?php echo " "; $b='b';?> </span>  </td>
					</tr>    
                    <tr>
                            <td colspan="3" rowspan="2"<span class="f14_font<?php echo $b; ?>"  >  <?php echo "مجموع"; ?> </span>  </td>
						    <td	rowspan="2"<span  id='sum1' name='sum1' class="f13_font<?php echo $b; ?>">  <?php echo round($sum1,1);  ?> </span> </td>
                        	<td colspan="2" <span  id='sum2' name='sum2' class="f131_font<?php echo $b; ?>">  <?php echo round($sum2,1); ?> </span> </td>
                            <td	rowspan="2"<span  id='sum4' name='sum4' class="f13_font<?php echo $b; ?>">  <?php echo round($sum4,1); ?> </span> </td>
                            <td	colspan="2"<span  id='sum5' name='sum5' class="f131_font<?php echo $b; ?>">  <?php echo round($sum5,1); ?> </span> </td>
                            <td	rowspan="2"<span  id='sum7' name='sum7' class="f13_font<?php echo $b; ?>">  <?php echo round($sum7,1); ?> </span> </td>
                            <td colspan="2"<span  id='sum8' name='sum8' class="f131_font<?php echo $b; ?>">  <?php echo round($sum8,1); ?> </span> </td>
                            <td colspan="2"<span  id='sum10' name='sum10' class="f131_font<?php echo $b; ?>">  <?php echo round($sum10,1); ?> </span> </td>
                            <td rowspan="2"<span  id='sum12' name='sum12' class="f13_font<?php echo $b; ?>">  <?php echo round($sum12,1); ?> </span> </td>
                            <td colspan="2"<span  id='sum13' name='sum13' class="f131_font<?php echo $b; ?>">  <?php echo round($sum13,1); ?> </span> </td>
                            <td colspan="2"<span  id='sum15' name='sum15' class="f131_font<?php echo $b; ?>">  <?php echo round($sum15,1); ?> </span> </td>	
                            <td rowspan="2"<span  id='sum17' name='sum17' class="f13_font<?php echo $b; ?>">  <?php echo round($sum17,1); ?> </span> </td>      
							<td colspan="2"<span  id='sum18' name='sum18' class="f131_font<?php echo $b; ?>">  <?php echo round($sum18,1); ?> </span> </td>
                            <td colspan="2"<span  id='sum20' name='sum20' class="f131_font<?php echo $b; ?>">  <?php echo round($sum20,1); ?> </span> </td>
                            <td colspan="2"<span  id='sum22' name='sum22' class="f131_font<?php echo $b; ?>">  <?php echo round($sum22,1); ?> </span> </td>
                            <td colspan="2"<span  id='sum24' name='sum24' class="f131_font<?php echo $b; ?>">  <?php echo round($sum24,1); ?> </span> </td>
                        </tr>
                        <tr>
						   <td	colspan="2" <span  id='sum3' name='sum3' class="f132_font<?php echo $b; ?>">  <?php echo round($sum3,1); ?> </span> </td>
						   <td colspan="2"<span  id='sum6' name='sum6' class="f132_font<?php echo $b; ?>">  <?php echo round($sum6,1); ?> </span> </td>
                           <td	colspan="2"<span  id='sum9' name='sum9' class="f132_font<?php echo $b; ?>">  <?php echo round($sum9,1); ?> </span> </td>
                           <td colspan="2"<span  id='sum11' name='sum11' class="f132_font<?php echo $b; ?>">  <?php echo round($sum11,1); ?> </span> </td>	
                           <td colspan="2"<span  id='sum14' name='sum14' class="f132_font<?php echo $b; ?>">  <?php echo round($sum14,1); ?> </span> </td>	
                           <td colspan="2"<span  id='sum16' name='sum16' class="f132_font<?php echo $b; ?>">  <?php echo round($sum16,1); ?> </span> </td>
                           <td colspan="2"<span  id='sum19' name='sum19' class="f132_font<?php echo $b; ?>">  <?php echo round($sum19,1); ?> </span> </td>
                           <td colspan="2"<span  id='sum21' name='sum21' class="f132_font<?php echo $b; ?>">  <?php echo round($sum21,1); ?> </span> </td>
                           <td colspan="2"<span  id='sum23' name='sum23' class="f132_font<?php echo $b; ?>">  <?php echo round($sum23,1); ?> </span> </td>
                           <td colspan="2"<span  id='sum25' name='sum25' class="f132_font<?php echo $b; ?>">  <?php echo round($sum25,1); ?> </span> </td>
                            
						</tr>

						

						
						
						 <tr> <td colspan="25" > <?php echo '&nbsp'; ?>  </td> </tr>
   					     <tr> <td colspan="25" > <?php echo '&nbsp'; ?>  </td> </tr>
   				     	 <tr> <td colspan="25" > <?php echo '&nbsp'; ?>  </td> </tr>
						<tr> <td colspan="25" > <?php echo '&nbsp'; ?>  </td> </tr>
						<tr> <td colspan="25" > <?php echo '&nbsp'; ?>  </td> </tr>
						
						 
				         <tr> 
            				<td colspan="10" style="text-align:center"> <?php echo "مدیر عامل/مدیراعتبارات"; ?>  </td>
                            <td colspan="5"> <?php echo ""; ?>  </td>
					        <td colspan="10" style="text-align:center"> <?php echo "مدیرآب و خاک و امور فنی ومهندسی"; ?>  </td>
					     </tr>
   					
					     <tr> 
		                    <td colspan="10" style="text-align:center"> <?php echo $stTitle; ?>   </td>
					        <td colspan="5"> <?php echo ""; ?>  </td>
			                <td colspan="10" style="text-align:center" > <?php echo "سازمان جهاد کشاورزی استان"; ?>   </td>
				        </tr>
				    
						
						 <tr> <td colspan="25" > <?php echo '&nbsp'; ?>  </td> </tr>
   					     <tr> <td colspan="25" > <?php echo '&nbsp'; ?>  </td> </tr>
   				     	 <tr> <td colspan="25" > <?php echo '&nbsp'; ?>  </td> </tr>
						<tr> <td colspan="25" > <?php echo '&nbsp'; ?>  </td> </tr>
						<tr> <td colspan="25" > <?php echo '&nbsp'; ?>  </td> </tr>
					    
                   		<?php  for($x=0;$x<$i;$x++){ ?>
						<tr> 
		                    <td colspan="1" <span class="f7_font">  <?php echo ''; ?> </span> </td>
         					<td colspan="11" <span class="f7_font">  <?php echo($Comment[$x]); ?> </span> </td>
                            <td colspan="1" <span class="f7_font">  <?php echo ''; ?> </span> </td>
      					    <td colspan="11" <span class="f7_font">  <?php $x=$x+2; echo($Comment[$x]); ?> </span> </td>
						    <td colspan="1" <span class="f7_font">  <?php echo ''; ?> </span> </td>
                           	
                        </tr>
					    
			   		    <?php } ?>

	                        <INPUT type="hidden" id="rowcnt" value="<?php print $rown; ?>"/>
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

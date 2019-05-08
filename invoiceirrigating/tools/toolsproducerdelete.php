<?php
/*
tools/toolsproducerdelete.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود

*/
include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');


if ($login_Permission_granted==0) header("Location: ../login.php");



$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
$linearray = explode('_',$ids);
$toolsmarksID=$linearray[0];
$type=$linearray[1];
if ($type==1)
{
        $query = " DELETE FROM toolsmarks WHERE toolsmarksid='$toolsmarksID'
               and toolsmarksid not in (
               select toolsmarksid from invoicedetail  union all 
               select toolsmarksid from pricelistdetail union all 
               select toolsmarksid from toolspref union all 
               select ToolsMarksIDpriceref from toolspref union all
               select toolsmarksid from primarypricelistdetail where Price>0 );";
                              
    $result = mysql_query($query);
    
    $query = " DELETE FROM primarypricelistdetail WHERE toolsmarksid not in (select toolsmarksid from toolsmarks )  ;";
    $result = mysql_query($query);    
}
else if ($type==2)
{
    $query = "update toolsmarks set hide=mod((ifnull(hide,0)+1),2) WHERE toolsmarksid='$toolsmarksID'  ;";
    $result = mysql_query($query);  
    //print $query;exit;
}


    
    header("Location: ".$_SERVER["HTTP_REFERER"]);
                                            
                            
?>

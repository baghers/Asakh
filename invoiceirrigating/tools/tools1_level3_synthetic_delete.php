<?php
/*
tools/tools1_level3_synthetic_delete.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools1_level3_synthetic.php
*/

include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');




if ($login_Permission_granted==0) header("Location: ../login.php");

    $linearray = explode('_',substr($_GET["uid"],40,strlen($_GET["uid"])-45));
    $gadget3syntheticID=$linearray[0];//شناسه  جدول سطح 3 ابزار ترکیبی
    $Gadget3IDmaster=$linearray[1];//شناسه جدول سطح سوم ابزار
        
        /*
           gadget3 جدول سطح سوم ابزار
           gadget3id شناسه جدول سطح سوم ابزار
           toolsmarks جدول ابزار مارک که دارای ستون های ارتباطی زیر می باشد
                ابزار و مارک از ترکیب سناسه طرح، شناسه تولیدکننده و شناسه مارک تشکیل می شود
                gadget3ID شناسه سطح 3 ابزار
                ProducersID شناسه جدول تولیدکننده
                MarksID شناسه جدول مارک
           toolsmarksid شناسه ابزار و مارک
           invoicedetail جدول ریز آیتم های پیش فاکتور
    */ 
    
    $query = "SELECT count(*) cnt FROM invoicedetail
    inner join toolsmarks on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID
    inner join gadget3 on gadget3.Gadget3ID=toolsmarks.Gadget3ID
     WHERE gadget3.Gadget3ID='$Gadget3IDmaster'";
      try 
								  {		
									     $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
								  } 
    
    $resquery = mysql_fetch_assoc($result);
    //print $query;
    //exit;
    if (($resquery["cnt"])>0) 
    {
        print " این مقدار در جداول زیر گردش دارد "."پیش فاکتورها";
        exit;
    }
    
          
    
    $query = " DELETE FROM gadget3synthetic WHERE gadget3syntheticID=$gadget3syntheticID ;";
    try 
								  {		
									     mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
								  }
    
    header("Location: tools1_level3_synthetic.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget3IDmaster.rand(10000,99999));
                                            
                            
?>

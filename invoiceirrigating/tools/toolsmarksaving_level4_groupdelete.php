<?php 
/*
tools/toolsmarksaving_level4_groupdelete.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/toolsmarksaving_level4_list.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");


    $Gadget3IDProducersID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);

    if (substr($Gadget3IDProducersID,0,2)=='0,')
        $Gadget3IDProducersID=substr($Gadget3IDProducersID,2);
    
    $Gadget3ID='0';
    $ProducersID='';// شناسه تولید کننده
        
    $alllinearray = explode(',',$Gadget3IDProducersID);   
    foreach ($alllinearray as $value) 
    {
        $linearray = explode('_',$value);
        $Gadget3ID.=','.$linearray[0];//جدول سطح سوم ابزار
        $ProducersID=$linearray[1];// شناسه تولید کننده
    }
    
    if (substr($Gadget3ID,0,2)=='0,')
        $Gadget3ID=substr($Gadget3ID,2);
        /*
                toolsmarks جدول ابزار مارک که دارای ستون های ارتباطی زیر می باشد
            ابزار و مارک از ترکیب سناسه طرح، شناسه تولیدکننده و شناسه مارک تشکیل می شود
            gadget3ID شناسه سطح 3 ابزار
            ProducersID شناسه جدول تولیدکننده
            MarksID شناسه جدول مارک
        gadget3 جدول سطح سوم ابزار
        */
    $query = " DELETE FROM toolsmarks WHERE ProducersID=$ProducersID and Gadget3ID in ($Gadget3ID) 
               and toolsmarksid not in (select toolsmarksid from invoicedetail  union all select toolsmarksid from pricelistdetail union all 
               select toolsmarksid from toolspref union all 
                select ToolsMarksIDpriceref from toolspref)  ;";
             try 
								  {		
									    $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
								  }
    
    
    //print $query;
    //exit;    
    
    $query = "SELECT Gadget2ID FROM gadget3 WHERE Gadget3ID  in ('$Gadget3ID')";
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
	$Gadget2ID = $resquery["Gadget2ID"];
    
    header("Location: toolsmarksaving_level4_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget2ID.'_'.$ProducersID.rand(10000,99999));
                                            
?>
            
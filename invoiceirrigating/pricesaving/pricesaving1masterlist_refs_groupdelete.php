<?php 

/*
pricesaving/pricesaving1masterlist_refs_groupdelete.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
pricesaving/pricesaving1masterlist_refs.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");
    $toolsmarksIDPriceListMasterID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);//شناسه مارک ابزار و لیست قیمت
    $codes=$_GET["codes"];//کدها جهت حذف

    if (substr($toolsmarksIDPriceListMasterID,0,2)=='0,')
        $toolsmarksIDPriceListMasterID=substr($toolsmarksIDPriceListMasterID,2);
    
    $toolsmarksID='0';
    $PriceListMasterID='';
        
    $alllinearray = explode(',',$toolsmarksIDPriceListMasterID);   
    foreach ($alllinearray as $value) 
    {
        $linearray = explode('_',$value);
        $toolsmarksID.=','.$linearray[0];//شناسه مارک ابزا
        $PriceListMasterID=$linearray[1];// لیست قیمت
    }
    
    //print $PriceListMasterID;
    //exit;
    $PriceListMasterID=$_GET["pid"];
    
        $linearray = explode('-',$codes);
        $currpage=$linearray[0];//شماره صفحه فعلی
        $currg2id=$linearray[1];//شناسه جدول سطح دوم ابزار
        $currpid=$linearray[2];//شناسه تولیدکننده
        $currmid=$linearray[3];//مارک
        $showzero=$linearray[4];//نمایش مبالغ صفر
        $shownzero=$linearray[5];//نمایش مبالغ غیرصفر
        /*
       toolsmarks جدول ابزار و مارک
       toolsmarksid شناسه ابزار و مارک
       invoicedetail ریز پیش فاکتورها
       toolsmarksid شناسه ابزار و مارک
       toolspref جدول مرجع قیمتی
       */
       
        $query = " DELETE FROM toolsmarks WHERE toolsmarksid in ($toolsmarksID)
               and toolsmarksid not in (
               select toolsmarksid from invoicedetail  union all 
               select toolsmarksid from toolspref union all 
               select ToolsMarksIDpriceref from toolspref );";
    
    //print $query;
    //exit;
    
    
	          	  	 	try 
								  {		
									  	 $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
    //pricelistdetail لیست قیمت تایید شده
    $query = " DELETE FROM pricelistdetail WHERE toolsmarksid not in (select toolsmarksid from toolsmarks )  ;";
              	  	 	try 
								  {		
									  	 $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    //primarypricelistdetail لیت قیمت اولیه تولیدکنندگان
    $query = " DELETE FROM primarypricelistdetail WHERE toolsmarksid not in (select toolsmarksid from toolsmarks )  ;";
              	  	 	try 
								  {		
									  	 $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
    
    
    
    header("Location: ".$_SERVER['HTTP_REFERER']);
    /*
    header("Location: pricesaving1masterlist_refs.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$PriceListMasterID.rand(10000,99999)
    ."&page=$currpage&g2id=$currg2id&pid=$currpid&mid=$currmid&showzero=$showzero&shownzero=$shownzero");
    */
                                            
?>
            
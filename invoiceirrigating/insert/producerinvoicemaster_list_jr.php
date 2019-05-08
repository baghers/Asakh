<?php 

/*

insert/producerinvoicemaster_list_jr.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/producerinvoicemaster_list.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


$_POST['selectedproducerinvoicemasterID']=$_POST['selectedIID'];//شناسه پیش فاکتور تولیدکننده
$_POST['selectedproducersID']=$_POST['selectedAID'];//تولیدکننده
$_POST['selectedClerkID']=$_POST['selectedCID'];//کاربر
    if (($_POST['selectedproducerinvoicemasterID']>0)&& ($_POST['selectedproducersID']>0))
    {
        
        $temp_array = array('error' => '1');
   	    $savetime=date('Y-m-d H:i:s');
        /*
        primaryinvoicemaster  پیش فاکتور تولیدکننده
        PriceListMasterID لیست قیمت
        operatorcoID مجری
        ProducersID تولیدکننده
        Serial سریال
        Title عنوان
        Description شرح
        TransportCost هزینه حمل
        Discont تخفیف
        InvoiceDate تاریخ
        Rowcnt تعداد ردیف
        pricenotinrep در تعهد متقاضی یا مجری
        SaveTime زمان
        SaveDate تاریخ
        ClerkID کاربر
        */    
        $query = "
        insert into primaryinvoicemaster (PriceListMasterID,operatorcoID,ProducersID,Serial,Title,Description,TransportCost
        ,Discont,InvoiceDate,Rowcnt,pricenotinrep
        ,SaveTime,SaveDate,ClerkID)
        select PriceListMasterID,$_POST[selectedproducersID],ProducersID,Serial,Title,Description,TransportCost,Discont,InvoiceDate,Rowcnt,pricenotinrep
        ,'$savetime','".date('Y-m-d')."','$_POST[selectedClerkID]' from primaryinvoicemaster where primaryinvoicemasterID=$_POST[selectedproducerinvoicemasterID]";
		     
      	  			  	try 
								  {		
									   $result = mysql_query($query);	
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }


        
        $query = "SELECT  last_insert_id() primaryinvoicemasterID";

		 	  			  	try 
								  {		
									   $result = mysql_query($query);	
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

		$row = mysql_fetch_assoc($result);
		
        
        //  print $query;
        if ($row['primaryinvoicemasterID']>0)
        {
            /*
            primaryinvoicedetail ریز پیش فاکتور تولیدکننده
            primaryInvoiceMasterID شناسه
            ToolsMarksID شناسه ابزار
            Number تعداد
            Description شرح
            SaveTime زمان
            SaveDate تاریخ
            ClerkID کاربر
            */  
            $query = "
            insert into primaryinvoicedetail (primaryInvoiceMasterID,ToolsMarksID,Number,Description
            ,SaveTime,SaveDate,ClerkID)
            select '$row[primaryinvoicemasterID]',ToolsMarksID,Number,Description
            ,'$savetime','".date('Y-m-d')."','$_POST[selectedClerkID]' from primaryinvoicedetail where primaryInvoiceMasterID=$_POST[selectedproducerinvoicemasterID]";
            
            $temp_array = array('error' => '$query');
    
            //  print $query;
             	  			  	try 
								  {		
									   $result = mysql_query($query);	
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

        }
        
        echo json_encode($temp_array);
        
    	exit;
    
    
    }
    else
    {
    	$Serial = $_POST['in1'];
        $Title = $_POST['in2'];
        $Description = $_POST['in6'];
        $ProducersID = $_POST['in8'];
        $operatorcoID = $_POST['in3'];
        $InvoiceDate = $_POST['in4'];
        $Rowcnt = $_POST['in5'];
        $PriceListMasterID = $_POST['in9'];
    	if ($ProducersID != ""){
    
    		$query = "SELECT Serial FROM primaryinvoicemaster WHERE Serial = '" . $Serial . "' and  operatorcoID = '" . $operatorcoID . "'";
    		 	  			  	try 
								  {		
									   $result = mysql_query($query);	
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    		$row = mysql_fetch_assoc($result);
            if ($row['Serial']>0)
    		$uid = $row['Serial'];
    		$result = false;
    		if ($uid == ""){
    		          /*
                primaryinvoicemaster  پیش فاکتور تولیدکننده
                PriceListMasterID لیست قیمت
                operatorcoID مجری
                ProducersID تولیدکننده
                Serial سریال
                Title عنوان
                Description شرح
                TransportCost هزینه حمل
                Discont تخفیف
                InvoiceDate تاریخ
                Rowcnt تعداد ردیف
                pricenotinrep در تعهد متقاضی یا مجری
                SaveTime زمان
                SaveDate تاریخ
                ClerkID کاربر
                */  
    			$query = "INSERT INTO primaryinvoicemaster(PriceListMasterID,operatorcoID,Rowcnt,Serial, Title,Description,ProducersID,
                InvoiceDate,SaveTime,SaveDate,ClerkID) VALUES('$PriceListMasterID','" .
    
            
                $operatorcoID . "', '" . $Rowcnt . "', '" . $Serial . "', '" . $Title . "', '" . $Description . "', '" . $ProducersID . "', '" . 
                $InvoiceDate . "'
                , '" . date('Y-m-d H:i:s'). "','".date('Y-m-d')."','".$_POST['in7']."');";
                //print $query;
                $temp_array = array('error' => '0'); 
                 	  			  	try 
								  {		
									   $result = mysql_query($query);	
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    			//header("Location: clerk.php");
    			$register = true;
    		}
            else
                $temp_array = array('error' => '1');
    	}
        echo json_encode($temp_array);   
    	exit();
    }
    			
	
   
   
   
			
			
		
	

?>




<?php
/*
codding/codding2costpricelistmaster_delete.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
codding/codding2costpricelistmaster.php

*/

include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');




if ($login_Permission_granted==0) header("Location: ../login.php");

    $CostPriceListMasterID  = substr($_GET["uid"],40,strlen($_GET["uid"])-45);//فهرست بها


    
    
    
    ///////////////بررسی گردش در سایر جداول
    $deletefromtable="costpricelistmaster";
    $deletefromtablefield="CostPriceListMasterID ";
    $deletefromtablefieldvalue=$CostPriceListMasterID;
    $hascirculation="";
    $query = " SELECT DISTINCT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE upper(COLUMN_NAME) like '%".strtoupper($deletefromtablefield)."%' AND TABLE_SCHEMA = '$_server_db';";
   
	 try 
	  {		
		 $result = mysql_query($query);
	  }
	  //catch exception
	  catch(Exception $e) 
	  {
		echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
	  }

    while($row = mysql_fetch_assoc($result))
    {
        if($row['TABLE_NAME']<>$deletefromtable && $row['TABLE_NAME']<>"invoicedetail")
        {
            $queryin = " SELECT count( * ) cnt FROM $row[TABLE_NAME] WHERE $deletefromtablefield ='$deletefromtablefieldvalue'";
            
			 try 
			  {		
				 $resultin = mysql_query($queryin);
			  }
			  //catch exception
			  catch(Exception $e) 
			  {
				echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
			  }

            $rowin = mysql_fetch_assoc($resultin);
            if ($rowin['cnt']>0)
            $hascirculation.=" ".$row['TABLE_NAME'];
        }
        
    }
    if (strlen($hascirculation)>0) 
    {
        print " این مقدار در جداول زیر گردش دارد ".$hascirculation;
        exit;
    }
    
    
    $query = " DELETE FROM $deletefromtable WHERE $deletefromtablefield = '$deletefromtablefieldvalue';";
    
			 try 
			  {		
				 $result = mysql_query($query);
			  }
			  //catch exception
			  catch(Exception $e) 
			  {
				echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
			  }

    //print $query;
    header("Location: "."codding2costpricelistmaster.php");
                        
                            
?>

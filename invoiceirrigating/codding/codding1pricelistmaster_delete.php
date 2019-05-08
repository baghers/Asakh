<?php

/*

//codding/codding1pricelistmaster_delete.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/codding/codding1pricelistmaster_delete.php
 -
*/

include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');




if ($login_Permission_granted==0) header("Location: ../login.php");

    $PriceListMasterID  = substr($_GET["uid"],40,strlen($_GET["uid"])-45);//لیست قیمت

    
    
    ///////////////بررسی گردش در سایر جداول
    $deletefromtable="pricelistmaster";
    $deletefromtablefield="PriceListMasterID ";
    $deletefromtablefieldvalue=$PriceListMasterID;
    $hascirculation="";
    $query = " SELECT DISTINCT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE upper(COLUMN_NAME) like '%".strtoupper($deletefromtablefield)."%' AND TABLE_SCHEMA = '$_server_db';";
   
	
							try 
							  {		
								$result = mysql_query($query);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
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
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
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
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

    //print $query;
    header("Location: "."codding1pricelistmaster.php");
                        
                            
?>

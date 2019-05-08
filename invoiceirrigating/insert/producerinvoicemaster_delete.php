<?php

/*

insert/producerinvoicemaster_delete.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود

*/
include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');




if ($login_Permission_granted==0) header("Location: ../login.php");
    $primaryinvoicemasterID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
/*
primaryinvoicemaster  پیش فاکتور صادره تولید کننده
ProducersID شناسه تولید کننده
*/

    $query = "SELECT primaryinvoicemaster.primaryinvoiceMasterID FROM primaryinvoicemaster 
    WHERE primaryinvoiceMasterID ='$primaryinvoicemasterID' and ProducersID='$login_ProducersID';";
   
		  			  	try 
								  {		
									  $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    $resquery = mysql_fetch_assoc($result);
    if (!$resquery["primaryinvoiceMasterID"]) header("Location: ../logout.php");
    
    
    
    
    ///////////////بررسی گردش در سایر جداول
    $deletefromtable="primaryinvoicemaster";
    $deletefromtablefield="primaryinvoiceMasterID";
    $deletefromtablefieldvalue=$primaryinvoicemasterID;
    $hascirculation="";
    $query = " SELECT DISTINCT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE upper(COLUMN_NAME) like '%".strtoupper($deletefromtablefield)."%' AND TABLE_SCHEMA = '$_server_db';";
    			  	try 
								  {		
									  $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    while($row = mysql_fetch_assoc($result))
    {
        if($row['TABLE_NAME']<>$deletefromtable && $row['TABLE_NAME']<>"primaryinvoicedetail")
        {
            $queryin = " SELECT count( * ) cnt FROM $row[TABLE_NAME] WHERE $deletefromtablefield =$deletefromtablefieldvalue";
          
						  	try 
								  {		
									    $resultin = mysql_query($queryin);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
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
    
    //primaryinvoicedetail ریز پیش فاکتور تولیدکننده
    $query = " DELETE FROM primaryinvoicedetail WHERE primaryinvoiceMasterID = '$deletefromtablefieldvalue';";
    
    					  	try 
								  {		
									    $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
    //primaryinvoicemaster  پیش فاکتور تولیدکننده
    $query = " DELETE FROM primaryinvoicemaster WHERE primaryinvoiceMasterID = '$deletefromtablefieldvalue';";
    					  	try 
								  {		
									    $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    //print $query;
    header("Location: "."producerinvoicemaster_list.php");
                        
                            
?>

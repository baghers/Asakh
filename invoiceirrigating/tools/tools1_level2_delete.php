<?php
/*
tools/tools1_level2_delete.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools1_level2_list.php
*/
include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');




if ($login_Permission_granted==0) header("Location: ../login.php");

    $Gadget2ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);//شناسه جدول سطح دوم ابزار

    
    ///////////////بررسی گردش در سایر جداول
    $deletefromtable="gadget2";//gadget2 جدول سطح دوم ابزار
    $deletefromtablefield="Gadget2ID";//شناسه جدول سطح دوم ابزار
    $deletefromtablefieldvalue=$Gadget2ID;
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
        if(($row['TABLE_NAME']<>$deletefromtable)  )
        {
            $queryin = " SELECT count( * ) cnt FROM $row[TABLE_NAME] WHERE $deletefromtablefield =$deletefromtablefieldvalue";
            $resultin = mysql_query($queryin);
            $rowin = mysql_fetch_assoc($resultin);
            if ($rowin['cnt']>0)
            $hascirculation.=" ".$row['TABLE_NAME'];
        }
        //print $row['TABLE_NAME'];
        
        
    }
    //exit(0);
    if (strlen($hascirculation)>0) 
    {
        print " این مقدار در جداول زیر گردش دارد ".$hascirculation;
        exit;
    }


    $query = "SELECT * FROM gadget2 WHERE Gadget2ID  ='$Gadget2ID'";
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
	$Gadget1ID = $resquery["Gadget1ID"];
           
    $query = " DELETE FROM gadget2 WHERE $deletefromtablefield =$deletefromtablefieldvalue;";
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
    header("Location: tools1_level2_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget1ID.rand(10000,99999));
                                            
                            
?>

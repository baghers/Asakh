<?php
/*
tools/tools1_level1_delete.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools1_level1_list.php
*/
include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');




if ($login_Permission_granted==0) header("Location: ../login.php");

    $Gadget1ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);//شناسه جدول سطح اول ابزار


       
    
    ///////////////بررسی گردش در سایر جداول
    $deletefromtable="gadget1";//جدول سطح اول ابزار
    $deletefromtablefield="Gadget1ID";//شناسه جدول سطح اول ابزار
    $deletefromtablefieldvalue=$Gadget1ID;
    $hascirculation="";
    $query = " SELECT DISTINCT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE upper(COLUMN_NAME) like '%".strtoupper($deletefromtablefield)."%' AND TABLE_SCHEMA = '$_server_db';";
    $result = mysql_query($query);
    while($row = mysql_fetch_assoc($result))
    {
        if($row['TABLE_NAME']<>$deletefromtable)
        {
            $queryin = " SELECT count( * ) cnt FROM $row[TABLE_NAME] WHERE $deletefromtablefield =$deletefromtablefieldvalue";
            $resultin = mysql_query($queryin);
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
    
    
    
    $query = " DELETE FROM gadget1 WHERE $deletefromtablefield =$deletefromtablefieldvalue;";
                try 
								  {		
									    mysql_query($query);  
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }
    header("Location: tools1_level1_list.php");
                        
                            
?>

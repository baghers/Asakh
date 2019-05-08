<?php
/*
tools/tools1_level3_delete.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools1_level3_list.php
*/
include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');




if ($login_Permission_granted==0) header("Location: ../login.php");

    $Gadget3ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);//شناسه جدول سطح سوم ابزار

    if (substr($Gadget3ID,0,2)=='0,')
        $Gadget3ID=substr($Gadget3ID,2);
    //print $Gadget3ID;
    
    //exit;
    
    ///////////////بررسی گردش در سایر جداول
    $deletefromtable="gadget3";// جدول سطح سوم ابزار
    $deletefromtablefield="Gadget3ID";//شناسه جدول سطح سوم ابزار
    $deletefromtablefieldvalue=$Gadget3ID;
    $hascirculation="";
    $query = " SELECT DISTINCT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE upper(COLUMN_NAME) like 
    '%".strtoupper($deletefromtablefield)."%' AND TABLE_SCHEMA = '$_server_db';";
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
        if($row['TABLE_NAME']<>$deletefromtable && $row['TABLE_NAME']<>'gadget3operational'//جدول هزینه های اجرای ابزارها
        && $row['TABLE_NAME']<>'toolsmarks'   ) 
        {
            $queryin = " SELECT count( * ) cnt FROM $row[TABLE_NAME] WHERE $deletefromtablefield in ($deletefromtablefieldvalue)";
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
    print ' ID='.$Gadget3ID;

    if (strlen($hascirculation)>0) 
    {
        print " این مقدار در جداول زیر گردش دارد ".$hascirculation;
        exit;
    }
    /*
           gadget3 جدول سطح سوم ابزار
           gadget3id شناسه جدول سطح سوم ابزار
           toolsmarks جدول ابزار مارک که دارای ستون های ارتباطی زیر می باشد
                ابزار و مارک از ترکیب سناسه طرح، شناسه تولیدکننده و شناسه مارک تشکیل می شود
                gadget3ID شناسه سطح 3 ابزار
                ProducersID شناسه جدول تولیدکننده
                MarksID شناسه جدول مارک
           toolsmarksid شناسه ابزار و مارک
           toolspref جدول مرجع قیمتی
    */
    
    $query = "SELECT count(*) cnt FROM toolsmarks
    inner join toolspref on toolsmarks.ToolsMarksID=toolspref.ToolsMarksIDpriceref
    inner join gadget3 on gadget3.Gadget3ID=ToolsMarks1.Gadget3ID
     WHERE gadget3.Gadget3ID  in ('$Gadget3ID')";
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
    //print $query;
    if (($resquery["cnt"])>0) 
    {
        print " این کالا به عنوان  "."مرجع قیمتی کالایی می باشد";
        exit;
    }
      
    $query = "SELECT count(*) cnt FROM toolsmarks
    inner join toolspref on toolsmarks.ToolsMarksID=toolspref.ToolsMarksID
    inner join gadget3 on gadget3.Gadget3ID=ToolsMarks1.Gadget3ID
     WHERE gadget3.Gadget3ID  in ('$Gadget3ID')";
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
    //print $query;
    if (($resquery["cnt"])>0) 
    {
        print "ابتدا مرجع قیمتی این کالا را حذف نمایید";
        exit;
    }
    
    
    $query = "SELECT count(*) cnt FROM invoicedetail
    inner join toolsmarks on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID
    inner join gadget3 on gadget3.Gadget3ID=toolsmarks.Gadget3ID
     WHERE gadget3.Gadget3ID  in ('$Gadget3ID')";
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
    //print $query;
    if (($resquery["cnt"])>0) 
    {
        print " این مقدار در جداول زیر گردش دارد "."پیش فاکتورها";
        exit;
    }
    
    

    
    
    $query = "SELECT Gadget2ID FROM gadget3 WHERE Gadget3ID  in ('$Gadget3ID')";
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
	$Gadget2ID = $resquery["Gadget2ID"];
          
    
    $query = "SELECT count(*) cnt FROM invoicedetail
            inner join toolsmarks on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID
            inner join gadget3 on gadget3.Gadget3ID=toolsmarks.Gadget3ID
            WHERE toolsmarks.Gadget3ID  in ('$Gadget3ID')";
            try 
                {		
                    $result = mysql_query($query);  
                    $resquery = mysql_fetch_assoc($result);
                    //print $query;
                    if (($resquery["cnt"])<=0) 
                    {
                        $query = " DELETE FROM primarypricelistdetail WHERE $deletefromtablefield in ($deletefromtablefieldvalue) ;";//جدول قیمت اولیه فروشندگان
                        $result = mysql_query($query);
                        $query = " DELETE FROM pricelistdetail WHERE $deletefromtablefield in ($deletefromtablefieldvalue) ;";//ریز قیمت های تایید شده
                        $result = mysql_query($query);
                        $query = " DELETE FROM toolsmarks WHERE $deletefromtablefield in ($deletefromtablefieldvalue) ;";
                        $result = mysql_query($query);
                        $query = " DELETE FROM gadget3operational WHERE $deletefromtablefield in ($deletefromtablefieldvalue);";//جدول هزینه های اجرای ابزارها
                        $result = mysql_query($query);
                        $query = " DELETE FROM gadget3 WHERE $deletefromtablefield  in ($deletefromtablefieldvalue);";
                        $result = mysql_query($query);
                        $query = "delete FROM pricelistdetail where ToolsMarksID in ( select ToolsMarksID from (select ToolsMarksID from pricelistdetail
                        inner join toolsmarks on toolsmarks.ToolsMarksID=pricelistdetail.ToolsMarksID
                        inner join gadget3 on gadget3.Gadget3ID=toolsmarks.Gadget3ID
                         WHERE gadget3.Gadget3ID  in ('$Gadget3ID'))as view1 )";
                        $result = mysql_query($query);
                    }
                }
                //catch exception
                catch(Exception $e) 
                {
                    echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                }
            

    
    header("Location: tools1_level3_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget2ID.rand(10000,99999));
                                            
                            
?>

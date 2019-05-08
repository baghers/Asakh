<?php

/*
codding/codding4table_detail_delete.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
 codding/codding4table_detail.php

*/

include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');




    $ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
        $linearray = explode('_',$ID);
        $TBLNAME=$linearray[0];//نام جدول
        $TBLTITLE=$linearray[1];//عنوان فارسی جدول
        $TBLID=$linearray[2];//شناسه جدول
        $tblkey=$linearray[3];//کلید جدول
        $tblval=$linearray[4];//مقدار جدول
        
    if ($login_RolesID!=19 && $backdoor==0 && !in_array($TBLNAME,array("applicantsurvey","applicantsystemtype","applicantwsource","appsubprj"))) header("Location: ../login.php");
    ///////////////بررسی گردش در سایر جداول
    $deletefromtable=$TBLNAME;
    $deletefromtablefield=$TBLNAME."ID";
    $deletefromtablefieldvalue=$TBLID;
    $hascirculation="";
    $query = " SELECT DISTINCT TABLE_NAME,COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE upper(COLUMN_NAME) like '%".strtoupper($deletefromtablefield)."%' AND TABLE_SCHEMA = '$_server_db';";
 
	
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
    while($row = mysql_fetch_assoc($result))
    {
        if (($deletefromtablefield=='marksID') && ($row['COLUMN_NAME']=='ToolsMarksID')) continue;
        if(($row['TABLE_NAME']<>$deletefromtable)  )
        {
            $queryin = " SELECT count( * ) cnt FROM $row[TABLE_NAME] WHERE $row[COLUMN_NAME] =$deletefromtablefieldvalue";
            //print $queryin;
            
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
        //print $row['TABLE_NAME'];
        
        
    }
    //exit(0);
    if (strlen($hascirculation)>0) 
    {
        print " این مقدار در جداول زیر گردش دارد ".$hascirculation;
        exit;
    }


    $query = " DELETE FROM $TBLNAME WHERE $deletefromtablefield =$deletefromtablefieldvalue;";
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
    header("Location: codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999));
                                                                
                            
?>

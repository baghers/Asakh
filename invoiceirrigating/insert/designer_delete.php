<?php 

/*

insert/designer_delete.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
insert/designer_list.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$uid = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
$linearr = explode('^',$uid);
$id=$linearr[0];//شناسه
$login_RolesID=$linearr[1];//نقش

//if ($login_Permission_granted==0) header("Location: ../login.php");
//print $id;exit;

$permitrolsid = array("1","2","5","9","10","20");

 if (in_array($login_RolesID, $permitrolsid))
  {
    /*
    designer جدول طراح
    DesignerID شناسه طراح
    DesignerCoID شرکت طراح
    OperatorCoID شرکت مجری
    */
        $query = " update designer set DesignerCoID=0,OperatorCoID=0 where DesignerID='$id';";
 
	 					   		try 
								  {		
									     mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    
	       $query = " update members set DesignerCoID=0,OperatorCoID=0 where membersID='$id';";
    					   		try 
								  {		
									     mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

  
  }

$permitrolsid = array("1");

 if (in_array($login_RolesID, $permitrolsid))
  {
    ///////////////بررسی گردش در سایر جداول
    $deletefromtable="designer";
    $deletefromtablefield="designerID";
    $deletefromtablefieldvalue=$id;
    $hascirculation="";
    $query = " SELECT DISTINCT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE upper(COLUMN_NAME) like '%".
    strtoupper($deletefromtablefield)."%' AND TABLE_SCHEMA = '$_server_db';";
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
        if($row['TABLE_NAME']<>$deletefromtable)
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
        print "$id این مقدار در جداول زیر گردش دارد ".$hascirculation;
        exit;
    }
    $query = " DELETE from designer where DesignerID='$id';";
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
  
    header("Location: designer_list.php");

                                            
                            
?>

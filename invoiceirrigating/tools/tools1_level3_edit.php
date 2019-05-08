<?php 
/*
tools/tools1_level3_edit.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools1_level3_list.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

if (! $_POST)
{
    $id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);//شناسه جدول سطح سوم ابزار
    
    /*
    gadget3operational جدول هزینه های اجرای ابزارها
     gadget3 جدول سطح سوم ابزار
     gadget3id شناسه جدول سطح سوم ابزار
     CostCoef ضریب هزینه اجرا
     newcoef ضریب جدید اجرا در صورت تغییر
     code کد کالا
    */
    $query = "select gadget3operational.CostCoef,gadget3.code gadget3opcode,Gadget3IDOperationalnew,newcoef,gadget3operational.Gadget3IDOperational 
    from gadget3operational 
    inner join gadget3 on gadget3.gadget3id=gadget3operational.Gadget3IDOperational
    left outer join (
    select gadget3IDold,Gadget3IDOperationalold,Gadget3IDOperationalnew,newcoef 
    from gadget3operationalnewcoefs gadget3operationalnewcoefsout where SaveTime=
    (select max(SaveTime) from gadget3operationalnewcoefs where 
    gadget3operationalnewcoefsout.gadget3IDold=gadget3operationalnewcoefs.gadget3IDold
    and gadget3operationalnewcoefsout.Gadget3IDOperationalnew=gadget3operationalnewcoefs.Gadget3IDOperationalnew)
    
    ) gadget3operationalnewcoefs
    on gadget3operationalnewcoefs.gadget3IDold=gadget3operational.gadget3id 
    and gadget3operationalnewcoefs.Gadget3IDOperationalold=gadget3operational.Gadget3IDOperational
    
    WHERE gadget3operational.Gadget3ID  ='$id';";
    //print $query;
    $result = mysql_query($query);
    
    if($resquery = mysql_fetch_assoc($result))
    {
        $Gadget3codeOperational1=$resquery["gadget3opcode"];
        $CostCoef1 = $resquery["CostCoef"];        
        $newcoef1 = $resquery["newcoef"];          
        $Gadget3IDOperationalnew1 = $resquery["Gadget3IDOperationalnew"];  
        $Gadget3IDOperational1 = $resquery["Gadget3IDOperational"];      
    }    
    if($resquery = mysql_fetch_assoc($result))
    {
        $Gadget3codeOperational2=$resquery["gadget3opcode"];
        $CostCoef2 = $resquery["CostCoef"];         
        $newcoef2 = $resquery["newcoef"];         
        $Gadget3IDOperationalnew2 = $resquery["Gadget3IDOperationalnew"];  
        $Gadget3IDOperational2 = $resquery["Gadget3IDOperational"];     
    }    
    if($resquery = mysql_fetch_assoc($result))
    {
        $Gadget3codeOperational3=$resquery["gadget3opcode"];
        $CostCoef3 = $resquery["CostCoef"];       
        $newcoef3 = $resquery["newcoef"];     
        $Gadget3IDOperationalnew3 = $resquery["Gadget3IDOperationalnew"];   
        $Gadget3IDOperational3 = $resquery["Gadget3IDOperational"];          
    }    
    if($resquery = mysql_fetch_assoc($result))
    {
        $Gadget3codeOperational4=$resquery["gadget3opcode"];
        $CostCoef4 = $resquery["CostCoef"];         
        $newcoef4 = $resquery["newcoef"];     
        $Gadget3IDOperationalnew4 = $resquery["Gadget3IDOperationalnew"];     
        $Gadget3IDOperational4 = $resquery["Gadget3IDOperational"];      
    }    
    if($resquery = mysql_fetch_assoc($result))
    {
        $Gadget3codeOperational5=$resquery["gadget3opcode"];
        $CostCoef5 = $resquery["CostCoef"];        
        $newcoef5 = $resquery["newcoef"];
        $Gadget3IDOperationalnew5 = $resquery["Gadget3IDOperationalnew"];   
        $Gadget3IDOperational5 = $resquery["Gadget3IDOperational"];              
    }
    
    

    /*
        gadget2 جدول سطح دوم ابزار
        gadget2id شناسه جدول سطح دوم ابزار
        gadget3 جدول سطح سوم ابزار
        gadget3id شناسه جدول سطح سوم ابزار
        Code کد
        Title عنوان
        unitsID شناسه واحد کالا
        sizeunitsID شناسه اندازه کالا
        spec1 مشخصه اول
        opsize اندازه عملیاتی
        UnitsID2 شناسه واحد فرعی
        UnitsCoef2 ضریب اجرایی 2
        MaterialTypeID شناسه نوع مواد
        zavietoolsorattabaghe مقدار زاویه/طول/سرعت/طبقه
        zavietoolsorattabagheUnitsID واحد  زاویه/طول/سرعت/طبقه
        fesharzekhamathajm مقدار فشار/ضخامت/حجم
        fesharzekhamathajmUnitsID واحد فشار/ضخامت/حجم
        operatorid شناسه عملگر
        spec2id خصوصیت 2
        spec3id خصوصیت 3
        spec3sizeunitsid واحد خصوصیت 3
        IsHide غیر فعال شدن کالا
    */
    
    
    $query = "SELECT gadget3.*,gadget2.title gadget2title  FROM gadget3 
    inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
    WHERE gadget3.Gadget3ID  ='$id';";
    //print $query;
    $result = mysql_query($query);
    $resquery = mysql_fetch_assoc($result);
   	$Gadget2ID = $resquery["Gadget2ID"];
    $Code = $resquery["Code"];
	$Title = $resquery["Title"];
    $unitsID = $resquery["unitsID"];    
    $sizeunitsID = $resquery["sizeunitsID"];
    $spec1 = $resquery["spec1"];
    $opsize = $resquery["opsize"];
    $UnitsID2 = $resquery["UnitsID2"];
    $UnitsCoef2 = $resquery["UnitsCoef2"];
    $materialtypeid = $resquery["MaterialTypeID"];
    $zavietoolsorattabaghe = $resquery["zavietoolsorattabaghe"];
    $zavietoolsorattabagheUnitsID = $resquery["zavietoolsorattabagheUnitsID"];
    $fesharzekhamathajm = $resquery["fesharzekhamathajm"];
    $fesharzekhamathajmUnitsID = $resquery["fesharzekhamathajmUnitsID"];
    $size2 = $resquery["size2"];
    $size11 = $resquery["size11"];
    $size12 = $resquery["size12"];
    $size13 = $resquery["size13"];
    $operatorid= $resquery["operatorid"];
    $spec2id = $resquery["spec2id"];
    $spec3id = $resquery["spec3id"];
    $spec3size = $resquery["spec3size"];
    $spec3sizeunitsid = $resquery["spec3sizeunitsid"];
    $gadget2title=$resquery["gadget2title"];
    if ($resquery["IsHide"]==1)
    $IsHide = "checked";
    
                        
    $Gadget3ID=$id;  
    if (!$Gadget3ID) header("Location: ../logout.php");
}

$register = false;

if ($_POST){
	$Gadget2ID = $_POST["Gadget2ID"];
    $Code = $_POST["Code"];
	$Title = $_POST["Title"];
    $unitsID = $_POST["unitsID"];
    
        
    $sizeunitsID = $_POST["sizeunitsID"];
    $spec1 = $_POST["spec1"];
    $opsize = $_POST["opsize"];
    $UnitsID2 = $_POST["UnitsID2"];
    $UnitsCoef2 = $_POST["UnitsCoef2"];
    $materialtypeid = $_POST["materialtypeid"];
    $zavietoolsorattabaghe = $_POST["zavietoolsorattabaghe"];
    $zavietoolsorattabagheUnitsID = $_POST["zavietoolsorattabagheUnitsID"];
    $fesharzekhamathajm = $_POST["fesharzekhamathajm"];
    $fesharzekhamathajmUnitsID = $_POST["fesharzekhamathajmUnitsID"];
    $size2 = $_POST["size2"];
    $size11 = $_POST["size11"];
    $size12 = $_POST["size12"];
    $size13 = $_POST["size13"];
    $operatorid= $_POST["operatorid"];
    $spec2id = $_POST["spec2id"];
    $spec3id = $_POST["spec3id"];
    $spec3size = $_POST["spec3size"];
    $spec3sizeunitsid = $_POST["spec3sizeunitsid"];
    if ($_POST["IsHide"]=='on')
    $IsHide = 1;
    else
    $IsHide = 0;
    
	$Gadget3ID = $_POST["Gadget3ID"];
    //print "salam";
	if ($Gadget3ID != ""){
		$query = "
		UPDATE gadget3 SET
		Code = '" . $Code . "', 
		Title = '" . $Title . "', 
		Gadget2ID = '" . $Gadget2ID . "', 
		unitsID = '" . $unitsID . "',
		UnitsID2 = '" . $UnitsID2 . "',
		UnitsCoef2 = '" . $UnitsCoef2 . "',
		sizeunitsID = '" . $sizeunitsID . "',
		opsize = '" . $opsize . "',
		spec1 = '" . $spec1 . "',
		materialtypeid = '" . $materialtypeid . "',
		zavietoolsorattabaghe = '" . $zavietoolsorattabaghe . "',
		zavietoolsorattabagheUnitsID = '" . $zavietoolsorattabagheUnitsID . "',
        fesharzekhamathajm = '" . $fesharzekhamathajm . "',
        fesharzekhamathajmUnitsID = '" . $fesharzekhamathajmUnitsID . "',
        size2 = '" . $size2 . "',
        size11 = '" . $size11 . "',
        size12 = '" . $size12 . "',
        size13 = '" . $size13 . "',
        operatorid = '" . $operatorid . "',
        spec2id = '" . $spec2id . "',
        spec3id = '" . $spec3id . "',
        spec3size = '" . $spec3size . "',
        spec3sizeunitsid = '" . $spec3sizeunitsid . "',
        IsHide = '" . $IsHide . "',
        
        
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE Gadget3ID = $Gadget3ID;";
        $result = mysql_query($query);
        
        //print $query;
        //exit;
        
         
        $query = "select gadget3.Code as _key,gadget3.Gadget3ID as _value from gadget3
                         inner join gadget2 on gadget2.gadget2ID=gadget3.Gadget2ID
                         inner join gadget1 on gadget1.gadget1ID=gadget2.Gadget1ID and ifnull(gadget1.ISCost,0)=1";
        $ID = get_key_value_from_query_into_array($query);
        
        if ($ID["$_POST[Gadget3codeOperational1]"]>0 
        and ($_POST['CostCoef1']!=$_POST['newcoef1'] || $ID["$_POST[Gadget3codeOperational1]"]!=$_POST['Gadget3IDOperational1'] ) 
        and strlen($_POST['newcoef1'])>0
        )
        {
            $sql="INSERT INTO gadget3operationalnewcoefs(gadget3IDold, Gadget3IDOperationalnew,Gadget3IDOperationalold,newcoef,SaveTime,SaveDate,ClerkID) 
            select '$Gadget3ID','".$ID["$_POST[Gadget3codeOperational1]"]."','$_POST[Gadget3IDOperational1]','$_POST[newcoef1]','".date('Y-m-d H:i:s')
            ."','".date('Y-m-d')."','$login_userid' from gadget3 
            where gadget3.gadget3id='$Gadget3ID' and gadget3.gadget3id not in 
            (select gadget3idold from gadget3operationalnewcoefs where gadget3idold='$Gadget3ID' 
            and Gadget3IDOperationalnew='".$ID["$_POST[Gadget3codeOperational1]"]."'
            and abs(newcoef-'$_POST[newcoef1]')<0.0001 );";
            //print $sql;exit;
            mysql_query($sql); 
        }
            
           //print strlen($_POST['newcoef2']);exit;
           
    	if ($ID["$_POST[Gadget3codeOperational2]"]>0 
        and ($_POST['CostCoef2']!=$_POST['newcoef2'] || $ID["$_POST[Gadget3codeOperational2]"]!=$_POST['Gadget3IDOperational2'] ) 
        and strlen($_POST['newcoef2'])>0)
        {
          // print $_POST['Gadget3IDOperational2'];exit;
            if ($_POST['Gadget3IDOperational2']==0)
            {
                
                mysql_query("INSERT INTO gadget3operational(gadget3ID, Gadget3IDOperational,CostCoef,SaveTime,SaveDate,ClerkID) 
                VALUES('$Gadget3ID','".$ID["$_POST[Gadget3codeOperational2]"]."','0','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');"); 
            }
            
            mysql_query("INSERT INTO gadget3operationalnewcoefs(gadget3IDold, Gadget3IDOperationalnew,Gadget3IDOperationalold,newcoef,SaveTime,SaveDate,ClerkID) 
            select '$Gadget3ID','".$ID["$_POST[Gadget3codeOperational2]"]."','".$ID["$_POST[Gadget3codeOperational2]"]."','$_POST[newcoef2]','".date('Y-m-d H:i:s')
            ."','".date('Y-m-d')."','$login_userid' from gadget3 where gadget3.gadget3id='$Gadget3ID' and gadget3.gadget3id not in 
            (select gadget3idold from gadget3operationalnewcoefs where gadget3idold='$Gadget3ID' and Gadget3IDOperationalnew='".$ID["$_POST[Gadget3codeOperational2]"]."'
            and abs(newcoef-'$_POST[newcoef2]')<0.0001) ;"); 
        }
    	if ($ID["$_POST[Gadget3codeOperational3]"]>0 
        and ($_POST['CostCoef3']!=$_POST['newcoef3'] || $ID["$_POST[Gadget3codeOperational3]"]!=$_POST['Gadget3IDOperational3'] ) 
        and strlen($_POST['newcoef3'])>0)
        {
            
            if ($_POST['Gadget3IDOperational3']==0)
            {
                mysql_query("INSERT INTO gadget3operational(gadget3ID, Gadget3IDOperational,CostCoef,SaveTime,SaveDate,ClerkID) 
                VALUES('$Gadget3ID','".$ID["$_POST[Gadget3codeOperational3]"]."','0','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');"); 
            }
            
            mysql_query("INSERT INTO gadget3operationalnewcoefs(gadget3IDold, Gadget3IDOperationalnew,Gadget3IDOperationalold,newcoef,SaveTime,SaveDate,ClerkID) 
            select '$Gadget3ID','".$ID["$_POST[Gadget3codeOperational3]"]."','".$ID["$_POST[Gadget3codeOperational3]"]."','$_POST[newcoef3]','".date('Y-m-d H:i:s')
            ."','".date('Y-m-d')."','$login_userid' from gadget3 where gadget3.gadget3id='$Gadget3ID' and gadget3.gadget3id not in 
            (select gadget3idold from gadget3operationalnewcoefs where gadget3idold='$Gadget3ID' and Gadget3IDOperationalnew='".$ID["$_POST[Gadget3codeOperational3]"]."'
            and abs(newcoef-'$_POST[newcoef3]')<0.0001) ;"); 
        }
    	if ($ID["$_POST[Gadget3codeOperational4]"]>0 
        and ($_POST['CostCoef4']!=$_POST['newcoef4'] || $ID["$_POST[Gadget3codeOperational4]"]!=$_POST['Gadget3IDOperational4'] ) 
        and strlen($_POST['newcoef4'])>0)
        {
            if ($_POST['Gadget3IDOperational4']==0)
            {
                mysql_query("INSERT INTO gadget3operational(gadget3ID, Gadget3IDOperational,CostCoef,SaveTime,SaveDate,ClerkID) 
                VALUES('$Gadget3ID','".$ID["$_POST[Gadget3codeOperational4]"]."','0','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');"); 
            }
            mysql_query("INSERT INTO gadget3operationalnewcoefs(gadget3IDold, Gadget3IDOperationalnew,Gadget3IDOperationalold,newcoef,SaveTime,SaveDate,ClerkID) 
            select '$Gadget3ID','".$ID["$_POST[Gadget3codeOperational4]"]."','".$ID["$_POST[Gadget3codeOperational4]"]."','$_POST[newcoef4]','".date('Y-m-d H:i:s')
            ."','".date('Y-m-d')."','$login_userid' from gadget3 where gadget3.gadget3id='$Gadget3ID' and gadget3.gadget3id not in 
            (select gadget3idold from gadget3operationalnewcoefs where gadget3idold='$Gadget3ID' and Gadget3IDOperationalnew='".$ID["$_POST[Gadget3codeOperational4]"]."'
            and abs(newcoef-'$_POST[newcoef4]')<0.0001) ;"); 
        }
    	if ($ID["$_POST[Gadget3codeOperational5]"]>0 
        and ($_POST['CostCoef5']!=$_POST['newcoef5'] || $ID["$_POST[Gadget3codeOperational5]"]!=$_POST['Gadget3IDOperational5'] ) 
        and strlen($_POST['newcoef5'])>0)
        {
            if ($_POST['Gadget3IDOperational5']==0)
            {
                mysql_query("INSERT INTO gadget3operational(gadget3ID, Gadget3IDOperational,CostCoef,SaveTime,SaveDate,ClerkID) 
                VALUES('$Gadget3ID','".$ID["$_POST[Gadget3codeOperational5]"]."','0','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');"); 
            }
            mysql_query("INSERT INTO gadget3operationalnewcoefs(gadget3IDold, Gadget3IDOperationalnew,Gadget3IDOperationalold,newcoef,SaveTime,SaveDate,ClerkID) 
            select '$Gadget3ID','".$ID["$_POST[Gadget3codeOperational5]"]."','".$ID["$_POST[Gadget3codeOperational5]"]."','$_POST[newcoef5]','".date('Y-m-d H:i:s')
            ."','".date('Y-m-d')."','$login_userid' from gadget3 where gadget3.gadget3id='$Gadget3ID' and gadget3.gadget3id not in 
            (select gadget3idold from gadget3operationalnewcoefs where gadget3idold='$Gadget3ID' and Gadget3IDOperationalnew='".$ID["$_POST[Gadget3codeOperational5]"]."'
            and abs(newcoef-'$_POST[newcoef5]')<0.0001) ;"); 
        }
    	
        			
        
        $register = true;
        //print $query;
        //exit(0);

	}
    
    $query='select ProducersID as _value,Title as _key from producers where ifnull(DisableAddGadget,0)=0 order by Title   COLLATE utf8_persian_ci';
    $ID = get_key_value_from_query_into_array($query);
    foreach ($ID as $key => $value)
    {
            $query = "SELECT count(*) cnt FROM invoicedetail
            inner join toolsmarks on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID
            inner join gadget3 on gadget3.Gadget3ID=toolsmarks.Gadget3ID
            WHERE toolsmarks.Gadget3ID  ='$Gadget3ID' and toolsmarks.ProducersID='$value'";
            $result = mysql_query($query);
            $resquery = mysql_fetch_assoc($result);
            //print $query;
            if (($resquery["cnt"])>0) 
            {
                continue;
            }
            

                $query = "SELECT count(*) cnt FROM primarypricelistdetail
                inner join toolsmarks on toolsmarks.ToolsMarksID=primarypricelistdetail.ToolsMarksID
                WHERE toolsmarks.Gadget3ID  ='$Gadget3ID' and toolsmarks.ProducersID='$value'";
                $result = mysql_query($query);
                $resquery = mysql_fetch_assoc($result);
                if (($resquery["cnt"])>0) 
                {
                    continue;       
                }
                            
                $query = "SELECT count(*) cnt FROM pricelistdetail
                inner join toolsmarks on toolsmarks.ToolsMarksID=pricelistdetail.ToolsMarksID
                WHERE toolsmarks.Gadget3ID  ='$Gadget3ID' and toolsmarks.ProducersID='$value'";
                $result = mysql_query($query);
                $resquery = mysql_fetch_assoc($result);
                if (($resquery["cnt"])>0) 
                {
                    continue;       
                }
    
                        
                $query = "
                
                SELECT count(*) cnt FROM toolsmarks
                inner join toolspref on toolsmarks.ToolsMarksID=toolspref.ToolsMarksIDpriceref
                WHERE toolsmarks.Gadget3ID  ='$Gadget3ID' and toolsmarks.ProducersID='$value' 
                ";
                $result = mysql_query($query);
                $resquery = mysql_fetch_assoc($result);
                //print $query;
                if (($resquery["cnt"])>0) 
                {
                                continue;   
                 }
                 $query = "
                SELECT count(*) cnt FROM toolsmarks
                inner join toolspref on toolsmarks.ToolsMarksID=toolspref.ToolsMarksID
                WHERE toolsmarks.Gadget3ID  ='$Gadget3ID' and toolsmarks.ProducersID='$value' 
                
                ";
                $result = mysql_query($query);
                $resquery = mysql_fetch_assoc($result);
                //print $query;
                if (($resquery["cnt"])>0) 
                {
                                continue;   
                }
                $query = "
                SELECT count(*) cnt FROM toolsmarks
                WHERE Gadget3ID  ='$Gadget3ID' and ProducersID='$value' and MarksID<>'128'
                ";
                $result = mysql_query($query);
                $resquery = mysql_fetch_assoc($result);
                //print $query;
                if (($resquery["cnt"])>0) 
                {
                                continue;   
                }
                
            mysql_query("DELETE FROM toolsmarks WHERE Gadget3ID = '$Gadget3ID' and ProducersID='$value' ;");
            //print $_POST["Producer$value"]."salam";
            if ($_POST["Producer$value"]=='on')
            {
                mysql_query("INSERT INTO toolsmarks(MarksID,gadget3ID, ProducersID,SaveTime,SaveDate,ClerkID) 
                VALUES('128','$Gadget3ID','$value','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');"); 
            }
    }
    
    
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>تصحیح ابزار سطح 3</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
</head>
<body>


	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>
    <script>
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
	}
         function SelectAll()
                {
                    if ($("input[id^='Producer']:checked").length == $("input[id^='Producer']").length)
                    $("input[id^='Producer']").prop('checked', false);
                    else
                    $("input[id^='Producer']").prop('checked', true);
                    //$("select[id^='ProducersID']").selectedIndex=0;
                }
                
    
    </script>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php

				if ($_POST){
					if ($register){
						echo '<p class="note">ثبت با موفقيت انجام شد</p>';
                        header("Location: tools1_level3_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$_POST["Gadget2ID"].rand(10000,99999));
                        
					}else{
						echo '<p class="error">خطا در ثبت...</p>';
					}
				}

?>
			<!-- top -->
        	<?php include('../includes/top.php'); ?>
            <!-- /top -->

            <!-- main navigation -->
            <?php include('../includes/navigation.php'); ?>
            <!-- /main navigation -->
            <?php include('../includes/subnavigation.php'); ?>

			<!-- header -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
                <form action="tools1_level3_edit.php" method="post">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <?php 
                    
                    
                    $query='select unitsID as _value,Title as _key from units order by Title   COLLATE utf8_persian_ci';
                    $allunitsID = get_key_value_from_query_into_array($query);
                    
                    $query='select sizeunitsID as _value,Title as _key from sizeunits order by Title  COLLATE utf8_persian_ci';
                    $allsizeunitsID = get_key_value_from_query_into_array($query);
                     
                    $query="select gadget3.Code as _value,
                    replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT('فصل',gadget2.Code,'-',ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )
                    as _key from gadget3
                     inner join gadget2 on gadget2.gadget2ID=gadget3.Gadget2ID
                     inner join gadget1 on gadget1.gadget1ID=gadget2.Gadget1ID and ifnull(gadget1.ISCost,0)=1
                     left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
                    left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
                    left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
                    left outer join units on units.UnitsID=gadget3.unitsID 
                    left outer join toolsmarks on toolsmarks.Gadget3ID=gadget3.Gadget3ID
                    left outer join invoicedetail on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID
                    left outer join operator on operator.operatorID=gadget3.operatorID
                    left outer join spec2 on spec2.spec2id=gadget3.spec2id
                    left outer join spec3 on spec3.spec3id=gadget3.spec3id
                    left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
                    left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
                      order by _key  COLLATE utf8_persian_ci";
                    $IDOperational = get_key_value_from_query_into_array($query);
                    
                    $query='select ProducersID as _value,Title as _key from producers order by Title COLLATE utf8_persian_ci';
                    $allProducersID = get_key_value_from_query_into_array($query);
                     
                     
					 $query="select ProducersID as _value,ProducersID as _key from toolsmarks where Gadget3ID='$Gadget3ID'";
    				 $toolsproducer = get_key_value_from_query_into_array($query);
                      
                      $query='select operatorid as _value,Title as _key from operator order by Title COLLATE utf8_persian_ci';
                    $alloperatorid = get_key_value_from_query_into_array($query);
                    
                    
                    $query='select spec2id as _value,Title as _key from spec2 order by Title COLLATE utf8_persian_ci';
                    $allspec2id = get_key_value_from_query_into_array($query);
                    
                    
                    $query='select spec3id as _value,Title as _key from spec3 order by Title COLLATE utf8_persian_ci';
                    $allspec3id = get_key_value_from_query_into_array($query);
                    
                    $query='select materialtypeid as _value,Title as _key from materialtype order by Title COLLATE utf8_persian_ci';
                    $allmaterialtypeid = get_key_value_from_query_into_array($query);
                    
                    
                     
                    print 
                    "<h1 align='center'>$gadget2title </h1><div style ='text-align:left;'> <a  href=tools1_level3_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget2ID.rand(10000,99999) 
                    ."><img style = \"width: 4%;\" src=\"../img/Return.png\" ></a></div>
                    
                    
                    <tr>
                      ".select_option('materialtypeid','نوع مواد',',',$allmaterialtypeid,0,'','','1','rtl',0,'',$materialtypeid,'','75')."
                      <td width='20%' class='label'>مشخصه1:</td>
                      <td colspan='6' width='80%' class='data'><input name='spec1' type='text' class='textbox' id='spec1' value='$spec1' size='55' maxlength='100' /></td>
                      <td width='20%' class='label'>عنوان:</td>
                      <td colspan='5' width='80%' class='data'><input name='Title' type='text' class='textbox' id='Title' value='$Title' size='27' maxlength='100' /></td>
                     
                    
                    
                    </tr>
                    
                    <tr>
                      ".select_option('unitsID','واحد کالا',',',$allunitsID,0,'','','1','rtl',0,'',$unitsID,'','75')."
                      <td width='20%' class='label'>اندازه:</td>
                      <td  width='80%' class='data'><input name='size11' type='text' class='textbox' id='size11' value='$size11' size='3' maxlength='100' /></td>
                      ".select_option('operatorid','',',',$alloperatorid,0,'','','1','rtl',0,'',$operatorid)."
                      <td width='80%' class='data'><input name='size12' type='text' class='textbox' id='size12' value='$size12' size='3' maxlength='100' /></td>
                      <td  width='80%' class='data'><input name='size13' type='text' class='textbox' id='size13' value='$size13' size='3' maxlength='100' /></td>
                      ".
                      select_option('sizeunitsID','',',',$allsizeunitsID,0,'','','2','rtl',0,'',$sizeunitsID,'','125').
                      "
                      <td width='20%' class='label'>زاویه/طول/سرعت:</td>
                      <td width='80%' class='data'><input name='zavietoolsorattabaghe' type='text' class='textbox' id='zavietoolsorattabaghe' value='$zavietoolsorattabaghe' size='3' maxlength='100' /></td>
                      ".
                      select_option('zavietoolsorattabagheUnitsID','',',',$allsizeunitsID,0,'','','3','rtl',0,'',$zavietoolsorattabagheUnitsID).
                      "
                      
                    </tr>
                    
                    
                    <tr>
                      
                    
                      ".select_option('spec2id','مشخصه2',',',$allspec2id,0,'','','1','rtl',0,'',$spec2id,'','75')."
                      <td width='20%' class='label'>فشار/ضخامت/حجم:</td>
                      <td width='80%' class='data'><input name='fesharzekhamathajm' type='text' class='textbox' id='fesharzekhamathajm' value='$fesharzekhamathajm' size='3' maxlength='100' /></td>
                      ".select_option('fesharzekhamathajmUnitsID','',',',$allsizeunitsID,0,'','','3','rtl',0,'',$fesharzekhamathajmUnitsID,'','156')."
                      
                      ".select_option('spec3id','مشخصه3',',',$allspec3id,0,'','','1','rtl',0,'',$spec3id,'','65')."
                      <td width='20%' class='label'>اندازه مشخصه3</td>
                      <td width='80%' class='data'><input name='spec3size' type='text' class='textbox' id='spec3size' value='$spec3size' size='3' maxlength='100' /></td>
                      ".select_option('spec3sizeunitsid','',',',$allsizeunitsID,0,'','','3','rtl',0,'',$spec3sizeunitsid)."
                      
                    
                    </tr>  
                    
                    <tr>
                      <td width='20%' class='label'>اندازه فرعی:</td>
                      <td width='80%' class='data'><input name='size2' type='text' class='textbox' id='size2' value='$size2' size='8' maxlength='100' /></td>
                      ".select_option('UnitsID2','واحد فرعی ',',',$allsizeunitsID,0,'','','4','rtl',0,'',$UnitsID2,'','213')."
                      <td width='20%' class='label'>ضریب تبدیل ف به ا:</td>
                      <td width='80%' class='data'><input name='UnitsCoef2' type='text' class='textbox' id='UnitsCoef2' value='$UnitsCoef2' size='6' maxlength='100' /></td>
                    <td width='20%' class='label'>کد کالا:</td>
                    <td  colspan='2'  width='80%' class='data'><input name='Code' type='text' class='textbox' id='Code' value='$Code' size='8' maxlength='6' /></td>
                      
                    </tr>
                    
                    <tr>
                    <td width='20%' class='label'>اجرایی1:</td>
                    <td  width='80%' class='data'><input name='Gadget3codeOperational1' type='text' class='textbox' id='Gadget3codeOperational1' 
                    onblur=\"  
                    document.getElementById('Gadget3codecomboOperational1').value=document.getElementById('Gadget3codeOperational1').value;
                    \"
                    value='$Gadget3codeOperational1' size='8' maxlength='100' /></td>
                    ".select_option('Gadget3codecomboOperational1','',',',$IDOperational,0,'','','7','rtl',0,'',$Gadget3codeOperational1,"
                    onblur=\"  
                    document.getElementById('Gadget3codeOperational1').value=document.getElementById('Gadget3codecomboOperational1').value;
                    \"
                    ",455)."
                    <td colspan='2' width='20%' class='label'>ضریب تبدیل:</td>
                      <td width='80%' class='data'><input name='CostCoef1' type='text' class='textbox' id='CostCoef1' value='$CostCoef1' size='3' maxlength='100' /></td>
                      <td width='80%' class='data'><input name='newcoef1' type='text' class='textbox' id='newcoef1' value='$newcoef1' size='3' maxlength='100' />
                      <input name='Gadget3IDOperationalnew1' type='hidden' class='textbox' id='Gadget3IDOperationalnew1' value='$Gadget3IDOperationalnew1' />
                      <input name='Gadget3IDOperational1' type='hidden' class='textbox' id='Gadget3IDOperational1' value='$Gadget3IDOperational1' />
                     
                     
                      </td>
                    </tr>
                    
                    <tr>
                    <td width='20%' class='label'>اجرایی2:</td>
                    <td  width='80%' class='data'><input name='Gadget3codeOperational2' type='text' class='textbox' id='Gadget3codeOperational2' 
                    onblur=\"  
                    document.getElementById('Gadget3codecomboOperational2').value=document.getElementById('Gadget3codeOperational2').value;
                    \"
                    value='$Gadget3codeOperational2' size='8' maxlength='100' /></td>
                    ".select_option('Gadget3codecomboOperational2','',',',$IDOperational,0,'','','7','rtl',0,'',$Gadget3codeOperational2,"
                    onblur=\"  
                    document.getElementById('Gadget3codeOperational2').value=document.getElementById('Gadget3codecomboOperational2').value;
                    \"
                    ",455)."
                    <td colspan='2' width='20%' class='label'>ضریب تبدیل:</td>
                      <td width='80%' class='data'><input name='CostCoef2' type='text' class='textbox' id='CostCoef2' value='$CostCoef2' size='3' maxlength='100' /></td>
                      <td width='80%' class='data'><input name='newcoef2' type='text' class='textbox' id='newcoef2' value='$newcoef2' size='3' maxlength='100' />
                      <input name='Gadget3IDOperationalnew2' type='hidden' class='textbox' id='Gadget3IDOperationalnew2' value='$Gadget3IDOperationalnew2' />
                      <input name='Gadget3IDOperational2' type='hidden' class='textbox' id='Gadget3IDOperational2' value='$Gadget3IDOperational2' /></td>
                    </tr>
                    
                    
                    
                    <tr>
                    <td width='20%' class='label'>اجرایی3:</td>
                    <td  width='80%' class='data'><input name='Gadget3codeOperational3' type='text' class='textbox' id='Gadget3codeOperational3' 
                    onblur=\"  
                    document.getElementById('Gadget3codecomboOperational3').value=document.getElementById('Gadget3codeOperational3').value;
                    \"
                    value='$Gadget3codeOperational3' size='8' maxlength='100' /></td>
                    ".select_option('Gadget3codecomboOperational3','',',',$IDOperational,0,'','','7','rtl',0,'',$Gadget3codeOperational3,"
                    onblur=\"  
                    document.getElementById('Gadget3codeOperational3').value=document.getElementById('Gadget3codecomboOperational3').value;
                    \"
                    ",455)."
                    <td colspan='2' width='20%' class='label'>ضریب تبدیل:</td>
                      <td width='80%' class='data'><input name='CostCoef3' type='text' class='textbox' id='CostCoef3' value='$CostCoef3' size='3' maxlength='100' /></td>
                       <td width='80%' class='data'><input name='newcoef3' type='text' class='textbox' id='newcoef3' value='$newcoef3' size='3' maxlength='100' />
                       <input name='Gadget3IDOperationalnew3' type='hidden' class='textbox' id='Gadget3IDOperationalnew3' value='$Gadget3IDOperationalnew3' />
                      <input name='Gadget3IDOperational3' type='hidden' class='textbox' id='Gadget3IDOperational3' value='$Gadget3IDOperational3' /></td>
                   </tr>
                    
                    
                    <tr>
                    <td width='20%' class='label'>اجرایی4:</td>
                    <td  width='80%' class='data'><input name='Gadget3codeOperational4' type='text' class='textbox' id='Gadget3codeOperational4' 
                    onblur=\"  
                    document.getElementById('Gadget3codecomboOperational4').value=document.getElementById('Gadget3codeOperational4').value;
                    \"
                    value='$Gadget3codeOperational4' size='8' maxlength='100' /></td>
                    ".select_option('Gadget3codecomboOperational4','',',',$IDOperational,0,'','','7','rtl',0,'',$Gadget3codeOperational4,"
                    onblur=\"  
                    document.getElementById('Gadget3codeOperational4').value=document.getElementById('Gadget3codecomboOperational4').value;
                    \"
                    ",455)."
                    <td colspan='2' width='20%' class='label'>ضریب تبدیل:</td>
                      <td width='80%' class='data'><input name='CostCoef4' type='text' class='textbox' id='CostCoef4' value='$CostCoef4' size='3' maxlength='100' /></td>
                       <td width='80%' class='data'><input name='newcoef4' type='text' class='textbox' id='newcoef4' value='$newcoef4' size='3' maxlength='100' />
                       <input name='Gadget3IDOperationalnew4' type='hidden' class='textbox' id='Gadget3IDOperationalnew4' value='$Gadget3IDOperationalnew4' />
                      <input name='Gadget3IDOperational4' type='hidden' class='textbox' id='Gadget3IDOperational4' value='$Gadget3IDOperational4' /></td>
                   </tr>
                    
                    
                    <tr>
                    <td width='20%' class='label'>اجرایی5:</td>
                    <td  width='80%' class='data'><input name='Gadget3codeOperational5' type='text' class='textbox' id='Gadget3codeOperational5' 
                    onblur=\"  
                    document.getElementById('Gadget3codecomboOperational5').value=document.getElementById('Gadget3codeOperational5').value;
                    \"
                    value='$Gadget3codeOperational5' size='8' maxlength='100' /></td>
                    ".select_option('Gadget3codecomboOperational5','',',',$IDOperational,0,'','','7','rtl',0,'',$Gadget3codeOperational5,"
                    onblur=\"  
                    document.getElementById('Gadget3codeOperational5').value=document.getElementById('Gadget3codecomboOperational5').value;
                    \"
                    ",455)."
                    <td colspan='2' width='20%' class='label'>ضریب تبدیل:</td>
                      <td width='80%' class='data'><input name='CostCoef5' type='text' class='textbox' id='CostCoef5' value='$CostCoef5' size='3' maxlength='100' /></td>
                       <td width='80%' class='data'><input name='newcoef5' type='text' class='textbox' id='newcoef5' value='$newcoef5' size='3' maxlength='100' />
                       <input name='Gadget3IDOperationalnew5' type='hidden' class='textbox' id='Gadget3IDOperationalnew5' value='$Gadget3IDOperationalnew5' />
                      <input name='Gadget3IDOperational5' type='hidden' class='textbox' id='Gadget3IDOperational5' value='$Gadget3IDOperational5' /></td>
                   </tr>
                    
                    <tr>
                    <td width='20%' class='label'>Gadget3ID:</td>
                    
                    <td colspan=2 class='data'><input name='Gadget3ID' type='' readonly class='textbox' id='Gadget3ID'  value='$Gadget3ID'  size='18' maxlength='15' /></td>
                    
                    <td class='data'><input type='checkbox' name='IsHide' $IsHide>پنهان</input></td>
                    
                    <td class='data'><input name='Gadget2ID' type='hidden' readonly class='textbox' id='Gadget2ID'  value='$Gadget2ID'  size='2' maxlength='15' /></td>
                    </tr>
                    

                    ";
                    
                    

					 
                     
                     
                     $cnt=0;
                     print "<tr>
                            <table style='border:2px solid;'><a onclick=\"SelectAll();\"><img style = 'width: 5%;' src='../img/accept_page.png' title='  Select All '>  </a>تولیدکنندگان/واردکنندگان/<tr>";
                     foreach ($allProducersID as $key => $value)
                     {
                        if ($value>0)
                        {
                            $cnt++;
                            if (in_array($value, $toolsproducer))
                              print "<td class='data'><input type='checkbox' id='Producer$value' name='Producer$value' checked>$key</input></td>";
                            else 
                              print "<td class='data'><input type='checkbox' id='Producer$value' name='Producer$value'>$key</input></td>";
                        if (($cnt%8)==0)
                            print "</tr><tr>";
                        }
                     }
                    print "</tr></table></tr>";
                            
					  ?>
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="تصحیح" /></td>
                     </tr>
                    </tfoot>
                   </table>
                  </form>
            </div>
			<!-- /content -->

            <!-- footer -->
			<?php include('../includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
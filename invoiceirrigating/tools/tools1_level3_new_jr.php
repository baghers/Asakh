<?php 
/*
tools/tools1_level3_new_jr.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools1_level3_new.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

	$selectedgadget3ID=$_POST['selectedgadget3ID'];//شناسه جدول سطح سوم ابزار
	
    /*
    gadget3operational جدول هزینه های اجرای ابزارها
     gadget3 جدول سطح سوم ابزار
     gadget3id شناسه جدول سطح سوم ابزار
     CostCoef ضریب هزینه اجرا
     newcoef ضریب جدید اجرا در صورت تغییر
     code کد کالا
    */
    $query = "select gadget3operational.CostCoef,gadget3.code gadget3opcode 
    from  gadget3operational 
inner join gadget3 on gadget3.gadget3id=gadget3operational.Gadget3IDOperational
    WHERE gadget3operational.Gadget3ID  ='$selectedgadget3ID';";
                try 
								  {		
									     $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
								  } 
   
    
    if($resquery = mysql_fetch_assoc($result))
    {
        $Gadget3codeOperational1=$resquery["gadget3opcode"];
        $CostCoef1 = $resquery["CostCoef"];        
    }    
    if($resquery = mysql_fetch_assoc($result))
    {
        $Gadget3codeOperational2=$resquery["gadget3opcode"];
        $CostCoef2 = $resquery["CostCoef"];        
    }    
    if($resquery = mysql_fetch_assoc($result))
    {
        $Gadget3codeOperational3=$resquery["gadget3opcode"];
        $CostCoef3 = $resquery["CostCoef"];        
    }    
    if($resquery = mysql_fetch_assoc($result))
    {
        $Gadget3codeOperational4=$resquery["gadget3opcode"];
        $CostCoef4 = $resquery["CostCoef"];        
    }    
    if($resquery = mysql_fetch_assoc($result))
    {
        $Gadget3codeOperational5=$resquery["gadget3opcode"];
        $CostCoef5 = $resquery["CostCoef"];        
    }
    
    
    
    $query = "SELECT gadget3.*,gadget2.title gadget2title  FROM gadget3 
    inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
    WHERE gadget3.Gadget3ID  ='$selectedgadget3ID';";
                    try 
								  {		
									     $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
								  } 
    
    $resquery = mysql_fetch_assoc($result);
	
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
    
    
    $query='select ProducersID as _value,Title as _key from producers order by Title COLLATE utf8_persian_ci';
    $allProducersID = get_key_value_from_query_into_array($query);
    $query="select ProducersID as _value,ProducersID as _key from toolsmarks where Gadget3ID='$selectedgadget3ID'";
    $toolsproducer = get_key_value_from_query_into_array($query);                
    //$boxstr="<table style='border:2px solid;'>ÊæáíÏ˜ääÏÇä/æÇÑÏ˜ääÏÇä/<tr>";
    $boxstr="<table style='border:2px solid;'><tr>";
    foreach ($allProducersID as $key => $value)
    {
        if ($value>0)
        {
            $cnt++;
            if (in_array($value, $toolsproducer))
                $boxstr=$boxstr. "<td class='data'><input type='checkbox' id='Producer$value' name='Producer$value' checked>$key</input></td>";
            else 
                $boxstr=$boxstr. "<td class='data'><input type='checkbox' id='Producer$value' name='Producer$value'>$key</input></td>";
            if (($cnt%8)==0)
                $boxstr=$boxstr. "</tr><tr>";
        }
    }         
           
    $boxstr=$boxstr. "</tr></table>";
                     
    $temp_array = array(
     'Code' => $Code
	,'Title' => $Title
    ,'unitsID' => $unitsID
    ,'Gadget3IDOperational' => $Gadget3IDOperational    
    ,'sizeunitsID' => $sizeunitsID
    ,'spec1' => $spec1
    ,'opsize' => $opsize
    ,'UnitsID2' => $UnitsID2
    ,'UnitsCoef2' => $UnitsCoef2
    ,'CostCoef1' => $CostCoef1
    ,'CostCoef2' => $CostCoef2
    ,'CostCoef3' => $CostCoef3
    ,'CostCoef4' => $CostCoef4
    ,'CostCoef5' => $CostCoef5
    ,'materialtypeid' => $materialtypeid
    ,'zavietoolsorattabaghe' => $zavietoolsorattabaghe
    ,'zavietoolsorattabagheUnitsID' => $zavietoolsorattabagheUnitsID
    ,'fesharzekhamathajm' => $fesharzekhamathajm
    ,'fesharzekhamathajmUnitsID' => $fesharzekhamathajmUnitsID
    ,'size2' => $size2
    ,'size11' => $size11
    ,'size12' => $size12
    ,'size13' => $size13
    ,'operatorid' => $operatorid
    ,'spec2id' => $spec2id
    ,'spec3id' => $spec3id
    ,'spec3size' => $spec3size
    ,'spec3sizeunitsid' => $spec3sizeunitsid
    ,'Gadget3codeOperational1' => $Gadget3codeOperational1
    ,'Gadget3codeOperational2' => $Gadget3codeOperational2
    ,'Gadget3codeOperational3' => $Gadget3codeOperational3
    ,'Gadget3codeOperational4' => $Gadget3codeOperational4
    ,'Gadget3codeOperational5' => $Gadget3codeOperational5
    ,'boxstr' => $boxstr);
        
        
        
        
        echo json_encode($temp_array);
		exit();
    			
	
   
   
   
			
			
		
	

?>




<?php 
/*
tools/tools_producer_copy_jr.php

??? ???? ?? ??? ???? ???? ???? ???????? ?? ???
tools/tools_producer_copy.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

	$selectedProducersIDsource=$_POST['selectedProducersIDsource'];// ???? ???? ????
	$selectedPriceListMasterIDfrom=$_POST['selectedPriceListMasterIDfrom'];//?? ???? ????
    
    /*
           gadget1 ???? ??? ??? ?????
           gadget1id ????? ???? ??? ??? ?????
           gadget2 ???? ??? ??? ?????
           gadget2id ????? ???? ??? ??? ?????
           gadget3 ???? ??? ??? ?????
           gadget3id ????? ???? ??? ??? ?????
           toolsmarks ???? ????? ???? ?? ????? ???? ??? ??????? ??? ?? ????
                ????? ? ???? ?? ????? ????? ???? ????? ?????????? ? ????? ???? ????? ?? ???
                gadget3ID ????? ??? 3 ?????
                ProducersID ????? ???? ??????????
                MarksID ????? ???? ????
           toolsmarksid ????? ????? ? ????
           toolspref ???? ???? ?????
           invoicedetail ???? ??? ???? ??? ??? ??????
           pricelistdetail ???? ??? ???? ?????
    */
    
	if ($selectedProducersIDsource>0)
    {
        $query = "select '0' As _value, ' ' As _key Union All  
        select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID
        inner join gadget3 on gadget3.gadget2ID=gadget2.gadget2ID
        inner join toolsmarks toolsmarksp on gadget3.gadget3ID=toolsmarksp.gadget3ID and toolsmarksp.producersID='$selectedProducersIDsource'
        order by _key  COLLATE utf8_persian_ci";
                       
                       
        $allProducersID = get_key_value_from_query_into_array($query);
        
        $boxstr="<table style='border:2px solid;'><tr>";
        foreach ($allProducersID as $key => $value)
        {
            if ($value>0)
            {
                $cnt++;
                $boxstr=$boxstr. "<td class='data'><input type='checkbox' id='Producer$value' name='Producer$value'>$key</input></td>";
                if (($cnt%5)==0)
                    $boxstr=$boxstr. "</tr><tr>";
            }
        }         
               
        $boxstr=$boxstr. "</tr></table><tr><a onclick=\"SelectAll();\"> Select All </a></tr>";
                         
                     
        $query3="Select '0' As _value, ' ' As _key Union All  
        select distinct marks.marksID as _value,marks.Title as _key from gadget3
        inner join toolsmarks on gadget3.gadget3ID=toolsmarks.gadget3ID and toolsmarks.producersID='$selectedProducersIDsource'
        inner join marks on marks.marksID=toolsmarks.marksID    
        inner join toolsmarks toolsmarksp on gadget3.gadget3ID=toolsmarksp.gadget3ID and toolsmarksp.producersID='$selectedProducersIDsource'
        order by _key  COLLATE utf8_persian_ci";      
        $query=$query3;
        try 
								  {		
									    $result = mysql_query($query); 
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo '????? ??? ? ?? ?? ??? ????? ??: ' .$e->getMessage();
								  }                           
	   
       $width=75;
       $width="style='width: ".$width."px'";         		
	   $selectstr3="<select $font_style_string $width  name='MarksID' id='MarksID'  >";
        while($row = mysql_fetch_assoc($result))
	    {
	  		$options3.="<option  value='$row[_value]'> $row[_key] </option>";  
              
            $cnt3++;$v3=$row['_value'];$key3=$row['_key'];
	    }
        $selectstr3=$selectstr3.$options3."</select>";
        
    
                     
        $temp_array = array('selectstr2' => $boxstr,'selectstr3' => $selectstr3);
        
        
        
        
        echo json_encode($temp_array);
		exit();
        
    }
	if ($selectedPriceListMasterIDfrom>0)
    {
        $query = "select '0' As _value, ' ' As _key Union All
        SELECT distinct producers.producersid as _value,producers.Title  as _key FROM `pricelistdetail`
        inner join toolsmarks on toolsmarks.toolsmarksid=pricelistdetail.toolsmarksid
        inner join producers on producers.producersid=toolsmarks.producersid
        inner join gadget3 on  gadget3.gadget3id=toolsmarks.gadget3id and gadget2id not in (202,376,494,495)
        where ifnull(pricelistdetail.hide,0)=0 and `pricelistdetail`.`PriceListMasterID` ='$selectedPriceListMasterIDfrom' and price>0
        order by _key  COLLATE utf8_persian_ci";
                       
                       
        $allProducersID = get_key_value_from_query_into_array($query);
        
        $boxstr="<table style='border:2px solid;'><tr>";
        foreach ($allProducersID as $key => $value)
        {
            if ($value>0)
            {
                $cnt++;
                $boxstr=$boxstr. "<td class='data'><input type='checkbox' id='Producer$value' name='Producer$value'>$key</input></td>";
                if (($cnt%5)==0)
                    $boxstr=$boxstr. "</tr><tr>";
            }
        }         
               
        $boxstr=$boxstr. "</tr></table><tr><a onclick=\"SelectAll();\"> Select All </a></tr>";
                    
        
    
                     
        $temp_array = array('selectstr2' => $boxstr);
        
        
        
        
        echo json_encode($temp_array);
		exit();
        
    }
    			
	
   
   
   
			
			
		
	

?>




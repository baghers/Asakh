<?php 
/*
tools/tools1_level3_synthetic_jr.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools1_level3_synthetic.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php
/*
        producers جدول تولیدکننده
        producersid شناسه تولید کننده
        producers.Title عنوان تولید کننده
        pricelistdetail جدول قیمت های تایید شده
        marks جدول مارک ها
        toolsmarks جدول ابزار مارک که دارای ستون های ارتباطی زیر می باشد
            ابزار و مارک از ترکیب سناسه طرح، شناسه تولیدکننده و شناسه مارک تشکیل می شود
            gadget3ID شناسه سطح 3 ابزار
            ProducersID شناسه جدول تولیدکننده
            MarksID شناسه جدول مارک
        toolsmarksid شناسه ابزار و مارک
        gadget3 جدول سطح سوم ابزار
*/
	$Gadget3ID=$_POST['Gadget3ID'];
	
    $query = "select toolsmarks.toolsMarksid as _value,CONCAT(CONCAT(producers.title,'_'),marks.title) as _key 
                     from toolsmarks 
                     inner join marks on marks.Marksid=toolsmarks.Marksid
                     inner join producers on producers.Producersid=toolsmarks.Producersid
                     
                     where Gadget3ID in ($Gadget3ID)
                     order by _key   COLLATE utf8_persian_ci";
    try 
								  {		
									     $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
								  }
                                
	   
       $width=150;
       $width="style='width: ".$width."px'";         		
	   $selectstr3="<select $font_style_string $width  name='pmid' id='pmid'  >";
        while($row = mysql_fetch_assoc($result))
	    {
	  		$options3.="<option  value='$row[_value]'> $row[_key] </option>";  
              
            $cnt3++;$v3=$row['_value'];$key3=$row['_key'];
	    }
        $selectstr3=$selectstr3.$options3."</select>";             
    $temp_array = array('selectstr3' => $selectstr3);
        
        echo json_encode($temp_array);
		exit();
    	
?>




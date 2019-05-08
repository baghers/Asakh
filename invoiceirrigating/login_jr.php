<?php 
/*
login_jr.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('includes/connect.php'); ?>
<?php include('includes/check_user.php'); ?>
<?php include('includes/elements.php'); ?>
<?php

// صفحه دریافت لیست شرکت ها بسته به نقش انتخابی در صفحه لاگین
$condnew="";
if (strlen(strstr(strtoupper($_SERVER['SERVER_NAME']),'FCPM'))>0)
$condnew=" and ifnull(clerk.Disable,0)=0  ";
        $string=$_POST['selectedusername'];
		 $ascii = NULL;
             if ((strlen($string)+5)<10)
                $ascii =$ascii.'00'. (strlen($string)+5);
            else if ((strlen($string)+5)<100)
                $ascii =$ascii.'0'. (strlen($string)+5);    
            else $ascii =$ascii.(strlen($string)+5);
            
        for ($i = 0; $i < strlen($string); $i++)
        {
            if (ord($string[$i])<10)
                $ascii =$ascii.'00'. ord($string[$i]);
            else if (ord($string[$i])<100)
                $ascii =$ascii.'0'. ord($string[$i]);    
            else $ascii =$ascii.ord($string[$i]);
        }
        while (strlen($ascii)<120)
            $ascii =$ascii.rand(100,999);


//if ($login_designerCO==1)
	if ($login_ostan==101 || $_POST['selectedrolesID']==3) $login_ost="";
    else $login_ost="and substring(clerk.CityId,1,2)=$login_ostan";
	
	if ($login_ostan==101) $loginDisable="where 1=1";else $loginDisable="where ifnull(clerk.Disable,0)<>1";
	//clerk جدول کاربران
    $query="Select MMC,HW,BR  from clerk 
	WHERE 1=1 $login_ost and substr(NOC,4,(substr(NOC,1,3)-5)*3)=substr('$ascii',4,(substr('$ascii',1,3)-5)*3)";
    $result = mysql_query($query);  
    $row = mysql_fetch_assoc($result);  
	/*
    designerco جدول طراحان
    operatorco جدول مجری ها
    producers جدول تولیدککننده ها
    */
    $query="";
    if ($_POST['selectedrolesID']<4)
    $query="Select '0' As _value, ' ' As _key ";
    if(($_POST['selectedrolesID']==1) && ($row['MMC']>0))
        $query="Select '0' As _value, ' ' As _key Union All
        select distinct DesignerCoID as _value,Title as _key from designerco 
		inner join clerk on designerco.DesignerCoID=clerk.MMC $loginDisable $login_ost 
		order by _key  COLLATE utf8_persian_ci ";
    else if(($_POST['selectedrolesID']==2) && ($row['HW']>0))
        $query="Select '0' As _value, ' ' As _key Union All
        select distinct operatorcoID as _value,Title as _key from operatorco 
		left outer join clerk on operatorco.operatorcoID=clerk.HW $loginDisable $login_ost  $condnew
		order by _key  COLLATE utf8_persian_ci ";
    else if(($_POST['selectedrolesID']==3) && ($row['BR']>0))
        $query="Select '0' As _value, ' ' As _key Union All
        select distinct ProducersID as _value,Title as _key from producers 
		order by _key  COLLATE utf8_persian_ci ";
    
	
	if (strlen($query)>0)
    {                                   
	   $result = mysql_query($query);
       $width=164;
       $width="style='width: ".$width."px'";  
       $options1='';       		
	   $selectstr2="<select  $width name='CoID'  id='CoID' >";
        while($row = mysql_fetch_assoc($result))
	    {
	  		$options1.="<option  value='$row[_value]'> $row[_key] </option>";  
            $cnt1++;$v1=$row['_value'];$key1=$row['_key'];
	    }
        
        $selectstr2.=$options1."</select>";
   }    
      
       $temp_array = array('val0' => $selectstr2);
        
        echo json_encode($temp_array);
		exit();
       
   
   
			
			
		
	

?>




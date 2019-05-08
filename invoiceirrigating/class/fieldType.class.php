<?php

/*

//class/fieldType.class.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/insert/designer_edit.php
 -
*/

//کلاس ایجاد سلکت آپشن
class fieldType
{
    //تابع ایجاد سلکت آپشن با دریافت آرایه کلید و مقدارها
    //$id شناسه المنت
    //$lbl آرایه کلید سلکت آپشن
    //$val آرایه مقدار سلکت آپشن
    function Select($id,$lbl,$val)
	{
        $retorno = "<select name='$id' id='$id' class='span2'  >";
		  $i=0;
		  do{
	      $retorno .= "<option value=\"$val[$i]\">$lbl[$i]</option>\n";
		  $i++;
		  }while($i < count($lbl));

       $retorno .= "</select>\n";
	 return $retorno;
   }
    //تابع ایجاد سلکت آپشن با دریافت کوئری
    //$id شناسه المنت
    //$lbl عنوان ستون کلید سلکت آپشن در کوئری
    //$val عنوان ستون مقدار سلکت آپشن در کوئری
    //$tbl نام جدول پایگاه داده مورد استفاده در کوئری
    //$whr شرط محدود کننده مورد استفاده در کوئری
   function SelectDb($id,$lbl,$val,$tbl,$whr)
	{
        $retorno = "<select name='$id' id='$id' class='span2'  >";
		
        try 
        {		
            $qya= mysql_query("SELECT * FROM $tbl  $whr order by YearID desc ")or die(mysql_error());
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
        
		
		while($rya=mysql_fetch_array($qya))
	       $retorno .= "<option value=".$rya[$val].">".$rya[$lbl]."</option>";
		if($tbl=='year')
		 $retorno .= "<option selected=\"selected\"></option>";   
       $retorno .= "</select>\n";
       
       //print $retorno;
	 return $retorno;
    }
    
    //تابع ایجاد سلکت آپشن با دریافت کوئری و انتخاب یکی از آیتم ها
    //$id شناسه المنت
    //$lbl عنوان ستون کلید سلکت آپشن در کوئری
    //$val عنوان ستون مقدار سلکت آپشن در کوئری
    //$qury کوئری اسخراج آیتم های مورد استفاده در سلکت آپشن
    //$vals مقداری که می خواهیم به عنوان مقدار انتخاب شده و پیش فرض نمایش داده شود
    
	function dropDb($id,$lbl,$val,$qury,$vals)
	{
        $retorno = "<select name='$id' id='$id' class='span2' style='width:120px'  >";
		
		
        
        try 
        {		
            $qya= mysql_query($qury)or die(mysql_error());
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
        
		$retorno .= "<option ></option>";
		while($rya=mysql_fetch_array($qya))
		{
		   if($vals==$rya[$val])
		    $selected='selected';
		  else
  		    $selected='';
	       $retorno .= "<option $selected value=".$rya[$val]." >".$rya[$lbl]."</option>";
		}
       $retorno .= "</select>\n";
	 return $retorno;
    }
    
    //تابع ایجاد سلکت آپشن با دریافت کوئری و انتخاب یکی از آیتم ها به همراه یک سری اتریبیوت های دلخواه ورودی
    //$id شناسه المنت
    //$lbl عنوان ستون کلید سلکت آپشن در کوئری
    //$val عنوان ستون مقدار سلکت آپشن در کوئری
    //$qury کوئری اسخراج آیتم های مورد استفاده در سلکت آپشن
    //$vals مقداری که می خواهیم به عنوان مقدار انتخاب شده و پیش فرض نمایش داده شود    
    //$extra اتریبیوت های دلخواه ورودی
    function dropDb2($id,$lbl,$val,$qury,$vals,$extra)
	{
        $retorno = "<select name='$id' id='$id' class='span2' $extra  >";
		
		
        
        try 
        {		
            $qya= mysql_query($qury)or die(mysql_error());
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
        
		$retorno .= "<option ></option>";
		while($rya=mysql_fetch_array($qya))
		{
		   if($vals==$rya[$val])
		    $selected='selected';
		  else
  		    $selected='';
	       $retorno .= "<option $selected value=".$rya[$val]." >".$rya[$lbl]."</option>";
		}
       $retorno .= "</select>\n";
	 return $retorno;
    }
}

?>
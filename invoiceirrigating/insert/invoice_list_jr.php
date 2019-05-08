<?php 
//insert/invoice_list_jr.php
//اتصال به دیتا بیس
require_once('../includes/connect.php'); 

if ($_POST['type']==1)
{
    if ($_POST['melicode']>0)//در صورتی که کد ملی ارسال شده باشد اطلاعات کد ملی استخراج می شود
    {
        //Farmers جدول بهره برداران
        //NationalCode کد/شناسه ملی
        $query="Select * from Farmers where NationalCode='$_POST[melicode]'";
        try 
        {		
            $result = mysql_query($query);  
            $row = mysql_fetch_assoc($result);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
        //FName نام/ عنوان شرکت
        //LName نام خانوادگی /مدیر عامل
        //FathersName نام پدر/نماینده شرکت
        //BirthDate تاریخ تولد/تاریخ تاسیس
        //BirthPlace محل صدور/ثبت
        //Mobile تلفن همراه
        $temp_array = array(
        'val0' => $row['FName'],
        'val1' => $row['LName'], 
        'val2' => $row['NationalCode'], 
        'val3' => $row['BirthPlace'], 
        'val4' => $row['FathersName'], 
        'val5' => $row['BirthDate'], 
        'val6' => $row['Mobile']);
        echo json_encode($temp_array);
        exit();
    }
    
}
//در این بخش می خواهیم قیمت و واحد آیتم فهارس بها را استخراج نماییم
//$_POST['fehrestsID'] شناسه فهارس بها
//$_POST['Code'] کد آیتم فهارس بها
if (($_POST['fehrestsID']>0)|| ($_POST['Code']>0) )
{
    //$_POST['fehrestsmasterID']=2 فهرست بهای آبیاری تحت فشار
    /*
    fehrests جدول آیتم های فهرست بها ها
    costpricelistdetail جدول قیمت فهرست بهای آبیاری تحت فشار
    gadget1 لیست ابزار سطح یک
    gadget2 لیست ابزار سطح دو
    gadget3 لیست ابزار سطح سه
    costpricelistdetail.Price قیمت آیتم فهرست بها
    fehrests.Code کد فهرست بها
    fehrests.fehrestsID شناسه فهرست بها
    fehrests.Title عنوان فهرست بها
    fehrests.UnitTitle عنوان واحد فهرست بها
    gadget1.IsCost این آیتم هزینه اجرایی می باشد
    Gadget1ID شناسه سطح یک ابزار
    Gadget2ID شناسه سطح دو ابزار
    Gadget3ID شناسه سطح سه ابزار
    gadget3.code کد سطح سه ابزار
    CostPriceListMasterID شناسه فهرست بهای آبیاری تحت فشار طرح
    fehrests.fehrestsmasterID شناسه فصل فهرست بها
    */
    if ($_POST['fehrestsmasterID']==2)
    $query="Select costpricelistdetail.Price,fehrests.Code,fehrests.fehrestsID,fehrests.Title,fehrests.UnitTitle 
    from fehrests
    left outer join gadget1 on gadget1.IsCost = 1
    left outer join gadget2 on gadget2.Gadget1ID=gadget1.Gadget1ID
    left outer join gadget3 on gadget3.Gadget2ID=gadget2.Gadget2ID and gadget3.code=fehrests.Code
    
    left outer join costpricelistdetail on costpricelistdetail.CostPriceListMasterID='$_POST[CostPriceListMasterID]' 
    and costpricelistdetail.gadget3id=gadget3.gadget3id
    
    where fehrests.fehrestsmasterID='$_POST[fehrestsmasterID]' and ( fehrests.fehrestsID='$_POST[fehrestsID]' or fehrests.Code='$_POST[Code]')
    order by costpricelistdetail.Price desc
    ";
    else //سایر فهارس بها
    /*
    fehrests جدول آیتم های فهرست بها ها
    pricelistdetailall جدول قیمت فهرست بهاها
    gadget1 لیست ابزار سطح یک
    gadget2 لیست ابزار سطح دو
    gadget3 لیست ابزار سطح سه
    pricelistdetailall.Price قیمت آیتم فهرست بها
    fehrests.Code کد فهرست بها
    fehrests.fehrestsID شناسه فهرست بها
    fehrests.Title عنوان فهرست بها
    fehrests.UnitTitle عنوان واحد فهرست بها
    gadget1.IsCost این آیتم هزینه اجرایی می باشد
    Gadget1ID شناسه سطح یک ابزار
    Gadget2ID شناسه سطح دو ابزار
    Gadget3ID شناسه سطح سه ابزار
    gadget3.code کد سطح سه ابزار
    CostPriceListMasterID شناسه فهرست بها
    fehrests.fehrestsmasterID شناسه فصل فهرست بها
    */
    
    $query="Select pricelistdetailall.Price,fehrests.Code,fehrests.fehrestsID,fehrests.Title,fehrests.UnitTitle 
    from fehrests
    left outer join pricelistdetailall on pricelistdetailall.CostPriceListMasterID='$_POST[CostPriceListMasterID]' 
    and pricelistdetailall.fehrestsID=fehrests.fehrestsID
    where fehrests.fehrestsmasterID='$_POST[fehrestsmasterID]' and ( fehrests.fehrestsID='$_POST[fehrestsID]' or fehrests.Code='$_POST[Code]')
    order by pricelistdetailall.Price desc ";
    try 
        {		
            $result = mysql_query($query);  
            $row = mysql_fetch_assoc($result);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
                                  
     		
    	   
            
    $temp_array = array('valPrice' => number_format($row['Price']),
     'valCode' => $row['Code'], 'valfehrestsID' => $row['fehrestsID'], 'valUnitTitle' => $row['UnitTitle']);
    echo json_encode($temp_array);
    exit();
}

function ConvertFileToArray($FileName='')//تبدیل محتویات فایل متنی ارسالی به آرایه
  {
  	$ArrayName='';
	$fcontents = file ($FileName);//خواندن محتویات فایل
  	while (list ($line_num, $line) = each ($fcontents))//حلقه خواندن خط به خط از متغیر محتویات
	{
  		 $lineKey=htmlspecialchars ($line);
  		 $Key =substr ($lineKey,0,strlen($line)-strlen(strstr ($lineKey, '=')));//استخراج مقدار قبل از مساوی به عنوان کلید
  		 $Value= substr ($lineKey,strlen($line)-strlen(strstr ($lineKey, '='))+1, strlen(strstr ($lineKey, '='))-1);//تعیین مقدار بعد از مساوی به عنوان مقدار
  		 $ArrayName[$Key]=$Value;//افزودن کلید و مقدار به آرایه
    }
    return($ArrayName);//آرایه خروجی
  }

    $Array=ConvertFileToArray($_SERVER['DOCUMENT_ROOT'].'/cfg.txt');//تبدیل فایل به آرایه
	$home_path_iri=trim("$Array[home_path_iri]");// خواندن آدرس پوشه اصلی برنامه


        //پرس و جوی که شهرستان های یک استان را فیلتر می کند
        $query="Select '0' As _value, ' ' As _key Union All
        select id _value,CityName _key from tax_tbcity7digit where substring(id,1,2)=substring($_POST[selectedsoo],1,2)
        and substring(id,5,3)='000' and substring(id,3,5)!='00000' order by _key  COLLATE utf8_persian_ci ";
        try 
        {		
            $result = mysql_query($query);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
                                 
	   /////////////////////////////ایجاد کومبوباکس شهرستان ها با توجه به پرس و جوی فیلتر شده فوق
       $width=75;//عرض کومبو باکس
       $width="style='width: ".$width."px'";//رشته عرض کومبو باکس         		
	   $selectstr1="<select  $width name='sos'  id='sos' \" onchange = \"FilterComboboxes2('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php',this.tabIndex);\" >";
        while($row = mysql_fetch_assoc($result))
	    {
	  		if ($_POST[selectedsos]==$row['_value'])
              $options1.="<option  value='$row[_value]' selected=\"selected\"> $row[_key] </option>";
            else
              $options1.="<option  value='$row[_value]'> $row[_key] </option>";  
            $cnt1++;$v1=$row['_value'];$key1=$row['_key'];
	    }
        if ($cnt1==2)//در صورتی که تعداد ردیف ها دوتا که یکی اش خالی می باشد تعیین می کنیم که آن آیتم به عنوان پیش فرض انتخاب شود
        {
            $options1="<option  value='0'>  </option><option  value='$v1' selected=\"selected\"> $key1 </option>";
            $selectedProducersID=$v1;
        }
        $selectstr1.=$options1."</select>";
       
       
       //پرس و جوی که بخش یا شهر های های یک شهرستان را فیلتر می کند
        $query="Select '0' As _value, ' ' As _key Union All
        select id _value,CityName _key from tax_tbcity7digit where substring(id,1,4)=substring('$_POST[selectedsos]',1,4)
        and substring(id,6,2)='00' order by _key  COLLATE utf8_persian_ci ";
        try 
        {		
            $result = mysql_query($query);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
                                
	   /////////////////////////////ایجاد کومبوباکس بخش ها با توجه به پرس و جوی فیلتر شده فوق
       $width=75;//عرض کومبو باکس
       $width="style='width: ".$width."px'";//رشته عرض کومبو باکس  
       $options1='';       		
	   $selectstr2="<select  $width name='sob'  id='sob' >";
        while($row = mysql_fetch_assoc($result))
	    {
	  		$options1.="<option  value='$row[_value]'> $row[_key] </option>";  
            $cnt1++;$v1=$row['_value'];$key1=$row['_key'];
	    }
        if ($cnt1==2)//در صورتی که تعداد ردیف ها دوتا که یکی اش خالی می باشد تعیین می کنیم که آن آیتم به عنوان پیش فرض انتخاب شود
        {
            $options1="<option  value='0'>  </option><option  value='$v1' selected=\"selected\"> $key1 </option>";
            $_POST[selectedsos]=$v1;
        }
        $selectstr2.=$options1."</select>";
       
       
       
       //پرس و جوی استخراج هزینه حمل بر اساس سال فهرست بها
       /*
       transportcosttablemaster جدول هزینه های حمل
       year جدول سال ها
       month جدول ماه ها
       $_POST[selectedCostPriceListMasterID] شناسه فهرست بهای ارسالی
       $_POST[ostanid] شناسه استان ارسالی
       */
        $query="Select '0' As _value, ' ' As _key Union All
        select TransportCostTableMasterID as _value,CONCAT(CONCAT(year.Value,' '),month.Title) as _key from transportcosttablemaster
        inner join year on year.YearID=transportcosttablemaster.YearID
        inner join month on month.MonthID=transportcosttablemaster.MonthID
        where CostPriceListMasterID='$_POST[selectedCostPriceListMasterID]' and ostan='$_POST[ostanid]'
         ORDER BY _key DESC";
        try 
        {		
            $result = mysql_query($query);
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
       
       /////////////////////////////ایجاد کومبوباکس هزینه های حمل بر اساس فهرست بهای ارسالی و استان ارسالی
       $width=75;//عرض کومبو باکس
       $width="style='width: ".$width."px'";//رشته عرض کومبو باکس  
        $options1='';       		
	   $selectstr3="<select  $width name='TransportCostTableMasterID'  id='TransportCostTableMasterID' >";
         while($row = mysql_fetch_assoc($result))
	    {
	  		$options1.="<option  value='$row[_value]'> $row[_key] </option>";  
            $cnt1++;$v1=$row['_value'];$key1=$row['_key'];
	    }
        if ($cnt1==2)//در صورتی که تعداد ردیف ها دوتا که یکی اش خالی می باشد تعیین می کنیم که آن آیتم به عنوان پیش فرض انتخاب شود
        {
            $options1="<option  value='0'>  </option><option  value='$v1' selected=\"selected\"> $key1 </option>";
            $_POST['selectedCostPriceListMasterID']=$v1;
        }
        $selectstr3.=$options1."</select>";
        
       $temp_array = array('val0' => $selectstr1, 'val1' => $selectstr2, 'val2' => $selectstr3);
        
        echo json_encode($temp_array);
		exit();
       
   
   
			
			
		
	

?>




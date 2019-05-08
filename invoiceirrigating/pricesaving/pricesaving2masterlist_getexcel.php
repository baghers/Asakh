<?php
/*
pricesaving/pricesaving2masterlist_getexcel.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
pricesaving/pricesaving2masterlist.php
*/

include('../includes/connect.php');
include('../includes/check_user.php');
include('../includes/elements.php');





if ($login_Permission_granted==0) header("Location: ../login.php");
    $PriceListMasterID  = substr($_GET["uid"],40,strlen($_GET["uid"])-45);


/*
month جدول ماه
year جدول سال
*/     
$sql = "SELECT CONCAT(CONCAT(CONCAT(' لیست-قیمت-',monthprice.Title),'-'),yearprice.Value) pr 
FROM pricelistmaster 
left outer join month as monthprice on monthprice.MonthID=pricelistmaster.MonthID  
left outer join year as yearprice on yearprice.YearID=pricelistmaster.YearID 
 where pricelistmaster.PriceListMasterID ='$PriceListMasterID'";
 	try 
								  {		
									  	 $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

$row = mysql_fetch_assoc($result);

    
    $filename=$row['pr'];
    
    
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
       gadget2 جدول سطح دوم ابزار
       gadget1 جدول سطح اول ابزار
       gadget3id شناسه جدول سطح سوم ابزار
       gadget2id شناسه جدول سطح دوم ابزار
       hide غیرفعال نمودن قیمت تایید شده جهت استفاده های بعدی
       PriceListMasterID شناسه لیست قیمت
       price مبلغ
       units جدول واحدهای اندازه گیری کالا
       sizeunits  جدول واحد های سایز کالا مثل اتمسفر میلی متر ووو
       operator جدول عملگر های تشکیل دهنده نام کالا
       spec2 مشخصه 2 کالا ها
       spec3 مشخصه 3 کالا ها
       materialtype  نوع مواد ابزار مانند چدنی، پلی اتیلن و
       toolsmarks جدول ابزار و مارک
       toolsmarksid شناسه ابزار و مارک
       invoicedetail ریز پیش فاکتورها
       toolsmarksid شناسه ابزار و مارک
       toolspref جدول مرجع قیمتی
       */
            
             
    
    $query="select toolsmarks.ToolsMarksID,producers.title producerstitle,marks.title Markstitle, 
            replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as fulltitle 
            ,pricelistdetail.Price,CONCAT(CONCAT(pref.title,' '),mref.title) priceref
            from toolsmarks
            inner join marks on marks.MarksID=toolsmarks.MarksID
            inner join producers on producers.producersID=toolsmarks.producersID
            inner join gadget3 on gadget3.gadget3ID=toolsmarks.gadget3ID
            inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
            inner join gadget1 on gadget1.gadget1ID=gadget2.gadget1ID  and IsCost=0 and gadget1.gadget1id<>68 
            left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
            left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
            left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
            left outer join operator on operator.operatorID=gadget3.operatorID
            left outer join spec2 on spec2.spec2id=gadget3.spec2id
            left outer join spec3 on spec3.spec3id=gadget3.spec3id
            left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
            left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
            
            left outer join toolspref on toolspref.PriceListMasterID='$PriceListMasterID' and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
            left outer join pricelistdetail on  pricelistdetail.PriceListMasterID='$PriceListMasterID' and 
                                            pricelistdetail.ToolsMarksID=(case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end) 
        
       left outer join toolsmarks toolsmarksref on toolsmarksref.toolsmarksid=toolspref.ToolsMarksIDpriceref
       left outer join producers pref on pref.producersid=toolsmarksref.producersid     
       left outer join marks mref on mref.marksID=toolsmarksref.marksID   
       
                                            
            order by fulltitle COLLATE utf8_persian_ci,producerstitle COLLATE utf8_persian_ci,Markstitle COLLATE utf8_persian_ci
            "; 
  
    
    $query="select toolsmarks.ToolsMarksID,producers.title producerstitle,marks.title Markstitle, 
            replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as fulltitle 
            ,pricelistdetail.Price
            from toolsmarks
            inner join marks on marks.MarksID=toolsmarks.MarksID
            inner join producers on producers.producersID=toolsmarks.producersID
            inner join gadget3 on gadget3.gadget3ID=toolsmarks.gadget3ID
            inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
            inner join gadget1 on gadget1.gadget1ID=gadget2.gadget1ID  and IsCost=0 and gadget1.gadget1id<>68 
            left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
            left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
            left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
            left outer join operator on operator.operatorID=gadget3.operatorID
            left outer join spec2 on spec2.spec2id=gadget3.spec2id
            left outer join spec3 on spec3.spec3id=gadget3.spec3id
            left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
            left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
            
            left outer join pricelistdetail on  pricelistdetail.PriceListMasterID='$PriceListMasterID' and 
                                            pricelistdetail.ToolsMarksID=toolsmarks.toolsmarksID 
            where pricelistdetail.Price>0
                                            
            order by fulltitle COLLATE utf8_persian_ci,producerstitle COLLATE utf8_persian_ci,Markstitle COLLATE utf8_persian_ci
            ";  
            
            
    $query="select gadget3.gadget3ID,gadget1.title gadget1title,gadget2.title gadget2title,  
            replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as fulltitle 
            from gadget3
            left outer join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
            left outer join gadget1 on gadget1.gadget1ID=gadget2.gadget1ID  and IsCost=0 and gadget1.gadget1id<>68 
            left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
            left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
            left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
            left outer join operator on operator.operatorID=gadget3.operatorID
            left outer join spec2 on spec2.spec2id=gadget3.spec2id
            left outer join spec3 on spec3.spec3id=gadget3.spec3id
            left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
            left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
            
            where gadget3.gadget3id in
            
            (            
                SELECT toolsmarks.gadget3ID  FROM `invoicedetail`
                inner join toolsmarks on toolsmarks.toolsmarksid=invoicedetail.toolsmarksid
                where toolsmarks.gadget3ID not in 
                (
                SELECT toolsmarks.gadget3ID  FROM `primarypricelistdetail`
                inner join toolsmarks on toolsmarks.toolsmarksid=primarypricelistdetail.toolsmarksid
                )
            )
                                
            order by fulltitle COLLATE utf8_persian_ci
            ";  
            
           // print $query;
           // exit;            
    
     	try 
								  {		
									  	$result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=$filename.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    /*******Start of Formatting for Excel*******/
    //define separator (defines columns in excel & tabs in word)
    $sep = ";"; //tabbed character
    
    //start of printing column names as names of MySQL fields
    for ($i = 0; $i < mysql_num_fields($result); $i++) 
    {
        echo mysql_field_name($result,$i) . $sep;
    }
    print("\n");

    //end of printing column names

    //start while loop to get data

    while($row = mysql_fetch_row($result))
    {
        $schema_insert = "";
        for($j=0; $j<mysql_num_fields($result);$j++)
        {
            if(!isset($row[$j]))
                $schema_insert .= "".$sep;
            elseif ($row[$j] != "")
                $schema_insert .= "$row[$j]".$sep;
            else
                $schema_insert .= "".$sep;
        }
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= $sep;
        print(trim($schema_insert));
        print "\n";
    }

                            
?>

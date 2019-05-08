<?php 
/*
tools/tools1_level3_synthetic.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools1_level3_list.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");
/*
    gadget3operational جدول هزینه های اجرای ابزارها
     gadget3 جدول سطح سوم ابزار
     CostCoef ضریب هزینه اجرا
     newcoef ضریب جدید اجرا در صورت تغییر
     code کد کالا
     
     Num تعداد
     gadget3synthetic جدول سطح 3 ابزار ترکیبی
     Gadget3IDOsynthetic شناسه  جدول سطح 3 ابزار ترکیبی
     gadget3id شناسه جدول سطح سوم ابزار
     ToolsMarksIDpriceref شناسه ابزار مارک مرجع قیمتی           
     toolsmarks جدول ابزار مارک که دارای ستون های ارتباطی زیر می باشد
                ابزار و مارک از ترکیب سناسه طرح، شناسه تولیدکننده و شناسه مارک تشکیل می شود
                gadget3ID شناسه سطح 3 ابزار
                ProducersID شناسه جدول تولیدکننده
                MarksID شناسه جدول مارک
           toolsmarksid شناسه ابزار و مارک
*/
if ($_POST)
{
        $Gadget3IDmaster=$_POST['Gadget3IDmaster'];
        $gadget3ID=$_POST['gadget3ID'];
        $Number=$_POST['Number'];
        $ToolsMarksIDpriceref=$_POST['pmid'];
        
        if ($gadget3ID>0 && $Number>0)
        {
            $sql="INSERT INTO gadget3synthetic(gadget3ID,Gadget3IDOsynthetic, Num,ToolsMarksIDpriceref,SaveTime,SaveDate,ClerkID)
            select gadget3ID,'$gadget3ID','$Number','$ToolsMarksIDpriceref','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid' from gadget3
            where gadget3ID='$Gadget3IDmaster' and gadget3ID not in (SELECT gadget3ID FROM invoicedetail
            inner join toolsmarks on toolsmarks.ToolsMarksID=invoicedetail.ToolsMarksID and toolsmarks.Gadget3ID='$Gadget3IDmaster'
            
            union all
            SELECT gadget3ID FROM pricelistdetail
            inner join toolsmarks on toolsmarks.ToolsMarksID=pricelistdetail.ToolsMarksID and toolsmarks.Gadget3ID='$Gadget3IDmaster' and 
            pricelistdetail.price>0
            );";
            //print $sql;
              try 
								  {		
									     mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
								  } 
             
        }
}
else
{
    $Gadget3IDmaster= substr($_GET["uid"],40,strlen($_GET["uid"])-45);
}
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
  
  $sql = "SELECT gadget2.gadget2id,


replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )

FullTitle

FROM gadget3 
inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID
 
left outer join operator on operator.operatorID=gadget3.operatorID
left outer join spec2 on spec2.spec2id=gadget3.spec2id
left outer join spec3 on spec3.spec3id=gadget3.spec3id
left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
left outer join units on units.unitsID=gadget3.unitsID

where gadget3.gadget3ID='$Gadget3IDmaster' 
order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,sizeunits.title,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,sizeunitszavietoolsorattabaghe.title,cast(fesharzekhamathajm as decimal),fesharzekhamathajm,sizeunitsfesharzekhamathajm.title
 ";
               try 
								  {		
									     $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
								  } 

 
 
$row = mysql_fetch_assoc($result);

$gadget2id = $row['gadget2id'];
$FullTitle = $row['FullTitle'];
                        
      
$sql = "SELECT gadget3synthetic.gadget3syntheticID,
replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )
FullTitle,gadget3synthetic.Num,units.title unitstitle,CONCAT(CONCAT(producers.title,'_'),marks.title) as refp
,pricelistdetail.price,(pricelistdetail.price*gadget3synthetic.Num) pmultnum
 
FROM gadget3synthetic
inner join gadget3 on gadget3.gadget3ID=gadget3synthetic.Gadget3IDOsynthetic  
inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID

left outer join operator on operator.operatorID=gadget3.operatorID
left outer join spec2 on spec2.spec2id=gadget3.spec2id
left outer join spec3 on spec3.spec3id=gadget3.spec3id
left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
left outer join units on units.unitsID=gadget3.unitsID

inner join toolsmarks on toolsmarks.toolsMarksid=gadget3synthetic.ToolsMarksIDpriceref  
inner join marks on marks.Marksid=toolsmarks.Marksid
inner join producers on producers.Producersid=toolsmarks.Producersid


left outer join pricelistmaster on pricelistmaster.PriceListMasterID=(select max(PriceListMasterID) from pricelistmaster where ifnull(pfd,0)=1)
left outer join toolspref on toolspref.PriceListMasterID=pricelistmaster.PriceListMasterID and toolspref.ToolsMarksID=toolsmarks.ToolsMarksID
left outer join pricelistdetail on  pricelistmaster.PriceListMasterID=pricelistdetail.PriceListMasterID and 
pricelistdetail.ToolsMarksID=(case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end)         
        
where gadget3synthetic.gadget3ID='$Gadget3IDmaster' 
order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,sizeunits.title,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,sizeunitszavietoolsorattabaghe.title,cast(fesharzekhamathajm as decimal),fesharzekhamathajm,sizeunitsfesharzekhamathajm.title
 ";
 
 try 
								  {		
									     $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
								  } 

//print $sql;



?>
<!DOCTYPE html>
<html>
<head>
  	<title>اجزاء کالای <?php echo $FullTitle; ?></title>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	
<script type="text/javascript" language='javascript' src='../assets/jquery2.js'></script>

<script type="text/javascript" src="../lib/jquery2.js"></script>
<script type='text/javascript' src='../lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='../lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='../lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='../jquery.autocomplete.js'></script>
<script type='text/javascript' src='localdata.js'></script>
<link rel="stylesheet" type="text/css" href="main.css" />
<link rel="stylesheet" type="text/css" href="../jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="../lib/thickbox.css" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />

    <script>
    function farsireplace(valin)
{
    valin.trim();
    valin = valin.replace(/ي/g, "ی"); 
    valin = valin.replace(/ك/g, "ک"); 
    return valin;
}

 function FilterComboboxes(Url)
                {
                    var Gadget3ID=document.getElementById('gadget3ID').value;
                    //alert(Gadget3ID);
                    
                    $.post(Url, {Gadget3ID:Gadget3ID}, function(data){
                    $('#divpmid').html(data.selectstr3);
                       }, 'json');
                }
                
    </script>
    <!-- /scripts -->
</head>
<body >

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">

			<!-- top -->
        	<?php include('../includes/top.php'); ?>
            <!-- /top -->

            <!-- main navigation -->
            <?php include('../includes/navigation.php'); ?>
            <!-- /main navigation -->
			<!-- main navigation -->
            <?php include('../includes/subnavigation.php'); ?>
            <!-- /main navigation -->

			<!-- header -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
            
            <form action="tools1_level3_synthetic.php" method="post">
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                        
                        <h1 align="center">  اجزاء کالای <?php echo $FullTitle; ?></h1>
                        <div style = "text-align:left;">
                
               <a  href=<?php print 
                    "tools1_level3_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$gadget2id.rand(10000,99999) ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت'></a>
               </div>
               
                          <INPUT type="hidden" id="txtmaxSerial" value="<?php print $maxcode; ?>"/>
                          <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
                          <INPUT type="hidden" id="txturl" value="<?php print "$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php"; ?>"/>
                           <!-- div style = "text-align:left;">
                            <button title='افزودن طرح جدید' style="cursor:pointer;width:70px;height:70px;background-color:transparent; border-color:transparent;" type="button" onclick="add()">
                           <img style = 'width: 60%;' src='../img/Actions-document-new-icon.png' ></button > 
                          </div -->
                          
                          
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                            <th width="1%"></th>
                        	<th width="54%">عنوان</th>
                            <th width="5%">تعداد</th>
                            <th width="5%">واحد</th>
                            <th width="10%">مرجع قیمت</th>
                            <th width="10%">ق م</th>
                            <th width="10%">ت م</th>
                            <th width="5%"></th>
                        </tr>
                    </thead>
                    <thead>
                    </thead>     
                   <tbody>
                   
                        
                    
                                
                   <?php
                   
                            
                   $query="select gadget3.gadget3ID as _value,
                                    replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) as _key from gadget3 
                                    inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id and 
                                    inner join gadget1 on gadget1.gadget1id=gadget2.gadget1id and ifnull(iscost,0)=0
                                    left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
                                    left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
                                    left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
                                    left outer join operator on operator.operatorID=gadget3.operatorID
                                    left outer join spec2 on spec2.spec2id=gadget3.spec2id
                                    left outer join spec3 on spec3.spec3id=gadget3.spec3id
                                    left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
                                    left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
                                    where gadget3.gadget3ID not in (select gadget3ID from gadget3synthetic)
                                    order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,sizeunits.title,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,sizeunitszavietoolsorattabaghe.title,cast(fesharzekhamathajm as decimal),fesharzekhamathajm,sizeunitsfesharzekhamathajm.title";
                    $IDgadget3ID = get_key_value_from_query_into_array($query);
                               
                               $sql = "select gadget3.gadget3ID as _value,
                                    CONCAT(units.title,'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',gadget3.gadget3ID) as _key from gadget3 
                                    left outer join units on units.unitsID=gadget3.unitsID
                                        where gadget3.gadget3ID not in (select gadget3ID from gadget3synthetic)
                                        ";
                      $IDunitsID = get_key_value_from_query_into_array($sql);                        
                     print select_option('gadget3ID','',',',$IDgadget3ID,0,'','','1','rtl',0,'',$gadget3ID,"",1,'').
                            "<td class='data'><div id='divtxtlist'><input type='text' id='suggest' name='suggest'
                               onkeydown=\"document.getElementById('suggest').value=farsireplace(document.getElementById('suggest').value);\"
                                onfocus=\"
                                
                                    var z = new Array(document.getElementById('gadget3ID').length);
                                    for (var i = 0; i < document.getElementById('gadget3ID').length; i++) 
                                    {
                                        document.getElementById('gadget3ID').options[i].text=
                                        farsireplace(document.getElementById('gadget3ID').options[i].text);
                                        var str = document.getElementById('gadget3ID').options[i].text;
                                        z[i] = str;
                                    }
                                    $('#suggest').autocomplete(z, {matchContains: true,minChars: 0});
                                //alert(1);
                                \"
                                onblur=\"document.getElementById('suggest').value=farsireplace(document.getElementById('suggest').value);
                                    var v=document.getElementById('suggest').value;
                                var sel = document.getElementById('gadget3ID');
								for(var i1 = 0; i1 < sel.options.length; i1++) 
                                {
									var selv=sel.options[i1].text; 
                                    selv=farsireplace(selv);
                                    if(selv === v) 
                                    {
                                       sel.selectedIndex = i1;
                                       document.getElementById('unitsID').value=sel.value;
                                       FilterComboboxes('$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/tools/tools1_level3_synthetic_jr.php');
                                       
                                        break;
                                    }
                               } 
                               \"  type='text' class=\"textbox\"  style='width: 400px'  /></div></td>
                            <td class='data'><div id='divNumber'>
                            <input name='Number' type='text' class='textbox' id='Number'  style='width: 150px' /></div>
                            </td>
                            ".select_option('unitsID','',',',$IDunitsID,0,'','disabled','1','rtl',0,'',0,"",100,'')."
                            ".select_option('pmid','',',',array(),0,'','','2','rtl',0,'','0',"",'150')."
                            <td><input   name='submit' type='submit' class='button' id='submit' value='ثبت' /></td>
                            <td class='data'><input name='Gadget3IDmaster' type='hidden' readonly class='textbox' id='Gadget3IDmaster'  value='$Gadget3IDmaster'  size='2' maxlength='15' /></td>
                    ";
                    
                    
         
                     
                    
                    $Total=0;
                    while($row = mysql_fetch_assoc($result)){

                        $ID = $row['gadget3syntheticID'];
                        $FullTitle = $row['FullTitle'];
                        $Num = $row['Num'];
                        $unitstitle = $row['unitstitle'];
                        $Total+=$row['pmultnum'];
                        
?>                      
                        <tr>
                            
                            <td></td>
                            <td><?php echo $FullTitle; ?></td>
                            <td><?php echo $Num; ?></td>
                            <td><?php echo $unitstitle; ?></td>
                            <td><?php echo $row['refp']; ?></td>
                            <td><?php echo number_format($row['price']); ?></td>
                            <td><?php echo number_format($row['pmultnum']); ?></td>
                            <td><a 
                            href=<?php print "tools1_level3_synthetic_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID."_".$Gadget3IDmaster.rand(10000,99999); ?>
                            onClick="return confirm('مطمئن هستید که حذف شود ؟');"
                            > <img style = 'width: 125%;' src='../img/delete.png' title='حذف'> </a></td>
                        </tr><?php

                    }

?>

<tr>
                            
                            <td colspan="6">مجموع</td>
                            <td colspan="2"><?php echo number_format($Total); ?></td>
                        </tr>
                        
                   
                    </tbody>
                   
                </table>
                      
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
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

<?php 
/*
tools/tools1_level3_list.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools1_level2_list.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$formname='tools1_level3';


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
    
$Gadget2ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    

$query = "SELECT gadget1.Title gadget1Title,gadget2.Title gadget2Title,gadget1.Gadget1ID FROM gadget2 
inner join gadget1 on gadget2.Gadget1ID=gadget1.Gadget1ID
where Gadget2ID='$Gadget2ID'";
    try 
        {		
            $result = mysql_query($query);  
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }

$row = mysql_fetch_assoc($result);
$LevelTitle=" - ".$row['gadget1Title']." - ".$row['gadget2Title'];
$Gadget1ID=$row['Gadget1ID'];                

//----------
//----------
$sql = " SELECT gadget3.Code,gadget3.Gadget3ID,gadget3.IsHide,


replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )

FullTitle,case max(ifnull(invoicedetail.ToolsMarksID,0)) when 0 then '' else 'دارد' end gardesh
,

gadget3op.code gadget3opcode,gadget3op.title gadget3optitle,gadget3op.costcoef,units.title unitstitle 

FROM gadget3  
inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
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




left outer join (select gadget3operational.gadget3id,max(gadget3operational.Gadget3IDOperational),max(gadget3operational.costcoef) 
costcoef,gadget3.code,gadget3.title 
from 
(select view2.gadget3operationalID,view2.gadget3ID,view2.Gadget3IDOperational,view2.SaveTime,raw.CostCoef from (
select gadget3operationalID,gadget3ID,Gadget3IDOperational,max(SaveTime) SaveTime from (
SELECT gadget3operationalID,gadget3operational.gadget3ID,Gadget3IDOperational,CostCoef,'2000-09-13 17:04:42' SaveTime FROM `gadget3operational`
inner join gadget3 on gadget3.gadget3ID=gadget3operational.gadget3ID and gadget3.gadget2ID='$Gadget2ID'
union all
SELECT gadget3operationalID,gadget3operational.gadget3ID,gadget3operational.Gadget3IDOperational,newcoef CostCoef,gadget3operationalnewcoefs.SaveTime FROM `gadget3operational`
inner join gadget3 on gadget3.gadget3ID=gadget3operational.gadget3ID and gadget3.gadget2ID='$Gadget2ID'
inner join gadget3operationalnewcoefs on gadget3operationalnewcoefs.Gadget3IDOperationalold=gadget3operational.Gadget3IDOperational
and gadget3operationalnewcoefs.gadget3IDold=gadget3operational.gadget3ID)view1
group by gadget3operationalID,gadget3ID,Gadget3IDOperational)view2
inner join (SELECT gadget3operationalID,gadget3operational.gadget3ID,Gadget3IDOperational,CostCoef,'2000-09-13 17:04:42' SaveTime FROM `gadget3operational`
inner join gadget3 on gadget3.gadget3ID=gadget3operational.gadget3ID and gadget3.gadget2ID='$Gadget2ID'
union all
SELECT gadget3operationalID,gadget3operational.gadget3ID,gadget3operational.Gadget3IDOperational,newcoef CostCoef,gadget3operationalnewcoefs.SaveTime FROM `gadget3operational`
inner join gadget3 on gadget3.gadget3ID=gadget3operational.gadget3ID and gadget3.gadget2ID='$Gadget2ID'
inner join gadget3operationalnewcoefs on gadget3operationalnewcoefs.Gadget3IDOperationalold=gadget3operational.Gadget3IDOperational
and gadget3operationalnewcoefs.gadget3IDold=gadget3operational.gadget3ID)raw on 
raw.gadget3operationalID=view2.gadget3operationalID and raw.gadget3ID=view2.gadget3ID  and raw.Gadget3IDOperational=view2.Gadget3IDOperational
  and raw.SaveTime=view2.SaveTime)
 gadget3operational 
inner join gadget3 on gadget3.gadget3id=gadget3operational.Gadget3IDOperational
group by gadget3operational.gadget3id) 


gadget3op on gadget3op.gadget3id=gadget3.gadget3id




where gadget3.Gadget2ID='$Gadget2ID' and gadget3.Gadget3ID>0
group by gadget3.Code,gadget3.Gadget3ID,gadget3op.title,


replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )
order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,
spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),
size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,sizeunits.title,
cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,sizeunitszavietoolsorattabaghe.title,
cast(fesharzekhamathajm as decimal),fesharzekhamathajm,sizeunitsfesharzekhamathajm.title
    
 ";
    try 
        {		
            $result = mysql_query($sql);  
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }

//print $sql;
?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست ابزار سطح 3 <?php print $LevelTitle; ?></title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

    <script>
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
	}
    
    function DeleteAll(url)
    {
        if (! confirm('مطمئن هستید که حذف شود ؟')) return;
        
        var stid='0';
        for (var j=1;j<=(document.getElementById('records').rows.length-1);j++)
            if (document.getElementById('c'+j).checked && document.getElementById('gardesh'+j).value!='دارد')
                stid=stid+','+document.getElementById('c'+j).name.substr(3);
        
        if (stid.length>1)
        {
            stid =url+"?uid=7589017533115052234031978292123008350454"+stid+"87030";
        
        var stid2="http://"+stid.substring(7).replace("//","/");
        location.href=stid2;
        }
        
    }

    function MoveAll(url)
    {
        
        var stid='0';
        
        for (var j=1;j<=(document.getElementById('records').rows.length-1);j++)
            if (document.getElementById('c'+j).checked)
                stid=stid+','+document.getElementById('c'+j).name.substr(3);
        //alert(url);
            
        if (stid.length>1)
        {
            stid =url+"?uid=7589017533115052234031978292123008350454"+stid+"87030";
        
        var stid2="http://"+stid.substring(7).replace("//","/");
        location.href=stid2;
        }
        
    }
    
         function SelectAll()
                {
                    if ($("input[id^='c']:checked").length == $("input[id^='c']").length)
                    $("input[id^='c']").prop('checked', false);
                    else
                    $("input[id^='c']").prop('checked', true);
                    //$("select[id^='ProducersID']").selectedIndex=0;
                }
                
    
    </script>
    <!-- /scripts -->
</head>
<body>

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
                <table width="95%" align="center">
                    <tbody>
                        <td></td>
                            <h1 align="center">  لبست ابزار سطح 3 <?php print $LevelTitle; ?> </h1>
                        
                           
                
                <div style = "text-align:left;">
                
               <a  href=<?php print 
                    "tools1_level2_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget1ID.rand(10000,99999) ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت'></a>
               </div>
                        
                        
                            
                            <td width="50%" align="left"><?php

							if ($pages > 1){
								echo '<select name="pagination" id="pagination" onChange="selectpage(this);">';
								for($i = 1; $i <= $pages; $i++){
									echo '<option value="'.$i.'"';
									if ($page == $i) echo ' selected';
									echo '>'.$i.'</option>';
								}
								echo '</select>';
							}

                ?></td>
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                            <th width="1%">&nbsp;</th>
                        	<th width="1%">گ</th>
                        	<th width="4%">کد</th>
                            <th width="25%">عنوان</th>
                            <th width="5%"></th>
                            <th width="30%">هزینه اجرایی</th>
                            <th width="5%"></th>
                            <th width="5%">&nbsp;</th>
                            <th width="5%">&nbsp;</th>
                            <th width="5%">&nbsp;</th>
                            <th width="5%">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                    </thead> 
                   <tbody>
                   <a onclick="SelectAll();"><img style = 'width: 5%;' src='../img/accept_page.png' title='  Select All '>  </a>
                 
                <a onclick="MoveAll('<?php print"http://$_SERVER[HTTP_HOST]/$home_path_iri/tools/tools1_level3_movegadget2.php";?>');"><img style = 'width: 4%;' src='../img/Actions-document-export-icon.png' title='انتقال'></a>
                        <a onclick="DeleteAll('<?php print"http://$_SERVER[HTTP_HOST]/$home_path_iri/tools/tools1_level3_delete.php";?>');"><img style = 'width: 4%;' src='../img/app-delete-icon.png' title='حذف'></a>
               <a  href=<?php print 
                    "tools1_level3_new.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget2ID.rand(10000,99999) ?>>
                    <img style = 'width: 4%;' src='../img/Actions-document-new-icon.png' title=' جدید '></a>
                
               
                   <?php
                    $cnt=0;
                    
                    if (isset($result))
                    while($row = mysql_fetch_assoc($result)){
                    $cnt++;
                    
                        $Code = $row['Code'];
                        $ID = $row['Gadget3ID'];
                        $FullTitle = $row['FullTitle'];
                        $gardesh = $row['gardesh'];
                        $mgardesh= $row['mgardesh'];
                        $gpgardesh= $row['gpgardesh'];
                        $IsHidecolor='';if ($row['IsHide']==1) $IsHidecolor='#ff0000';
                        
                        
                        
?>
                        <tr >
                            <td > <input type="checkbox" id="c<?php echo $cnt; ?>" name="chk<?php echo $ID; ?>" value="1"/></td >
                            <td ><input size="1" readonly id="gardesh<?php echo $cnt; ?>" value="<?php echo $gardesh; ?>"/></td>

                            <td ><?php echo $ID; ?></td>
                            <td style = "color:<?php echo $IsHidecolor;?>;"><?php echo $FullTitle; ?></td>
                            <td><?php echo $row['gadget3opcode']; ?></td>
                            <td style = "color:<?php echo $IsHidecolor;?>;"><?php echo $row['gadget3optitle']; ?></td>   
                            <td style = "text-align:center;"><?php echo $row['costcoef']; ?></td>   
                            <td><?php echo $row['unitstitle']; ?></td>            
                            <td><a href=<?php print $formname."_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>> <img style = 'width: 75%;' title='ویرایش' src='../img/file-edit-icon.png' ></a></td>
                            <td><a href=<?php print "tools1_level3_list_gardesh.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>> 
                            <img style = 'width: 75%;' src='../img/accept_page.png' title='گردش'> </a></td>
                            
                            <td><a href=<?php print "tools1_level3_synthetic.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>> 
                            <img style = 'width: 75%;' src='../img/search_page.png' title='  اجزاء '> </a></td>
                            
                            
                        </tr><?php

                    }
                    
                    
                
?>                      
                </tbody>
                    
                      
                </table>
                
                    
                      
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

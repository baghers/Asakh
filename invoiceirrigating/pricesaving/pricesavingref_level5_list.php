<?php 
/*
pricesaving/pricesavingref_level5_list.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
pricesaving/pricesavingref_level4_list.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$formname='toolsmarksaving_level4';


        

if ($login_Permission_granted==0) header("Location: ../login.php");

$Gadget2IDProducersID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);

$linearray = explode('_',$Gadget2IDProducersID);
$Gadget2ID=$linearray[0];//سطح دو ابزار
$ProducersID=$linearray[1];//تولیدکننده
$PriceListMasterID=$linearray[2];//شناسه لیست قیمت

            
$per_page = 1000000;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;

/*
gadget1 جدول سطح 1 ابزار
gadget2 جدول سطح 2 ابزار
producers جدول تولیدکننده
*/
$query =   "select gadget1.Gadget1ID,producers.Title as PTitle,gadget1.Title as g1Title,gadget2.Title as g2Title  from producers,gadget2 
            inner join gadget1 on gadget2.gadget1ID=gadget1.gadget1ID
where 
            ProducersID='$ProducersID' and gadget2ID=$Gadget2ID
            
            
        ";


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
$LevelTitle=$row['PTitle'].' و کالای '.$row['g1Title'].' - '.$row['g2Title'];
$Gadget1ID= $row['Gadget1ID'];       

            /*
       toolsmarks جدول ابزار و مارک
       toolsmarksid شناسه ابزار و مارک
       invoicedetail ریز پیش فاکتورها
       toolsmarksid شناسه ابزار و مارک
       toolspref جدول مرجع قیمتی
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
       month جدول ماه
       year جدول سال
       */
       
$sql = "select distinct gadget3.Gadget3ID,
        replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' ) FullTitle 
        ,marks.title markstitle,toolsmarks.toolsmarksid,CONCAT(CONCAT(pref.title,' '),mref.title) priceref
        ,
        case ifnull(pref.producersid,0) when 0 then pricelistdetailprice.Price else ifnull(pricelistdetailprice.Price,0)  end Price
         
        from gadget3
        
        inner join gadget2 on gadget2.gadget2id=gadget3.gadget2id
        left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
        left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
        left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
        inner join  toolsmarks on toolsmarks.gadget3ID=gadget3.gadget3ID and toolsmarks.ProducersID='$ProducersID'
        inner join marks on marks.marksID=toolsmarks.marksID
            
        left outer join operator on operator.operatorID=gadget3.operatorID
            left outer join spec2 on spec2.spec2id=gadget3.spec2id
            left outer join spec3 on spec3.spec3id=gadget3.spec3id
            left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
            left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
       
       left outer join toolspref on toolspref.ToolsMarksID=toolsmarks.ToolsMarksID and toolspref.PriceListMasterID='$PriceListMasterID'
       
       left outer join pricelistdetail pricelistdetailprice on  pricelistdetailprice.PriceListMasterID='$PriceListMasterID' and 
                                            pricelistdetailprice.ToolsMarksID=(case ifnull(toolspref.ToolsMarksIDpriceref,0) when 0 then toolsmarks.toolsmarksID else toolspref.ToolsMarksIDpriceref end) 
        
       
       
       left outer join toolsmarks toolsmarksref on toolsmarksref.toolsmarksid=toolspref.ToolsMarksIDpriceref
       
       left outer join producers pref on pref.producersid=toolsmarksref.producersid     
       left outer join marks mref on mref.marksID=toolsmarksref.marksID     
       
        where gadget3.gadget2ID=$Gadget2ID
        order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,sizeunits.title,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,sizeunitszavietoolsorattabaghe.title,cast(fesharzekhamathajm as decimal),fesharzekhamathajm,sizeunitsfesharzekhamathajm.title  ";
//print $sql;


						try 
								  {		
									  $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست ابزار سطح 3 جهت ثبت مارک مرجع قیمتی<?php print $LevelTitle; ?></title>
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
            if (document.getElementById('c'+j).checked)
                stid=stid+','+document.getElementById('c'+j).name.substr(3);
        
        if (stid.length>1)
        {
            stid =url+"?uid=7589017533115052234031978292123008350454"+stid+"87030";
        
        var stid2="<?php print"$_server_httptype";?>://"+stid.substring(7).replace("//","/");
        location.href=stid2;
        }
        
    }

    function EditAll(url)
    {
        var stid='0';
        
        
        for (var j=1;j<=(document.getElementById('records').rows.length-2);j++)
            if (document.getElementById('c'+j).checked)
                stid=stid+','+document.getElementById('c'+j).name.substr(3);
        //alert(stid);
        
        //alert(1);
        if (stid.length>1)
        {
            //stid =url+"?uid=7589017533115052234031978292123008350454"+stid+"87030";
            stid =url+"?v1="+document.getElementById('kk').value+"&uid=7589017533115052234031978292123008350454"+stid+"87030";
        
        var stid2="<?php print"$_server_httptype";?>://"+stid.substring(7).replace("//","/");
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
                            <h1 align="center">  لبست ابزار سطح 3 جهت ثبت مارک مرجع قیمتی برای تولیدکننده <?php print $LevelTitle; ?> </h1>
                        
                            <div style = "text-align:left;"><a href=<?php print "pricesavingref_level4_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget1ID.'_'.$ProducersID.'_'.$PriceListMasterID.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                           
                            
                            
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
                        	<th width="5%"></th>
                        	<th width="5%">کد</th>
                            <th width="35%">عنوان</th>
                        	<th width="10%"><?php if ($login_RolesID==1) echo "مارک";?></th>
                        	<th width="25%"><?php if ($login_RolesID==1) echo "مارک قیمتی مرجع";?></th>
                        	<th width="15%">قیمت</th>
                        </tr>
                    </thead>
                    <thead>
                    </thead> 
                   <tbody>
                <td > <input type="hidden" id="kk" name="kk" value="<?php echo $ProducersID.'_'.$Gadget2ID.'_'.$PriceListMasterID; ?>"/></td >   
                <a onclick="SelectAll();"><img style = 'width: 5%;' src='../img/accept_page.png' title='  Select All '>  </a>
                <a onclick="EditAll('<?php print"$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/pricesaving/pricesavingref_level3_groupsaveref.php";?>');"><img style = 'width: 4%;' src='../img/add_to_folder.png' title='ثبت مارک قیمت مرجع'>
                          
                        <?php
                    
                    $cnt=0;
                    while($row = mysql_fetch_assoc($result)){
                    $cnt++;
                        $Code = $row['Code'];
                        $ID = $row['Gadget3ID'].'_'.$row['toolsmarksid'];
                        $Title = $row['FullTitle'];
                        
                        
                        
                        
                        
?>
                        <tr>
                            <td > <input type="checkbox" id="c<?php echo $cnt; ?>" name="chk<?php echo $ID; ?>" value="1"/></td >
                            <td><?php 
                            echo $row['toolsmarksid']; ?></td>
                            <td><?php echo $Title; ?></td>
                            <td><?php if ($login_RolesID==1) echo $row['markstitle']; ?></td>
                            <td><?php 
                            if ($login_RolesID==1)
                                echo $row['priceref']; ?></td>
                            <td><?php if ($row['Price']>0) echo number_format($row['Price']); else echo $row['Price'];  ?></td>
                            
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

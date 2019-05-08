<?php 
/*
tools/tools1_level3_list_gardesh.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools1_level3_list.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$tblname='applicantmaster';
if ($login_Permission_granted==0) header("Location: ../login.php");



$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);//شناسه جدول سطح سوم ابزار

$Gadget3IDmaster=$ids;
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
replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT
(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )

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
order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,sizeunits.title,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,sizeunitszavietoolsorattabaghe.title,cast(fesharzekhamathajm as decimal),fesharzekhamathajm,sizeunitsfesharzekhamathajm.title";
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

$gadget2id = $row['gadget2id'];
$FullTitle = $row['FullTitle'];        
$sql1 = "SELECT distinct gadget2.gadget2id,
replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT
(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )

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
inner join gadget3operational on gadget3operational.Gadget3IDOperational='$Gadget3IDmaster' and gadget3operational.gadget3ID=gadget3.gadget3ID


order by gadget2.Title COLLATE utf8_persian_ci,materialtype.title COLLATE utf8_persian_ci,spec1 COLLATE utf8_persian_ci,gadget3.Title COLLATE utf8_persian_ci,CAST(size11 AS Decimal),size11,CAST(size12 AS Decimal),size12,CAST(size13 AS Decimal),size13,sizeunits.title,cast(zavietoolsorattabaghe as decimal),zavietoolsorattabaghe,sizeunitszavietoolsorattabaghe.title,cast(fesharzekhamathajm as decimal),fesharzekhamathajm,sizeunitsfesharzekhamathajm.title

";


try 
        {		
            $result1 = mysql_query($sql1);  
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        } 


$sql = "select distinct year.Value fb,applicantstates.Title applicantstatesTitle,operatorco.title operatorcotitle, 
  designerco.title DesignerCotitle ,ApplicantFName,ApplicantName,invoicemaster.Title invoicemasterTitle
  ,applicantmaster.applicantmasterid,applicantmaster.DesignerCoID,applicantmaster.operatorcoID,applicantmaster.applicantstatesID 
  from invoicedetail
inner join invoicemaster on invoicemaster.invoicemasterid=invoicedetail.invoicemasterid 
inner join applicantmaster on applicantmaster.applicantmasterid=invoicemaster.applicantmasterid
inner join applicantstates on applicantstates.applicantstatesID=applicantmaster.applicantstatesID
inner join costpricelistmaster on costpricelistmaster.CostPriceListMasterID=applicantmaster.CostPriceListMasterID
inner join year on year.YearID=costpricelistmaster.YearID
left outer join designerco on designerco.DesignerCoID=applicantmaster.DesignerCoID
left outer join operatorco on operatorco.operatorcoID=applicantmaster.operatorcoID
inner join toolsmarks on toolsmarks.toolsmarksid=invoicedetail.toolsmarksid 
inner join (SELECT gadget3ID FROM gadget3operational 
where gadget3operational.Gadget3IDOperational='$Gadget3IDmaster') gardesh on gardesh.gadget3ID=toolsmarks.gadget3ID
order by fb desc,applicantstatesTitle COLLATE utf8_persian_ci,ApplicantName  COLLATE utf8_persian_ci,ApplicantFName COLLATE utf8_persian_ci";



try 
        {		
            $result = mysql_query($sql);
            $row = mysql_fetch_assoc($result); 
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
  	<title>گردش هزینه اجرایی <?php echo $FullTitle; ?></title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

    <script>
	function selectpage(obj){
		window.location.href = '?page=' + obj.value;
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
                        <tr>
                        <h1 align="center">گردش هزینه اجرایی <?php echo $FullTitle; ?></h1>
                          <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
                          
                           <!--INPUT type="button" value="افزودن طرح جدید" onclick="add()"/-->
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
                        	<th >ردیف</th>
                        	<th >طراح</th>
                        	<th >مجری</th>
                            <th >نام طرح</th>
                            <th >عنوان پیش فاکتور</th>
                            <th >فهرست بها</th>
                            <th >وضعیت</th>
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                   <?php
                   
                   $cnt=0;
                    do{
                        $cnt++;
                        $DesignerCotitle = $row['DesignerCotitle'];
                        $ApplicantName = $row['ApplicantFName'].' '.$row['ApplicantName'];
                        $invoicemasterTitle = $row['invoicemasterTitle'];
?>                      
                        <tr>
                            <td><?php echo $cnt; ?></td>
                            <td><?php echo $DesignerCotitle; ?></td>
                            <td><?php echo $row['operatorcotitle']; ?></td>
                            <td><?php echo "<a  target='_blank' href='../insert/summaryinvoice.php?uid=".
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).$row['applicantmasterid'].'_2_'.$row['DesignerCoID'].'_'.$row['operatorcoID'].'_'.$row['applicantstatesID'].
                            rand(10000,99999)."'>$ApplicantName</a>";
                            
                            ; ?></td>
                            <td><?php echo $invoicemasterTitle; ?></td>
                            <td><?php echo $row['fb']; ?></td>
                            <td><?php echo $row['applicantstatesTitle']; ?></td>
                        </tr><?php

                    }
                    while($row = mysql_fetch_assoc($result));

?>
                   
                    </tbody>
                   
                </table>
                
                
                
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                        	<th >ردیف</th>
                        	<th >عنوان</th>
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                   <?php
                   $cnt=0;
                    while($row1 = mysql_fetch_assoc($result1))
                    {
                        $cnt++;
                        
?>                      
                        <tr>
                            <td><?php echo $cnt; ?></td>
                            <td><?php echo $row1['FullTitle']; ?></td>
                        </tr><?php

                    }

?>
                   
                    </tbody>
                   
                </table>
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
                   
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

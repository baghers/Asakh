<?php 
/*
tools/toolsunused.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود

*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

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


    $field="replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )";
    $sql = "select 
         CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title) as f2,
         $field as f4
         from gadget3 
         inner join gadget2 on gadget2.gadget2ID=gadget3.gadget2ID
         inner join gadget1 on gadget1.gadget1ID=gadget2.gadget1ID and iscost<>1
         left outer join sizeunits sizeunitszavietoolsorattabaghe on sizeunitszavietoolsorattabaghe.SizeUnitsID=gadget3.zavietoolsorattabagheUnitsID 
         left outer join sizeunits sizeunitsfesharzekhamathajm on sizeunitsfesharzekhamathajm.SizeUnitsID=gadget3.fesharzekhamathajmUnitsID 
         left outer join sizeunits on sizeunits.SizeUnitsID=gadget3.sizeunitsID 
            left outer join operator on operator.operatorID=gadget3.operatorID
            left outer join spec2 on spec2.spec2id=gadget3.spec2id
            left outer join spec3 on spec3.spec3id=gadget3.spec3id
            left outer join sizeunits spec3sizeunits on spec3sizeunits.SizeUnitsID=gadget3.spec3sizeunitsid
            left outer join materialtype on materialtype.materialtypeid=gadget3.materialtypeid
         where gadget3.gadget3id not in (select gadget3id from toolsmarks)
         order by f4   COLLATE utf8_persian_ci";
        $result = mysql_query($sql);
        //print $sql;
	
?>
<!DOCTYPE html>
<html>
<head>
  	<title>کالاهای بدون مارک</title>
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
                        
                        <h1 align="center">کالاهای بدون مارک<?php print $row['ApplicantName'] ?> </h1>
                            
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
                        	<th width="30%">گروه کالا</th>
                            <th width="70%">عنوان</th>
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                   <?php
                   while($row = mysql_fetch_assoc($result)){

                        $f2 = $row['f2'];
                        $f4 = $row['f4'];
                    $hasresult=1;
?>                      
                        <tr>    
                            <td><?php echo $f2; ?></td>
                            <td><?php echo $f4; ?></td>
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

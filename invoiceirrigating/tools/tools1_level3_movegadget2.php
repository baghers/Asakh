<?php 
/*
tools/tools1_level3_movegadget2.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools1_level3_list.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");
    /*
        gadget3 جدول سطح سوم ابزار
        gadget2 جدول سطح دوم ابزار
        gadget3id شناسه جدول سطح سوم ابزار
        gadget2id شناسه جدول سطح دوم ابزار
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
if (! $_POST)
{
    $id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    
    $query = "SELECT replace(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(gadget2.Title,' '),ifnull(materialtype.title,'')),' '),ifnull(spec1,'')),' '),ifnull(gadget3.Title,'')),CONCAT(' ' ,CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(size11,''),''),ifnull(operator.Title,'')),''),ifnull(size12,'')),''),ifnull(size13,' ')),CONCAT(ifnull(sizeunits.title,''),' ') ))),ifnull(zavietoolsorattabaghe,'')),''),ifnull(sizeunitszavietoolsorattabaghe.title,'')),''),ifnull(spec2.title,'')),' '),ifnull(fesharzekhamathajm,'')),''),ifnull(sizeunitsfesharzekhamathajm.title,'')),' '),CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(ifnull(spec3.Title,''),''),ifnull(spec3size,'')),''),ifnull(spec3sizeunits.title,'')),' ')),''),' '),' '),'  ',' ' )

FullTitle,gadget2.title gadget2title,gadget3.gadget2ID

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
WHERE gadget3.Gadget3ID in ($id)
order by FullTitle COLLATE utf8_persian_ci

    ";
    //print $query;
    try 
        {		
            $result = mysql_query($query);  
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        } 
        
    
    if (!$id) header("Location: ../logout.php");
}

$register = false;

if ($_POST){
	$Gadget3IDs = $_POST["Gadget3IDs"];
    $transfergadget2ID = $_POST["transfergadget2ID"];
    $gadget2ID = $_POST["gadget2ID"];
    
    
	
	$query = "
		UPDATE gadget3 SET
		Gadget2ID = '" . $transfergadget2ID . "'
		WHERE Gadget3ID in ( $Gadget3IDs );";
        try 
        {		
            $result = mysql_query($query);  
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        } 
        
        $register = true;
    
    
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>انتقال ابزار</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
</head>
<body>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php

				if ($_POST){
					if ($register){
						echo '<p class="note">ثبت با موفقيت انجام شد</p>';
                        header("Location: tools1_level3_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$gadget2ID.rand(10000,99999));
                        
					}else{
						echo '<p class="error">خطا در ثبت...</p>';
					}
				}

?>
			<!-- top -->
        	<?php include('../includes/top.php'); ?>
            <!-- /top -->

            <!-- main navigation -->
            <?php include('../includes/navigation.php'); ?>
            <!-- /main navigation -->
            <?php include('../includes/subnavigation.php'); ?>

			<!-- header -->
            <?php include('../includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
                <form action="tools1_level3_movegadget2.php" method="post">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <?php 
                    $sttools="";
                    while($row = mysql_fetch_assoc($result))
                    {
                        $sttools.="<br>$row[FullTitle]";
                        $gadget2ID=$row['gadget2ID'];
                    }
    
                    
                     
                    $query="select distinct gadget2.gadget2ID as _value,CONCAT(CONCAT(gadget1.Title,'-'),gadget2.Title)  as _key from gadget1 
                            inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID
                            order by _key   COLLATE utf8_persian_ci";
                    $IDallgadget2ID = get_key_value_from_query_into_array($query);
                    
                    
                    print $sttools.
                    "<h1 align='center'></h1><div style ='text-align:left;'> <a  href=tools1_level3_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$gadget2ID.rand(10000,99999) 
                    ."><img style = \"width: 4%;\" src=\"../img/Return.png\" ></a></div>
                    
                    <td class='data'><input name='Gadget3IDs' type='hidden' readonly class='textbox' id='Gadget3IDs'  value='$id'  /></td>
                    <td class='data'><input name='gadget2ID' type='hidden' readonly class='textbox' id='gadget2ID'  value='$gadget2ID'  /></td>
                    
                    <tr>
                    
                    "
                    .select_option('transfergadget2ID','انتقال به سطح',',',$IDallgadget2ID,0,'','','1','rtl')
                    ;
                    
                    
                            
					  ?>
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="انتقال" /></td>
                     </tr>
                    </tfoot>
                   </table>
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
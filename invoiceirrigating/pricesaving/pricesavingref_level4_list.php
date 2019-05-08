<?php 
/*
pricesaving/pricesavingref_level4_list.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
pricesaving/pricesavingref_level5_list.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


        

if ($login_Permission_granted==0) header("Location: ../login.php");

$Gadget1IDProducersID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);

$linearray = explode('_',$Gadget1IDProducersID);
$Gadget1ID=$linearray[0];//شناسه سطح 1 ابزار
$ProducersID=$linearray[1];//تولیدکننده
$PriceListMasterID=$linearray[2];//شناسه لیست قیمت
            
$per_page = 1000000;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;

//producers جدول تولیدکننده
$query =   "select producers.Title as PTitle,gadget1.Title as g1Title  from producers,gadget1 where 
            ProducersID='$ProducersID' and gadget1ID=$Gadget1ID
            
            
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
$LevelTitle=$row['PTitle'].' و کالای '.$row['g1Title'];
        
/*
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
*/

$sql = "select distinct gadget2.Gadget2ID,gadget2.Title,
         gcnt.cnt from gadget2
        inner join gadget3 on gadget3.gadget2ID=gadget2.gadget2ID 
        inner join  toolsmarks toolsmarksp on toolsmarksp.gadget3ID=gadget3.gadget3ID and toolsmarksp.ProducersID='$ProducersID'
        inner join (select count(*) cnt,gadget2id from toolsmarks inner join gadget3 on toolsmarks.gadget3id=gadget3.gadget3id and toolsmarks.ProducersID='$ProducersID' group by gadget2id) gcnt on 
        gcnt.Gadget2ID=gadget2.Gadget2ID
        where gadget2.gadget1ID=$Gadget1ID
        order by gadget2.Title   COLLATE utf8_persian_ci ";
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
  	<title>لیست ابزار سطح 2 جهت ثبت مارک مرجع قیمتی<?php print $LevelTitle; ?></title>
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
                        <td></td>
                            <h1 align="center">  لبست ابزار سطح 2 جهت ثبت مارک مرجع قیمتی برای تولیدکننده <?php print $LevelTitle; ?> </h1>
                        
                            <div style = "text-align:left;"><a href=<?php print "pricesavingref_level3_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$PriceListMasterID.'_'.$ProducersID.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
                            
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
                        	<th width="10%">کد</th>
                            <th width="70%">عنوان</th>
                            <th width="10%">تعداد</th>
                            <th width="5%">&nbsp;</th>
                            <th width="5%">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                    </thead> 
                   <tbody><?php
                    $cnt=0;
                    while($row = mysql_fetch_assoc($result)){
                    $cnt++;
                        $Code = $row['Code'];
                        $ID = $row['Gadget2ID'].'_'.$ProducersID.'_'.$PriceListMasterID;
                        $Title = $row['Title'];
?>
                        <tr>
                            <td><?php echo $Code; ?></td>
                            <td><?php echo $Title; ?></td>
                            <td><?php echo $row['cnt']; ?></td>
                            <td><a href=<?php print "pricesavingref_level5_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                             <img style = 'width: 60%;' src='../img/attachment.png' title='  ثبت مرجع قیمتی '> </a></td>
                            
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

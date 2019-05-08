<?php 
/*
pricesaving/pricesavingref_level3_list.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
pricesaving/pricesavingref_level2_list.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php



        

if ($login_Permission_granted==0) header("Location: ../login.php");
    
    
$linearray = explode('_',substr($_GET["uid"],40,strlen($_GET["uid"])-45));
$PriceListMasterID=$linearray[0];//لیست قیمت
$ProducersID=$linearray[1];//تولیدکننده

$per_page = 1000000;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;

//producers تولیدکننده
$query =   "select Title from producers where 
            ProducersID='$ProducersID'";

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
$LevelTitle=$row['Title'];//عنوان سطح
        
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
$sql = "select distinct gadget1.Gadget1ID,gadget1.Title from gadget1 
        inner join gadget2 on gadget2.gadget1ID=gadget1.gadget1ID
        inner join gadget3 on gadget3.gadget2ID=gadget2.gadget2ID
        inner join  toolsmarks on toolsmarks.gadget3ID=gadget3.gadget3ID and toolsmarks.ProducersID='$ProducersID'
        order by gadget1.Title    COLLATE utf8_persian_ci";
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
  	<title>لیست ابزار سطح 1 جهت ثبت مارک مرجع قیمتی<?php print $LevelTitle; ?></title>
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
                            <h1 align="center">  لبست ابزار سطح 1 جهت ثبت مارک مرجع برای تولیدکننده <?php print $LevelTitle; ?> </h1>
                        
                            <div style = "text-align:left;"><a href=<?php print "pricesavingref_level2_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ProducersID.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
                            
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
                            <th width="80%">عنوان</th>
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
                        

                        
                        $ID = $row['Gadget1ID'].'_'.$ProducersID.'_'.$PriceListMasterID.'_1';
                        $Title = $row['Title'];
?>
                        <tr>
                            <td><?php echo $Code; ?></td>
                            <td><?php echo $Title; ?></td>
                            <td><a href=<?php print "pricesavingref_level4_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                             <img style = 'width: 60%;' src='../img/search_page.png' title='  ريز '> </a>
                             </td>
                             <td><a href=
                             <?php print "pricesavingref_level3_groupsaveref.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>
                             >
                             <img style = 'width: 60%;' src='../img/add_to_folder.png' title='ثبت مارک قیمت مرجع'></a>
                             </td>
                            
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

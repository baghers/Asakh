<?php 
/*
tools/toolsmarksaving_level1_list.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools1_level3_synthetic.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$formname='toolsmarksaving_level1_';

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
*/
//----------
//----------
$sql = "select case ifnull(prodash.producersid,0)>0 when 1 then '--' else '' end hasprodash, producers.ProducersID,producers.Title,marks1.title markid1t,marks2.title markid2t,marks3.title markid3t,
marks4.title markid4t,marks5.title markid5t,marks6.title markid6t,markid1,markid2,markid3,markid4,markid5,markid6 from ( select ProducersID,markid1,markid2,markid3,markid4,markid5 
,(select max(marksid) marksid from  toolsmarks where toolsmarks.ProducersID=view5.ProducersID and toolsmarks.marksid<>128
 and toolsmarks.marksid<>markid1 and toolsmarks.marksid<>markid2 and toolsmarks.marksid<>markid3 and toolsmarks.marksid<>markid4
 and toolsmarks.marksid<>markid5) markid6

from ( select ProducersID,markid1,markid2,markid3,markid4 
,(select max(marksid) marksid from  toolsmarks where toolsmarks.ProducersID=view4.ProducersID and toolsmarks.marksid<>128
 and toolsmarks.marksid<>markid1 and toolsmarks.marksid<>markid2 and toolsmarks.marksid<>markid3 and toolsmarks.marksid<>markid4) markid5

from ( select ProducersID,markid1,markid2,markid3 
,(select max(marksid) marksid from  toolsmarks where toolsmarks.ProducersID=view3.ProducersID and toolsmarks.marksid<>128
 and toolsmarks.marksid<>markid1 and toolsmarks.marksid<>markid2 and toolsmarks.marksid<>markid3) markid4

from ( select ProducersID,markid1,markid2 
,(select max(marksid) marksid from  toolsmarks where toolsmarks.ProducersID=view2.ProducersID and toolsmarks.marksid<>128
 and toolsmarks.marksid<>markid1 and toolsmarks.marksid<>markid2) markid3
 
from (   select ProducersID,markid1 
,(select max(marksid) marksid from  toolsmarks where toolsmarks.ProducersID=view1.ProducersID and toolsmarks.marksid<>128
 and toolsmarks.marksid<>markid1) markid2

from ( SELECT producers.ProducersID
,(select max(marksid) marksid from  toolsmarks where toolsmarks.ProducersID=producers.ProducersID and toolsmarks.marksid<>128) markid1 
FROM producers) view1) view2) view3) view4) view5) view6

left outer join marks marks1 on marks1.marksID=markid1
left outer join marks marks2 on marks2.marksID=markid2
left outer join marks marks3 on marks3.marksID=markid3
left outer join marks marks4 on marks4.marksID=markid4
left outer join marks marks5 on marks5.marksID=markid5
left outer join marks marks6 on marks6.marksID=markid6
inner join producers on producers.producersid=view6.ProducersID
left outer join (select distinct producersid from toolsmarks where marksid=128) prodash on prodash.producersid=producers.producersid


 order by producers.Title  COLLATE utf8_persian_ci";
     try 
								  {		
									     $result = mysql_query($sql);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
								  }

?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست تولیدکنندگان جهت ثبت مارک</title>
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
                            <h1 align="center">  لیست تولیدکنندگان جهت ثبت مارک  </h1>
                        
                            
                            
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
                            <th width="25%">عنوان</th>
                            <th width="10%">مارک1</th>
                            <th width="10%">مارک2</th>
                            <th width="10%">مارک3</th>
                            <th width="10%">مارک4</th>
                            <th width="10%">مارک5</th>
                            <th width="10%">مارک6</th>
                            <th width="10%">مارک7</th>
                            <th width="5%">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                    </thead> 
                   <tbody><?php

                    while($row = mysql_fetch_assoc($result)){

                        $Code = $row['Code'];
                        $Title = $row['Title'];
                        $ID=$row['ProducersID'];
?>
                        <tr>
                            <td><?php echo $Title; ?></td>
                            <td><?php echo $row['hasprodash']; ?></td>
                            <td><?php echo $row['markid1t']; ?></td>
                            <td><?php echo $row['markid2t']; ?></td>
                            <td><?php echo $row['markid3t']; ?></td>
                            <td><?php echo $row['markid4t']; ?></td>
                            <td><?php echo $row['markid5t']; ?></td>
                            <td><?php echo $row['markid6t']; ?></td>
                            <td><a href=<?php print "toolsmarksaving_level2_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                             <img style = 'width: 60%;' src='../img/search_page.png' title='  ريز '> </a></td>
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

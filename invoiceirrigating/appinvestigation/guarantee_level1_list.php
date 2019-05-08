<?php 

/*

//appinvestigation/guarantee_level1_list.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
/appinvestigation/guarantee_level2_list.php
 -
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


if ($login_Permission_granted==0) header("Location: ../login.php");
$per_page = 1000000;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;
//----------
//----------

$sql = " select 1 type,'فروشندگان' title union all select 2 type,'شرکت های مجری' title  
 order by title COLLATE utf8_persian_ci";
 
$result = mysql_query($sql);
						try 
							  {		
								mysql_query($sql);
							  }
							  //catch exception
							  catch(Exception $e) 
							  {
								echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
							  }

?>
<!DOCTYPE html>
<html>
<head>
  	<title>تضمین</title>
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
                            <h1 align="center">تضمین</h1>
                        
                       
                          
                            
                            
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
                        	<th width="10%">ردیف</th>
                            <th width="85%">عنوان</th>
                            <th width="5%">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                    </thead> 
                   <tbody><?php
                    $rowcnt=0;
                    while($row = mysql_fetch_assoc($result)){
                        $rowcnt++;
?>
                        <tr>
                            <td><?php echo $rowcnt; ?></td>
                            <td><?php echo $row['title']; ?></td>
                            <td><a href=<?php print "guarantee_level2_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$row['type'].rand(10000,99999); ?>>
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

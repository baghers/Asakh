<?php 

/*

codding/codding5cities.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
codding/codding5desert.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php



if ($login_Permission_granted==0) header("Location: ../login.php");

if ($login_designerCO==1) $Idostan="where substring(id,3,5)=00000"; 
else $Idostan="where substring(id,1,7)=$login_ostanId"."00000";
    /*
    tax_tbcity7digit جدول شهرها
    id شناسه شهر
    CityName نام شهر
    */
    
$sql = "select id ,substring(CityName,7) CityName from tax_tbcity7digit $Idostan order by CityName  COLLATE utf8_persian_ci";

//print $sql;



   try 
      {		
       $result = mysql_query($sql);
      }
      //catch exception
      catch(Exception $e) 
      {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
      }

?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست استان ها</title>
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
                        
                        <h1 align="center">  لیست استان ها </h1>
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
                        	<th width="5%">ردیف</th>
                        	<th width="10%">کد</th>
                        	<th width="80%">عنوان</th>
                            <th width="5%"></th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                        	<th colspan="8"><div id="mydiv" >  </div></th>
                        </tr>
                    </thead>     
                   <tbody>
                    
                                
                   <?php
                    $rown=0;
                    while($row = mysql_fetch_assoc($result))
                    {

                        $ID = $row['id'];
                        $TITLE = $row['CityName'];
                        $rown++;
?>                      
                        <tr>
                            
                            <td><?php echo $rown; ?></td>
                            <td><?php echo $ID; ?></td>
                            <td><?php echo $TITLE; ?></td>
                            <td><a href=<?php print "codding5desert.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = 'width: 60%;' src='../img/search.png' title=' مشاهده '></a></td>
                            <td>
                            </td>
                        </tr><?php

                    }

?>
                   
                    </tbody>
                   
                </table>
                <div style='visibility: hidden'>
					  ?>
                      </div>
                      
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

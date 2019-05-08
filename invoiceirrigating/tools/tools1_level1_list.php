<?php 
/*
tools/tools1_level1_list.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$formname='tools1_level1';
$tblname='gadget1';//جدول سطح اول ابزار

if ($login_Permission_granted==0) header("Location: ../login.php");
    /*
        gadget1 جدول سطح اول ابزار
        gadget1id شناسه جدول سطح اول ابزار
        Code کد
        Title عنوان
        IsCost هزینه اجرایی بودن کالا
        DefaultProducer تولیدکننده پیش فرض
        producers جدول ولیدکنندگان
        ProducersID شناسه تولیدکنندگان
        Title عنوان تولیدکننده
    */
//----------
//----------
$sql = " SELECT gadget1.*,producers.Title as PTitle FROM gadget1  
left outer join producers on producers.ProducersID=gadget1.DefaultProducer
 order by gadget1.title COLLATE utf8_persian_ci";
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
  	<title>لیست ابزار سطح یک</title>
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
                            <h1 align="center">  لبست ابزار سطح 1  </h1>
                        
                        <div style = "text-align:left;">
                            <a href=<?php print $formname."_new.php?uid=" ?>> <img style = 'width: 5%;' src='../img/Actions-document-new-icon.png' title=' جدید '> </a>
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
                        	<th width="5%">کد</th>
                            <th width="50%">عنوان</th>
                            <th width="15   %">تولید کننده پیش فرض</th>
                            <th width="10%">هزینه</th>
                            <th width="5%">&nbsp;</th>
                            <th width="5%">&nbsp;</th>
                            <th width="5%">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                    </thead> 
                   <tbody><?php

                    while($row = mysql_fetch_assoc($result)){

                        $Code = $row['Code'];
                        $ID = $row['Gadget1ID'];
                        $Title = $row['Title'];
                        $PTitle = $row['PTitle'];
                        $IsCost = $row['IsCost'];
?>
                        <tr>
                            <td><?php echo $Code; ?></td>
                            <td><?php echo $Title; ?></td>
                            <td><?php echo $PTitle; ?></td>
                            <td><?php echo $IsCost; ?></td>
                            <td><a href=<?php print "tools1_level2_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = 'width: 60%;' src='../img/search_page.png' title='  ريز '> </a></td>
                            <td><a href=<?php print $formname."_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>> 
                            <img style = 'width: 60%;' src='../img/file-edit-icon.png' title=' ويرايش '> </a></td>
                            <td><a href=<?php print $formname."_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>
                            onClick="return confirm('مطمئن هستید که حذف شود ؟');"
                            > <img style = 'width: 60%;' src='../img/delete.png' title='حذف'> </a></td>
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

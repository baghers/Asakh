<?php
/*
tools/tools1_level2_list.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools1_level1_list.php
*/
 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$formname='tools1_level2';

    /*
        gadget2 جدول سطح دوم ابزار
        gadget2id شناسه جدول سطح دوم ابزار
        gadget1id شناسه جدول سطح اول ابزار
        Code کد
        Title عنوان
    */
        

if ($login_Permission_granted==0) header("Location: ../login.php");

$Gadget1ID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    

$query = "SELECT Title FROM gadget1 where Gadget1ID='$Gadget1ID'";
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
$LevelTitle=$row['Title'];
        
        

//----------
//----------
$sql = " SELECT gadget2.* FROM gadget2 where Gadget1ID='$Gadget1ID' order by gadget2.title COLLATE utf8_persian_ci ";
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
  	<title>لیست ابزار سطح 2 <?php print $LevelTitle; ?></title>
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
                            <h1 align="center">  لبست ابزار سطح 2 <?php print $LevelTitle; ?> </h1>
                        
                                                        
                            <div style = "text-align:left;">
                            <a href=<?php print $formname."_new.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget1ID.rand(10000,99999) ?>>
                             <img style = 'width: 5%;' src='../img/Actions-document-new-icon.png' title=' جدید '> </a>
                            <a href=<?php print "tools1_level1_list.php"; ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a>
                            
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
                            <th width="80%">عنوان</th>
                            <th width="5%">&nbsp;</th>
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
                        $ID = $row['Gadget2ID'];
                        $Title = $row['Title'];
?>
                        <tr>
                            <td><?php echo $Code; ?></td>
                            <td><?php echo $Title; ?></td>
                            <td><a href=<?php print "tools1_level3_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>> 
                            <img style = 'width: 60%;' src='../img/search_page.png' title='  ريز '> </a></td>
                            <td><a href=<?php print $formname."_edit.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>> 
                            <img style = 'width: 60%;' src='../img/file-edit-icon.png' title=' ويرايش '> </a></td>
                            <td><a href=<?php print $formname."_delete.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>
                            onClick="return confirm('مطمئن هستید که حذف شود ؟');"
                            > <img style = 'width: 60%;' src='../img/delete.png' title='حذف'> </a></td>
                        </tr><?php

                    }
                    if ($cnt==0)
                    print "<a href=".$formname."_new.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget1ID.rand(10000,99999).
                    "> ثبت جدید </a>";
                    
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

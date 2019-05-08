<?php 
/*
tools/tools1_level1_new.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools1_level1_list.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php
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
    
if ($login_Permission_granted==0) header("Location: ../login.php");

if (! $_POST)
{
    $query = "SELECT max(CAST(Code AS UNSIGNED))+1 maxcode FROM gadget1 ";

		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
		
        if ($row['maxcode']>0)
		  $Code = $row['maxcode'];
        else $Code = 1;
}

$register = false;

if ($_POST){
	$Code = $_POST["Code"];
	$Title = $_POST["Title"];
    
    if ($_POST["IsCost"]=='on')
	   $IsCost = 1;
    else $IsCost = 0;
    
	$DefaultProducer = $_POST["DefaultProducer"];
	
	if ($Title != ""){
        mysql_query("INSERT INTO gadget1(Code,Title,IsCost, DefaultProducer,SaveTime,SaveDate,ClerkID) 
        VALUES('$Code','$Title','$IsCost','$DefaultProducer','".date('Y-m-d H:i:s')."','".date('Y-m-d')."','$login_userid');"); 
        $register = true;

	}
    
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>ثبت ابزار سطح 1</title>
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
						$Code = "";
						$YearID = "";
                        header("Location: tools1_level1_list.php");
                        
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
                <form action="tools1_level1_new.php" method="post">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <div style = "text-align:left;"><a  href=<?php print "tools1_level1_list.php" ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
                     <tr>
                      <td width="20%" class="label">کد:</td>
                      <td width="80%" class="data"><input name="Code" type="text" class="textbox" id="Code" value="<?php echo $Code; ?>" size="6" maxlength="6" /></td>
                     </tr>
                     <tr>
                      <td class="label">عنوان:</td>
                      <td class="data"><input name="Title" type="text" class="textbox" id="Title"   size="50" maxlength="100" /></td>
                     </tr>
                     <tr>
                     <td></td>
                     <?php print "<td class='data'><input type='checkbox' name='IsCost'>هزینه می باشد</input></td>";  ?>
                     
                     </tr>
                     
                     
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="ثبت" /></td>
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
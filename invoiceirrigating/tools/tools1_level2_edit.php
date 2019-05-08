<?php 
/*
tools/tools1_level2_edit.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools1_level2_list.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

if (! $_POST)
{
        /*
        gadget2 جدول سطح دوم ابزار
        gadget2id شناسه جدول سطح دوم ابزار
        Code کد
        Title عنوان
        gadget1id شناسه جدول سطح اول ابزار
    */
    $id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    
    $query = "SELECT * FROM gadget2 WHERE Gadget2ID  ='$id';";
    try 
    {		
        $result = mysql_query($query); 
    }
    //catch exception
    catch(Exception $e) 
    {
        echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
    }
    
    $resquery = mysql_fetch_assoc($result);
	$Code = $resquery["Code"];
	$Title = $resquery["Title"];
	$Gadget1ID = $resquery["Gadget1ID"];
                        
    $Gadget2ID=$id;  
    if (!$resquery["Title"]) header("Location: ../logout.php");
}

$register = false;

if ($_POST){
	$Code = $_POST["Code"];
	$Title = $_POST["Title"];
    
	$Gadget2ID = $_POST["Gadget2ID"];
	$Gadget1ID = $_POST["Gadget1ID"];
    //print "salam";
	if ($Title != ""){
		$query = "
		UPDATE gadget2 SET
		Code = '" . $Code . "', 
		Title = '" . $Title . "', 
		Gadget1ID = '" . $Gadget1ID . "',
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE Gadget2ID = $Gadget2ID;";
        try 
        {		
            mysql_query($query);  
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }
        $register = true;
        //print $query;

	}
    
    
    
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>تصحیح ابزار سطح 2</title>
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
                        header("Location: tools1_level2_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$_POST["Gadget1ID"].rand(10000,99999));
                        
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
                <form action="tools1_level2_edit.php" method="post">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <div style = "text-align:left;"><a  href=<?php print 
                    "tools1_level2_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget1ID.rand(10000,99999) ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
                     <tr>
                      <td width="20%" class="label">کد:</td>
                      <td width="80%" class="data"><input name="Code" type="text" class="textbox" id="Code" value="<?php echo $Code; ?>" size="6" maxlength="6" /></td>
                     </tr>
                     <tr>
                      <td class="label">عنوان:</td>
                      <td class="data"><input name="Title" type="text" class="textbox" id="Title" value="<?php echo $Title; ?>"  size="50" maxlength="100" /></td>
                     </tr>
                     <tr>
                     <td></td>
                     
                     
                     
                     <tr>
                      <td class="data"><input name="Gadget1ID" type="hidden" class="textbox" id="Gadget1ID"  value="<?php echo $Gadget1ID ; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     <tr>
                      <td class="data"><input name="Gadget2ID" type="text" class="textbox" id="Gadget2ID" readonly value="<?php echo $Gadget2ID ; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     
                     
                     
                     
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="تصحیح" /></td>
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
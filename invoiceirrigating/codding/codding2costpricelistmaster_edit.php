<?php 

/*
codding/codding2costpricelistmaster_edit.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
codding/codding2costpricelistmaster.php

*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

if (! $_POST)
{
$id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
//costpricelistmaster جدول فهرست بها
$query = "SELECT * FROM costpricelistmaster WHERE CostPriceListMasterID = '" . $id . "';";

		 try 
			  {		
				 $result = mysql_query($query);
			  }
			  //catch exception
			  catch(Exception $e) 
			  {
				echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
			  }

//print $query;
    $resquery = mysql_fetch_assoc($result);
	$SelectedYearID = $resquery["YearID"];
	$SelectedMonthID = $resquery["MonthID"];  
    $CostPriceListMasterID=$id;  
    $pfd=$resquery["pfd"]; 
    
    
    $straddedfields="";
    if ($pfd==1)
        $straddedfields.$straddedfields=
        "<tr><td class='label'>مجاز برای مشاورین:</td><td class='data'><input checked name='pfd' type='checkbox' class='textbox' id='pfd'  /></td></tr>";    

else $straddedfields.$straddedfields=
        "<tr><td class='label'>مجاز برای مشاورین:</td><td class='data'><input name='pfd'  type='checkbox' class='textbox' id='pfd'  /></td></tr>";    
    
}

$register = false;

if ($_POST){
    
    
	$SelectedYearID = $_POST["YearID"];
	$SelectedMonthID = $_POST["MonthID"];
    $CostPriceListMasterID=$_POST["CostPriceListMasterID"];  
    
    
    if ($_POST["pfd"]=='on')
        $pfd = 1;
    else $pfd = 0;//مجاز برای مشاورین
		
	if ($SelectedYearID != "" && $SelectedMonthID != ""){
		$query = "
		UPDATE costpricelistmaster SET
		YearID = '" . $SelectedYearID . "', 
		MonthID = '" . $SelectedMonthID . "', 
		pfd = '" . $pfd . "',  
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE CostPriceListMasterID = " . $CostPriceListMasterID . ";";
        
        
		 try 
			  {		
				 $result = mysql_query($query);
			  }
			  //catch exception
			  catch(Exception $e) 
			  {
				echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
			  }

        $register = true;

	}
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>تصحیح لیست قیمت</title>
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
                        header("Location: codding2costpricelistmaster.php");
                        
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
                <form action="codding2costpricelistmaster_edit.php" method="post">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <div style = "text-align:left;"><a  href=<?php print "codding2costpricelistmaster.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$CostPriceListMasterID.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                         
                     <?php

					 $query='select YearID as _value,Value as _key from year';
    				 $ID = get_key_value_from_query_into_array($query);
                     print "<tr>".select_option('YearID','سال',',',$ID,0,'','','1','rtl',0,'',$SelectedYearID)."</tr>";
                     
					 $query='select MonthID as _value,Title as _key from month';
    				 $ID = get_key_value_from_query_into_array($query);
                     print select_option('MonthID','ماه',',',$ID,0,'','','1','rtl',0,'',$SelectedMonthID);
                     echo $straddedfields; 
                     
                     
					  ?>

                     <tr>
                      <td class="data"><input name="CostPriceListMasterID" type="hidden" class="textbox" id="CostPriceListMasterID"  value="<?php echo $CostPriceListMasterID; ?>"  size="30" maxlength="15" /></td>
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
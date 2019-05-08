<?php

/*

codding/codding5countys_edit.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
codding/codding5desert.php
*/

 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

if (! $_POST)
{
$cid = substr($_GET["uid"],40,strlen($_GET["uid"])-45);//شناسه شهر
    /*
    tax_tbcity7digit جدول شهرها
    id شناسه شهر
    CityName نام شهر
    applicantmaster جدول مشخصات طرح
    fzkargah کد
    ClerkIDExcellentSupervisor شناسه کاربر ناظر عالی
    DesignerCoIDnazer ناظر
    ClerkIDinspector بازرس
    */
$query = "select tax_tbcity7digit.CityName,fzkargah,ClerkIDExcellentSupervisor,DesignerCoIDnazer,ClerkIDinspector 
,tax_tbcity7digit.fieldCode
from 
        tax_tbcity7digit 
        WHERE tax_tbcity7digit.id = '$cid'";

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
	$CityName = $resquery["CityName"];
	$fzkargah = $resquery["fzkargah"]; 
        
}

$register = false;

if ($_POST){
    
    
	$CityName = $_POST["CityName"];
	$fzkargah = $_POST["fzkargah"];
	$cid=$_POST["cid"];  
    	
		$query = "
		UPDATE tax_tbcity7digit SET
		CityName = '" . $CityName . "', 
		fzkargah = '" . $fzkargah . "', 
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
        WHERE tax_tbcity7digit.id = '$cid'";

//print $query;exit;        
       
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


?>
<!DOCTYPE html>
<html>
<head>
  	<title>تصحیح اطلاعات دشت</title>
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
                        header("Location: codding5countries.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$cid.rand(10000,99999));
                        
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
                <form action="codding5countys_edit.php" method="post">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <div style = "text-align:left;"><a  href=<?php print "codding5countries.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$cid.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                         
                     <?php
    
					 print "<td  class='label'>دشت/شهرستان:</td>
                      <td class='data'><input  value='$CityName'
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                             name='CityName' type='text' class='textbox' id='CityName'    /></td>";
                             
					 print "<td  class='label'>کد :</td>
                      <td class='data'><input  value='$fzkargah'
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                             name='fzkargah' type='text' class='textbox' id='fzkargah'    /></td>";
                     
                     
					  ?>

                     <tr>
                      <td class="data"><input name="cid" type="hidden" class="textbox" id="cid"  value="<?php echo $cid; ?>"  size="30" maxlength="15" /></td>
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
<?php 

/*

codding/codding5countries_edit.php

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
$query = "select tax_tbcity7digit.CityName,ClerkIDExcellentSupervisor,DesignerCoIDnazer,ClerkIDinspector 
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
	$ClerkIDExcellentSupervisor = $resquery["ClerkIDExcellentSupervisor"]; 
	$DesignerCoIDnazer = $resquery["DesignerCoIDnazer"]; 
	$ClerkIDinspector = $resquery["ClerkIDinspector"]; 
    $fieldCode = $resquery["fieldCode"]; 
        
}

$register = false;

if ($_POST){
    
    
	$CityName = $_POST["CityName"];
	$fieldCode = $_POST["fieldCode"];
	$ClerkIDExcellentSupervisor = $_POST["ClerkIDExcellentSupervisor"];
	$ClerkIDinspector = $_POST["ClerkIDinspector"];
	$DesignerCoIDnazer = $_POST["DesignerCoIDnazer"];
    $cid=$_POST["cid"];  
    	
		$query = "
		UPDATE tax_tbcity7digit SET
		CityName = '" . $CityName . "', 
		fieldCode = '" . $fieldCode . "', 
		ClerkIDExcellentSupervisor = '" . $ClerkIDExcellentSupervisor . "',
		ClerkIDinspector = '" . $ClerkIDinspector . "',
		DesignerCoIDnazer = '" . $DesignerCoIDnazer . "',
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
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
                        header("Location: codding5desert.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$cid.rand(10000,99999));
                        
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
                <form action="codding5countries_edit.php" method="post">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <div style = "text-align:left;"><a  href=<?php print "codding5desert.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$cid.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                         
                     <?php
    
					 print "<td  class='label'>دشت/شهرستان:</td>
                      <td class='data'><input  value='$CityName'
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                             name='CityName' type='text' class='textbox' id='CityName'    /></td>";
                             
					 print "<td  class='label'>کد :</td>
                      <td class='data'><input  value='$fieldCode'
                      style = \"border:1px solid black;border-color:#777;text-align:right;font-size:14;line-height:120%;font-family:'B Nazanin';width: 100px\"
                             name='fieldCode' type='text' class='textbox' id='fieldCode'    /></td>";
                     
                     
					 $query="select clerkID,clerk.CPI,DVFS from clerk 
                     where city in (13, 14)  and CityId='$login_CityId'";
					 
                  
					 		 try 
								  {		
										   $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

                     $ID[' ']=' ';
                     while($row = mysql_fetch_assoc($result))
                        $ID[trim(decrypt($row['CPI'])." ".decrypt($row['DVFS']))]=trim($row['clerkID']);
                     $ID=mykeyvalsort($ID);
                     
                     
                     print "</tr><tr>".select_option('ClerkIDExcellentSupervisor','ناظر عالی:',',',$ID,0,'','','1','rtl',0,'',
                     $ClerkIDExcellentSupervisor,'','','');


			    
					 
					 
					 $query="select clerkID,clerk.CPI,DVFS from clerk where city=11 and substring(CityId,1,2)=substring('$cid',1,2)";
                  
					 	 try 
								  {		
										   $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

                     $ID2[' ']=' ';
                     while($row = mysql_fetch_assoc($result))
                        $ID2[trim(decrypt($row['CPI'])." ".decrypt($row['DVFS']))]=trim($row['clerkID']);
                     $ID2=mykeyvalsort($ID2);
                     print "</tr><tr>".select_option('ClerkIDinspector','بازبین:',',',$ID2,0,'','','1','rtl',0,'',
                     $ClerkIDinspector,'','','');
                     
                                          
                     $query="select DesignerCoID as _value,Title as _key from designerco where isnazer=1   ORDER BY _key";
                            $ID2 = get_key_value_from_query_into_array($query);
                            print "<td  class='label'>مشاور ناظر:</td>".
                            select_option('DesignerCoIDnazer','',',',$ID2,0,'','','1','rtl','','',$DesignerCoIDnazer);
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
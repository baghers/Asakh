<?php 
/*
pricesaving/pricesavingpipe_edit.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
pricesaving/pricesavingpipe_edit.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");
if (! $_POST)
{
    /*
pipeprice جدول قیمت لوله
maxpe100pipeprice  سقف قیمت لوله 100
maxpe80pipeprice سقف قیمت لوله 80
maxpe32pipeprice سقف قیمت لوله 32
maxpe40pipeprice سقف قیمت لوله 40
*/

    $id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
    $query = "SELECT * 
    FROM pipeprice 
    where  PipePriceID = '".$id ."'";
    $result = mysql_query($query);
    $resquery = mysql_fetch_assoc($result);
    
    //print $query;
    
    $Date = $resquery['Date'];
    $PE32 = $resquery['PE32'];
    $PE40 = $resquery['PE40'];
    $PE80 = $resquery['PE80'];
    $PE100 = $resquery['PE100'];
    $PipePriceID = $resquery['PipePriceID'];       
    $pfd=$resquery["pfd"]; 
    
    
    $straddedfields="";
    if ($login_PipeProducer!=1)
    {
    if ($pfd==1)
        $straddedfields.$straddedfields=
        "<tr><td class='label'>مجاز برای مشاورین:</td><td class='data'><input checked name='pfd' type='checkbox' class='textbox' id='pfd'  /></td></tr>";    

else $straddedfields.$straddedfields=
        "<tr><td class='label'>مجاز برای مشاورین:</td><td class='data'><input name='pfd'  type='checkbox' class='textbox' id='pfd'  /></td></tr>";    
            
    }
       
}
    $register = false;
if ($_POST){
    //supervisorcoderrquirement جدول پیکربندی سیستم
    $query = "select ValueInt from supervisorcoderrquirement where KeyStr='maxpe100pipeprice' and ostan='$login_ostanId'
            union all select ValueInt from supervisorcoderrquirement where KeyStr='maxpe80pipeprice' and ostan='$login_ostanId'";
                              
    
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
    $maxpe100=$row['ValueInt'];	   
    $row = mysql_fetch_assoc($result); 		
    $maxpe80=$row['ValueInt'];	   
            
    if ($_POST['PE80']>$maxpe80)
    {
        echo "مبلغ قیمت لوله PE80 بیشتر از سقف مجاز می باشد";
        echo "لطفا با مدیریت آب و خاک و امور فنی مهندسی تماس حاصل فرمایید";
        exit;
    }
    if ($_POST['PE100']>$maxpe100)
    {
        echo "مبلغ قیمت لوله PE100 بیشتر از سقف مجاز می باشد";
        echo "لطفا با مدیریت آب و خاک و امور فنی مهندسی تماس حاصل فرمایید";
        exit;
    }
    
	$Date = $_POST['Date'];
    $PE32 = $_POST['PE32'];
    $PE40 = $_POST['PE40'];
    $PE80 = $_POST['PE80'];
    $PE100 = $_POST['PE100'];
    $PipePriceID = $_POST['PipePriceID'];
    $PE32 = str_replace(',', '', $PE32);
    $PE40 = str_replace(',', '', $PE40);
    $PE80 = str_replace(',', '', $PE80);
    $PE100 = str_replace(',', '', $PE100);
    
    if ($_POST["pfd"]=='on')
        $pfd = 1;
    else $pfd = 0;
    
	if ($PipePriceID != "" ){
		$query = "
		UPDATE pipeprice SET
		Date = '" . $Date . "', 
		PE32 = '" . $PE32 . "', 
		PE40 = '" . $PE40 . "', 
		PE80 = '" . $PE80 . "',
		PE100 = '" . $PE100 . "',  
		pfd = '" . $pfd . "',  
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE PipePriceID = " . $PipePriceID . ";";
								try 
								  {		
									  $result = mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

        $register = true;
	}
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>تصحیح قیمت لوله</title>
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
						$Serial = "";
						$ProducersID = "";
                        header("Location: pricesavingpipe.php");
                        
                        
                        
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
                <form action="pricesavingpipe_edit.php" method="post">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <?php   print "<script type='text/javascript'> 


	function selectpage(obj){
		window.location.href ='?uid=' +document.getElementById('uid').value+ '&page=' + obj.value;
	}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

    function convert(aa) {
        //alert(1);
        var number = document.getElementById(aa).value.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        number = number.replace(\",\", \"\");
        
        number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        //alert(numberWithCommas(number));
        document.getElementById(aa).value=numberWithCommas(number);
        
    }
    

</script>
";  ?>

                    
                    
                    <div style = "text-align:left;"><a  href=<?php print "pricesavingpipe.php"; ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                    
                     <tr>
                      <td width="20%" class="label">تاریخ:</td>
                      <td width="80%" class="data"><input name="Date" type="text" class="textbox" id="Date" value="<?php echo $Date ?>" size="10" maxlength="10" /></td>
                     </tr>
                     <tr>
                      <td class="label">قیمت لوله PE32:</td>
                      <td class="data"><input name="PE32" type="text" class="textbox" id="PE32" onKeyUp="convert('PE32')" value="<?php echo number_format($PE32); ?>"  size="15" maxlength="50" /></td>
                     </tr>
                     <tr>
                      <td class="label">قیمت لوله PE40:</td>
                      <td class="data"><input name="PE40" type="text" class="textbox" id="PE40" onKeyUp="convert('PE40')" value="<?php echo number_format($PE40); ?>"  size="15" maxlength="50" /></td>
                     </tr>
                     <tr>
                      <td class="label">قیمت لوله PE80:</td>
                      <td class="data"><input name="PE80" type="text" class="textbox" id="PE80" onKeyUp="convert('PE80')" value="<?php echo number_format($PE80); ?>"  size="15" maxlength="50" /></td>
                     </tr>
                     <tr>
                      <td class="label">قیمت لوله PE100:</td>
                      <td class="data"><input name="PE100" type="text" class="textbox" id="PE100" onKeyUp="convert('PE100')" value="<?php echo number_format($PE100); ?>" size="10" maxlength="10" /></td>
                     </tr>
                     
                     <?php echo $straddedfields; ?>
                     <tr>
                      <td class="data"><input name="PipePriceID" type="text" readonly class="textbox" id="PipePriceID"  value="<?php echo $PipePriceID; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="تصحیح " /></td>
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
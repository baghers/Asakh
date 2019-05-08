<?php 

/*

insert/producerinvoicemaster_edit.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود

*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");
if (! $_POST)
{
$id = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
/*
primaryinvoicemaster  پیش فاکتور صادره تولید کننده
*/
$query = "SELECT * 
FROM primaryinvoicemaster 
where  primaryinvoicemasterID = '".$id ."'";

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
	
    $Serial = $resquery['Serial'];
    $primaryinvoicemasterID = $resquery['primaryInvoiceMasterID'];
    $Title = $resquery['Title'];
    $Description = $resquery['Description'];
    $Rowcnt = $resquery['Rowcnt'];
    //$ProducersID = $resquery    ['ProducersID'];
    $ApplicantMasterID= $resquery    ['ApplicantMasterID'];
    $InvoiceDate= $resquery    ['InvoiceDate'];
    //print $pricenotinrep;
    if ($pricenotinrep>0)      
       $pricenotinrepselected="checked";
       
    if ($costnotinrep>0)      
       $costnotinrepselected="checked";
                        
}

    $register = false;

if ($_POST){
        		          /*
                primaryinvoicemaster  پیش فاکتور تولیدکننده
                PriceListMasterID لیست قیمت
                operatorcoID مجری
                ProducersID تولیدکننده
                Serial سریال
                Title عنوان
                Description شرح
                TransportCost هزینه حمل
                Discont تخفیف
                InvoiceDate تاریخ
                Rowcnt تعداد ردیف
                pricenotinrep در تعهد متقاضی یا مجری
                SaveTime زمان
                SaveDate تاریخ
                ClerkID کاربر
                */  
    
	$Serial = $_POST['Serial'];
    $primaryinvoicemasterID = $_POST['primaryinvoicemasterID'];
    $Title = $_POST['Title'];
    $Description = $_POST['Description'];
    $Rowcnt = $_POST['Rowcnt'];
    //$ProducersID = $_POST['ProducersID'];
    $ApplicantMasterID = $_POST['ApplicantMasterID'];
    $InvoiceDate= $_POST['InvoiceDate'];
    
    $_POST['pricenotinrep'] = $_POST['pricenotinrep'];
    $pricenotinrep= $_POST['pricenotinrep'];
    
    
    $_POST['costnotinrep'] = $_POST['costnotinrep'];
    $costnotinrep= $_POST['costnotinrep'];
    
    //print $_POST['pricenotinrep'];
    //exit(0);
    
		
		$query = "
		UPDATE primaryinvoicemaster SET
		Serial = '" . $Serial . "', 
		Title = '" . $Title . "',
		Description = '" . $Description . "', 
		Rowcnt = '" . $Rowcnt . "', 
		InvoiceDate = '" . $InvoiceDate. "',  
		SaveTime = '" . date('Y-m-d H:i:s') . "', 
		SaveDate = '" . date('Y-m-d') . "', 
		ClerkID = '" . $login_userid . "'
		WHERE primaryinvoicemasterID = " . $primaryinvoicemasterID . ";";
        
        //print $query;
        
        
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


?>
<!DOCTYPE html>
<html>
<head>
  	<title>تصحیح پیش فاکتور/لیست لوازم</title>
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
                        header("Location: producerinvoicemaster_list.php");
                        
                        
                        
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
                <form action="producerinvoicemaster_edit.php" method="post">
                   <table width="600" align="center" class="form">
                    <tbody>
                    <div style = "text-align:left;"><a  href=<?php print "producerinvoicemaster_list.php?"; ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                    
                     <tr>
                      <td width="20%" class="label">سريال:</td>
                      <td width="80%" class="data"><input name="Serial" type="text" class="textbox" id="Serial" value="<?php echo $Serial ?>" size="6" maxlength="6" /></td>
                     </tr>
                     <tr>
                      <td class="label">عنوان پیش فاکتور/لیست لوازم:</td>
                      <td class="data"><input name="Title" type="text" class="textbox" id="Title" value="<?php echo $Title; ?>"  size="15" maxlength="50" /></td>
                     </tr>
                     <tr>
                      <td class="label">تاریخ پیش فاکتور/لیست لوازم:</td>
                      <td class="data"><input name="InvoiceDate" type="text" class="textbox" id="InvoiceDate"  value="<?php echo $InvoiceDate; ?>" size="10" maxlength="10" /></td>
                     </tr>
                     <?php
                    /*
                     $limited = array("9");
                     if ( in_array($login_RolesID, $limited))
					   $query='select ProducersID as _value,Title as _key from producers where ProducersID=148 order by Title  COLLATE utf8_persian_ci';
                     else $query='select ProducersID as _value,Title as _key from producers order by Title  COLLATE utf8_persian_ci';
                            
    				 $ID = get_key_value_from_query_into_array($query);
                     print select_option('ProducersID','صادرکننده',',',$ID,0,'','','1','rtl',0,'',$ProducersID,'','','');
                        */
					  ?>

                     <tr>
                      <td class="label">تعداد ردیف های پیش فاکتور/لیست لوازم:</td>
                      <td class="data"><input name="Rowcnt" type="text" class="textbox" id="Rowcnt"  value="<?php echo $Rowcnt; ?>"  size="10" maxlength="10" /></td>
                     </tr>
                     
                     <tr>
                      <td class="label">توضیحات:</td>
                      <td class="data"><input name="Description" type="text" class="textbox" id="Description"  value="<?php echo $Description; ?>"  size="30" maxlength="130" /></td>
                     </tr>
                     
                     
                     <tr>
                      <td class="data"><input name="primaryinvoicemasterID" type="hidden" class="textbox" id="primaryinvoicemasterID"  value="<?php echo $primaryinvoicemasterID; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     
                     <tr>
                      <td class="data"><input name="ApplicantMasterID" type="hidden" class="textbox" id="ApplicantMasterID"  value="<?php echo $ApplicantMasterID; ?>"  size="30" maxlength="15" /></td>
                     </tr>
                     
                     </tbody>
                    <tfoot>
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="تصحیح پيش فاکتور " /></td>
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
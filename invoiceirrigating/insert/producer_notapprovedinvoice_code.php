<?php 

/*

insert/producer_notapprovedinvoice_code.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
 
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


if ($login_Permission_granted==0) header("Location: ../login.php");

if ($login_userid==28) //کابر دمو
    $hide='display:none';


if ($_POST)
{
	$ID=$_POST['invoicemasterid']/300020001;
    //primaryinvoicemaster پیش فاکتور اولیه فروشنده
    $query = " SELECT `primaryinvoicemaster`.`primaryInvoiceMasterID` PID 
	FROM `primaryinvoicemaster`
    where `primaryinvoicemaster`.`primaryInvoiceMasterID`=$ID ";
		
       	   				  	try 
								  {		
									mysql_query($query);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();
								  }

    //print $query;
    //exit;
	$result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    if ($row['PID']>0){
	$link="producer_notapprovedinvoice_detail.php?uid=".rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.'_2'.rand(10000,99999);
    header("Location: $link");}
    
	else 
	  echo '<script type="text/javascript"> alert(\'کد رهگیری سفارش صحیح نمیباشد\'); </script>';
// echo ("<script LANGUAGE='JavaScript'>window.alert('کد رهگیری سفارش صحیح نمیباشد') window.location.href='http://';</script>");
                                                            
}
?>
    <style>
    p.page { page-break-after: always; }
.f9_font{
    border:0px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f10_font{
    background-color:#b0eab9;border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:120%;font-weight: bold;font-family:'B Nazanin';
}
.f11_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f12_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:130%;font-weight: bold;font-family:'B Nazanin';
}
.f13_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:center;font-size:10.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}
.f14_font{
    border:1px solid black;border-color:#0000ff #0000ff;text-align:right;font-size:12.0pt;line-height:95%;font-weight: bold;font-family:'B Nazanin';
}

      </style>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست طرح ها</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
     <link type="text/css" rel="stylesheet" href="../css/persiandatepicker-default.css" />
        <script type="text/javascript" src="../assets/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="../js/persiandatepicker.js"></script>
        

    
   <script>
   function CheckForm()
    {
                    
                    var selectedBankcode=document.getElementById('invoicemasterid').value;
                    if (selectedBankcode.length==0)
                    {
					alert('كد رهگيري را وارد نكرده ايد!');
					return false;
					} else 
					return true;  
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
                   <tbody>
                        <table width="95%" align="center">
                
                   <tr>
						<td>
					 <div class="module_menu">
                        <div>
                            <div>
                             <div>
							 <h3>پيگيري وضعيت سفارش</h3>
							  <form action="" method="post" onSubmit="return CheckForm()"  style="float:none;">
								 <p style="font-size:11px; font-family:Tahoma;">كد رهگيري  را در كادر زير وارد كنيد: </p>
							     <p><input  name="invoicemasterid" type="text" class="textbox" id="invoicemasterid" 
						  value="" size="20" maxlength="20" /></p>
								<input   name="submit" type="submit" class="button" id="submit" size="16" value="جستجو" />
							  </form>
							 </div>
							 </div>
							 </div>
							 </div>
				   	</td> 
						</tr>
                			
				   </table>
                    
                    </tbody>
                </form>
                
            </div>
			<!-- /content -->

            <!-- footer -->
			<?php
            
            
             include('../includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>

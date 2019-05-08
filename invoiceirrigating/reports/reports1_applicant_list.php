<?php 
/*
reorts/reports1_applicant_list.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$formname='manualcostlist_applicant';
$tblname='applicantmaster';
if ($login_Permission_granted==0) header("Location: ../login.php");

/*
applicantmaster جدول مشخصات طرح
month ماه
join سال
DesignerCoID شرکت طراح
*/

        
$sql = "
SELECT ".$tblname.".*,month.Title monthtitle,year.Value year 
FROM $tblname 
inner join month on month.MonthID=$tblname.MonthID
inner join year on year.YearID=$tblname.YearID
where  DesignerCoID = '" . $login_DesignerCoID . "'
 
ORDER BY year.Value DESC,month.code DESC LIMIT " . $start . ", " . $per_page . ";";
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
  	<title>لیست طرح ها</title>
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
                        <tr>
                        
                        <h1 align="center">  لیست طرح های مختلف </h1>
                          <INPUT type="hidden" id="txtmaxSerial" value="<?php print $maxcode; ?>"/>
                          <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
                          <INPUT type="hidden" id="txturl" value="<?php print "$_server_httptype://$_SERVER[HTTP_HOST]/$home_path_iri/insert/invoice_list_jr.php"; ?>"/>
                           <!--INPUT type="button" value="افزودن طرح جدید" onclick="add()"/-->
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
                        	<th width="5%">سريال</th>
                            <th width="15%">کد طرح</th>
                            <th width="5%">سال</th>
                            <th width="10%">ماه</th>
                            <th width="60%">نام متقاضي</th>
                            <th width="5%">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                    
                                
                   <?php
                    
                    while($row = mysql_fetch_assoc($result)){

                        $Code = $row['Code'];
                        $ID = $row['ApplicantMasterID'];
                        $ApplicantName = $row['ApplicantName'];
                        $year = $row['year'];
                        $monthtitle = $row['monthtitle'];
                        $BankCode=$row['BankCode'];
?>                      
                        <tr>
                            
                            <td><?php echo $Code; ?></td>
                            <td><?php echo $BankCode; ?></td>
                            <td><?php echo $year; ?></td>
                            <td><?php echo $monthtitle; ?></td>
                            <td><?php echo $ApplicantName; ?></td>
                            
                        </tr><?php

                    }

?>
                   
                    </tbody>
                   
                </table>
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
                   
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

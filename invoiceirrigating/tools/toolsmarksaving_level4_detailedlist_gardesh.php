<?php 
/*
tools/toolsmarksaving_level4_detailedlist_gardesh.php


فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/toolsmarksaving_level4_detailedlist.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$tblname='applicantmaster';
if ($login_Permission_granted==0) header("Location: ../login.php");



$ids = substr($_GET["uid"],40,strlen($_GET["uid"])-45);

$linearray = explode('_',$ids);
$ProducersID=$linearray[1];//شناسه تولید کننده
$toolsmarksid=$linearray[2];//شناسه ابزار و مارک
$Gadget2ID=$linearray[3];//شناسه سطح 2 ابزار
            /*
operatorco پیمانکاران
invoicemaster عناوین پیش فاکتورها
applicantmaster لیست طرح ها    
designerco طراحان
pricelistmaster لیست قیمت
month ماه
year سال
    */
$sql = "
select operatorco.title operatorcotitle, designerco.title DesignerCotitle ,ApplicantFName,ApplicantName,invoicemaster.Title invoicemasterTitle from invoicedetail
inner join invoicemaster on invoicemaster.invoicemasterid=invoicedetail.invoicemasterid 
inner join applicantmaster on applicantmaster.applicantmasterid=invoicemaster.applicantmasterid
left outer join designerco on designerco.DesignerCoID=applicantmaster.DesignerCoID
left outer join operatorco on operatorco.operatorcoID=applicantmaster.operatorcoID
where invoicedetail.toolsmarksid='$toolsmarksid' 

union all
select '' operatorcotitle, 'لیست قیمت' DesignerCotitle ,'' ApplicantFName,'' ApplicantName,CONCAT(CONCAT(yearprice.Value,' '),monthprice.Title) invoicemasterTitle from pricelistdetail
inner join pricelistmaster on pricelistmaster.pricelistmasterid=pricelistdetail.pricelistmasterid 
left outer join month as monthprice on monthprice.MonthID=pricelistmaster.MonthID  
left outer join year as yearprice on yearprice.YearID=pricelistmaster.YearID 

where pricelistdetail.toolsmarksid='$toolsmarksid'

";
     try 
								  {		
									    $result = mysql_query($sql);
$row = mysql_fetch_assoc($result);
								  }
								  //catch exception
								  catch(Exception $e) 
								  {
									echo 'اجراي پرس و جو با خطا مواجه شد: ' .$e->getMessage();exit;
								  }

//print $sql;




?>
<!DOCTYPE html>
<html>
<head>
  	<title></title>
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
                        
                        <h1 align="center">   </h1>
                          <INPUT type="hidden" id="txtuserid" value="<?php print $login_userid; ?>"/>
                          <div style = "text-align:left;"><a  href=<?php print "toolsmarksaving_level4_detailedlist.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$Gadget2ID.'_'.$ProducersID.rand(10000,99999); ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                      
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
                        	<th width="15%">طراح</th>
                        	<th width="15%">مجری</th>
                            <th width="30%">نام طرح</th>
                            <th width="40%">عنوان پیش فاکتور</th>
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
                   <?php
                    do{
                        
                        $DesignerCotitle = $row['DesignerCotitle'];
                        $ApplicantName = $row['ApplicantFName'].' '.$row['ApplicantName'];
                        $invoicemasterTitle = $row['invoicemasterTitle'];
?>                      
                        <tr>
                            <td><?php echo $DesignerCotitle; ?></td>
                            <td><?php echo $row['operatorcotitle']; ?></td>
                            <td><?php echo $ApplicantName; ?></td>
                            <td><?php echo $invoicemasterTitle; ?></td>
                        </tr><?php

                    }
                    while($row = mysql_fetch_assoc($result));

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

﻿<?php 
/*
pricesaving/pricesavingref_level2_list.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
pricesaving/pricesavingref_level1_list.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

$tblname='pricelistmaster';



if ($login_Permission_granted==0) header("Location: ../login.php");
$per_page = 1000000;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;
//----------

$ProducersID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);//شناسه تولیدکننده

//print $ProducersID;
          
/*
month جدول ماه
year جدول سال
*/ 
if ($login_RolesID==1)
$sql = "
SELECT ".$tblname.".*,month.Title monthtitle,year.Value year 
FROM $tblname 
inner join month on month.MonthID=$tblname.MonthID
inner join year on year.YearID=$tblname.YearID
 
ORDER BY year.Value DESC ,month.Code DESC ;";
else
$sql = "
SELECT ".$tblname.".*,month.Title monthtitle,year.Value year 
FROM $tblname 
inner join month on month.MonthID=$tblname.MonthID
inner join year on year.YearID=$tblname.YearID
where pfd=1 
ORDER BY year.Value DESC ,month.Code DESC ;";



//print $sql;
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
  	<title>لیست قیمت ها</title>
	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

    <script>

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
                        
                        <h1 align="center">  لیست قیمت ها </h1>
                            
                            
                            <div style = "text-align:left;"><a  href=<?php print "pricesavingref_level1_list.php"; ?>><img style = "width: 4%;" src="../img/Return.png" title='بازگشت' ></a></div>
                            
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
                        	<th width="8%">سال</th>
                            <th width="80%">ماه</th>
                            <th width="5%">&nbsp;</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                        	<th colspan="8"><div id="mydiv" >  </div></th>
                        </tr>
                    </thead>     
                   <tbody>
                    
                                
                   <?php
                    
                    while($row = mysql_fetch_assoc($result)){

                        $year = $row['year'];
                        $monthtitle = $row['monthtitle'];
                        $ID=$row['PriceListMasterID'];
                        //print "salam".$ID;
?>                      
                        <tr>
                            
                            <td><?php echo $year; ?></td>
                            <td><?php echo $monthtitle; ?></td>
                            <td><a href=<?php print "pricesavingref_level3_list.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.'_'.$ProducersID.rand(10000,99999); ?>>
                            <img style = 'width: 70%;' src='../img/search.png' title=' ريز '></a></td>
                            
                            
                        </tr><?php

                    }

?>
                   
                    </tbody>
                   
                </table>
                <div style='visibility: hidden'>
                          <?php

					 $query='select YearID as _value,Value as _key from year';
    				 $ID = get_key_value_from_query_into_array($query);
                     print select_option('YearID','',',',$ID,0,'','','1','rtl',0,'',$YearID);

					  ?>
                      </div>
                      
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

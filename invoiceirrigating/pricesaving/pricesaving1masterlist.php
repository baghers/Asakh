<?php 
/*
pricesaving/pricesaving1masterlist.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
pricesaving/compareprices.php
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php


$formname='pricesaving1masterlist';
$tblname='pricelistmaster';//جدول لیست قیمت ها


if ($login_Permission_granted==0) header("Location: ../login.php");

$per_page = 1000000;
$page = is_numeric($_GET["page"]) ? intval($_GET["page"]) : 1;
$start = ($page - 1) * $per_page;
//----------

//print $login_RolesID;

if ($login_ProducersID>0)
{
           /*
       producersid شناسه تولید کننده
       pricelistdetail جدول قیمت های تایید شده
       toolsmarks جدول ابزار و مارک
       toolsmarksid شناسه ابزار و مارک
       PriceListMasterID شناسه لیست قیمت
       */
    $query = "SELECT distinct pricelistdetail.PriceListMasterID as _value,pricelistdetail.PriceListMasterID  as _key  FROM `pricelistdetail`
inner join toolsmarks on toolsmarks.toolsmarksid=pricelistdetail.toolsmarksid
where price>0 and toolsmarks.ProducersID='$login_ProducersID'";
//print $query;
$PriceListMasterIDs = get_key_value_from_query_into_array($query);
}

$cond="";
if ($login_RolesID==3)
$cond=" and ifnull(pfp,0)=1";
if ($login_RolesID<>1)
$cond=" and ifnull(pfm,0)=1";
if ($login_RolesID==2)
$cond=" and ifnull(pfo,0)=1";
  
/*
month جدول ماه
year جدول سال
*/  
$sql = "
SELECT ".$tblname.".*,month.Title monthtitle,year.Value year 
FROM $tblname 
inner join month on month.MonthID=$tblname.MonthID
inner join year on year.YearID=$tblname.YearID
where 1=1 $cond 
ORDER BY year.Value DESC ,month.Code DESC ";


//print $sql;

				  	 	try 
								  {		
									  	  	$result = mysql_query($sql.$login_limited);
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
                         <?php if ($login_RolesID<>2){ ?>
                        <h1 align="center">  فروشندگان محترم: در صورت نیاز به تغییر قیمت های تایید شده لطفا   با مدیریت آب و خاک هماهنگی به عمل آورید </h1>
                        <br />
						<?php } ?>
                        <h1 align="center">  لیست قیمت ها </h1>
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
                        	<th width="10%" style = "color:#000000;text-align:center;font-size:12;font-weight: bold;font-family:'B Nazanin';" >سال</th>
                            <th width="10%" style = "color:#000000;text-align:center;font-size:12;font-weight: bold;font-family:'B Nazanin';">ماه</th>
                            <th width="5%">&nbsp;</th>
                            <th width="5%">&nbsp;</th>
                            <th width="70%">&nbsp;</th>
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
                       
                        $IDt=$login_ProducersID.'_'.$row['PriceListMasterID'].'_'.$row['year'].'_'.$row['monthtitle'];
                        
                        $st0="style = \"color:#000000;text-align:center;font-size:12;font-weight: bold;font-family:'B Nazanin';\"";
                        $st1="style = \"color:#00f772;text-align:center;font-size:12;font-weight: bold;font-family:'B Nazanin';\"";
                        $existance=0;
                        if ($PriceListMasterIDs)
                        foreach ($PriceListMasterIDs as $key => $value)
                            if ($ID==$value)
                                    $existance=1;
                                    
                        //print "salam".$ID;
?>                      
                        <tr>
                            
                            <?php
                            if ($existance==1)
                            {
                                echo "<td $st0>".$year."</td>
                                      <td $st0>".$monthtitle."</td>";
                            }
                            else
                            {
                             
                                echo "<td $st1>".$year."</td>
                                      <td $st1>".$monthtitle."</td>";   
                            }
							$linksay='';
							if ($ID==26 && ($login_RolesID==1 || $login_RolesID==13 || $login_RolesID==14 || $login_RolesID==17))	$linksay="onClick=\" alert('اخطار: لیست کالاها در حال بروزرسانی می باشد');\" ";
							
                            ?>
                            <td><a <?php print $linksay; ?> href=<?php print "pricesaving1masterlist_refs.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999).""; ?>>
                            <img style = 'width: 70%;' src='../img/search.png' title=' ريز '></a></td>
							<?php ?>
							
                         <td><a href=<?php print "pricesaving1masterlist_files.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$IDt.rand(10000,99999).""; ?>>
                            <img style = 'width: 60%;' src='../img/imagesg.jpg' title='فایل لیست قیمت'></a></td>
                            
                            <?php 
							
                            if ($login_RolesID==18 || $login_designerCO==1)
                            
                            echo "<td><a href='pricesaving1compareprices.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).
                            rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999)."'>
                            <img style = 'width: 4%;' src='../img/accept_page.png' title=' مقایسه قیمت ها '></a></td>"; ?>
                            
                            
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

<?php 
/*
tools/tools_listrequest.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
tools/tools_listrequest_done.php
*/

include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php

if ($login_Permission_granted==0) header("Location: ../login.php");

   /*
       producers جدول تولیدکننده
       producersid شناسه تولید کننده
       producers.Title عنوان تولید کننده
       toolsrequest جدول ابزارهای درخواستی
       toolsrequestID شناسه ابزار درخواستی
       units جدول واحدهای اندازه گیری کالا
       sizeunits  جدول واحد های سایز کالا مثل اتمسفر میلی متر ووو
       materialtype  نوع مواد ابزار مانند چدنی، پلی اتیلن و
       */
       
  $sql = "SELECT producers.title producerstitle,toolsrequestID,toolsrequest.Title,MarkTitle,units.title unitstitle ,
  Description,case state when 1 then 'ثبت درخواست' else 'کالا ثبت شد' end  state,state stcode
  ,size,sizeunits.title sizeunitstitle,fesharzekhamathajm,fesharzekhamathajmUnits.title fesharzekhamathajmUnitstitle,materialtype.title MaterialTypetitle 
FROM toolsrequest
left outer join producers on producers.producersid=toolsrequest.producersid
left outer join units on units.unitsID=toolsrequest.unitsID
left outer join sizeunits on sizeunits.sizeunitsID=toolsrequest.sizeunitsID
left outer join sizeunits fesharzekhamathajmUnits on fesharzekhamathajmUnits.sizeunitsID=toolsrequest.fesharzekhamathajmUnitsID
left outer join materialtype on materialtype.MaterialTypeID=toolsrequest.MaterialTypeID
order by state,toolsrequest.Title COLLATE utf8_persian_ci";
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
  	<title>لیست درخواست های کالا</title>

	<meta http-equiv="content-type" content="../text/html; charset=UTF-8" />
	
<script type="text/javascript" language='javascript' src='../assets/jquery2.js'></script>

<script type="text/javascript" src="../lib/jquery2.js"></script>
<script type='text/javascript' src='../lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='../lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='../lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='../jquery.autocomplete.js'></script>
<script type='text/javascript' src='localdata.js'></script>
<link rel="stylesheet" type="text/css" href="main.css" />
<link rel="stylesheet" type="text/css" href="../jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="../lib/thickbox.css" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />

    <!-- /scripts -->
</head>
<body >

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
            
            <form action="tools_request.php" method="post">
                <table width="95%" align="center">
                    <tbody>
                        <tr>
                        
                        <div style = "text-align:left;">
                 &nbsp
               </div>
                   
                          
                        </tr>
                   </tbody>
                </table>
                <table id="records" width="95%" align="center">
                    <thead>
                        <tr>
                            <th width="10%">فروشنده</th>
                            <th width="20%">عنوان کالا</th>
                        	<th colspan="2" width="5%">سایز</th>
                            <th colspan="2" width="15%">فشار،ضخامت،حجم،زاویه،طول،و...</th>
                            <th width="5%">نوع مواد</th>
                            <th width="5%">مارک</th>
                            <th width="5%">واحد کالا</th>
                            <th width="20%">توضیحات</th>
                            <th width="10%"></th>
                            <th width="5%"></th>
                        </tr>
                    </thead>
                    <thead>
                    </thead>     
                   <tbody>
                   
                        
                    
                                
                   <?php
                   
                   
                    while($row = mysql_fetch_assoc($result)){

                        $ID = $row['toolsrequestID'].'_'.$row['stcode'];
                        $Title = $row['Title'];
                        $MarkTitle = $row['MarkTitle'];
                        $Unit = $row['unitstitle'];
                        $Description=$row['Description'];
                        $state=$row['state'];
                        $producerstitle=$row['producerstitle'];
                        
                        
?>                      
                        <tr>
                            
                            <td><?php echo $producerstitle; ?></td>
                            <td><?php echo $Title; ?></td>
                            <td><?php echo $row['size']; ?></td>
                            <td><?php echo $row['sizeunitstitle']; ?></td>
                            <td><?php echo $row['fesharzekhamathajm']; ?></td>
                            <td><?php echo $row['fesharzekhamathajmUnitstitle']; ?></td>
                            <td><?php echo $row['MaterialTypetitle']; ?></td>
                            <td><?php echo $MarkTitle; ?></td>
                            
                            <td><?php echo $Unit; ?></td>
                            <td><?php echo $Description; ?></td>
                            <td><?php echo $state; ?></td>
                            <td><a 
                            href=<?php print "tools_listrequest_done.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>
                            onClick="return confirm('مطمئن هستید که تایید شود ؟');"
                            > <img style = 'width: 75%;' src='../img/refresh.png' title='ثبت درخواست'> </a></td>
                        </tr><?php

                    }

?>

                        
                   
                    </tbody>
                   
                </table>
                      
                 <tr >
                        <span colsapn="1" id="fooBar">  &nbsp;</span>
                   </tr>
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

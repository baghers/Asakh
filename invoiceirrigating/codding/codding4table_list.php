<?php

/*

codding/codding4table_list.php

فرم هایی که این صفحه داخل آنها فراخوانی می شود
codding/codding4table_list.php
*/

 include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<?php



if ($login_Permission_granted==0) header("Location: ../login.php");
/*
        designercocontract جدول قراردادهای مشاورین
        designerco جدول شرکت طراح ";
		applicantstategroups جدول گروه وضعیت طرحها
        freestate جدول مراحل آزاد سازی
        creditsource جدول اعتبارات
        applicantstates جدول مراحل مختلف طرح  
        appstatesee جدول مشاهده مراحل مختلف طرح 
        appstatedone جدول تغییر مراحل مختلف طرح 
        costsgroups جدول گروه های هزینه های اجرایی 
        marks جدول مارک  
        materialtype جدول نوع مواد  
        operator جدول عملگر  
        producers جدول تولیدکننده  
        roles جدول نقش  
        sizeunits جدول واحداندازه  
        spec2 جدول مشخصه2  
        spec3 جدول مشخصه3   
        operatorcoر شرکت مجری 
        units جدول واحدکالا 
        issuer جدول صادر کننده مجوز طراحان
        designsystemgroups جدول سیستم آبیاری
        applicantwsource جدول منابع آبی
        designerco جدول شرکت طراح 
        designercocontract جدول قراردادهای مشاورین
*/

if($login_RolesID==19) 
$gcond="union all SELECT 'designercocontract' TBLNAME, 'قراردادهای مشاورین' TITLE
        union all SELECT 'designerco' TBLNAME, 'شرکت طراح' TITLE ";
else  $gcond="
		union all SELECT 'applicantstategroups' TBLNAME, 'گروه وضعیت طرحها' TITLE
        union all SELECT 'freestate' TBLNAME, 'مراحل آزاد سازی' TITLE
        union all SELECT 'creditsource' TBLNAME, 'اعتبارات' TITLE
        
        union all SELECT 'applicantstates' TBLNAME, 'مراحل مختلف طرح' TITLE  
        union all SELECT 'appstatesee' TBLNAME, 'مشاهده مراحل مختلف طرح' TITLE 
        union all SELECT 'appstatedone' TBLNAME, 'تغییر مراحل مختلف طرح' TITLE 
        union all SELECT 'costsgroups' TBLNAME, 'گروه های هزینه های اجرایی' TITLE 
        union all SELECT 'marks' TBLNAME, 'مارک' TITLE  
        union all SELECT 'materialtype' TBLNAME, 'نوع مواد' TITLE  
        union all SELECT 'operator' TBLNAME, 'عملگر' TITLE  
        union all SELECT 'producers' TBLNAME, 'تولیدکننده' TITLE  
        union all SELECT 'roles' TBLNAME, 'نقش' TITLE  
        union all SELECT 'sizeunits' TBLNAME, 'واحداندازه' TITLE  
        union all SELECT 'spec2' TBLNAME, 'مشخصه2' TITLE  
        union all SELECT 'spec3' TBLNAME, 'مشخصه3' TITLE   
        union all SELECT 'operatorco' TBLNAME, 'شرکت مجری' TITLE 
        union all SELECT 'units' TBLNAME, 'واحدکالا' TITLE 
        union all SELECT 'issuer' TBLNAME, 'صادر کننده مجوز طراحان' TITLE
        union all SELECT 'designsystemgroups' TBLNAME, 'سیستم آبیاری' TITLE
        union all SELECT 'applicantwsource' TBLNAME, 'منابع آبی' TITLE
        union all SELECT 'designerco' TBLNAME, 'شرکت طراح' TITLE 
        union all SELECT 'designercocontract' TBLNAME, 'قراردادهای مشاورین' TITLE
        
		";
        
$sql = "SELECT 'contracttype' TBLNAME, 'نوع قرارداد' TITLE
        
        
        
        $gcond
		";



//print $sql;


   try 
      {		
        $result = mysql_query($sql);
      }
      //catch exception
      catch(Exception $e) 
      {
        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
      }

?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست جداول</title>
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
                        
                        <h1 align="center">  لیست جداول </h1>
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
                        	<th width="95%">عنوان</th>
                            <th width="5%">&nbsp;</th>
                        </tr>
                    </thead>
                   <tbody>
                    
                                
                   <?php
                    
                    while($row = mysql_fetch_assoc($result)){

                        $ID = $row['TBLNAME'].'_'.$row['TITLE'];
                        $TITLE = $row['TITLE'];
?>                      
                        <tr>
                            
                            <td><?php echo $TITLE; ?></td>
                            <td><a href=<?php print "codding4table_detail.php?uid=".rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).$ID.rand(10000,99999); ?>>
                            <img style = 'width: 40%;' src='../img/calendar_empty.png' title=' مشاهده '></a></td>
                            <td>
                            </td>
                        </tr><?php

                    }

?>
                   
                    </tbody>
                   
                </table>
                <div style='visibility: hidden'>
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

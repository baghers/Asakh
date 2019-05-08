<?php 
/*
help/help2.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
 
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<?php include('../includes/elements.php'); ?>
<!DOCTYPE html>
<html>
<head>
  	<title>سوالات متداول</title>
<meta http-equiv="X-Frame-Options" content="deny" />
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
                
                <table id="records" width="95%" align="center">
                    
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
        <?php 
               echo 
                   "<tr >        
                    <td  align='right' style = \"border:0px solid black;color: blue;text-align:right;width: 100%;font-size:16;line-height:125%;font-weight: bold;font-family:'B Nazanin';\">
                    1) در ورود به سامانه مشکل دارم و وارد سایت نمی شوم؟
                    </td>
                    </tr >
                    
                    <tr >        
                    <td  align='right' style = \"border:0px solid black;color: black;text-align:right;width: 100%;font-size:16;line-height:125%;font-family:'B Nazanin';\">
                    مشکل در ورود به سایت می تواند به دلایل زیر باشد
                    <br>
                    1- نام کاربری یا کلمه عبور نادرست می باشد.
                    <br>
                    2- نقش مورد نظر اعم از مشاور طراح، شرکت مجری، فروشنده و... به درستی انتخاب نشده است.
                    <br>
                    3- نام کاربری، کلمه عبور و نقش به درستی وارد شده است ولی فایر فاکس در حالت آفلاین می باشد که نباید در این وضعیت باشد. بدین منظور به منوی فایل مرورگر فایرفاکس بروید
                    و مطمئن شوید گزینه  Work Offline انتخاب نشده باشد.
                    <br>
                    <img style = 'display: block;margin-left: auto;margin-right: auto;width: 95%;' src='../img/workoffline.png'  >
                    <br><br>
                    4- با رفتن به منوی Tools گزینه Options را انتخاب کرده و در پنجره نمایش داده شده به تب 
                    Privacy رفته و مطمئن شوید تنظیمات  مشخص شده
                    در مرورگر شما مطابق تنظیمات مشخص شده در باکس قرمز رنگ زیر می باشد.
                    
                    <br>
                    <img style = 'display: block;margin-left: auto;margin-right: auto;width: 50%;' src='../img/2013-07-03-10-58-28-d9c869.png'  >
                   
                    </td>
                    
                    </tr >
                    
                    "; 
                
        ?>
                
                    </thead>     
                   <tbody>
                    
                    
                                 
            
                   
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

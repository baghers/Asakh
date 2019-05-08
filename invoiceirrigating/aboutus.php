<?php include('includes/connect.php'); ?>
<?php include('includes/check_user.php'); ?>
<?php include('includes/elements.php'); ?>
<?php


//if (!$login_user) header("Location: login.php");

 

?>
<!DOCTYPE html>
<html>
<head>
  	<title>درباره ما</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='assets/jquery.js'></script>

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
        	<?php include('includes/top.php'); ?>
            <!-- /top -->

            <!-- main navigation -->
            <?php include('includes/navigation.php'); ?>
            <!-- /main navigation -->
			<!-- main navigation -->
            <?php include('includes/subnavigation.php'); ?>
            <!-- /main navigation -->

			<!-- header -->
            <?php include('includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
                
                <table id="records" width="95%" align="center">
                    
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
        <?php 
		
              echo 
              
                 "
                 <tr >        
                    <td  align='right' 
                    style = \"border:0px solid black;color: black;text-align:right;width: 100%;font-size:16pt;line-height:125%;font-weight: bold;font-family:'B Nazanin';\">
                             
                          درباره ما
                                                   
                    </td>
                    </tr >
                 
                 <tr >        
                    <td  align='right' 
                    style = \"border:0px solid black;color: black;text-align:right;width: 100%;font-size:14pt;line-height:125%;font-weight: bold;font-family:'B Nazanin';\">
                    
                    خدمات رایانه ای داده پردازی طوس رهام اولین سامانه برخط تهیه صورت وضعیت در پروژه های پیمانکاری در ایران است. تعاملات گروه‏‏‌های مختلف مرتبط در انجام پروژه های پیمانکاری شامل پیمانکاران، مهندسین مشاور، تولید کنندگان، فروشندگان و... توسط خدمات رایانه ای داده پردازی طوس رهام انجام می شود. 
<br>
همواره بهترین انتخاب و بهترین خدمات، شایسته کاربران خدمات رایانه ای داده پردازی طوس رهام می باشد.
<br>
خدمات رایانه ای داده پردازی طوس رهام در سال 1389 با راه اندازی سامانه تحت ویندوز تهیه دفترچه مطالعات پروژه های آبیاری تحت فشار کار خود را آغاز نمود. 
<br>
سپس با هدف دسترسی سراسری نسخه تحت وب خدمات رایانه ای داده پردازی طوس رهام در سال 1393 عملیاتی شد.


                    </td>
                    </tr >
                    "; 

/*               
                   "<tr >        
                    <td  align='right' style = \"border:0px solid black;color: black;text-align:right;width: 100%;font-size:20;line-height:125%;font-weight: bold;font-family:'B Nazanin';\">
                    
                    انجمن صنفی کارفرمایی شرکت های آبیاری تحت فشار
                    <br>
                    تلفن:
                   	<br>
                    051-37238049
                    <br>
                    051-37238349
                    <br>
                    نمابر:
                    <br>
                    051-37237591
                    <br>
                    نشانی:
                    بلوار شهید قرنی - بین قرنی 23 و 25 - ساختمان مجد 2 (ساختمان اطمینان) - طبقه چهارم - واحد 402
                    <br>
                    نشانی الکترونیکی:
                    <br>
                    Toosraham@gmail.com
                    </td>
                    </tr >
                    "; 
*/                    
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
			<?php include('includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>

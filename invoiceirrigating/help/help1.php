<?php 
/*
help/help1.php

فرم هايي که اين صفحه داخل آنها فراخواني مي شود
  
*/
include('../includes/connect.php'); ?>
<?php include('../includes/check_user.php'); ?>
<!DOCTYPE html>
<html>
<head>
  	<title>لیست فایل های راهنما</title>
<meta http-equiv="X-Frame-Options" content="deny" />
	<link rel="stylesheet" href="../assets/style.css" type="text/css" />
	<!-- scripts -->
    <script language='javascript' src='../assets/jquery.js'></script>

	<script type="text/javascript">
function showdiv(id)
{
//alert('ss');
var elem = document.getElementById(id + '_content');
if(elem.style.display=='none')
{
elem.style.display='';
}
else
{
elem.style.display='none';
}
}
</script>	

	
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
                        <tr>
                        	<th width="80%">عنوان</th>
                            <th width="10%"></th>
                        </tr>
                    </thead>
                    <thead>
                        <!--tr><th colspan="8"><div id="mydiv" >  </div></th></tr-->
                    </thead>     
                   <tbody>
<?php
/*$arrayrow= array(
    'راهنمای ثبت الگوی کشت و منبع آبی طرح های آبرسانی ' => 'guide-olgosys.pdf',
    'راهنمای ثبت طرح آب رسانی ' => 'guide-sabt.doc',
    'راهنمای ثبت طرح و لیست لوازم(نسخه  کم حجم) ' => 'invirinvoicesavingL.wmv',
    'راهنمای ثبت طرح و لیست لوازم (نسخه با کیفیت) ' => 'invirinvoicesavingH.wmv',
    'راهنمای ارسال طرح جهت بازبینی و پیگیری آن(نسخه  کم حجم) ' => 'invirinvoicesavingmanagerL.wmv',
    'راهنمای ارسال طرح جهت بازبینی و پیگیری آن (نسخه با کیفیت) ' => 'invirinvoicesavingmanagerH.wmv',
    'راهنمای ثبت قیمت فروشندگان' => 'producershelp.jpg',
    'راهنمای ارسال طرح توسط مهندسین مشاور جهت بازبینی' => 'designersendhelp.jpg',
    'فرم درخواست همکاری' => 'form.jpg',
    'راهنمای تغییر احجام اجرایی' => 'changeguide.jpg',
    'راهنمای تغییر وضعیت طرح' => 'change.jpg',
    'راهنمای ثبت سازه های طرح' => 'sazeguide.jpg',
    'راهنمای ثبت برنده پیشنهاد' => 'modiran@pishnahadw.jpg',
    'راهنمای ثبت آزادسازی اقساط' => 'modiran@azadsazi.jpg',
    'راهنمای آزادسازی ظرفیت' => 'modiran@azadzarfiat.jpg',
    'راهنمای مدیریت پیشنهاد قیمت' => 'modiran@pishnahadm.jpg');
    */
$arrayrow= array(
    'راهنمای بارگذاری فایل اکسل لوازم مورد نیاز پروژه پیمانکاران' => 'registertools.pdf',
    'راهنمای بارگذاری فایل اکسل لوازم مورد نیاز پروژه مهندسین مشاور' => 'registertoolsdesigner.pdf',
    'راهنمای بارگذاری فایل اکسل فهرست بهای دستی ' => 'registerfehrest.pdf',
    'راهنمای بارگذاری فایل اکسل فهارس بها ' => 'registerfahares.pdf');
	//----------
	 $i=0;
  $permitrolsid = array("1","18");
  $permitrolsidm = array("1","18","5","7","10","11","13","14","16","19","23","27","29","30");
            foreach($arrayrow as $key => $value)
            {
				$i++;
				$linearray =explode('@',$value);
				if (! in_array($login_RolesID, $permitrolsid) && ($linearray[0]=='modir'))  break;
				if (! in_array($login_RolesID, $permitrolsidm) && ($linearray[0]=='modiran'))  break;
				
					print " 
							 <tr ><td colspan='3' style = \"text-align:right;font-size:16;line-height:150%;font-weight: bold;font-family:'B Nazanin';\">
							 <a href='javascript:void();' onclick='showdiv(id);' id='test$i'> $key </a>
							 </td>
							 <td>
							 <a href='../../upfolder/help/$value' ><img style = 'width: 20%;' src='../img/download.png' title='دانلود' ></a>
							 </td>
							 </tr>
							 ";
						  				
?>						
 <tr>  <td colspan='4' >   
<div id="test<?php echo $i;?>_content" style="display:none;" class='f13_font'>

<font face="B Nazanin" >
<?php 
              echo "    
                        $key<br><a href='../../upfolder/help/$value' ><img style = 'width: 50%;' src='../../upfolder/help/$value' title='دانلود' ></a>
                    ";
	 ?>

</font>


</div>
			 
  </td>  </tr>   
  
					
<?php					
					
					
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

<?php 

//اتصال به دیتا بیس
include('includes/connect.php');
// بررسی لاگین شده یا نه 
//از روی سیشن به متغیرها انتقال می دهد
//مثل 
//$login_RolesID
 include('includes/check_user.php'); 
 // توابع مرتبط با المنت های اچ تی امال صفحات
  include('includes/elements.php'); 
//حاوی توابع سراسری پروژه است
//به عنوان نمونه چک شدن اعتبار کد ملی در تابع زیر انجام می شود
//checkMelliCode   
 include('includes/functiong.php');
 
if ($login_Permission_granted==0) header("Location: login.php");

//این متغیر در صورتی که عملیات با موفقیت انجام شود true می شود
$register = false;

    
if ($_POST){
    
    //اعتبار کد ملی در تابع checkMelliCode بررسی می شود
    //این تابع دوتا ورودی دارد اول کد ملی و ورودی دوم نوع شخصیت حقیقی و حقوقی که 
    // اگر $_POST['personality'] برابر یک بود یعنی شخصیت حقوقی است و در غیر اینصورت حقیقی 
    if (strlen($_POST['NationalCode'])==11) $personality=1; else $personality=0;
    if (!checkMelliCode($_POST['NationalCode'],$personality))   
    {
        print "کد ملی وارد شده نا معتبر می باشد";
    }
    else
    {
            //درج اطلاعات بهره بردار در جدول بهره برداران
           	//Farmers جدول بهره برداران
            
            //FName نام/ عنوان شرکت
            //personality نوع شخصیت
            //lname نام خانوادگی /مدیر عامل
            //NationalCode کد ملی/ شناسه ملی
            //FathersName نام پدر/نماینده شرکت
            //BirthPlace محل تولد/شهرستان
            //BirthDate تاریخ تولد/تاریخ تاسیس
            
            //Phone تلفن
            //Mobile همراه
            //Email ایمیل
            //SaveDate تاریخ ثبت اطلاعات
            //SaveTime زمان ثبت اطلاعات
            //ClerkID نام کاربر ثبت کننده
            
        if (strlen($_POST['NationalCode'])>0 && strlen($_POST['FName'])>0 && strlen($_POST['LName'])>0 && strlen($_POST['FarmersID'])>0)
        {
                $query = "update Farmers 
                set FName='$_POST[FName]',LName='$_POST[LName]',FathersName='$_POST[FathersName]',personality='$_POST[personality]'
                ,BirthPlace='$_POST[BirthPlace]',BirthDate='$_POST[BirthDate]',Phone='$_POST[Phone]',Mobile='$_POST[Mobile]',Email='$_POST[Email]'
                ,SaveTime='" . date('Y-m-d H:i:s'). "',SaveDate='".gregorian_to_jalali(date('Y-m-d'))."',ClerkID='$login_userid',personality='$_POST[personality]'
                where FarmersID='$_POST[FarmersID]';";
            		
                    
                    try 
                    {		
                        $result = mysql_query($query);
            		//header("Location: clerk.php");
        			$register = true;
        			//print $query;exit;
                    }
                    //catch exception
                    catch(Exception $e) 
                    {
                        $register = false;
                        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                    }
                    
                    
       	}            
    }
}
else
{
    //$FarmersID شناسه بهره بردار
    $FarmersID = substr($_GET["uid"],40,strlen($_GET["uid"])-45);
}



?>
<!DOCTYPE html>
<html>
<head>
  	<title>تصحیح</title>
<meta http-equiv="X-Frame-Options" content="deny" />
	
<script type="text/javascript" language='javascript' src='assets/jquery2.js'></script>

<script type="text/javascript" src="lib/jquery2.js"></script>
<script type='text/javascript' src='lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='jquery.autocomplete.js'></script>
<script type='text/javascript' src='localdata.js'></script>
<link rel="stylesheet" type="text/css" href="main.css" />
<link rel="stylesheet" type="text/css" href="jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="lib/thickbox.css" />
	<link rel="stylesheet" href="assets/style.css" type="text/css" />
    
     <script >
        
function Filter(val)
{
    if (val==1)
    {
        document.getElementById('nc').innerHTML = 'کد ملی:';
        document.getElementById('fn').innerHTML = 'نام:';
        document.getElementById('ln').innerHTML = 'نام خانوادگی:';
        document.getElementById('dn').innerHTML = 'نام پدر:';
        document.getElementById('bd').innerHTML = 'تاریخ تولد:';
        document.getElementById('bp').innerHTML = 'محل تولد:';
    }
    else
    {
        document.getElementById('nc').innerHTML = 'شناسه ملی:';
        document.getElementById('fn').innerHTML = 'عنوان شرکت:';
        document.getElementById('ln').innerHTML = 'مدیر عامل:';
        document.getElementById('dn').innerHTML = 'نماینده شرکت:';
        document.getElementById('bd').innerHTML = 'تاریخ تاسیس:';
        document.getElementById('bp').innerHTML = 'شهرستان:';        
    }
}
      </script >
</head>
<body>

	<!-- container -->
	<div id="container">

    	<!-- wrapper -->
		<div id="wrapper">
<?php
				if ($_POST)
				{
					if ($register)
					{
							echo "<p class='note'>ثبت با موفقیت ثبت شد</p>";
							//header("Location: msgsending8.php");
					}
					else
					{
							echo '<p class="error">خطا در ثبت...</p>';
					}
				}
?>
			<!-- top منوی بالا با توجه به نقش  -->
        	<?php include('includes/top.php'); ?>
            <!-- /top -->

            <!-- main navigation     این اینکلود در تمامی صفحات است و اگر جمله ای در این صفحه چاپ شود در تمامی صفحات قابل نمایش است-->
            <?php include('includes/navigation.php'); ?>
            <!-- subnavigation ثانویه     این اینکلود در تمامی صفحات است و اگر جمله ای در این صفحه چاپ شود در تمامی صفحات قابل نمایش است-->
            <?php include('includes/subnavigation.php'); ?>

			<!-- header             صفحه انتظار برای لود داده های اجکس -->
            <?php include('includes/header.php');
            
            
            //پرس و جوی استخراج مشخصات بهره بردار جاری که شناسه آن از متغیر گت در یافت شد و در 
            //$FarmersID قرار گرفته است
            $query="select * from Farmers
                where FarmersID='$FarmersID'";
            
            
            try 
                    {		
                        $result = mysql_query($query);
                        $resquery = mysql_fetch_assoc($result);
                    }
                    //catch exception
                    catch(Exception $e) 
                    {
                        $register = false;
                        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                    }
                    
         ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
                <form action="register_farmer_edit.php" method="post" enctype="multipart/form-data">
                   <table width="600" align="center" class="form">
                   
                    <!-- personality شخصیت -->
                     <tr>
                      <td class="label">شخصیت:</td>
                      <td class="data">
                       <input name="personality" onclick = "Filter('1');" type="radio" id="personality" value="0" <?php if ($resquery["personality"] == 0) echo " checked"; ?> >حقیقی </input>
                       <input name="personality" onclick = "Filter('2');" type="radio" id="personality" value="1" <?php if ($resquery["personality"] == 1) echo " checked"; ?>>حقوقی </input>
                      </td>
                     </tr>
                     
                     <!-- NationalCode کدملی -->
                   <tr>
                      <td class="label"><label id="nc">کد ملی:</label></td>
                      <td class="data"><input name="NationalCode" type="text" class="textbox" id="NationalCode"  readonly  size="30" maxlength="50" value="<?php echo  $resquery["NationalCode"]; ?>" /></td>
                      <td class="data"><input name="FarmersID" type="hidden" class="textbox" id="FarmersID"    size="5" maxlength="5" value="<?php print $FarmersID; ?>" /></td>
                     </tr>       
                    
                    <!-- FName نام/ عنوان شرکت -->
                     <tr>
                      <td class="label"><label id="fn">نام:</label></td>
                      <td class="data"><input name="FName" type="text" class="textbox" id="FName"    size="30" maxlength="50" value="<?php echo  $resquery["FName"]; ?>" /></td>
                     </tr>
                      <!-- LName نام خانوادگی /مدیر عامل --> 
                     <tr>
                      <td class="label"><label id="ln">نام خانوادگی:</label></td>
                      <td class="data"><input name="LName" type="text" class="textbox" id="LName"    size="30" maxlength="50" value="<?php echo  $resquery["LName"]; ?>" /></td>
                     </tr>
                     
                     <!-- FathersName نام پدر/نماینده شرکت --> 
                     <tr>
                      <td class="label"><label id="dn">نام پدر:</label></td>
                      <td class="data"><input name="FathersName" type="text" class="textbox" id="FathersName"    size="30" maxlength="50" value="<?php echo  $resquery["FathersName"]; ?>" /></td>
                     </tr>
                     <!-- BirthDate تاریخ تولد/تاریخ تاسیس --> 
                     <tr>
                      <td class="label"><label id="bd">تاریخ تولد:</label></td>
                      <td class="data"><input name="BirthDate" type="text" class="textbox" id="BirthDate"    size="30" maxlength="50" value="<?php echo  $resquery["BirthDate"]; ?>" /></td>
                     </tr>
                     
                     <!-- BirthPlace محل تولد/شهرستان -->
                     <tr>
                      <td class="label"><label id="bp">محل تولد:</label></td>
                      <td class="data"><input name="BirthPlace" type="text" class="textbox" id="BirthPlace"    size="30" maxlength="50" value="<?php echo  $resquery["BirthPlace"]; ?>" /></td>
                     </tr>
                     
                     <!-- Phone تلفن --> 
                     <tr>
                      <td class="label">تلفن:</td>
                      <td class="data"><input name="Phone" type="text" class="textbox" id="Phone"    size="30" maxlength="50" value="<?php echo  $resquery["Phone"]; ?>" /></td>
                     </tr>
                     
                     
                     <!-- Mobile همراه -->
                     <tr>
                      <td class="label"> همراه:</td>
                      <td class="data"><input name="Mobile" type="textbox" class="textbox" id="Mobile"    size="30" maxlength="11" value="<?php echo  $resquery["Mobile"]; ?>" /></td>
			         </tr>
					 
                     <!-- Email ایمیل -->
					 <tr>
                      <td class="label">ایمیل:</td>
				      <td class="data"><input name="Email" type="textbox" class="textbox" id="Email"    size="30" maxlength="50" value="<?php echo  $resquery["Email"]; ?>" /></td>
			    	 </tr>
					         
                     </tbody>
                    <tfoot>
                    <!-- submit ثبت نام -->
                     <tr>
                      <td>&nbsp;</td>
                      <td><input name="submit" type="submit" class="button" id="submit" value="ثبت نام" /></td>
                     </tr>
                    </tfoot>
                   
                   </table>
                   
                   
                   
                  </form>
            </div>
			<!-- /content -->

            <!-- footer -->
			<?php
            //چاپ زیر نوشته پایین صفحات
             include('includes/footer.php'); ?>
            <!-- /footer -->

		</div>
        <!-- /wrapper -->

	</div>
    <!-- /container -->

</body>
</html>
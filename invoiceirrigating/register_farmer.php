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

if ($_POST)
{
    //اعتبار کد ملی در تابع checkMelliCode بررسی می شود
    //این تابع دوتا ورودی دارد اول کد ملی و ورودی دوم نوع شخصیت حقیقی و حقوقی که 
    // اگر $_POST['personality'] برابر یک بود یعنی شخصیت حقوقی است و در غیر اینصورت حقیقی   
    
    
    
    if (!checkMelliCode($_POST['NationalCode'],$_POST['personality']))   
    {
        print "کد ملی وارد شده نا معتبر می باشد";
    }
    else
    {
        //در پرس و جوی زیر بررسی می شودکه آیا قبلا مشخصات بهرهبردار ثبت شده یا خیر
        $query="select * from Farmers
                where NationalCode='$_POST[NationalCode]'";
        try 
        {		
            $result = mysql_query($query); 
        }
        //catch exception
        catch(Exception $e) 
        {
            echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
        }

        
        $resquery = mysql_fetch_assoc($result);
        if ($resquery['NationalCode']==$_POST['NationalCode'])
        {
            print "ثبت نام شما قبلا انجام شده است";
            
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
            
            if (strlen($_POST['NationalCode'])>0 && strlen($_POST['FName'])>0 && strlen($_POST['LName'])>0)
            {
                $query = "INSERT INTO Farmers 
                (`FName`,personality, `LName`, `NationalCode`, `FathersName`, `BirthPlace`, `BirthDate`, `Phone`, `Mobile`, `Email`, `SaveDate`, `SaveTime`, `ClerkID`) 
                    VALUES( 
                    '$_POST[FName]'
                    , '$_POST[personality]',
                    '$_POST[LName]', 
                    '$_POST[NationalCode]', 
                    '$_POST[FathersName]', 
                    '$_POST[BirthPlace]', 
                    '$_POST[BirthDate]', 
                    '$_POST[Phone]', 
                    '$_POST[Mobile]', 
                    '$_POST[Email]', '" . date('Y-m-d H:i:s'). "','".gregorian_to_jalali(date('Y-m-d'))."','$login_userid');";
                    try 
                    {		
                        $result = mysql_query($query); 
                        $register = true;
                    }
                    //catch exception
                    catch(Exception $e) 
                    {
                        $register = false;
                        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                    }
                    //آخرین شناسه جدول بهره بردار درج شده توسط این کاربر
                    $iid=mysql_insert_id();
        	}            
        }
    }       
}


?>
<!DOCTYPE html>
<html>
<head>
  	<title>ثبت نام متقاضی</title>
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
							echo "<p class='note'>ثبت نام شما با شناسه ".$iid." با موفقیت ثبت شد</p>";
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
            <?php include('includes/header.php'); ?>
			<!-- /header -->

			<!-- content -->
			<div id="content">
                <form action="register_farmer.php" method="post" enctype="multipart/form-data">
                   <table width="600" align="center" class="form">
                    <tbody>
                    
                    <!-- personality شخصیت -->
                    <tr>
                      <td class="label">شخصیت:</td>
                      <td class="data">
                       <input  onclick = "Filter('1');" name="personality" type="radio" id="personality" value="0" checked >حقیقی </input>
                       <input   onclick = "Filter('2');" name="personality" type="radio" id="personality" value="1" >حقوقی </input>
                      </td>
                     </tr>
                    <!-- NationalCode کدملی --> 
                     <tr>
                      <td class="label"><label id="nc">کد ملی:</label></td>
                      <td class="data"><input name="NationalCode" type="text" class="textbox" id="NationalCode"    size="30" maxlength="50" value="" /></td>
                     </tr>       
                    
                    <!-- FName نام/ عنوان شرکت --> 
                     <tr>
                      <td class="label"><label id="fn">نام:</label></td>
                      <td class="data"><input name="FName" type="text" class="textbox" id="FName"    size="30" maxlength="50" value="" /></td>
                     </tr>
                   <!-- LName نام خانوادگی /مدیر عامل -->
                     <tr>
                      <td class="label"><label id="ln">نام خانوادگی:</label></td>
                      <td class="data"><input name="LName" type="text" class="textbox" id="LName"    size="30" maxlength="50" value="" /></td>
                     </tr>
                    <!-- FathersName نام پدر/نماینده شرکت -->
                     <tr>
                      <td class="label"><label id="dn">نام پدر:</label></td>
                      <td class="data"><input name="FathersName" type="text" class="textbox" id="FathersName"    size="30" maxlength="50" value="" /></td>
                     </tr>
                    <!-- BirthDate تاریخ تولد/تاریخ تاسیس -->
                     <tr>
                      <td class="label"><label id="bd">تاریخ تولد:</label></td>
                      <td class="data"><input name="BirthDate" type="text" class="textbox" id="BirthDate"    size="30" maxlength="50" value="" /></td>
                     </tr>
                    <!-- BirthPlace محل تولد/شهرستان --> 
                     <tr>
                      <td class="label"><label id="bp">محل تولد:</label></td>
                      <td class="data"><input name="BirthPlace" type="text" class="textbox" id="BirthPlace"    size="30" maxlength="50" value="" /></td>
                     </tr>
                    <!-- Phone تلفن --> 
                     <tr>
                      <td class="label">تلفن:</td>
                      <td class="data"><input name="Phone" type="text" class="textbox" id="Phone"    size="30" maxlength="50" value="" /></td>
                     </tr>
                    <!-- Mobile همراه -->
                     <tr>
                      <td class="label"> همراه:</td>
                      <td class="data"><input name="Mobile" type="textbox" class="textbox" id="Mobile"    size="30" maxlength="11" value="" /></td>
			         </tr>
					 <!-- Email ایمیل -->
					 <tr>
                      <td class="label">ایمیل:</td>
				      <td class="data"><input name="Email" type="textbox" class="textbox" id="Email"    size="30" maxlength="50" value="" /></td>
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
                   <?php
                   
                   
            //در زیر لیست بهره برداران سامانه جهت مشاهده و ویرایش اطلاعات نمایش داده شده است.
            echo "
            <table id='records' width='95%' align='left'>
            <thead style = \"text-align:center;font-size:14;font-weight: bold;font-family:'B Nazanin';\">
            	 <tr>
                            <th style = 'text-align:center;'>ردیف</th>
                            <th style = 'text-align:center;'>کد ملی</th>
                            <th style = 'text-align:center;'>نام</th>
                            <th style = 'text-align:center;'>نام خانوادگی</th>
                            <th style = 'text-align:center;'>نام پدر</th>
                            <th style = 'text-align:center;'>تاریخ تولد</th>
                            <th style = 'text-align:center;'>محل تولد</th>
                            <th style = 'text-align:center;'>تلفن</th>
                            <th style = 'text-align:center;'> همراه</th>
                            <th style = 'text-align:center;'>ایمیل</th>
                         </tr>
            </thead>
            <tbody >";
            // $login_userid==683 شناسه کاربر میز خدمت آب و خاک در دبیرخانه طبقه همکف
            //نقش های زیر نیز امکان مشاهده و ویرایش اطلاعات را دارند که از جدول نقش های اخذ می شود
            $permitrolsidforsave = array("1","13","14","18","17","26"); 
            if (in_array($login_RolesID, $permitrolsidforsave) || $login_userid==683)
            {
                if ($login_RolesID==26)
                $query="select * from Farmers
                inner join clerk on clerk.ClerkID='$login_userid' and Farmers.NationalCode=clerk.melicode
                order by Lname COLLATE utf8_persian_ci,Fname COLLATE utf8_persian_ci";
                else
                if ($login_RolesID==17)
                $query="
                select distinct `FarmersID`, `FName`, `LName`, `NationalCode`, `FathersName`, `BirthPlace`, `BirthDate`, `Phone`, `Mobile`, `Email`, `SaveDate`, `SaveTime`, `ClerkID`, `personality` from
                (
                select distinct Farmers.FarmersID, Farmers.FName, Farmers.LName, Farmers.NationalCode, Farmers.FathersName, Farmers.BirthPlace, Farmers.BirthDate, Farmers.Phone, Farmers.Mobile, Farmers.Email, Farmers.SaveDate, Farmers.SaveTime, Farmers.ClerkID, Farmers.personality from Farmers
                inner join applicantmaster on applicantmaster.melicode=Farmers.NationalCode and substring(applicantmaster.CityId,1,4)=substring('$login_CityId',1,4)
                union all 
                select distinct Farmers.FarmersID, Farmers.FName, Farmers.LName, Farmers.NationalCode, Farmers.FathersName, Farmers.BirthPlace, Farmers.BirthDate, Farmers.Phone, Farmers.Mobile, Farmers.Email, Farmers.SaveDate, Farmers.SaveTime, Farmers.ClerkID, Farmers.personality from Farmers where ClerkID='$login_userid'
                )v1
                order by Lname COLLATE utf8_persian_ci,Fname COLLATE utf8_persian_ci";
                else
                //پرس و جوی استخراج مشخصات بهره برداران
                $query="select * from Farmers
                order by Lname COLLATE utf8_persian_ci,Fname COLLATE utf8_persian_ci";
                
    /*            $query="SELECT appchangestate.Description,appchangestate.SaveDate,CPI,DVFS,applicantstates.Title,ApplicantFName,ApplicantName,appchangestate.applicantmasterid 
FROM  `appchangestate` 
inner join clerk on clerk.ClerkID=appchangestate.ClerkID
inner join applicantstates on applicantstates.applicantstatesID=appchangestate.applicantstatesID
inner join applicantmaster on applicantmaster.applicantmasterid=appchangestate.applicantmasterid
WHERE  appchangestate.SaveDate <=  '2018-05-21'
AND  appchangestate.SaveDate >=  '2017-12-22'
order by appchangestate.SaveDate desc
";
     $query="




SELECT tbl_log.*,CPI,DVFS 
FROM  `tbl_log` 
inner join clerk on clerk.ClerkID=tbl_log.ClerkID
WHERE tbl_log.SaveDate <=  '2018-02-04'
AND tbl_log.SaveDate >=  '2017-12-22'
order by tbl_log.SaveDate desc
";
*/

            }
            else
                //پرس و جوی استخراج مشخصات بهره برداران
                $query="select * from Farmers where ClerkID='$login_userid'
                order by Lname COLLATE utf8_persian_ci,Fname COLLATE utf8_persian_ci";
                
                try 
                    {		
                        $result = mysql_query($query);
                    }
                    //catch exception
                    catch(Exception $e) 
                    {
                        $register = false;
                        echo 'اجرای پرس و جو با خطا مواجه شد: ' .$e->getMessage();
                    }
                    
                $Rowcnt=0;
                while($resquery = mysql_fetch_assoc($result))
                {
                    
                        
                    /*
                    $first_name = decrypt($resquery['CPI']);
                        $last_name = decrypt($resquery['DVFS']);
                        
                        $Rowcnt++;
                    echo "<tr>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$Rowcnt</td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: left;font-size:10.0pt;font-family:'B Nazanin';\">$first_name $last_name</td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\"></td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[LName]</td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[applicantmasterid] </td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[ApplicantFName] </td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[ApplicantName] </td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[Title] </td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".gregorian_to_jalali($resquery['SaveDate'])." </td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[Description] </td>
                        ";
                        echo "<tr>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$Rowcnt</td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: left;font-size:10.0pt;font-family:'B Nazanin';\">$first_name $last_name</td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[tName]</td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[tID]</td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[colname] </td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[oldval] </td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[newval] </td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[Title] </td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">".gregorian_to_jalali($resquery['SaveDate'])." </td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[Description] </td>
                        ";
                        continue;
                        */
                        
                        
                        
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
                
                $Rowcnt++;
                    echo "<tr>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:9.0pt;font-family:'B Nazanin';\">$Rowcnt</td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: left;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[NationalCode]</td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[FName]</td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[LName]</td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[FathersName] </td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[BirthDate] </td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[BirthPlace] </td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[Phone] </td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[Mobile] </td>
                        <td class='f10_font$b'  style=\"color:#$cl;text-align: center;font-size:10.0pt;font-family:'B Nazanin';\">$resquery[Email] </td>
                        ";
                //register_farmer_edit.php لینک به صفحه ویرایش مشخصات بهره بردار که شناسه بهره بردار را دریافت می کند        
                        echo "
                       	<td class='no-print'><a  target='_blank' href='register_farmer_edit.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$resquery['FarmersID'].rand(10000,99999)."'><img style = 'width: 20px;' src='img/file-edit-icon.png' title=' ویرایش '></a></td>
                    			";
                //designer_list.php لینک ریز اعضای مالکین یا اعضای هیئت مدیره
                              /*  print "<td class='no-print'><a  target='_blank' href='designer_list.php?uid=".rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).rand(10000,99999).
                                    rand(10000,99999).$resquery['FarmersID'].rand(10000,99999)."'>
                            <img style = 'width: 25px;' src='img/search.png' title=' ريز اعضا'></a></td>"; 
                            */
                    
                }
    
            
            
            echo "
            </tbody >
            </table>
            ";
                    ?>
                   
                  
                   
                   
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